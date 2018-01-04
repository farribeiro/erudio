(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class HistoricoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, cursoService, unidadeService, cursoOfertadoService, turmaService, etapaService, $scope) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.cursoService = cursoService;
            this.permissaoLabel = "RELATORIOS";
            this.titulo = "Histórico Escolar";
            this.tituloBuscaCustom = "";
            this.linkModulo = "/#!/historicos/";
            this.busca = {nome: null, codigo: null, dataNascimento: null, curso: null, status: null};
            this.pagina = 0;
            this.scope = $scope;
            this.scope.unidade = null;
            this.curso = {id: null};
            this.etapa = {id: null};
            this.turma = {id: null};
            this.encerrada = null;
            this.cursosOfertados = [];
            this.etapas = [];
            this.turmas = [];
            this.itemBusca = '';
            this.unidadeService = unidadeService;
            this.cursoOfertadoService = cursoOfertadoService;
            this.turmaService = turmaService;
            this.etapaService = etapaService;
            this.finalLista = false;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Matrículas Encontradas' }];
            this.opcoes = [];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.template = this.util.getTemplateListaEspecial();
            this.lista = this.util.getTemplateListaEspecifica('historicos');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/historicos/partials');
            this.timeout(()=>{ this.util.aplicarMascaras(); },1000);
        }
        
        buscarCursos() {
            this.cursoService.getAll(null, true).then((cursos) => this.cursos = cursos);
        }

        buscarCursosOfertados() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.unidade.id}, true).then((cursos) => this.cursosOfertados = cursos);
        }
        
        buscarEtapas(loader) {
            this.etapaService.getAll({curso: this.curso.id},loader).then((etapas) => { this.etapas = etapas; });
        }

        buscarTurmas() {
            this.turmaService.getAll({unidadeEnsino: this.scope.unidade.id, etapa: this.etapa.id, curso: this.curso.id, encerrado: this.encerrada}, true).then((turmas) => this.turmas = turmas);
        }

        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }
        
        imprimir() {
            var url = this.erudioConfig.urlServidor+'/report/historico-turma?turma='+this.turma.id;
            this.util.getPDF(url,'application/pdf','_blank');
        }

        buscarMatriculas(loader) {
            var contador = 0;
            var options = { page: this.pagina };
            if (!this.util.isVazio(this.busca.nome)) { options.aluno_nome = this.busca.nome; contador++; }
            if (!this.util.isVazio(this.busca.codigo)) { options.codigo = this.busca.codigo; contador++; }
            if (!this.util.isVazio(this.busca.dataNascimento)) { options.aluno_dataNascimento = this.util.converteData(this.busca.dataNascimento); contador++; }
            if (!this.util.isVazio(this.busca.curso)) { options.curso = this.busca.curso; contador++; }
            if (!this.util.isVazio(this.busca.status)) { options.status = this.busca.status; contador++; }

            if (contador > 0) {
                this.service.getAll(options,loader).then((matriculas) => {
                    if (this.pagina === 0) { this.objetos = matriculas; } else { 
                        if (matriculas.length !== 0) { this.objetos = this.objetos.concat(matriculas); } else { this.finalLista = true; this.pagina--; }
                    }
                });
            } else {
                this.util.toast('Deve se preencher um campo pelo menos para realizar a busca.');
            }
            
        }
        
        limparBusca() { this.busca = {nome: null, codigo: null, dataNascimento: null, curso: null, status: null}; }

        limparBuscaPorTurma() {
            this.cursosOfertados = []; this.etapas = []; this.turmas = [];
            this.scope.unidade = null; this.curso = {id: null}; this.etapa = {id: null};
            this.turma = {id: null}; this.encerrada = null; this.itemBusca = '';
        }

        paginar(){ this.pagina++; this.buscarMatriculas(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                if (this.util.isAdmin()) { this.isAdmin = true;} else { 
                    this.isAdmin = false;
                    this.scope.unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.buscarCursosOfertados();
                }
                this.util.mudarImagemToolbar('historicos/assets/images/historicos.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarCursos();
                $(".fit-screen").scroll(function(){
                    let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                    let altura = Math.floor(Number($(".main-layout").height()));
                    let total = altura + distancia;
                    if (total === 0) { self.paginar(); }
                });
                this.timeout(() => { 
                    $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.buscarMatriculas(true); } }); 
                    this.scope.$watch("unidade", (query) => {
                        if (!this.util.isVazio(this.scope.unidade)) { this.buscarCursosOfertados(); }
                    });
                }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    HistoricoController.$inject = ["MatriculaService","Util","$mdDialog","ErudioConfig","$timeout","CursoService","UnidadeService","CursoOfertadoService","TurmaService","EtapaService","$scope"];
    angular.module('HistoricoController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoController',HistoricoController);
})();
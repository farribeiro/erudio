(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class AlunosEnturmadosController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, unidadeService, etapaService, cursoService, cursoOfertadoService, turmaService, instituicaoService, etapaOfertadaService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.cursoOfertadoService = cursoOfertadoService;
            this.permissaoLabel = "RELATORIOS"; this.titulo = "Alunos Enturmados"; this.linkModulo = "/#!/alunos-enturmados/"; this.etapaOfertadaService = etapaOfertadaService;
            this.scope.busca = {unidadeEnsino:null, cursoOfertado:null, etapa: null, turma: null}; this.objetos = []; this.isAdmin = false; this.itemBusca = '';
            this.etapaService = etapaService; this.cursoService = cursoService; this.cursoOfertadoService = cursoOfertadoService; this.etapas = []; this.turmas = [];
            this.itemBuscaPorUnidade = ''; this.scope.buscaPorUnidade = {unidadeEnsino:null}; this.itemBuscaPorUnidadeQuanti = '';
            this.scope.buscaQuanti = {instituicao:null, curso:null}; this.scope.buscaPorUnidadeQuanti = {unidadeEnsino:null};
            this.turmaService = turmaService; this.instituicaoService = instituicaoService;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Aluno' }];
            this.opcoes = [{tooltip: 'Imprimir', icone: 'print', opcao: 'imprimir', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Etapa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('alunosEnturmados');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosEnturmados/partials');
        }

        mostrarImpressao() { if (!this.util.isVazio(this.turmas)) { return true; } else { return false; } }

        limparBusca() { this.scope.busca = {unidadeEnsino:null, cursoOfertado:null, etapa: null, turma: null}; this.itemBusca = ''; this.cursosOfertados = []; this.etapas = []; this.turmas = []; }

        limparBuscaPorUnidade() { this.itemBuscaPorUnidade = ''; this.scope.buscaPorUnidade = {unidadeEnsino:null}; }

        limparBuscaQuanti() { this.scope.buscaQuanti = {instituicao:null, curso:null}; }

        limparBuscaPorUnidadeQuanti() { this.itemBuscaPorUnidadeQuanti = ''; this.scope.buscaPorUnidadeQuanti = {unidadeEnsino:null}; }

        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }

        buscarInstituicoes() {
            this.instituicaoService.getAll(null,true).then((instituicoes) => {
                this.instituicoes = instituicoes;
            });
        }

        buscarTurmas(curso,etapa) {
            this.turmaService.getAll({curso: curso, etapa: etapa, unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((turmas) => {
                this.turmas = turmas;
            });
        }

        buscarEtapas(curso) {
            this.etapaOfertadaService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id, curso: curso},true).then((etapas) => {
                this.etapas = etapas;
            });
        }

        buscarCursos() {
            this.cursoService.getAll(null,true).then((cursos) => {
                this.cursos = cursos;
            });
        }

        buscarCursosOfertados() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((cursos) => {
                this.cursosOfertados = cursos;
            });
        }        

        imprimir() {
            var url = this.service.getURL(this.scope.busca.turma);
            this.util.getPDF(url,"application/pdf",'_blank');
        }

        imprimirNominalPorUnidade() {
            var url = this.service.getURLNominalUnidade(this.scope.buscaPorUnidade.unidadeEnsino.id);
            this.util.getPDF(url,"application/pdf",'_blank');
        }

        imprimirQuantiPorInstituicao() {
            var url = this.service.getURLQuantiInstituicao(this.scope.buscaQuanti.instituicao, this.scope.buscaQuanti.curso);
            this.util.getPDF(url,"application/pdf",'_blank');
        }

        imprimirQuantiPorUnidade() {
            var url = this.service.getURLQuantiPorUnidade(this.scope.buscaPorUnidadeQuanti.unidadeEnsino.id);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                if (this.util.isAdmin()) { this.isAdmin = true;} else { 
                    this.isAdmin = false;
                    this.scope.busca.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.scope.buscaPorUnidade.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.scope.itemBuscaPorUnidadeQuanti.unidadeEnsino = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao;
                    this.buscarCursos();
                }
                this.util.mudarImagemToolbar('alunosEnturmados/assets/images/alunosEnturmados.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarInstituicoes();
                this.buscarCursos();
                this.timeout(() => { 
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                    this.scope.$watch("busca.unidadeEnsino", (query) => {
                        if (!this.util.isVazio(this.scope.busca.unidadeEnsino)) { this.buscarCursosOfertados(); }
                    }); 
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    AlunosEnturmadosController.$inject = ["AlunosEnturmadosService","Util","$mdDialog","ErudioConfig","$timeout","$scope","UnidadeService","EtapaService","CursoService","CursoOfertadoService","TurmaService","InstituicaoService","EtapaOfertadaService"];
    angular.module('AlunosEnturmadosController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosEnturmadosController',AlunosEnturmadosController);
})();
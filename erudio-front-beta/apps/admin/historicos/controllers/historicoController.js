(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class HistoricoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, cursoService) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.cursoService = cursoService;
            this.permissaoLabel = "RELATORIOS";
            this.titulo = "Histórico Escolar";
            this.tituloBuscaCustom = "Buscar Matrículas";
            this.linkModulo = "/#!/historicos/";
            this.busca = {nome: null, codigo: null, dataNascimento: null, curso: null, status: null};
            this.pagina = 0;
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
        
        buscarMatriculas(loader) {
            var contador = 0;
            var options = { page: this.pagina };
            if (!this.util.isVazio(this.busca.nome)) { options.nome = this.busca.nome; contador++; }
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
        
        paginar(){ this.pagina++; this.buscarMatriculas(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
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
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.buscarMatriculas(true); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    HistoricoController.$inject = ["MatriculaService","Util","$mdDialog","ErudioConfig","$timeout","CursoService"];
    angular.module('HistoricoController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoController',HistoricoController);
})();
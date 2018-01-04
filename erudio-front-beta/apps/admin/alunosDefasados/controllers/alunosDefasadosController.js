(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class AlunosDefasadosController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, cursoOfertadoService, unidadeService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.cursoOfertadoService = cursoOfertadoService;
            this.permissaoLabel = "RELATORIOS"; this.titulo = "Alunos Defasados"; this.linkModulo = "/#!/alunos-defasados/";
            this.scope.busca = {unidadeEnsino:null, cursoOfertado:null}; this.objetos = []; this.isAdmin = false; this.itemBusca = '';
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
            this.lista = this.util.getTemplateListaEspecifica('alunosDefasados');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosDefasados/partials');
        }

        limparBusca() { this.busca = {nome:null, dataNascimento:null}; this.itemBusca = ''; this.cursos = []; }

        filtrar (query) { 
            if (query.length > 2) {
                return this.unidadeService.getAll({nome: query},true);
            } else { return []; }
        }

        buscarCursos() {
            this.cursoOfertadoService.getAll({unidadeEnsino: this.scope.busca.unidadeEnsino.id},true).then((cursos) => {
                this.cursos = cursos;
            });
        }        

        imprimir() {
            var url = this.service.getURL(this.scope.busca.cursoOfertado);
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
                    this.buscarCursos();
                }
                this.util.mudarImagemToolbar('alunosDefasados/assets/images/alunosDefasados.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.timeout(() => { 
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                    this.scope.$watch("busca.unidadeEnsino", (query) => {
                        if (!this.util.isVazio(this.scope.busca.unidadeEnsino)) { this.buscarCursos(); }
                    }); 
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    AlunosDefasadosController.$inject = ["AlunosDefasadosService","Util","$mdDialog","ErudioConfig","$timeout","$scope","CursoOfertadoService","UnidadeService"];
    angular.module('AlunosDefasadosController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosDefasadosController',AlunosDefasadosController);
})();
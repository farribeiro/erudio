(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class AlunosANEEController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope, unidadeService, instituicaoService) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null; this.unidadeService = unidadeService;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope; this.instituicaoService = instituicaoService;
            this.permissaoLabel = "RELATORIOS"; this.titulo = "Alunos ANEE"; this.linkModulo = "/#!/alunos-anee/";
            this.scope.busca = {unidadeEnsino:null, instituicao:null}; this.isAdmin = false; this.itemBusca = '';
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
            this.lista = this.util.getTemplateListaEspecifica('alunosANEE');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/alunosANEE/partials');
        }

        mostrarImpressao() { if (!this.util.isVazio(this.turmas)) { return true; } else { return false; } }

        limparBusca() { this.scope.busca = {unidadeEnsino:null, instituicao:null}; }

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

        imprimir() {
            var url = this.service.getURLPorInstituicao(this.scope.busca.instituicao);
            this.util.getPDF(url,"application/pdf",'_blank');
        }

        imprimirNominalPorUnidade() {
            var url = this.service.getURL(this.scope.busca.unidadeEnsino.id);
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
                }
                this.util.mudarImagemToolbar('alunosANEE/assets/images/alunosANEE.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarInstituicoes();
                this.timeout(() => { 
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    AlunosANEEController.$inject = ["AlunoANEEService","Util","$mdDialog","ErudioConfig","$timeout","$scope","UnidadeService","InstituicaoService"];
    angular.module('AlunosANEEController',['ngMaterial', 'util', 'erudioConfig']).controller('AlunosANEEController',AlunosANEEController);
})();
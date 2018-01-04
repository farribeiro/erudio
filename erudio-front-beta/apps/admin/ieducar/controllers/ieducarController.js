(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class IeducarController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $scope) {
            this.service = service; this.util = util; this.mdDialog = $mdDialog; this.aluno = null;
            this.erudioConfig = erudioConfig; this.timeout = $timeout; this.scope = $scope;
            this.permissaoLabel = "RELATORIOS"; this.titulo = "iEducar"; this.linkModulo = "/#!/ieducar/";
            this.busca = {nome:null, dataNascimento:null}; this.objetos = []; this.isAdmin = false;
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
            this.lista = this.util.getTemplateListaEspecifica('ieducar');
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/ieducar/partials');
        }

        limparBusca() { this.busca = {nome:null, dataNascimento:null}; this.objetos = []; }

        buscarAlunos() {
            this.service.getAll(this.busca).then((alunos) => {
                this.objetos = alunos;
            });
        }

        executarOpcao(event,opcao,objeto) { 
            this.aluno = objeto; this.imprimir(this.aluno);
        }
        

        imprimir(aluno) {
            var url = this.service.getURLHistorico(aluno);
            this.util.getPDF(url,"application/pdf",'_blank');
        }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('ieducar/assets/images/ieducar.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.timeout(() => {
                    $('.empty-state').hide(); $('botao-adicionar').hide();
                    this.util.aplicarMascaras();
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    IeducarController.$inject = ["IeducarService","Util","$mdDialog","ErudioConfig","$timeout","$scope"];
    angular.module('IeducarController',['ngMaterial', 'util', 'erudioConfig']).controller('IeducarController',IeducarController);
})();
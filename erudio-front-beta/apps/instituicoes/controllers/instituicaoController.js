(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class InstituicaoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.permissaoLabel = "INSTITUICOES";
            this.titulo = "Instituições";
            this.linkModulo = "/#!/instituicoes/";
            this.instituicao = null;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { return this.util.validarEscrita(opcao, this.opcoes, this.escrita); }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome da Instituição' }];
            this.opcoes = [{ tooltip: 'Remover', icone: 'delete', opcao: 'remover' }];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Instituição', icone: 'add', href: this.link+'novo' }
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('instituicoes');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }
        
        buscarInstituicoes() {
            this.pagina = 0;
            this.service.getAll({pagina: this.pagina}).then((instituicoes) => {
                if (this.pagina === 0) { this.objetos = instituicoes; } else { this.objetos.concat(instituicoes); }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.instituicao = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event,index) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Instituição", "Deseja remover esta instituição?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                self.service.remover(self.instituicao, "Instituição","f").then(function(response){ self.objetos.splice(index,1); });
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarInstituicoes(); } else { this.executarBusca(query); } }
        
        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(function(){
                if (self.util.isVazio(query)) { query = ''; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.instituicaoService.getAll({ nome: query }).then((instituicoes) => { this.objetos = instituicoes; });
                }
            },800);
        }
        
        proximaPagina() { this.pagina++; this.buscarInstituicoes(); }
        paginaAnterior() { if (pagina >= 0) { this.pagina--; this.buscarInstituicoes(); } }
        
        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
                this.preparaLista();
                this.buscarInstituicoes();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    InstituicaoController.$inject = ["InstituicaoService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('InstituicaoController',['ngMaterial', 'util', 'erudioConfig', 'instituicaoDirectives']).controller('InstituicaoController',InstituicaoController);
})();
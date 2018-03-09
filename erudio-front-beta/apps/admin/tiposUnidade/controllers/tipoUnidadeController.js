(function (){
    /*
     * @ErudioDoc TipoUnidadeController Controller
     * @Module tipos-unidade
     * @Controller TipoUnidadeController
     */
    class TipoUnidadeController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.permissaoLabel = "TIPOS_UNIDADE";
            this.titulo = "Tipos de Unidade";
            this.linkModulo = "/#!/tipos-unidade/";
            this.tipo = null;
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Tipo' }];
            this.opcoes = [{ tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true }];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Tipo de Unidade', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('tiposUnidade');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }
        
        buscarTipos(loader) {
            this.service.getAll({page: this.pagina},loader).then((tipos) => {
                if (this.pagina === 0) { this.objetos = tipos; } else { 
                    if (tipos.length !== 0) { this.objetos = this.objetos.concat(tipos); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.tipo = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Tipo de Unidade", "Deseja remover este Tipo de Unidade?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.tipo.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.tipo, "Tipo de Unidade","f");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarTipos(true);  this.buscaIcone = 'search'; } else { this.executarBusca(query); } }
    
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarTipos(true); }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; self.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((tipos) => { self.objetos = tipos; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        
        paginar(){ this.pagina++; this.buscarTipos(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('tiposUnidade/assets/images/tiposUnidade.jpeg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarTipos();
                this.timeout(function(){
                    $(".fit-screen").scroll(function(){
                        let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                        let altura = Math.floor(Number($(".main-layout").height()));
                        let total = altura + distancia;
                        if (total === 0) { self.paginar(); }
                    });
                },500);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    TipoUnidadeController.$inject = ["TipoUnidadeService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('TipoUnidadeController',['ngMaterial', 'util', 'erudioConfig']).controller('TipoUnidadeController',TipoUnidadeController);
})();
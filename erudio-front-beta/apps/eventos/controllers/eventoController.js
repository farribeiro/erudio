//var eventoModule = angular.module('eventoModule', ['servidorModule', 'eventoDirectives']);
//eventoModule.controller('eventoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function ($scope, Servidor, Restangular, $timeout, $templateCache) {

(function (){
    /*
        * @ErudioDoc Instituição Controller
        * @Module instituicoes
        * @Controller InstituicaoController
        */
    class EventoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.permissaoLabel = "EVENTOS";
            this.titulo = "Eventos";
            this.linkModulo = "/#!/eventos/";
            this.evento = null;
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Evento' }];
            this.opcoes = [{ tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true }];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Evento', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('eventos');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }
        
        buscarEventos(loader) {
            var options = null;
            if (!this.isAdmin) { 
                let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                options = {page: this.pagina, instituicao: unidade};
            } else { options = {page: this.pagina}; };
            this.service.getAll(options,loader).then((eventos) => {
                if (this.pagina === 0) { this.objetos = eventos; } else { 
                    if (eventos.length !== 0) { this.objetos = this.objetos.concat(eventos); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.evento = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Evento", "Deseja remover este Evento?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.evento.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.evento, "Evento","m");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarEventos(); this.buscaIcone = 'search'; } else { this.executarBusca(query); this.buscaIcone = 'clear'; } }
        
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarEventos(true); }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; self.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    let options = null;
                    if (!self.isAdmin) { 
                        let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                        options = {nome: query, instituicao: unidade};
                    } else { options = {nome: query}; };
                    self.service.getAll(options,true).then((regimes) => { self.objetos = regimes; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        
        paginar(){ this.pagina++; this.buscarEventos(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('eventos/assets/images/eventos.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarEventos();
                $(".fit-screen").scroll(function(){
                    let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                    let altura = Math.floor(Number($(".main-layout").height()));
                    let total = altura + distancia;
                    if (total === 0) { self.paginar(); }
                });
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    EventoController.$inject = ["EventoService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('EventoController',['ngMaterial', 'util', 'erudioConfig']).controller('EventoController',EventoController);
})();
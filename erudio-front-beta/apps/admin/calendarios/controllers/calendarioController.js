(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class CalendarioController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.permissaoLabel = "CALENDARIO";
            this.titulo = "Calendários";
            this.linkModulo = "/#!/calendarios/";
            this.calendario = null;
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.isAdmin = false;
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Calendário' }];
            this.opcoes = [{tooltip: 'Ver Calendário', icone: 'event', opcao: 'calendario', validarEscrita: true}, {tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Calendário', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('calendarios');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }
                
        buscarCalendarios(loader) {
            var options = null;
            if (!this.isAdmin) { 
                let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                options = {page: this.pagina, instituicao: unidade};
            } else { options = {page: this.pagina}; };
            if (this.util.isAdmin()) { options = {page: this.pagina}; } else { 
                let unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.id;
                options = {page: this.pagina, instituicao: unidade };
            }
            this.service.getAll(options,loader).then((calendarios) => {
                if (this.pagina === 0) { this.objetos = calendarios; } else { 
                    if (calendarios.length !== 0) { this.objetos = this.objetos.concat(calendarios); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.calendario = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break;
                case 'calendario': this.verCalendario(); break;
                default: return false; break;
            }
        }

        verCalendario() { this.util.redirect(this.erudioConfig.dominio + this.linkModulo + 'view/' + this.calendario.id); }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Calendário", "Deseja remover este calendário?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.calendario.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.calendario, "Calendário","m");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarCalendarios(); this.buscaIcone = 'search'; } else { this.executarBusca(query); this.buscaIcone = 'clear'; } }
        
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarCalendarios(true); }

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
                    self.service.getAll(options,true).then((calendarios) => { self.objetos = calendarios; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        
        paginar(){ this.pagina++; this.buscarCalendarios(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarCalendarios();
                if (this.util.isAdmin()) { this.isAdmin = true; } else { this.isAdmin = false; }
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
    
    CalendarioController.$inject = ["CalendarioService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('CalendarioController',['ngMaterial', 'util', 'erudioConfig']).controller('CalendarioController',CalendarioController);
})();
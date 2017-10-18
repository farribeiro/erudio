(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class TurnoController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $filter) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.filter = $filter;
            this.permissaoLabel = "TURNOS";
            this.titulo = "Turnos";
            this.linkModulo = "/#!/turnos/";
            this.modulo = null;
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome do Turno' }];
            this.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Turnos', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('turnos');
        }
        
        preparaBusca(){
            this.busca = '';
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }

        ajustaHora() {
            this.objetos.forEach(function(objeto, i) {
                var inicioArr = objeto.inicio.split(":"); var terminoArr = objeto.termino.split(":");
                objeto.inicio = inicioArr[0]+":"+inicioArr[1]; objeto.termino = terminoArr[0]+":"+terminoArr[1];
            });
        }
                
        buscarTurnos(loader) {
            this.service.getAll({page: this.pagina},loader).then((turnos) => {
                if (this.pagina === 0) { this.objetos = turnos; this.ajustaHora(); } else { 
                    if (turnos.length !== 0) { this.objetos = this.objetos.concat(turnos); this.ajustaHora(); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.turno = objeto; this.modalExclusao(event);
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Turnos", "Deseja remover este turno?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.turno.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.turno, "Turno","m");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarTurnos(); this.buscaIcone = 'search'; } else { this.executarBusca(query); this.buscaIcone = 'clear'; } }
        
        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarTurnos(true); }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                self.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; self.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((turnos) => { self.objetos = turnos; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }
        
        paginar(){ this.pagina++; this.buscarTurnos(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('turnos/assets/images/turnos.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarTurnos();
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
    
    TurnoController.$inject = ["TurnoService","Util","$mdDialog","ErudioConfig","$timeout","$filter"];
    angular.module('TurnoController',['ngMaterial', 'util', 'erudioConfig']).controller('TurnoController',TurnoController);
})();
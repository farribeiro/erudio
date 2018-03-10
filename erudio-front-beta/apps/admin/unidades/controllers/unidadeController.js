(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class UnidadeController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.permissaoLabel = "UNIDADES_ENSINO";
            this.titulo = "Unidades de Ensino";
            this.linkModulo = "/#!/unidades/";
            this.unidade = null;
            this.pagina = 0;
            this.finalLista = false;
            this.busca = '';
            this.buscaIcone = 'search';
            this.contadorTeste = 0;
            this.iniciar();
        }

        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        preparaLista(){
            this.subheaders = [{ label: 'Nome da Unidade' }];
            this.opcoes = [{ tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true }];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Unidade', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('unidades');
        }

        preparaBusca(){
            this.buscaSimples = this.util.getTemplateBuscaSimples();
        }

        buscarUnidades(loader) {
            this.service.getAll({page: this.pagina},loader).then((unidades) => {
                if (this.pagina === 0) { this.objetos = unidades; } else {
                    if (unidades.length !== 0) { this.objetos = this.objetos.concat(unidades); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }

        executarOpcao(event,opcao,objeto) {
            this.unidade = objeto; this.modalExclusao(event);
        }

        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Unidade de Ensino", "Deseja remover esta Unidade de Ensino?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.unidade.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.unidade, "Unidade de Ensino","f");
                    self.objetos.splice(index,1);
                }
            });
        }

        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarUnidades(true); this.buscaIcone = 'search'; } else { this.executarBusca(query); } }

        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                this.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; this.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((unidades) => { self.objetos = unidades; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }

        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarUnidades(true); }

        paginar(){ this.pagina++; this.buscarUnidades(true); }

        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('unidades/assets/images/unidades.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarUnidades();
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

    UnidadeController.$inject = ["UnidadeService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('UnidadeController',['ngMaterial', 'util', 'erudioConfig']).controller('UnidadeController',UnidadeController);
})();
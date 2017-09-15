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
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.busca = '';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome da Instituição' }];
            this.opcoes = [{ tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true }];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Instituição', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateLista();
            this.lista = this.util.getTemplateListaEspecifica('instituicoes');
        }
        
        preparaBusca(){ this.buscaSimples = this.util.getTemplateBuscaSimples(); }
        
        buscarInstituicoes(loader) {
            this.service.getAll({page: this.pagina},loader).then((instituicoes) => {
                if (this.pagina === 0) { this.objetos = instituicoes; } else { 
                    if (instituicoes.length !== 0) { this.objetos = this.objetos.concat(instituicoes); } else { this.finalLista = true; this.pagina--; }
                }
            });
        }
        
        executarOpcao(event,opcao,objeto) { this.instituicao = objeto; this.modalExclusao(event); }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Instituição", "Deseja remover esta instituição?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.instituicao.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.instituicao, "Instituição","f");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        verificaBusca(query) { if (this.util.isVazio(query)) { this.buscarInstituicoes(true); this.buscaIcone = 'search'; } else { this.executarBusca(query); } }
        
        executarBusca(query) {
            this.timeout.cancel(this.delayBusca); var self = this;
            this.delayBusca = this.timeout(() => {
                this.buscaIcone = 'clear';
                if (self.util.isVazio(query)) { query = ''; this.buscaIcone = 'search'; }
                let tamanho = query.length;
                if (tamanho > 3) {
                    self.service.getAll({ nome: query },true).then((instituicoes) => { self.objetos = instituicoes; });
                } else {
                    self.util.toast('A busca é ativada com no mínimo 4 caracteres.');
                }
            },800);
        }

        limparBusca() { this.busca = ''; $('.busca-simples').val(''); this.buscaIcone = 'search'; this.buscarInstituicoes(true); }
        
        paginar(){ this.pagina++; this.buscarInstituicoes(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
                this.preparaLista();
                this.preparaBusca();
                this.buscarInstituicoes();
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
    
    InstituicaoController.$inject = ["InstituicaoService","Util","$mdDialog","ErudioConfig","$timeout"];
    angular.module('InstituicaoController',['ngMaterial', 'util', 'erudioConfig']).controller('InstituicaoController',InstituicaoController);
})();
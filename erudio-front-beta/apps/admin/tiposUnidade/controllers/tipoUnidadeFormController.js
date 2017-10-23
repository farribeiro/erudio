(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class TipoUnidadeFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.scope.tipo = null;
            this.permissaoLabel = "TIPOS_UNIDADE";
            this.titulo = "Tipos de Unidade";
            this.linkModulo = "/#!/tipos-unidade/";
            this.nomeForm = "tipoUnidadeForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('tiposUnidade','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações', href: this.util.getInputBlockCustom('tiposUnidade','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarTipo() {
            var self = this;
            this.scope.tipo = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((tipo) => {
                    this.scope.tipo = tipo;
                    this.util.aplicarMascaras();
                });
            } else {
                this.novo = true;
                this.timeout(function(){ self.util.aplicarMascaras(); },300);
            }
        }
        
        validaCampo() { this.util.validaCampo(); }
        
        validar() { return this.util.validar(this.nomeForm); }
        
        salvar() {
            if (this.validar()) {
                var resultado = null;
                if (this.util.isNovoObjeto(this.scope.tipo)) {
                    resultado = this.service.salvar(this.scope.tipo);
                } else {
                    resultado = this.service.atualizar(this.scope.tipo);
                }
                resultado.then(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); });
            }
        }
        
        iniciar(){
            let permissao = this.verificarPermissao(); var self = this;
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('tiposUnidade/assets/images/tiposUnidade.jpeg');
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.preparaForm();
                this.buscarTipo();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    TipoUnidadeFormController.$inject = ["TipoUnidadeService","Util","ErudioConfig","$routeParams","$timeout","$scope"];
    angular.module('TipoUnidadeFormController',['ngMaterial', 'util', 'erudioConfig']).controller('TipoUnidadeFormController',TipoUnidadeFormController);
})();
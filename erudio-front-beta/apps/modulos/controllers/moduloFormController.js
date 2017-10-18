(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class ModuloFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, cursoService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.cursoService = cursoService;
            this.timeout = $timeout;
            this.scope.modulo = null;
            this.permissaoLabel = "MODULOS";
            this.titulo = "Módulos";
            this.linkModulo = "/#!/modulos/";
            this.nomeForm = "moduloForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('modulos','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Módulo', href: this.util.getInputBlockCustom('modulos','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarCursos() { this.cursoService.getAll(null,true).then((cursos) => this.cursos = cursos); }
        
        buscarModulo() {
            var self = this;
            this.scope.modulo = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((modulo) => {
                    this.scope.modulo = modulo;
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
                if (this.util.isNovoObjeto(this.scope.modulo)) {
                    resultado = this.service.salvar(this.scope.modulo);
                } else {
                    resultado = this.service.atualizar(this.scope.modulo);
                }
                resultado.then(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); });
            }
        }
        
        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('modulos/assets/images/modulos.jpeg');
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                this.buscarCursos();
                this.buscarModulo();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    ModuloFormController.$inject = ["ModuloService","Util","ErudioConfig","$routeParams","$timeout","$scope","CursoService"];
    angular.module('ModuloFormController',['ngMaterial', 'util', 'erudioConfig']).controller('ModuloFormController',ModuloFormController);
})();
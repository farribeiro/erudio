(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class CursoFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, modalidadeEnsinoService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.modalidadeEnsinoService = modalidadeEnsinoService;
            this.scope.curso = null;
            this.permissaoLabel = "CURSOS";
            this.titulo = "Cursos";
            this.linkModulo = "/#!/cursos/";
            this.nomeForm = "cursoForm";
            this.iniciar();
        }

        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('cursos','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Curso', href: this.util.getInputBlockCustom('cursos','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }

        buscarModalidades() { this.modalidadeEnsinoService.getAll(null,true).then((modalidades) => this.modalidades = modalidades); }

        buscarCurso() {
            var self = this;
            this.scope.curso = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((curso) => {
                    this.scope.curso = curso;
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
                if (this.util.isNovoObjeto(this.scope.curso)) {
                    resultado = this.service.salvar(this.scope.curso);
                } else {
                    resultado = this.service.atualizar(this.scope.curso);
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
                this.util.mudarImagemToolbar('cursos/assets/images/cursos.jpg');
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.buscarModalidades();
                this.preparaForm();
                this.buscarCurso();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }

    CursoFormController.$inject = ["CursoService","Util","ErudioConfig","$routeParams","$timeout","$scope","ModalidadeEnsinoService"];
    angular.module('CursoFormController',['ngMaterial', 'util', 'erudioConfig']).controller('CursoFormController',CursoFormController);
})();
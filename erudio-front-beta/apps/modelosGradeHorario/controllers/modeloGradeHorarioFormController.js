(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class ModeloGradeHorarioFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, cursoService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.cursoService = cursoService;
            this.scope.modulo = null;
            this.mostraCurso = false;
            this.permissaoLabel = "MODELOS_HORARIO";
            this.titulo = "Modelos de Grade de Horário";
            this.linkModulo = "/#!/modelos-horario/";
            this.nomeForm = "modeloHorarioForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('modelosGradeHorario','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Modelo', href: this.util.getInputBlockCustom('modelosGradeHorario','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarCursos() { this.cursoService.getAll(null,true).then((cursos) => this.cursos = cursos); }
                
        buscarModelo() {
            var self = this;
            this.scope.modelo = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((modelo) => {
                    this.scope.modelo = modelo;
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
                if (this.util.isNovoObjeto(this.scope.modelo)) {
                    resultado = this.service.salvar(this.scope.modelo);
                } else {
                    resultado = this.service.atualizar(this.scope.modelo);
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
                this.util.mudarImagemToolbar('modelosGradeHorario/assets/images/modelosGradeHorario.jpg');
                this.scope.etapa = this.service.getEstrutura();
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.buscarCursos();
                this.preparaForm();
                this.buscarModelo();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    ModeloGradeHorarioFormController.$inject = ["ModeloGradeHorarioService","Util","ErudioConfig","$routeParams","$timeout","$scope","CursoService"];
    angular.module('ModeloGradeHorarioFormController',['ngMaterial', 'util', 'erudioConfig']).controller('ModeloGradeHorarioFormController',ModeloGradeHorarioFormController);
})();
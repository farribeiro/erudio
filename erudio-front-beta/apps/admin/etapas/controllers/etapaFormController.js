(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class EtapaFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, sharedService, cursoService, moduloService, sistemaAvaliacaoService, modeloGradeHorarioService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.cursoService = cursoService;
            this.moduloService = moduloService;
            this.sharedService = sharedService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.modeloGradeHorarioService = modeloGradeHorarioService;
            this.scope.etapa = null;
            this.mostraCurso = false;
            this.permissaoLabel = "ETAPAS";
            this.titulo = "Etapas";
            this.linkModulo = "/#!/etapas/";
            this.nomeForm = "etapaForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('etapas','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações da Etapa', href: this.util.getInputBlockCustom('etapas','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarCursos() { this.cursoService.getAll(null,true).then((cursos) => this.cursos = cursos); }
        buscarModulos() { this.moduloService.getAll(null,true).then((modulos) => this.modulos = modulos); }
        buscarSistemaAvaliacoes() { this.sistemaAvaliacaoService.getAll(null,true).then((sistemasAvaliacoes) => this.sistemasAvaliacoes = sistemasAvaliacoes); }
        buscarModeloGradeHorario() { this.modeloGradeHorarioService.getAll(null,true).then((modeloGradeHorario) => this.modeloGradeHorario = modeloGradeHorario); }
        
        buscarCurso() { 
            this.cursoEtapa = this.sharedService.getCursoEtapa();
            if (this.util.isVazio(this.cursoEtapa)) {
                this.mostraCurso = true;
                this.buscarCursos();
            } else {
                this.mostraCurso = false;
            }
        }
        
        buscarEtapa() {
            var self = this;
            this.scope.etapa = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((etapa) => {
                    this.scope.etapa = etapa;
                    this.util.aplicarMascaras();
                });
            } else {
                if (!this.util.isVazio(this.cursoEtapa)) { this.scope.etapa.curso.id = this.cursoEtapa; }
                this.novo = true;
                this.timeout(function(){ self.util.aplicarMascaras(); },300);
            }
        }
        
        validaCampo() { this.util.validaCampo(); }
        
        validar() { return this.util.validar(this.nomeForm); }
        
        salvar() {
            if (this.validar()) {
                var resultado = null;
                if (this.util.isNovoObjeto(this.scope.etapa)) {
                    resultado = this.service.salvar(this.scope.etapa);
                } else {
                    resultado = this.service.atualizar(this.scope.etapa);
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
                this.util.mudarImagemToolbar('etapas/assets/images/etapas.jpg');
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.scope.etapa = this.service.getEstrutura();
                this.buscarCurso();
                this.buscarModulos();
                this.buscarSistemaAvaliacoes();
                this.buscarModeloGradeHorario();
                this.preparaForm();
                this.buscarEtapa();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    EtapaFormController.$inject = ["EtapaService","Util","ErudioConfig","$routeParams","$timeout","$scope","Shared","CursoService","ModuloService","SistemaAvaliacaoService","ModeloGradeHorarioService"];
    angular.module('EtapaFormController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('EtapaFormController',EtapaFormController);
})();
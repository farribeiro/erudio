(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class DisciplinaFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, sharedService, cursoService, etapaService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.sharedService = sharedService;
            this.scope.disciplina = null;
            this.mostraCurso = false;
            this.mostraEtapa = false;
            this.permissaoLabel = "DISCIPLINAS";
            this.titulo = "Disciplinas";
            this.linkModulo = "/#!/disciplinas/";
            this.nomeForm = "disciplinaForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('disciplinas','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações da Etapa', href: this.util.getInputBlockCustom('disciplinas','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarCursos() { this.cursoService.getAll(null,true).then((cursos) => this.cursos = cursos); }
        buscarEtapas() { this.etapaService.getAll({curso: this.scope.disciplina.curso.id},true).then((etapas) => this.etapas = etapas); }
        
        buscarCursoEtapa() { 
            this.cursoEtapa = this.sharedService.getCursoEtapa();
            this.etapaDisciplina = this.sharedService.getEtapaDisciplina();
            if (this.util.isVazio(this.cursoEtapa)) {
                this.mostraCurso = true;
                this.buscarCursos();
            } else { this.mostraCurso = false; this.scope.disciplina.curso.id = this.cursoEtapa; }
            if (this.util.isVazio(this.etapaDisciplina)) {
                this.mostraEtapa = true;
            } else { this.mostraEtapa = false; this.scope.disciplina.etapa.id = this.etapaDisciplina; }
        }
        
        buscarDisicplina() {
            var self = this;
            this.scope.disciplina = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((disciplina) => {
                    this.scope.disciplina = disciplina;
                    this.util.aplicarMascaras();
                });
            } else {
                this.buscarCursoEtapa();
                this.novo = true;
                this.timeout(function(){ self.util.aplicarMascaras(); },300);
            }
        }
        
        validaCampo() { this.util.validaCampo(); }
        
        validar() { return this.util.validar(this.nomeForm); }
        
        salvar() {
            if (this.validar()) {
                var resultado = null;
                if (this.util.isNovoObjeto(this.scope.disciplina)) {
                    resultado = this.service.salvar(this.scope.disciplina);
                } else {
                    resultado = this.service.atualizar(this.scope.disciplina);
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
                this.util.mudarImagemToolbar('disciplinas/assets/images/disciplinas.jpg');
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.preparaForm();
                this.buscarDisicplina();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    DisciplinaFormController.$inject = ["DisciplinaService","Util","ErudioConfig","$routeParams","$timeout","$scope","Shared","CursoService","EtapaService"];
    angular.module('DisciplinaFormController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('DisciplinaFormController',DisciplinaFormController);
})();
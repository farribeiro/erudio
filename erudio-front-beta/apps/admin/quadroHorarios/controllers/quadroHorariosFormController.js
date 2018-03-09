(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class QuadroHorarioFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, unidadeService, modeloGradeHorarioService, turnoService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.unidadeService = unidadeService;
            this.modeloGradeHorarioService = modeloGradeHorarioService;
            this.turnoService = turnoService;
            this.timeout = $timeout;
            this.scope.grade = null;
            this.unidade = null;
            this.permissaoLabel = "QUADROS_HORARIO";
            this.titulo = "Grades de Horário";
            this.linkModulo = "/#!/quadros-horario/";
            this.nomeForm = "gradeForm";
            this.itemBusca = '';
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('quadroHorarios','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações da Grade de Horário', href: this.util.getInputBlockCustom('quadroHorarios','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarUnidades() { this.unidadeService.getAll(null,true).then((unidades) => this.unidades = unidades); }
        buscarModelos() { this.modeloGradeHorarioService.getAll(null,true).then((modelos) => this.modelos = modelos); }
        buscarTurnos() { this.turnoService.getAll(null,true).then((turnos) => this.turnos = turnos); }
        
        buscarGrade() {
            var self = this;
            this.scope.grade = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((grade) => {
                    this.scope.grade = grade;
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
                var resultado = null; this.scope.grade.inicio += ':00';
                this.scope.grade.unidadeEnsino.id = this.unidade.id;

                var modeloNome = '';
                this.modelos.forEach((modelo) => {
                    if (modelo.id === parseInt(this.scope.grade.modelo.id)) { modeloNome = modelo.nome; }
                });

                var turnoNome = '';
                this.turnos.forEach((turno) => {
                    if (turno.id === parseInt(this.scope.grade.turno.id)) { turnoNome = turno.nome; }
                });

                this.scope.grade.nome = modeloNome + " - " + turnoNome;
                if (this.util.isNovoObjeto(this.scope.grade)) {
                    resultado = this.service.salvar(this.scope.grade);
                } else {
                    resultado = this.service.atualizar(this.scope.grade);
                }
                resultado.then(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); });
            }
        }

        filtrar (query) { if (query.length > 2) { return this.unidadeService.getAll({nome: query}); } }
        
        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('quadroHorarios/assets/images/quadroHorarios.jpg');
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                if (this.isAdmin) { this.buscarUnidades(); }
                this.buscarModelos();
                this.buscarTurnos();
                this.buscarGrade();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    QuadroHorarioFormController.$inject = ["QuadroHorarioService","Util","ErudioConfig","$routeParams","$timeout","$scope","UnidadeService","ModeloGradeHorarioService","TurnoService"];
    angular.module('QuadroHorarioFormController',['ngMaterial', 'util', 'erudioConfig']).controller('QuadroHorarioFormController',QuadroHorarioFormController);
})();
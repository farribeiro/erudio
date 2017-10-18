(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class TurnoFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.scope.turno = null;
            this.permissaoLabel = "TURNOS";
            this.titulo = "Turnos";
            this.linkModulo = "/#!/turnos/";
            this.nomeForm = "turnoForm";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('turnos','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Turno', href: this.util.getInputBlockCustom('turnos','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarTurno() {
            var self = this;
            this.scope.turno = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((turno) => {
                    this.scope.turno = turno;
                    let temp = this.scope.turno.inicio.split(":");
                    this.scope.turno.inicio = temp[0]+":"+temp[1];
                    temp = this.scope.turno.termino.split(":");
                    this.scope.turno.termino = temp[0]+":"+temp[1];
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
                if (this.util.isNovoObjeto(this.scope.turno)) {
                    resultado = this.service.salvar(this.scope.turno);
                } else {
                    resultado = this.service.atualizar(this.scope.turno);
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
                this.util.mudarImagemToolbar('turnos/assets/images/turnos.jpg');
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                this.buscarTurno();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    TurnoFormController.$inject = ["TurnoService","Util","ErudioConfig","$routeParams","$timeout","$scope"];
    angular.module('TurnoFormController',['ngMaterial', 'util', 'erudioConfig']).controller('TurnoFormController',TurnoFormController);
})();
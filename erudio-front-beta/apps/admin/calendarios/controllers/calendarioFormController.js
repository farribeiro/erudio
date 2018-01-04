(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class CalendarioFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, unidadeService, sistemaAvaliacaoService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.unidadeService = unidadeService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.timeout = $timeout;
            this.scope.calendario = null;
            this.unidade = null;
            this.permissaoLabel = "CALENDARIO";
            this.titulo = "Calendários";
            this.linkModulo = "/#!/calendarios/";
            this.nomeForm = "calendarioForm";
            this.itemBusca = '';
            this.novo = true;
            this.hoje = new Date();
            this.dataInicio = new Date();
            this.dataTermino = new Date();
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('calendarios','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações do Calendário', href: this.util.getInputBlockCustom('calendarios','inputs')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarSistemas() { this.sistemaAvaliacaoService.getAll(null,true).then((sistemas) => this.sistemas = sistemas); }
        buscarUnidades() { this.unidadeService.getAll(null,true).then((unidades) => this.unidades = unidades); }
        buscarBases() {
            var attrAtual = JSON.parse(sessionStorage.getItem('atribuicao-ativa'));
            this.service.getAll({instituicao: attrAtual.instituicao.id}).then((bases) => this.bases = bases);
        }
        
        filtrar (query) { if (query.length > 2) { return this.unidadeService.getAll({nome: query}); } }

        buscarCalendario() {
            var self = this;
            this.scope.calendario = this.service.getEstrutura();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((calendario) => {
                    this.scope.calendario = calendario;
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
                this.scope.calendario.instituicao.id = this.unidade.id;
                var calBase = "";
                this.bases.forEach((base) => {
                    if (base.id === parseInt(this.scope.calendario.calendarioBase.id)) { calBase = base.nome; }
                });
                this.scope.calendario.nome = this.unidade.nome + " - " + calBase;
                this.scope.calendario.dataInicio = this.dataInicio.getYear() + '-' + this.dataInicio.getMonth() + '-' + this.dataInicio.getDay();
                this.scope.calendario.dataTermino = this.dataTermino.getYear() + '-' + this.dataTermino.getMonth() + '-' + this.dataTermino.getDay();
                var resultado = null;
                if (this.util.isNovoObjeto(this.scope.calendario)) {
                    resultado = this.service.salvar(this.scope.calendario);
                } else {
                    resultado = this.service.atualizar(this.scope.calendario);
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
                this.util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
                this.timeout(()=>{ this.validaCampo(); },500);
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                if (this.isAdmin) { this.buscarUnidades(); }
                this.buscarSistemas();
                this.buscarBases();
                this.buscarCalendario();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    CalendarioFormController.$inject = ["CalendarioService","Util","ErudioConfig","$routeParams","$timeout","$scope","UnidadeService","SistemaAvaliacaoService"];
    angular.module('CalendarioFormController',['ngMaterial', 'util', 'erudioConfig']).controller('CalendarioFormController',CalendarioFormController);
})();
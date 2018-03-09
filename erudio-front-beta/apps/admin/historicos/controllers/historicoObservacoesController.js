(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class HistoricoObservacaoController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, etapaService, etapaCursadaService, disciplinaCursadaService, $mdDialog, mediaService, disciplinaService, $filter, estadoService, cidadeService, observacaoService){
            this.service = service; this.scope = $scope; this.util = util; this.routeParams = routeParams; this.estado = null;
            this.erudioConfig = erudioConfig; this.mdDialog = $mdDialog; this.timeout = $timeout; this.estados = []; this.observacaoService = observacaoService;
            this.scope.matricula = null; this.filter = $filter; this.etapaService = etapaService; this.nomeCidade = ""; this.observacoes = [];
            this.mediaService = mediaService; this.disciplinaService = disciplinaService; this.etapaCursadaService = etapaCursadaService;
            this.disciplinaCursadaService = disciplinaCursadaService; this.estadoService = estadoService; this.cidadeService = cidadeService;
            this.disciplinasCursadas = []; this.modalDiscplina = null; this.etapasDisponiveis = null; this.observacao = null;
            this.scope.matricula = null; this.cursada = this.etapaCursadaService.getEstrutura(); this.linhasTabela = [];
            this.permissaoLabel = "RELATORIOS"; this.titulo = "Histórico Escolar"; this.linkModulo = "/#!/historicos/";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('historicos','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[ {label: 'Etapas', href: this.util.getInputBlockCustom('historicos','etapas')} ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }        
        
        validar(nomeForm) { return this.util.validar(nomeForm); }

        limparCampos() { this.observacao = this.observacaoService.getEstrutura(); }

        buscarObservacoes(matricula) {
            this.observacaoService.getAll({matricula: matricula.id},true).then((observacoes) => {
                this.observacoes = observacoes;
            });
        }

        carregarObservacao(objeto) {
            this.observacaoService.get(objeto.id,true).then((observacao) => {
                this.observacao = observacao;
            });
        }

        salvarObservacao() {
            if (this.validar('novaObservacaoForm')) {
                this.observacao.matricula.id = this.scope.matricula.id;
                this.observacaoService.salvar(this.observacao).then(() => {
                    this.limparCampos();
                    this.timeout(() => { this.buscarObservacoes(this.scope.matricula); },500);
                });
            }
        }

        atualizarObservacao() {
            if (this.validar('novaObservacaoForm')) {
                this.observacaoService.atualizar(this.observacao).then(() => {
                    this.limparCampos();
                    this.timeout(() => { this.buscarObservacoes(this.scope.matricula); },500);
                });
            }
        }
        
        modalExclusao(event,objeto) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Observação", "Deseja remover esta observação?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = objeto.id;
                var index = self.util.buscaIndice(id, self.observacoes);
                if (index !== false) {
                    self.observacaoService.remover(objeto, "Observação","f");
                    self.observacoes.splice(index,1);
                }
            });
        }
        
        buscarMatricula() {
            this.service.get(this.routeParams.id).then((matricula) => {
                this.scope.matricula = matricula;
                this.observacao = this.observacaoService.getEstrutura();
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo + this.scope.matricula.id};
                this.buscarObservacoes(matricula);
            });
        }

        iniciar(){
            let permissao = this.verificarPermissao(); var self = this;
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('historicos/assets/images/historicos.jpg');
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                this.buscarMatricula();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    HistoricoObservacaoController.$inject = ["MatriculaService","Util","ErudioConfig","$routeParams","$timeout","$scope","EtapaService","EtapaCursadaService","DisciplinaCursadaService","$mdDialog","MediaService","DisciplinaService","$filter","EstadoService","CidadeService","ObservacaoService"];
    angular.module('HistoricoObservacaoController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoObservacaoController',HistoricoObservacaoController);
})();
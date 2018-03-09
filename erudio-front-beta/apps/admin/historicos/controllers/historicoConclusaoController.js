(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class HistoricoConclusaoController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, etapaService, etapaCursadaService, disciplinaCursadaService, $mdDialog, mediaService, disciplinaService, $filter, estadoService, cidadeService){
            this.service = service; this.scope = $scope; this.util = util; this.routeParams = routeParams; this.estado = null;
            this.erudioConfig = erudioConfig; this.mdDialog = $mdDialog; this.timeout = $timeout; this.estados = [];
            this.scope.matricula = null; this.filter = $filter; this.etapaService = etapaService; this.nomeCidade = "";
            this.mediaService = mediaService; this.disciplinaService = disciplinaService; this.etapaCursadaService = etapaCursadaService;
            this.disciplinaCursadaService = disciplinaCursadaService; this.estadoService = estadoService; this.cidadeService = cidadeService;
            this.disciplinasCursadas = []; this.modalDiscplina = null; this.etapasDisponiveis = null;
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
        
        buscarEtapas(matricula) { 
            this.linhasTabela = [];
            this.etapaService.getAll({curso: matricula.curso.id},true).then((etapas) => { 
                this.etapas = etapas; this.buscarEtapasCursadas(matricula); 
                this.buscarEstados();
            });
        }

        carregarEtapa(cursada){
            this.cursada = cursada;
            this.etapa = cursada.etapa.id;
            this.estado = cursada.cidade.estado.id;
            this.nomeCidade = cursada.cidade.nome;
        }

        buscarEtapasCursadas(matricula) { 
            this.cursada = this.etapaCursadaService.getEstrutura();
            this.etapa = null; this.estado = null; this.nomeCidade = ''; this.cursada.cidade = null;
            this.cursada.matricula.id = matricula.id;
            this.etapaCursadaService.getAll({matricula: matricula.id},true).then((etapasCursadas) => {
                this.etapasCursadas = etapasCursadas;
            });
        }

        limparCampos() { this.buscarEtapasCursadas(this.cursada.matricula); }

        validar(nomeForm) { return this.util.validar(nomeForm); }

        salvarNovaEtapa() {
            if (this.validar('novaEtapaForm')) {
                this.cursada.etapa.id = this.etapa;
                this.etapaCursadaService.salvar(this.cursada).then(() => {
                    this.limparCampos();
                });
            }
        }

        atualizarEtapa() {
            if (this.validar('novaEtapaForm')) {
                delete this.cursada.etapa;
                this.etapaCursadaService.atualizar(this.cursada).then(() => {
                    this.limparCampos();
                });
            }
        }
        
        modalExclusao(event,objeto) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Dados de Conclusão", "Deseja remover estes dados?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = objeto.id;
                var index = self.util.buscaIndice(id, self.etapasCursadas);
                if (index !== false) {
                    self.etapaCursadaService.remover(objeto, "Dados de Conclusão","m");
                    self.etapasCursadas.splice(index,1);
                }
            });
        }
        
        buscarMatricula() {
            this.service.get(this.routeParams.id).then((matricula) => {
                this.scope.matricula = matricula;
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo + this.scope.matricula.id};
                this.buscarEtapas(matricula);
            });
        }

        buscarEstados() { 
            this.estadoService.getAll(null,true).then((estados) => {
                this.estados = estados;
            });
        }

        buscarCidades (query) { 
            if (query.length > 2) {
                var self = this;
                if (self.util.isVazio(this.estado)) {
                    return [{id: null, nome: 'É necessário selecionar um estado.'}];
                } else {
                    return this.cidadeService.getAll({estado: this.estado, nome: query},true);
                }
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
                this.util.mudarImagemToolbar('historicos/assets/images/historicos.jpg');
                $(".fit-screen").unbind('scroll');
                this.preparaForm();
                this.buscarMatricula();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    HistoricoConclusaoController.$inject = ["MatriculaService","Util","ErudioConfig","$routeParams","$timeout","$scope","EtapaService","EtapaCursadaService","DisciplinaCursadaService","$mdDialog","MediaService","DisciplinaService","$filter","EstadoService","CidadeService"];
    angular.module('HistoricoConclusaoController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoConclusaoController',HistoricoConclusaoController);
})();
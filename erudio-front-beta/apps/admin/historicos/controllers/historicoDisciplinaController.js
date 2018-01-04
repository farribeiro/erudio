(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class HistoricoDisciplinaController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, etapaService, etapaCursadaService, disciplinaCursadaService, $mdDialog, mediaService, disciplinaService, $filter){
            this.service = service; this.scope = $scope; this.util = util; this.routeParams = routeParams;
            this.erudioConfig = erudioConfig; this.mdDialog = $mdDialog; this.timeout = $timeout;
            this.scope.matricula = null; this.filter = $filter; this.etapaService = etapaService;
            this.mediaService = mediaService; this.disciplinaService = disciplinaService;
            this.etapaCursadaService = etapaCursadaService; this.disciplinaCursadaService = disciplinaCursadaService;
            this.disciplinasCursadas = []; this.disciplinasTodas = []; this.modalDiscplina = null; this.disciplinaIndex = null;
            this.nomeDisciplina = null; this.etapa = null; this.disciplina = null; this.abreNaoOfertada = false;
            this.linhasTabela = []; this.permissaoLabel = "RELATORIOS"; this.titulo = "Histórico Escolar";
            this.linkModulo = "/#!/historicos/"; this.iniciar();
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
                this.etapas = etapas;
                this.buscarDisciplinasCursadas(matricula);
                this.timeout(() => { this.util.aplicarMascaras(); },500);
            });
        }
        
        buscarDisciplinasCursadas(matricula) { 
            this.disciplinaCursadaService.getAll({matricula: matricula.id},true).then((disciplinasCursadas) => { 
                var filtrados = this.filter('filter')(disciplinasCursadas, {auto: false});
                this.disciplinasCursadas = filtrados;
            });
        }

        buscarDisciplinas(etapa) {
            this.disciplinasTodas = [];
            this.disciplinaService.getAll({etapa: etapa},true).then((disciplinas) => {
                this.disciplinasTodas = disciplinas;
            });
        }

        selecionarDisciplina(disciplina) {
            if (disciplina === 'outro') { this.abreNaoOfertada = true; } else { this.cursada.disciplina.id = disciplina; }
        }

        buscarDisciplinaNaoOfertada(disciplina) {
            if (disciplina.length > 2) {
                return this.disciplinaService.getAll({nome: disciplina, etapa: this.etapa, incluirNaoOfertadas: 1}, true);
            }
        }

        limparCampos() {
            this.cursada = this.disciplinaCursadaService.getEstrutura();
            this.cursada.frequenciaTotal = '100'; this.abreNaoOfertada = false;
            this.nomeDisciplina = null; this.etapa = null; this.disciplina = null;
        }

        carregarDisciplina(disciplina,$index) {
            this.disciplinaIndex = $index;
            this.disciplinaCursadaService.get(disciplina.id,true).then((cursada) => {
                this.cursada = cursada;
            });
        }

        validar(nomeForm) { return this.util.validar(nomeForm); }

        salvarNovaDisciplina() {
            if (this.validar('novaDisciplinaForm')) {
                this.cursada.matricula.id = this.scope.matricula.id;
                this.disciplinaCursadaService.salvar(this.cursada).then((cursada) => {
                    this.disciplinasCursadas.push(cursada);
                    this.limparCampos();
                });
            }
        }

        atualizarDisciplina() {
            if (this.validar('novaDisciplinaForm')) {
                delete this.cursada.nome; delete this.cursada.nomeExibicao; delete this.cursada.sigla;
                this.disciplinaCursadaService.atualizar(this.cursada).then((cursada) => {
                    this.disciplinasCursadas[index] = cursada;
                    this.limparCampos();
                });
            }
        }
        
        modalExclusao(event,objeto) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Disciplina", "Deseja remover esta disciplina?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = objeto.id;
                var index = self.util.buscaIndice(id, self.disciplinasCursadas);
                if (index !== false) {
                    self.disciplinaCursadaService.remover(objeto,"Disciplina","f");
                    self.disciplinasCursadas.splice(index,1);
                }
            });
        }
        
        buscarMatricula() {
            this.service.get(this.routeParams.id).then((matricula) => {
                this.scope.matricula = matricula;
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo + this.scope.matricula.id};
                this.cursada = this.disciplinaCursadaService.getEstrutura();
                this.cursada.frequenciaTotal = '100';
                this.buscarEtapas(matricula);
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
    
    HistoricoDisciplinaController.$inject = ["MatriculaService","Util","ErudioConfig","$routeParams","$timeout","$scope","EtapaService","EtapaCursadaService","DisciplinaCursadaService","$mdDialog","MediaService","DisciplinaService","$filter"];
    angular.module('HistoricoDisciplinaController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoDisciplinaController',HistoricoDisciplinaController);
})();
(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class HistoricoViewController {
        constructor(service, util, erudioConfig, routeParams, $timeout, $scope, etapaService, etapaCursadaService, disciplinaCursadaService, $mdDialog, mediaService, disciplinaService){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.mdDialog = $mdDialog;
            this.timeout = $timeout;
            this.scope.matricula = null;
            this.etapaService = etapaService;
            this.mediaService = mediaService;
            this.disciplinaService = disciplinaService;
            this.etapaCursadaService = etapaCursadaService;
            this.disciplinaCursadaService = disciplinaCursadaService;
            this.disciplinasCursadas = null;
            this.modalDiscplina = null;
            this.scope.matricula = null;
            this.permissaoLabel = "RELATORIOS";
            this.titulo = "Histórico Escolar";
            this.linkModulo = "/#!/historicos/";
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('historicos','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[ {label: 'Etapas', href: this.util.getInputBlockCustom('historicos','etapas')} ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        buscarEtapas(matricula) { this.etapaService.getAll({curso: matricula.curso.id},true).then((etapas) => { this.etapas = etapas; this.buscarEtapasCursadas(matricula); }); }
        buscarDisciplinasCursadas(etapa, matricula) { this.disciplinaCursadaService.getAll({etapa: etapa.id, matricula: matricula.id},true).then((disciplinasCursadas) => this.disciplinasCursadas = disciplinasCursadas); }

        buscarEtapasCursadas(matricula) { 
            this.etapaCursadaService.getAll({matricula: matricula.id},true).then((etapasCursadas) => {
                this.etapasCursadas = etapasCursadas;
                this.etapas.forEach((etapa) => {
                    this.etapasCursadas.forEach((etapaC) => {
                        if (etapa.id === etapaC.etapa.id) {
                            etapa.etapaCursada = etapaC;
                        }
                    });
                });
            });
        }

        buscarTodasDisciplinas(etapaCursada, disciplinaCursada, $index) {
            var self = this; var etapa = etapaCursada.matricula.etapaAtual;
            var options = {timeout: this.timeout, util: this.util, config: this.erudioConfig};
            if (disciplinaCursada !== undefined) { 
                if (!disciplinaCursada.auto) { options.disciplinaCursada = disciplinaCursada; }
            }
            this.disciplinaService.getAll({etapa: etapa.id, incluirNaoOfertadas: true }).then((disciplinas) => {
                options.disciplinas = disciplinas;
                this.disciplinasTodas = disciplinas;
                this.modalDiscplina = this.mdDialog.show({locals: {attrs: options }, controller: this.modalDisciplinasControl, templateUrl: this.erudioConfig.dominio+'/apps/admin/historicos/partials/adicionar-disciplina.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
                this.timeout(() => {
                    $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvarNovaDisciplina(); } });
                    if (this.util.isVazio(disciplinaCursada)) {
                        $(".salvar-nova-disciplina").click(function(){ self.salvarNovaDisciplina(); });
                    } else {
                        $(".atualizar-nova-disciplina").click(function(){ self.atualizarDisciplina(disciplinaCursada, $index); });
                    }
                },1000);
            });
        }

        validar(nomeForm) { return this.util.validar(nomeForm); }

        salvarNovaDisciplina() {
            if (this.validar('novaDisciplinaForm')) {
                let disciplinaCursada = this.disciplinaCursadaService.getEstrutura();
                disciplinaCursada.matricula.id = this.scope.matricula.id;
                let disciplinaNome = $("#disciplina span div.md-text").html();
                this.disciplinasTodas.forEach((disciplinaC) => {
                    if (disciplinaC.nomeExibicao === disciplinaNome) {
                        disciplinaCursada.disciplina.id = disciplinaC.id;
                    }
                });
                disciplinaCursada.mediaFinal = $("#media").val();
                disciplinaCursada.frequenciaTotal = $("#freq").val();
                disciplinaCursada.status = $("#status span div.md-text").html().toUpperCase();

                this.disciplinaCursadaService.salvar(disciplinaCursada).then((cursada) => {
                    this.disciplinasCursadas.push(cursada);
                    this.mdDialog.hide( this.modalDiscplina, "finished" );
                });
            }
        }

        atualizarDisciplina(disciplina,index) {
            if (this.validar('novaDisciplinaForm')) {
                delete disciplina.ano; delete disciplina.nome;
                delete disciplina.nomeExibicao; delete disciplina.sigla;
                disciplina.mediaFinal = $("#media").val();
                disciplina.frequenciaTotal = $("#freq").val();
                disciplina.status = $("#status span div.md-text").html().toUpperCase();
                this.disciplinaCursadaService.atualizar(disciplina).then((cursada) => {
                    this.disciplinasCursadas[index] = cursada;
                    this.mdDialog.hide( this.modalDiscplina, "finished" );
                });
            }
        }

        modalDisciplinasControl($scope, attrs) { 
            $scope.util = attrs.util;
            $scope.timeout = attrs.timeout;
            if (attrs.disciplinaCursada !== undefined) { 
                $scope.cursada = attrs.disciplinaCursada; $scope.atualizacaoDisciplina = true;
                $scope.cursada.disciplina.id = attrs.disciplinaCursada.disciplina.id;
            } else { $scope.cursada = []; $scope.atualizacaoDisciplina = false; }
            $scope.todasDisciplinas = attrs.disciplinas;
            $scope.config = attrs.config;

            $scope.validaCampo = function(){ $scope.util.validaCampo(); };
            $scope.timeout(()=>{ $scope.validaCampo(); },500);

            $scope.travarCampos = function(){ $scope.atualizacaoDisciplina = true; };
        }
        
        modalExclusao(event,objeto) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Disciplina", "Deseja remover esta disciplina?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = objeto.id;
                var index = self.util.buscaIndice(id, self.disciplinasCursadas);
                if (index !== false) {
                    self.service.remover(objeto, "Disciplina","f");
                    self.disciplinasCursadas.splice(index,1);
                }
            });
        }
        
        buscarMatricula() {
            this.service.get(this.routeParams.id).then((matricula) => {
                this.scope.matricula = matricula;
                this.buscarEtapas(matricula);
            });
        }

        abrirPainel(index, etapa) {            
            this.fecharPainel();
            $(".condensed-panel-"+index).hide();
            $(".expanded-panel-"+index).show();
            this.buscarDisciplinasCursadas(etapa, this.scope.matricula);
        }

        fecharPainel() {
            $(".expanded-panel").hide();
            $(".condensed-panel").show();
        }

        ultimoPainel(index) {
            if (this.etapas.length-1 === index) { 
                return 'ultimo-panel';
            } else {
                return '';
            }
        }

        abrirDisciplinasModal(etapaCursada,disciplinaCursada,$index) {
            this.buscarTodasDisciplinas(etapaCursada, disciplinaCursada, $index);
        }

        abrirDadosConclusaoModal(etapaCursada,disciplinaCursada,$index) {
            this.buscarTodasDisciplinas(etapaCursada, disciplinaCursada, $index);
        }

        abrirMediaParcial(cursada) {
            this.mediaService.getAll({disciplinaCursada: cursada.id},true).then((medias) => {
                var titulos = []; var mediasParciais = [];
                for (var i=0; i<medias.length; i++) { 
                    titulos.push("M"+(i+1)); titulos.push("Faltas");
                    mediasParciais.push(medias[i].valor); mediasParciais.push(medias[i].faltas);
                }
                this.mdDialog.show({locals: {attrs: {disciplina: cursada, medias: mediasParciais, titulos: titulos, config: this.erudioConfig} }, controller: this.modalMediasControl, templateUrl: this.erudioConfig.dominio+'/apps/admin/historicos/partials/medias-parciais.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
            });
        }

        modalMediasControl($scope, attrs) { 
            $scope.mediasParciais = attrs.medias;
            $scope.titulos = attrs.titulos;
            $scope.config = attrs.config;
            $scope.disciplina = attrs.disciplina;

            $scope.ultimaColuna = function (index){
                var classe = '';
                if (index%2 === 1) { classe += "odd-cell "; }
                return classe;
            };
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
    
    HistoricoViewController.$inject = ["MatriculaService","Util","ErudioConfig","$routeParams","$timeout","$scope","EtapaService","EtapaCursadaService","DisciplinaCursadaService","$mdDialog","MediaService","DisciplinaService"];
    angular.module('HistoricoViewController',['ngMaterial', 'util', 'erudioConfig','shared']).controller('HistoricoViewController',HistoricoViewController);
})();
(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class ChamadaController {
        constructor(service, util, erudioConfig, $timeout, turmaService, aulaService, diaService, quadroHorarioService, $mdDialog){
            this.service = service;
            this.util = util;
            this.erudioConfig = erudioConfig;
            this.turmaService = turmaService;
            this.aulaService = aulaService;
            this.quadroHorarioService = quadroHorarioService;
            this.mdDialog = $mdDialog;
            this.diaService = diaService;
            this.timeout = $timeout;
            
            
            this.horarios = [];
            this.menuHorarios = [];
            this.aulasSemana = {segunda: [], terca: [], quarta: [], quinta: [], sexta: []};
            this.calendario = null;
            this.mesCalendario = [];
            this.semanaCalendario = [];
            this.permissaoLabel = "HOME_PROFESSOR";
            this.nomeForm = "calendarioForm";
            this.mes = null;
            this.ano = null;

            this.dataAula = new Date();
            this.diasAula = [];
            this.turma = null;
            this.disciplina = JSON.parse(sessionStorage.getItem('turmaSelecionada'));

            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }

        buscarDatas() {
            var self = this; var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.horarios = [];
            this.turmaService.get(obj.turma.id,true).then((turma) => {
                this.turma = turma;
                this.quadroHorarioService.getHorarios(turma.quadroHorario.id,turma.id,true).then((horarios) => {
                    for (var i=0; i<horarios.length; i++) {
                        if (!this.util.isVazio(horarios[i].disciplinaAlocada)) {
                            if (this.diasAula.indexOf(horarios[i].diaSemana.diaSemana-1) === -1) {
                                this.diasAula.push(horarios[i].diaSemana.diaSemana-1);
                            }
                        }
                    }
                });
            });
        }

        diasDeAula(data) {
            var dia = data.getDay();
            return dia === 1 || dia === 2 || dia === 3 || dia === 4 || dia === 5;
        }

        buscarAlunos() {
            this.aulaService.getAll({  },true).then((aula) => {

            });
        }

        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back'};
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.buscarDatas();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    ChamadaController.$inject = ["CalendarioService","Util","ErudioConfig","$timeout","TurmaService","AulaService","DiaService","QuadroHorarioService","$mdDialog"];
    angular.module('ChamadaController',['ngMaterial', 'util', 'erudioConfig']).controller('ChamadaController',ChamadaController);
})();
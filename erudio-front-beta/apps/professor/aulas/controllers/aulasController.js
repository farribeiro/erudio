(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class AulaController {
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
            this.turma = null;
            this.disciplina = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
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
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        resetCalendario() { this.mesCalendario = []; this.semanaCalendario = []; }

        preparaCalendario(mes,ano) {
            var self = this;
            if (this.util.isVazio(mes) && this.util.isVazio(ano)) {
                var dateBase = new Date(); this.mes = dateBase.getMonth(); this.ano = dateBase.getFullYear();
                this.preparaCalendario(this.mes, this.ano);
            } else {
                this.diaS = 1; this.mes = mes; this.ano = ano; this.diaSemana = new Date(this.ano,this.mes,this.diaS).getDay();
                this.counterCalendario = this.diaSemana; this.gapInicio = this.diaSemana;
                this.diasMes = this.util.diasNoMes(this.mes,this.ano); this.semanaCalendario = new Array(this.gapInicio); this.nomeMes = this.util.nomeMes(this.mes);
                this.timeout(function(){ self.linkPaginacao(); },500); self.buscarEventos();
            }
        };

        buscarCalendario() {
            var self = this; var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.horarios = []; this.menuHorarios = []; this.aulasSemana = {segunda: [], terca: [], quarta: [], quinta: [], sexta: []};
            this.turmaService.get(obj.turma.id,true).then((turma) => {
                this.turma = turma;
                this.quadroHorarioService.getHorarios(turma.quadroHorario.id,turma.id,true).then((horarios) => {
                    for (var i=0; i<horarios.length; i++) {
                        horarios[i].inicio = this.util.converteHora(horarios[i].inicio);
                        if (!this.util.isVazio(horarios[i].disciplinaAlocada)) {
                            switch (horarios[i].diaSemana.diaSemana) {
                                case "2":
                                    this.aulasSemana.segunda.push(horarios[i]);
                                break;
                                case "3":
                                    this.aulasSemana.terca.push(horarios[i]);
                                break;
                                case "4":
                                    this.aulasSemana.quarta.push(horarios[i]);
                                break;
                                case "5":
                                    this.aulasSemana.quinta.push(horarios[i]);
                                break;
                                case "6":
                                    this.aulasSemana.sexta.push(horarios[i]);
                                break;
                                default: return false; break;
                            }
                        }
                    }
                });
                this.calendario = turma.calendario; this.preparaCalendario();
            });
        }

        /*escolherDia(dia) {
            var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            var aula = this.aulaService.getEstruturaAula();
            aula.turma.id = obj.turma.id;
            aula.dia.id = dia.id;
            aula.disciplinasOfertadas.push({id: obj.disciplinaId});
            
        }*/
        
        buscarEventos() {
            this.service.getDiasPorMes(this.calendario,this.mes+1,true).then((dias) => {
                this.dias = dias;
                for (var i=0; i<this.diasMes; i++) {                        
                    this.counterCalendario++; this.semanaCalendario.push(this.dias[i]);
                    if (this.counterCalendario === 7) { this.mesCalendario.push(this.semanaCalendario); this.counterCalendario = 0; this.semanaCalendario = []; }
                    if (i === this.diasMes-1) {
                        var dataFinal = new Date(this.ano,this.mes,i+1); this.gapFinal = 6 - dataFinal.getDay();
                        for (var j=0; j<this.gapFinal; j++) { this.semanaCalendario.push(null); if (j === this.gapFinal-1) { this.mesCalendario.push(this.semanaCalendario); } }
                    }
                }
            });
        }
        
        linkPaginacao() {
            this.proximoMes = new Date(this.ano,this.mes,1); this.proximoMes.setMonth(this.proximoMes.getMonth()+1);
            this.mesAnterior = new Date(this.ano,this.mes,1); this.mesAnterior.setMonth(this.mesAnterior.getMonth()-1);
        }
        
        classeTipoDia(dia){ if (!this.util.isVazio(dia)) { if (dia.efetivo) { return 'calendario-dia-efetivo'; } else if (dia.letivo) { return 'calendario-dia-letivo'; } else { return 'calendario-dia-nao-letivo'; } } }
        
        paginaProxima(){ this.resetCalendario(); this.preparaCalendario(this.proximoMes.getMonth(),this.proximoMes.getFullYear()); }
        paginaAnterior(){ this.resetCalendario(); this.preparaCalendario(this.mesAnterior.getMonth(),this.mesAnterior.getFullYear()); }

        iniciar(){
            let permissao = this.verificarPermissao();
            if (permissao) {
                this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back'};
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.buscarCalendario();
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    AulaController.$inject = ["CalendarioService","Util","ErudioConfig","$timeout","TurmaService","AulaService","DiaService","QuadroHorarioService","$mdDialog"];
    angular.module('AulaController',['ngMaterial', 'util', 'erudioConfig']).controller('AulaController',AulaController);
})();
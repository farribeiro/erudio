(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class AulaController {
        constructor(service, util, erudioConfig, $timeout, turmaService, aulaService, diaService, quadroHorarioService, frequenciaService){
            this.service = service;
            this.util = util;
            this.erudioConfig = erudioConfig;
            this.turmaService = turmaService;
            this.aulaService = aulaService;
            this.quadroHorarioService = quadroHorarioService;
            this.frequenciaService = frequenciaService;
            this.diaService = diaService;
            this.timeout = $timeout;
            this.turma = null;
            this.disciplina = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.horarios = [];
            this.menuHorarios = [];
            this.aulasSemana = {segunda: [], terca: [], quarta: [], quinta: [], sexta: []};
            this.calendario = null;
            this.mesCalendario = [];
            this.diaSelecionado = null;
            this.semanaCalendario = [];
            this.permissaoLabel = "HOME_PROFESSOR";
            this.nomeForm = "calendarioForm";
            this.aula = null;
            this.mes = null;
            this.ano = null;
            this.chamada = false;
            this.alunos = null;
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
                            horarios[i].inicio = this.util.converteHora(horarios[i].inicio);
                            switch (horarios[i].diaSemana.diaSemana) {
                                case "2":
                                    if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.segunda.push(horarios[i]); }
                                break;
                                case "3":
                                    if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.terca.push(horarios[i]); }
                                break;
                                case "4":
                                    if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.quarta.push(horarios[i]); }
                                break;
                                case "5":
                                    if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.quinta.push(horarios[i]); }
                                break;
                                case "6":
                                    if (horarios[i].disciplinaAlocada.id === this.disciplina.id) { this.aulasSemana.sexta.push(horarios[i]); }
                                break;
                                default: return false; break;
                            }
                        }
                    }
                });
                this.calendario = turma.calendario; this.preparaCalendario();
            });
        }

        escolherDia(dia,horarios) {
            this.diaSelecionado = dia;
            var obj = JSON.parse(sessionStorage.getItem('turmaSelecionada'));
            this.aulaService.getAll({dia: dia.id, disciplina: obj.id},true).then((aulas) => {
                if (aulas.length === 0) {
                    var aula = this.aulaService.getEstruturaAula();
                    aula.turma.id = obj.turma.id;
                    aula.dia.id = dia.id;
                    if (!obj.turma.etapa.frequanciaUnificada) {
                        aula.disciplina.id = obj.id;
                        if (horarios.length === 1) { aula.horario.id = horarios[0].id; }
                    }
                    var resultado = this.aulaService.salvar({aulas:[aula]});
                    resultado.then(() => { this.escolherDia(dia, horarios); });
                } else {
                    this.aula = aulas[0];
                    this.frequenciaService.getAll({aula: this.aula.id}, true).then((alunos) => {
                        this.alunos = alunos;
                    });
                }
            });
            this.chamada = true;
        }

        fecharForm(){
            this.chamada = false;
        }
        
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
    
    AulaController.$inject = ["CalendarioService","Util","ErudioConfig","$timeout","TurmaService","AulaService","DiaService","QuadroHorarioService","FrequenciaService"];
    angular.module('AulaController',['ngMaterial', 'util', 'erudioConfig']).controller('AulaController',AulaController);
})();
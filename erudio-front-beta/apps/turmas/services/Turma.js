(function (){
    'use strict';    
    class TurmaService {        
        constructor(rest,unidadeService,cursoService,etapaService,turnoService,calendarioService,quadroHorarioService,periodoService){
            this.rest = rest;
            this.unidadeService = unidadeService;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.turnoService = turnoService;
            this.quadroHorarioService = quadroHorarioService;
            this.calendarioService = calendarioService;
            this.periodoService = periodoService;
            this.url = 'turmas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nome: null, apelido: null, calendario: {id: null}, limiteAlunos: null, turno: {id: null}, etapa: {id: null}, unidadeEnsino: {id: null}, quadroHorario: {id: null}, periodo: {id: null} }; }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Turma", "F"); }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Turma", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Turma", "F"); }
    };
    
    angular.module('TurmaService',[]).service('TurmaService',TurmaService);
    TurmaService.$inject = ["BaseService","UnidadeService","CursoService","EtapaService","TurnoService","CalendarioService","QuadroHorarioService","PeriodoService"];
})();
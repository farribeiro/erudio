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
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome: null, apelido: null, calendario: {id: null}, limiteAlunos: null, turno: {id: null}, etapa: {id: null}, unidadeEnsino: {id: null}, quadroHorario: {id: null}, periodo: {id: null} }; }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Turma", "F",loader); }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Turma", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Turma", "F",loader); }
    };
    
    angular.module('TurmaService',[]).service('TurmaService',TurmaService);
    TurmaService.$inject = ["BaseService","UnidadeService","CursoService","EtapaService","TurnoService","CalendarioService","QuadroHorarioService","PeriodoService"];
})();
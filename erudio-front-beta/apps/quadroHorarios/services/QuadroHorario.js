(function (){
    'use strict';    
    class QuadroHorarioService {        
        constructor(rest,turnoService,unidadeService){
            this.rest = rest;
            this.turnoService = turnoService;
            this.unidadeService = unidadeService;
            this.url = 'quadro-horarios';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getTurnos(opcoes){ return this.turnoService.getAll(opcoes); }
        getUnidades(opcoes){ return this.unidadeService.getAll(opcoes); }
        getEstrutura() { return { nome: null, inicio: null, modelo: {id: null}, unidadeEnsino: {id: null}, turno: {id: null}, diasSemana: [{diaSemana: '2'}, {diaSemana: '3'}, {diaSemana: '4'}, {diaSemana: '5'}, {diaSemana: '6'}] }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Quadro de Horário", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Quadro de Horário", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Quadro de Horário", "M"); }
    };
    
    angular.module('QuadroHorarioService',[]).service('QuadroHorarioService',QuadroHorarioService);
    QuadroHorarioService.$inject = ["BaseService","TurnoService","UnidadeService"];
})();
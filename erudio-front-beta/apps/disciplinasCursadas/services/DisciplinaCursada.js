(function (){
    'use strict';    
    class DisciplinaCursadaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'disciplinas-cursadas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { matricula: {id: null}, disciplina: {id: null}, mediaFinal: null, frequenciaTotal: null, status: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Disciplina", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Disciplina", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Disciplina", "F"); }
    };

    angular.module('DisciplinaCursadaService',[]).service('DisciplinaCursadaService',DisciplinaCursadaService);
    DisciplinaCursadaService.$inject = ["BaseService"];
})();
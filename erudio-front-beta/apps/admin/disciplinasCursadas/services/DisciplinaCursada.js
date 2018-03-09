(function (){
    'use strict';    
    class DisciplinaCursadaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'disciplinas-cursadas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { matricula: {id: null}, disciplina: {id: null}, mediaFinal: null, frequenciaTotal: 100, status: null, ano: null }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Disciplina", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Disciplina", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Disciplina", "F",loader); }
    };

    angular.module('DisciplinaCursadaService',[]).service('DisciplinaCursadaService',DisciplinaCursadaService);
    DisciplinaCursadaService.$inject = ["BaseService"];
})();
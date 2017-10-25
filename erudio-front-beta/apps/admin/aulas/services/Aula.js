(function (){
    'use strict';    
    class AulaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'professor/aulas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstruturaAula() { return { turma: {id:null}, dia: {id:null}, horario: {id:null}, disciplina: {id:null} }; }
        getEstrutura() { return { aulas: [] }; }
        salvar(objeto,loader) { return this.rest.salvarLote(objeto, this.url, "Aula", "F", loader, true); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Aula", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Aula", "F",loader); }
    };
    
    angular.module('AulaService',[]).service('AulaService',AulaService);
    AulaService.$inject = ["BaseService"];
})();
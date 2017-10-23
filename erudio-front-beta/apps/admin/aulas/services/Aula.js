(function (){
    'use strict';    
    class AulaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'aulas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstruturaAula() { return { turma: {id:null}, dia: {id:null}, horario: {id:null}, disciplinasOfertadas: [] }; }
        getEstrutura() { return { aulas: [] }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Módulo", "M",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Módulo", "M",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Módulo", "M",loader); }
    };
    
    angular.module('AulaService',[]).service('AulaService',AulaService);
    AulaService.$inject = ["BaseService","CursoService"];
})();
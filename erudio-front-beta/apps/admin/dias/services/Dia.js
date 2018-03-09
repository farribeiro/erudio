(function (){
    'use strict';    
    class DiaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'dias';
        }
        
        get(id,idCalendario,loader){ return this.rest.um('calendarios/'+idCalendario+'/'+this.url,id,loader); }
        getAll(opcoes,idCalendario,loader){ return this.rest.buscar('calendarios/'+idCalendario+'/'+this.url,opcoes,loader); }
        //getEstrutura() { return { turma: {id:null}, dia: {id:null}, horario: {id:null}, disciplinasOfertadas: [] }; }
        salvar(objeto, idCalendario, loader) { return this.rest.salvar('calendarios/'+idCalendario+'/'+this.url, objeto, "Dia", "M",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Dia", "M",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Dia", "M",loader); }
    };
    
    angular.module('DiaService',[]).service('DiaService',DiaService);
    DiaService.$inject = ["BaseService"];
})();
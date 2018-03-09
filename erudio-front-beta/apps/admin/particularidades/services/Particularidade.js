(function (){
    'use strict';    
    class ParticularidadeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'particularidades';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('ParticularidadeService',[]).service('ParticularidadeService',ParticularidadeService);
    ParticularidadeService.$inject = ["BaseService"];
})();
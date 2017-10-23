(function (){
    'use strict';    
    class RacaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'racas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('RacaService',[]).service('RacaService',RacaService);
    RacaService.$inject = ["BaseService"];
})();
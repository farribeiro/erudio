(function (){
    'use strict';    
    class RacaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'racas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('RacaService',[]).service('RacaService',RacaService);
    RacaService.$inject = ["BaseService"];
})();
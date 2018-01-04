(function (){
    'use strict';    
    class BeneficioService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'beneficios-sociais';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('BeneficioService',[]).service('BeneficioService',BeneficioService);
    BeneficioService.$inject = ["BaseService"];
})();
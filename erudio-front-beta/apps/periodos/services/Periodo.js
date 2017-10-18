(function (){
    'use strict';    
    class PeriodoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'periodos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('PeriodoService',[]).service('PeriodoService',PeriodoService);
    PeriodoService.$inject = ["BaseService"];
})();
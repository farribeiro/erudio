(function (){
    'use strict';    
    class EstadoCivilService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'estados-civis';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('EstadoCivilService',[]).service('EstadoCivilService',EstadoCivilService);
    EstadoCivilService.$inject = ["BaseService"];
})();
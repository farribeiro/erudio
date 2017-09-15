(function (){
    'use strict';    
    class EstadoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'estados';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('EstadoService',[]).service('EstadoService',EstadoService);
    EstadoService.$inject = ["BaseService"];
})();
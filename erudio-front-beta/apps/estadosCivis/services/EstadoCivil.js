(function (){
    'use strict';    
    class EstadoCivilService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'estados-civis';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('EstadoCivilService',[]).service('EstadoCivilService',EstadoCivilService);
    EstadoCivilService.$inject = ["BaseService"];
})();
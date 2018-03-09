(function (){
    'use strict';    
    class NecessidadeEspecialService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'necessidades-especiais';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('NecessidadeEspecialService',[]).service('NecessidadeEspecialService',NecessidadeEspecialService);
    NecessidadeEspecialService.$inject = ["BaseService"];
})();
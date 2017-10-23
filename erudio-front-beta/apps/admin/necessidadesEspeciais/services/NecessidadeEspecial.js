(function (){
    'use strict';    
    class NecessidadeEspecialService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'necessidades-especiais';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('NecessidadeEspecialService',[]).service('NecessidadeEspecialService',NecessidadeEspecialService);
    NecessidadeEspecialService.$inject = ["BaseService"];
})();
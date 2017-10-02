(function (){
    'use strict';    
    class SistemaAvaliacaoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'sistemas-avaliacao';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('SistemaAvaliacaoService',[]).service('SistemaAvaliacaoService',SistemaAvaliacaoService);
    SistemaAvaliacaoService.$inject = ["BaseService"];
})();
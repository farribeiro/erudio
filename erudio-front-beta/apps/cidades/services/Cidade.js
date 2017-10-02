(function (){
    'use strict';    
    class CidadeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'cidades';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('CidadeService',[]).service('CidadeService',CidadeService);
    CidadeService.$inject = ["BaseService"];
})();
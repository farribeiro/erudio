(function (){
    'use strict';    
    class NacionalidadeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'nacionalidades';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('NacionalidadeService',[]).service('NacionalidadeService',NacionalidadeService);
    NacionalidadeService.$inject = ["BaseService"];
})();
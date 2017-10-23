(function (){
    'use strict';    
    class NacionalidadeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'nacionalidade';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('NacionalidadeService',[]).service('NacionalidadeService',NacionalidadeService);
    NacionalidadeService.$inject = ["BaseService"];
})();
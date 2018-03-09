(function (){
    'use strict';    
    class ConceitoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'avaliacoes-qualitativas/conceitos';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
    };
    
    angular.module('ConceitoService',[]).service('ConceitoService',ConceitoService);
    ConceitoService.$inject = ["BaseService"];
})();
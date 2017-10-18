(function (){
    'use strict';    
    class PermissaoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'permissoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
    };
    
    angular.module('PermissaoService',[]).service('PermissaoService',PermissaoService);
    PermissaoService.$inject = ["BaseService"];
})();
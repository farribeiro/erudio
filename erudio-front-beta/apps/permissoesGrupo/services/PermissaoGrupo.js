(function (){
    'use strict';    
    class PermissaoGrupoService {        
        constructor(rest,grupoService,permissaoService){
            this.rest = rest;
            this.grupoService = grupoService;
            this.permissaoService = permissaoService;
            this.url = 'atribuicoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getGrupos(opcoes){ return this.grupoService.getAll(opcoes); }
        getEstrutura() { return { grupo:{id:null}, permissao:{id:null} }; }
    };
    
    angular.module('PermissaoGrupoService',[]).service('PermissaoGrupoService',PermissaoGrupoService);
    PermissaoGrupoService.$inject = ["BaseService","GrupoService","PermissaoService"];
})();
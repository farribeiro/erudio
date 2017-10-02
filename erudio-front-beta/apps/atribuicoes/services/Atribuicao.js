(function (){
    'use strict';    
    class AtribuicaoService {        
        constructor(rest,grupoService,permissaoService){
            this.rest = rest;
            this.grupoService = grupoService;
            this.permissaoService = permissaoService;
            this.url = 'atribuicoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getGrupos(opcoes){ return this.grupoService.getAll(opcoes); }
        getEstrutura() { return { tipoAcesso:null, grupo:{id:null}, permissao:{id:null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Atribuição", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Atribuição", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Atribuição", "F"); }
    };
    
    angular.module('AtribuicaoService',[]).service('AtribuicaoService',AtribuicaoService);
    AtribuicaoService.$inject = ["BaseService","GrupoService","PermissaoService"];
})();
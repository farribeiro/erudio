(function (){
    'use strict';    
    class GrupoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'grupos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nome: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Grupo de Permissão", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Grupo de Permissão", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Grupo de Permissão", "M"); }
    };
    
    angular.module('GrupoService',[]).service('GrupoService',GrupoService);
    GrupoService.$inject = ["BaseService"];
})();
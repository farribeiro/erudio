(function (){
    'use strict';    
    class TipoUnidadeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'tipos-unidade-ensino';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome: null, sigla: null }; }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Tipo de Unidade", "M"); }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Tipo de Unidade", "M"); }        
        remover(objeto) { this.rest.remover(objeto, "Tipo de Unidade", "M"); }
    };
    
    angular.module('TipoUnidadeService',[]).service('TipoUnidadeService',TipoUnidadeService);
    TipoUnidadeService.$inject = ["BaseService"];
})();
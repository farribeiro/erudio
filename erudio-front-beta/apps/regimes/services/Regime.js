(function (){
    'use strict';    
    class RegimeService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'regimes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nome: null }; }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Regime de Ensino", "M"); }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Regime de Ensino", "M"); }        
        remover(objeto) { this.rest.remover(objeto, "Regime de Ensino", "M"); }
    };
    
    angular.module('RegimeService',[]).service('RegimeService',RegimeService);
    RegimeService.$inject = ["BaseService"];
})();
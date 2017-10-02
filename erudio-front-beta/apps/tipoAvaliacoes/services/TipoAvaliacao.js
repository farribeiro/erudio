(function (){
    'use strict';    
    class TipoAvaliacaoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'avaliacoes/tipos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nome: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Tipo de Avaliação", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Tipo de Avaliação", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Tipo de Avaliação", "M"); }
    };
    
    angular.module('TipoAvaliacaoService',[]).service('TipoAvaliacaoService',TipoAvaliacaoService);
    TipoAvaliacaoService.$inject = ["BaseService"];
})();
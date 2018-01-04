(function (){
    'use strict';    
    class ObservacaoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'observacoes-historico';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { texto: null, matricula: {id: null} }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Observação", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Observação", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Observação", "F",loader); }
    };
    
    angular.module('ObservacaoService',[]).service('ObservacaoService',ObservacaoService);
    ObservacaoService.$inject = ["BaseService"];
})();
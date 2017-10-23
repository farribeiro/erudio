(function (){
    'use strict';    
    class ModalidadeEnsinoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'modalidades-ensino';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nome: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Modalidade", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Modalidade", "F"); }        
        remover(objeto) { this.rest.remover(objeto, "Modalidade", "F"); }
    };
    
    angular.module('ModalidadeEnsinoService',[]).service('ModalidadeEnsinoService',ModalidadeEnsinoService);
    ModalidadeEnsinoService.$inject = ["BaseService"];
})();
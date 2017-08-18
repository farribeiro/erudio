(function (){
    'use strict';
    
    class TelefoneService {
        constructor(rest){
            this.rest = rest;
            this.url = 'telefones';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getAllByPessoa(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return {descricao: '', falarCom: null, numero: null, pessoa: { id: null } }; }
        
        salvar(objeto,feedback) { 
            if (feedback === true) {
                return this.rest.salvar(this.url, objeto, "Telefone", "M");
            } else {
                return this.rest.salvar(this.url, objeto);
            }
        }
        
        atualizar(objeto,feedback) { 
            if (feedback === true) {
                return this.rest.atualizar(objeto, "Telefone", "M");
            } else {
                return this.rest.atualizar(objeto);
            }
        }
        
        remover(objeto,feedback) {
            if (feedback === true) {
                return this.rest.remover(objeto, "Telefone", "M");
            } else {
                return this.rest.remover(objeto);
            }
        }
    }
    
    angular.module('TelefoneService',[]).service('TelefoneService',TelefoneService);
    TelefoneService.$inject = ["BaseService"];
}());
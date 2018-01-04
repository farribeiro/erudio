(function (){
    'use strict';
    
    class TelefoneService {
        constructor(rest){
            this.rest = rest;
            this.url = 'telefones';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getAllByPessoa(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return {descricao: '', falarCom: null, numero: null, pessoa: { id: null } }; }
        
        salvar(objeto,feedback,loader) { 
            if (feedback === true) {
                return this.rest.salvar(this.url, objeto, "Telefone", "M",loader);
            } else {
                return this.rest.salvar(this.url, objeto);
            }
        }
        
        atualizar(objeto,feedback,loader) { 
            if (feedback === true) {
                return this.rest.atualizar(objeto, "Telefone", "M",loader);
            } else {
                return this.rest.atualizar(objeto);
            }
        }
        
        remover(objeto,feedback,loader) {
            if (feedback === true) {
                return this.rest.remover(objeto, "Telefone", "M", loader);
            } else {
                return this.rest.remover(objeto, null, null, loader);
            }
        }
    }
    
    angular.module('TelefoneService',[]).service('TelefoneService',TelefoneService);
    TelefoneService.$inject = ["BaseService"];
}());
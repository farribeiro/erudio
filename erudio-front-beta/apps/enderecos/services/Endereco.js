(function (){
    'use strict';
    
    class EnderecoService {
        constructor(rest){
            this.rest = rest;
            this.url = 'enderecos';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, estado: { id:null }, latitude: null, longitude: null } }; }
        
        salvar(objeto,feedback) { 
            if (feedback === true) {
                return this.rest.salvar(this.url, objeto, "Endereço", "M");
            } else {
                return this.rest.salvar(this.url, objeto);
            }
        }
        
        atualizar(objeto,feedback) { 
            if (feedback === true) {
                return this.rest.atualizar(objeto, "Endereço", "M");
            } else {
                return this.rest.atualizar(objeto);
            }
        }
        
        remover(objeto,feedback) {
            if (feedback === true) {
                return this.rest.remover(objeto, "Endereço", "M");
            } else {
                return this.rest.remover(objeto);
            }
        }
    }
    
    angular.module('EnderecoService',[]).service('EnderecoService',EnderecoService);
    EnderecoService.$inject = ["BaseService"];
}());
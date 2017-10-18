(function (){
    'use strict';    
    class UsuarioService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'usuarios';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstrutura() { return { nomeExibicao:null, username:null, password:null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Usuário", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Usuário", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Usuário", "M"); }
    };
    
    angular.module('UsuarioService',[]).service('UsuarioService',UsuarioService);
    UsuarioService.$inject = ["BaseService"];
})();
(function (){
    'use strict';    
    class UsuarioService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'users';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nomeExibicao:null, username:null, password:null }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Usuário", "M",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Usuário", "M",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Usuário", "M",loader); }
    };
    
    angular.module('UsuarioService',[]).service('UsuarioService',UsuarioService);
    UsuarioService.$inject = ["BaseService"];
})();
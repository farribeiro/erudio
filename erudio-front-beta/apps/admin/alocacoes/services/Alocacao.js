(function (){
    'use strict';    
    class AlocacaoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'alocacoes';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        //getEstrutura() { return { tipoAcesso:null, grupo:{id:null}, permissao:{id:null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Alocação", "F",loader); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Alocação", "F",loader); }
        remover(objeto) { this.rest.remover(objeto, "Alocação", "F",loader); }
    };
    
    angular.module('AlocacaoService',[]).service('AlocacaoService',AlocacaoService);
    AlocacaoService.$inject = ["BaseService"];
})();
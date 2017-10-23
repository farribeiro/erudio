(function (){
    'use strict';    
    class CargoService {        
        constructor(rest,grupoService){
            this.rest = rest;
            this.grupoService = grupoService;
            this.url = 'cargos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getGrupos(opcoes){ return this.grupoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, professor: false, grupo: { id: null } }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Cargo", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Cargo", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Cargo", "M"); }
    };
    
    angular.module('CargoService',[]).service('CargoService',CargoService);
    CargoService.$inject = ["BaseService","GrupoService"];
})();
(function (){
    'use strict';    
    class CargoService {        
        constructor(rest,grupoService){
            this.rest = rest;
            this.grupoService = grupoService;
            this.url = 'cargos';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getGrupos(opcoes,loader){ return this.grupoService.getAll(opcoes,loader); }
        getEstrutura() { return { nome: null, professor: false, grupo: { id: null } }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Cargo", "M",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Cargo", "M",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Cargo", "M",loader); }
    };
    
    angular.module('CargoService',[]).service('CargoService',CargoService);
    CargoService.$inject = ["BaseService","GrupoService"];
})();
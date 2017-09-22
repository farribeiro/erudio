(function (){
    'use strict';    
    class EtapaCursadaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'etapas-cursadas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { matricula: {id: null}, etapa: {id: null}, unidadeEnsino: null, ano: null, cidade: {id: null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Etapa", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Etapa", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Etapa", "F"); }
    };

    angular.module('EtapaCursadaService',[]).service('EtapaCursadaService',EtapaCursadaService);
    EtapaCursadaService.$inject = ["BaseService"];
})();
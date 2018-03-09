(function (){
    'use strict';    
    class EtapaOfertadaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'etapas-ofertadas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { matricula: {id: null}, etapa: {id: null}, unidadeEnsino: null, ano: null, cidade: {id: null, estado:{id:null}} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Etapa", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Etapa", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Etapa", "F"); }
    };

    angular.module('EtapaOfertadaService',[]).service('EtapaOfertadaService',EtapaOfertadaService);
    EtapaOfertadaService.$inject = ["BaseService"];
})();
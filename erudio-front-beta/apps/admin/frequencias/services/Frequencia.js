(function (){
    'use strict';    
    class FrequenciaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'professor/frequencias';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome: null, curso: {id: null} }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Frequência", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Frequência", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Frequência", "F",loader); }
    };
    
    angular.module('FrequenciaService',[]).service('FrequenciaService',FrequenciaService);
    FrequenciaService.$inject = ["BaseService"];
})();
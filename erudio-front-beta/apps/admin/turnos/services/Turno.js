(function (){
    'use strict';    
    class TurnoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'turnos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome:null, inicio:null, termino:null }; }
        salvar(objeto) {
            objeto.inicio += ":00";
            objeto.termino += ":00";
            return this.rest.salvar(this.url, objeto, "Turnos", "M");
        }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Turnos", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Turnos", "M"); }
    };
    
    angular.module('TurnoService',[]).service('TurnoService',TurnoService);
    TurnoService.$inject = ["BaseService"];
})();
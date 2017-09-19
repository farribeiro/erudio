(function (){
    'use strict';    
    class EventoService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'eventos';
            this.tiposEvento = ['Atividade Escolar','Atividade Administrativa', 'Interesse Público', 'Período', 'Recesso', 'Feriado'];
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome: null, tipo: null, descricao: null }; }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Eventos", "M"); }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Eventos", "M"); }        
        remover(objeto) { this.rest.remover(objeto, "Eventos", "M"); }
    };
    
    angular.module('EventoService',[]).service('EventoService',EventoService);
    EventoService.$inject = ["BaseService"];
})();
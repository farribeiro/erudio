(function (){
    'use strict';    
    class TransferenciaService {        
        constructor(rest,matriculaService,unidadeService){
            this.rest = rest;
            this.matriculaService = matriculaService;
            this.unidadeService = unidadeService;
            this.url = 'transferencias';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getMatricula(opcoes){ return this.matriculaService.getAll(opcoes); }
        getUnidade(opcoes){ return this.unidadeService.getAll(opcoes); }
        getEstrutura() { return { justificativa: null, resposta: null, dataAgendamento: null, matricula: {id: null}, unidadeEnsinoDestino: {id: null}, unidadeEnsinoOrigem: {id: null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Transferência", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Transferência", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Transferência", "F"); }
    };
    
    angular.module('TransferenciaService',[]).service('TransferenciaService',TransferenciaService);
    TransferenciaService.$inject = ["BaseService","MatriculaService","UnidadeService"];
})();
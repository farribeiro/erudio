(function (){
    'use strict';    
    class AulaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'professor/aulas';
            this.urlAnotacoes = 'professor/anotacoes-aula';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getAnotacoes(opcoes,loader){ return this.rest.buscar(this.urlAnotacoes,opcoes,loader); }
        getEstruturaAula() { return { turma: {id:null}, dia: {id:null}, horario: {id:null}, disciplina: {id:null} }; }
        getEstrutura() { return { aulas: [] }; }
        getEstruturaAnotacao() { return { aula:{id:null}, conteudo:null }; }
        salvar(objeto,loader) { return this.rest.salvarLote(objeto, this.url, "Aula", "F", loader, true); }
        salvarAnotacao(objeto,loader) { return this.rest.salvarLote(objeto, this.urlAnotacoes, "Anotação", "F", loader, true); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Aula", "F",loader); }
        atualizarAnotacao(objeto,loader) { return this.rest.atualizar(objeto, "Anotação", "F",loader); }
        remover(objeto,loader,anotacao) { 
            if (anotacao) { this.rest.remover(objeto, "Anotação", "F",loader); } else { this.rest.remover(objeto, "Aula", "F",loader); }            
        }
    };
    
    angular.module('AulaService',[]).service('AulaService',AulaService);
    AulaService.$inject = ["BaseService"];
})();
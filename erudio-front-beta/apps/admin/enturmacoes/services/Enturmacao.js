(function (){
    'use strict';    
    class EnturmacaoService {        
        constructor(rest,matriculaService,turmaService){
            this.rest = rest;
            this.matriculaService = matriculaService;
            this.turmaService = turmaService;
            this.url = 'enturmacoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getMatriculas(opcoes){ return this.matriculaService.getAll(opcoes); }
        getTurma(opcoes){ return this.turmaService.getAll(opcoes); }
        getEstrutura() { return {turma: {id: null}, matricula: {id: null}}; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Enturmação", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Enturmação", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Enturmação", "F"); }
    };
    
    angular.module('EnturmacaoService',[]).service('EnturmacaoService',EnturmacaoService);
    EnturmacaoService.$inject = ["BaseService","MatriculaService","TurmaService"];
})();
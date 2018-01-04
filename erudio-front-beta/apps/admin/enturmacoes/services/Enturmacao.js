(function (){
    'use strict';    
    class EnturmacaoService {        
        constructor(rest,matriculaService,turmaService){
            this.rest = rest;
            this.matriculaService = matriculaService;
            this.turmaService = turmaService;
            this.url = 'enturmacoes';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getMatriculas(opcoes,loader){ return this.matriculaService.getAll(opcoes,loader); }
        getTurma(opcoes,loader){ return this.turmaService.getAll(opcoes,loader); }
        getEstrutura() { return {turma: {id: null}, matricula: {id: null}}; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Enturmação", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Enturmação", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Enturmação", "F",loader); }
    };
    
    angular.module('EnturmacaoService',[]).service('EnturmacaoService',EnturmacaoService);
    EnturmacaoService.$inject = ["BaseService","MatriculaService","TurmaService"];
})();
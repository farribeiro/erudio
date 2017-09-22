(function (){
    'use strict';    
    class MatriculaService {        
        constructor(rest,pessoaService,unidadeService,cursoService,turmaService){
            this.rest = rest;
            this.pessoaService = pessoaService;
            this.unidadeService = unidadeService;
            this.cursoService = cursoService;
            this.turmaService = turmaService;
            this.url = 'matriculas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getPessoas(opcoes){ return this.pessoaService.getAll(opcoes); }
        getUnidades(opcoes){ return this.unidadeService.getAll(opcoes); }
        getTurma(opcoes){ return this.turmaService.getAll(opcoes); }
        getEstrutura() { return {codigo: null, aluno: {id: null}, unidadeEnsino: {id: null}, curso: {id: null}}; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Matrícula", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Matrícula", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Matrícula", "F"); }
    };
    
    angular.module('MatriculaService',[]).service('MatriculaService',MatriculaService);
    MatriculaService.$inject = ["BaseService","PessoaService","UnidadeService","CursoService","TurmaService"];
})();
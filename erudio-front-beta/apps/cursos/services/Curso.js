(function (){
    'use strict';    
    class CursoService {        
        constructor(rest,modalidadeEnsinoService){
            this.rest = rest;
            this.modalidadeEnsinoService = modalidadeEnsinoService;
            this.url = 'cursos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getModalidades(opcoes){ return this.modalidadeEnsinoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, modalidade: {id: null}, especializado: false, alfabetizatorio: false }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Curso", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Curso", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Curso", "M"); }
    };
    
    angular.module('CursoService',[]).service('CursoService',CursoService);
    CursoService.$inject = ["BaseService","ModalidadeEnsinoService"];
})();
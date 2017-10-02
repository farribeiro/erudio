(function (){
    'use strict';    
    class DisciplinaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'disciplinas';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome: null, nomeExibicao: null, cargaHoraria: null, opcional: false, ofertado: true, curso: {id: null}, etapa: {id: null}, sigla: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Disciplina", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Disciplina", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Disciplina", "F"); }
    };
    
    angular.module('DisciplinaService',[]).service('DisciplinaService',DisciplinaService);
    DisciplinaService.$inject = ["BaseService"];
})();
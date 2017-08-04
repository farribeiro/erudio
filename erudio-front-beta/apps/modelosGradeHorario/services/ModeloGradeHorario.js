(function (){
    'use strict';    
    class ModeloGradeHorarioService {        
        constructor(rest,cursoService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.url = 'modulos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, curso: {id: null}, quantidadeAulas: null, duracaoAula: null, duracaoIntervalo: null, posicaoIntervalo: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Modelo de Grade de Horário", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Modelo de Grade de Horário", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Modelo de Grade de Horário", "M"); }
    };
    
    angular.module('ModeloGradeHorarioService',[]).service('ModeloGradeHorarioService',ModeloGradeHorarioService);
    ModeloGradeHorarioService.$inject = ["BaseService","CursoService"];
})();
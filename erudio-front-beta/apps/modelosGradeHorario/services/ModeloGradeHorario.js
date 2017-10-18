(function (){
    'use strict';    
    class ModeloGradeHorarioService {        
        constructor(rest,cursoService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.url = 'modelo-quadro-horarios';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, curso: {id: null}, quantidadeAulas: null, duracaoAula: null, duracaoIntervalo: null, posicaoIntervalo: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Modelo de Grade de Horário", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Modelo de Grade de Horário", "M"); }
        remover(objeto,loader) { this.rest.remover(objeto, "Modelo de Grade de Horário", "M", loader); }
    };
    
    angular.module('ModeloGradeHorarioService',[]).service('ModeloGradeHorarioService',ModeloGradeHorarioService);
    ModeloGradeHorarioService.$inject = ["BaseService","CursoService"];
})();
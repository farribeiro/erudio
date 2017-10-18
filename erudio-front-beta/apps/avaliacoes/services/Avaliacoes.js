(function (){
    'use strict';    
    class AvaliacaoService {        
        constructor(rest,cursoService,etapaService,disciplinaService,tipoAvaliacaoService,turmaService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.disciplinaService = disciplinaService;
            this.tipoAvaliacaoService = tipoAvaliacaoService;
            this.turmaService = turmaService;
            this.url = 'avaliacoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getEtapas(opcoes){ return this.etapaService.getAll(opcoes); }
        getDisciplinas(opcoes){ return this.disciplinaService.getAll(opcoes); }
        getTurmas(opcoes){ return this.turmaService.getAll(opcoes); }
        getTiposAvaliacao(opcoes){ return this.tipoAvaliacaoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, disciplina: { id: null }, aulaEntrega: { id: null }, tipo: { id: null }, media: null, habilidades: [] }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Avaliação", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Avaliação", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Avaliação", "F"); }
    };
    
    angular.module('AvaliacaoService',[]).service('AvaliacaoService',AvaliacaoService);
    AvaliacaoService.$inject = ["BaseService","CursoService","EtapaService","DisciplinaService","TipoAvaliacaoService","TurmaService"];
})();
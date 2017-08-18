(function (){
    'use strict';    
    class HabilidadeService {        
        constructor(rest,cursoService,etapaService,disciplinaService,sistemaAvaliacaoService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.disciplinaService = disciplinaService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.url = 'habilidades';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getEtapas(opcoes){ return this.etapaService.getAll(opcoes); }
        getDisciplinas(opcoes){ return this.disciplinaService.getAll(opcoes); }
        getSistemasAvaliacao(opcoes){ return this.sistemaAvaliacaoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, disciplina: { id: null }, media: null, sistema_avaliacao: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Habilidade", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Habilidade", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Habilidade", "F"); }
    };
    
    angular.module('HabilidadeService',[]).service('HabilidadeService',HabilidadeService);
    HabilidadeService.$inject = ["BaseService","CursoService","EtapaService","DisciplinaService","SistemaAvaliacaoService"];
})();
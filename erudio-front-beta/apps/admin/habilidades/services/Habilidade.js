(function (){
    'use strict';    
    class HabilidadeService {        
        constructor(rest,cursoService,etapaService,disciplinaService,sistemaAvaliacaoService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.disciplinaService = disciplinaService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.url = 'avaliacoes-qualitativas/habilidades';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getCursos(opcoes,loader){ return this.cursoService.getAll(opcoes,loader); }
        getEtapas(opcoes,loader){ return this.etapaService.getAll(opcoes,loader); }
        getDisciplinas(opcoes,loader){ return this.disciplinaService.getAll(opcoes,loader); }
        getSistemasAvaliacao(opcoes,loader){ return this.sistemaAvaliacaoService.getAll(opcoes,loader); }
        getEstrutura() { return { nome: null, disciplina: { id: null }, media: null, sistema_avaliacao: null }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Habilidade", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Habilidade", "F",loader); }
        remover(objeto,loader) { this.rest.remover(objeto, "Habilidade", "F",loader); }
    };
    
    angular.module('HabilidadeService',[]).service('HabilidadeService',HabilidadeService);
    HabilidadeService.$inject = ["BaseService","CursoService","EtapaService","DisciplinaService","SistemaAvaliacaoService"];
})();
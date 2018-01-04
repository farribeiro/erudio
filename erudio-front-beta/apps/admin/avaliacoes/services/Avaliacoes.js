(function (){
    'use strict';    
    class AvaliacaoService {        
        constructor(rest,cursoService,etapaService,disciplinaService,tipoAvaliacaoService,turmaService, conceitoService, habilidadeService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.etapaService = etapaService;
            this.disciplinaService = disciplinaService;
            this.tipoAvaliacaoService = tipoAvaliacaoService;
            this.turmaService = turmaService;
            this.conceitoService = conceitoService;
            this.habilidadeService = habilidadeService;
            this.urlQuali = 'avaliacoes-qualitativas';
            this.urlQuanti = 'avaliacoes-quantitativas';
            this.urlSalvarQuali = 'notas-qualitativas';
            this.urlSalvarQuanti = 'notas-quantitativas';
        }
        
        //QUALITATIVA
        getNotaQualitativa(id,loader){ return this.rest.um(this.urlSalvarQuali,id,loader); }
        getNotasQualitativas(opcoes,loader){ return this.rest.buscar(this.urlSalvarQuali,opcoes,loader); }
        getQualitativa(id,loader){ return this.rest.um(this.urlQuali,id,loader); }
        getQualitativas(opcoes,loader){ return this.rest.buscar(this.urlQuali,opcoes,loader); }
        getEstruturaQualitativa() { return { media: null, disciplina: { id: null }, nome: null, tipo: null }; }
        getEstruturaNotaQualitativa() { return { media: { id: null }, avaliacao: { id: null }, habilidadesAvaliadas: [] }; }

        salvarAvaliacaoQuali(objeto,label,loader) { 
            if (label) { return this.rest.salvar(this.urlQuali, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlQuali, objeto, null, null, loader); }
        }

        salvarQualitativa(objeto,label,loader) { 
            if (label) { return this.rest.salvar(this.urlSalvarQuali, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlSalvarQuali, objeto, null, null, loader); }
        }

        //QUANTITATIVA
        getNotaQuantitativa(id,loader){ return this.rest.um(this.urlSalvarQuanti,id,loader); }
        getNotasQuantitativas(opcoes,loader){ return this.rest.buscar(this.urlSalvarQuanti,opcoes,loader); }
        getQuantitativa(id,loader){ return this.rest.um(this.urlQuanti,id,loader); }
        getQuantitativas(opcoes,loader){ return this.rest.buscar(this.urlQuanti,opcoes,loader); }
        getEstruturaQuantitativa() { return { media: { id: null }, avaliacao: { id: null }, valor: null }; }
        getEstrutura() { return { nome: null, disciplina: { id: null }, dataEntrega: null, tipo: { id: null }, media: null, habilidades: [], peso: 1 }; }
        
        salvarAvaliacaoQuanti(objeto,label,loader) { 
            if (label) { return this.rest.salvar(this.urlQuanti, objeto, "Avaliação", "F", loader); }
            else { return this.rest.salvar(this.urlQuanti, objeto, null, null, loader); }
        }
        
        salvarQuantitativa(objeto,label,loader) { 
            if (label) { return this.rest.salvar(this.urlSalvarQuanti, objeto, "Avaliação", "F", loader);  }
            else { return this.rest.salvar(this.urlSalvarQuanti, objeto, null, null ,loader);  }
        }

        //OUTROS
        atualizar(objeto, label, loader) { 
            if (label) { return this.rest.atualizar(objeto, "Avaliação", "F", loader);  }
            else { return this.rest.atualizar(objeto, null, null, loader);  }
        }

        remover(objeto,loader) { this.rest.remover(objeto, "Avaliação", "F", loader); }

        getCursos(opcoes,loader){ return this.cursoService.getAll(opcoes,loader); }
        getEtapas(opcoes,loader){ return this.etapaService.getAll(opcoes,loader); }
        getDisciplinas(opcoes,laoder){ return this.disciplinaService.getAll(opcoes,loader); }
        getTurmas(opcoes,loader){ return this.turmaService.getAll(opcoes,loader); }
        getConceitos(opcoes,loader){ return this.conceitoService.getAll(opcoes,loader); }
        getHabilidades(opcoes,loader){ return this.habilidadeService.getAll(opcoes,loader); }
        getTiposAvaliacao(opcoes,loader){ return this.tipoAvaliacaoService.getAll(opcoes,loader); }
        getTiposAvaliacaoQuali() { return [{id:'DIAGNOSTICO',nome:'Diagnóstico'},{id:'PROCESSUAL',nome:'Processual'},{id:'FINAL',nome:'Final'}]; }
    };
    
    angular.module('AvaliacaoService',[]).service('AvaliacaoService',AvaliacaoService);
    AvaliacaoService.$inject = ["BaseService","CursoService","EtapaService","DisciplinaService","TipoAvaliacaoService","TurmaService","ConceitoService","HabilidadeService"];
})();
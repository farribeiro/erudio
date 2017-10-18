(function (){
    'use strict';    
    class EtapaService {        
        constructor(rest,modalidadeEnsinoService,sistemaAvaliacaoService,modeloGradeHorarioService){
            this.rest = rest;
            this.modalidadeEnsinoService = modalidadeEnsinoService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.modeloGradeHorarioService = modeloGradeHorarioService;
            this.url = 'etapas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getModalidades(opcoes){ return this.modalidadeEnsinoService.getAll(opcoes); }
        getSistemaAvaliacoes(opcoes){ return this.sistemaAvaliacaoService.getAll(opcoes); }
        getModeloGradeHorarios(opcoes){ return this.modeloGradeHorarioService.getAll(opcoes); }
        getEstrutura() { return { nome: null, nomeExibicao: null, ordem: null, modulo:{ id:null }, modeloQuadroHorario:{ id:null }, sistemaAvaliacao:{ id:null }, limiteAlunos: null, integral: true, curso: { id:null }, idadeRecomendada: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Etapa", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Etapa", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Etapa", "F"); }
    };
    
    angular.module('EtapaService',[]).service('EtapaService',EtapaService);
    EtapaService.$inject = ["BaseService","ModalidadeEnsinoService","SistemaAvaliacaoService","ModeloGradeHorarioService"];
})();
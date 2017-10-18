(function (){
    'use strict';    
    class CalendarioService {        
        constructor(rest,instituicaoService,sistemaAvaliacaoService,modeloGradeHorarioService,unidadeService){
            this.rest = rest;
            this.instituicaoService = instituicaoService;
            this.sistemaAvaliacaoService = sistemaAvaliacaoService;
            this.unidadeService = unidadeService;
            this.modeloGradeHorarioService = modeloGradeHorarioService;
            this.url = 'calendarios';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getDiasPorMes(calendario, mes, loader) { return this.rest.buscar(this.url+'/'+calendario.id+'/meses/'+mes,null,loader);  }
        getInstituicoes(opcoes){ return this.instituicaoService.getAll(opcoes); }
        getUnidades(opcoes){ return this.unidadeService.getAll(opcoes); }
        getSistemaAvaliacoes(opcoes){ return this.sistemaAvaliacaoService.getAll(opcoes); }
        getModeloGradeHorarios(opcoes){ return this.modeloGradeHorarioService.getAll(opcoes); }
        getEstrutura() { return { nome: null, dataInicio: new Date(), dataTermino: new Date(), instituicao: {id: null}, calendarioBase: {id: null}, sistemaAvaliacao: {id: null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Calendário", "M"); }
        salvarDias(objeto,calendario) { return this.rest.salvarLote(objeto, this.url+'/'+calendario.id+'/dias', "Calendário", "M", true); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Calendário", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Calendário", "M"); }
    };
    
    angular.module('CalendarioService',[]).service('CalendarioService',CalendarioService);
    CalendarioService.$inject = ["BaseService","InstituicaoService","SistemaAvaliacaoService","ModeloGradeHorarioService","UnidadeService"];
})();
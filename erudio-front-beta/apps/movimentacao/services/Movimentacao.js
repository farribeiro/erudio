(function (){
    'use strict';    
    class MovimentacaoService {        
        constructor(rest,matriculaService,unidadeService,enturmacaoService,turmaService){
            this.rest = rest;
            this.matriculaService = matriculaService;
            this.enturmacaoService = enturmacaoService;
            this.turmaService = turmaService;
            this.url = 'movimentacoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getMatricula(opcoes){ return this.matriculaService.getAll(opcoes); }
        getEstrutura() { return { matricula: { id: null }, enturmacaoOrigem: { id: null }, turmaDestino: { id: null }, justificativa: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Movimentação", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Movimentação", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Movimentação", "M"); }
    };
    
    angular.module('MovimentacaoService',[]).service('MovimentacaoService',MovimentacaoService);
    MovimentacaoService.$inject = ["BaseService","MatriculaService","EnturmacaoService","TurmaService"];
})();
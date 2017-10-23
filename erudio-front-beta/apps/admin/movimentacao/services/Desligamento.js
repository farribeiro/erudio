(function (){
    'use strict';    
    class DesligamentoService {        
        constructor(rest,matriculaService,unidadeService){
            this.rest = rest;
            this.matriculaService = matriculaService;
            this.url = 'desligamentos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getMatricula(opcoes){ return this.matriculaService.getAll(opcoes); }
        getEstrutura() { return { matricula: {id: null}, justificativa: null, destino: null, motivo: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Desligamento", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Desligamento", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Desligamento", "M"); }
    };
    
    angular.module('DesligamentoService',[]).service('DesligamentoService',DesligamentoService);
    DesligamentoService.$inject = ["BaseService","MatriculaService"];
})();
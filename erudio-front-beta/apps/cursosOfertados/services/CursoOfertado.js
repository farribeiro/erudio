(function (){
    'use strict';    
    class CursoOfertadoService {
        constructor(rest){
            this.rest = rest;
            this.url = 'cursos-ofertados';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { curso: { id:null }, unidadeEnsino: { id: null } }; }
        
        atualizar(objeto, feedback) { 
            if (feedback === true) {
                return this.rest.atualizar(objeto, "Curso", "M");
            } else {
                return this.rest.atualizar(objeto);
            }    
        }
        
        salvar(objeto, feedback) {
            if (feedback === true) {
                return this.rest.salvar(this.url, objeto, "Curso", "M");
            } else {
                return this.rest.salvar(this.url, objeto);
            }
        }
        
        remover(objeto, feedback) {
            if (feedback === true) {
                this.rest.remover(objeto, "Curso", "M");
            } else {
                this.rest.remover(objeto);
            }
            
        }
    };
    
    angular.module('CursoOfertadoService',[]).service('CursoOfertadoService',CursoOfertadoService);
    CursoOfertadoService.$inject = ["BaseService"];
})();
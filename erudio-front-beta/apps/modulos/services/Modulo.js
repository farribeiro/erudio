(function (){
    'use strict';    
    class ModuloService {        
        constructor(rest,cursoService){
            this.rest = rest;
            this.cursoService = cursoService;
            this.url = 'modulos';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getCursos(opcoes){ return this.cursoService.getAll(opcoes); }
        getEstrutura() { return { nome: null, curso: {id: null} }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Módulo", "M"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Módulo", "M"); }
        remover(objeto) { this.rest.remover(objeto, "Módulo", "M"); }
    };
    
    angular.module('ModuloService',[]).service('ModuloService',ModuloService);
    ModuloService.$inject = ["BaseService","CursoService"];
})();
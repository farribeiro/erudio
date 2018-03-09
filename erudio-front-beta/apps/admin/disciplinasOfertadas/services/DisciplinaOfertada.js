(function (){
    'use strict';    
    class DisciplinaOfertadaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'disciplinas-ofertadas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        //getEstrutura() { return { matricula: {id: null}, disciplina: {id: null}, mediaFinal: null, frequenciaTotal: null, status: null }; }
        salvar(objeto,loader) { return this.rest.salvar(this.url, objeto, "Disciplina", "F",loader); }
        atualizar(objeto,loader) { return this.rest.atualizar(objeto, "Disciplina", "F",loader); }
        fecharMediaFinal(objeto, loader) { return this.rest.salvarLote({nome:''}, this.url+'/'+objeto.id+'/media-final', "Médias", "F", loader, true); }
        remover(objeto,loader) { this.rest.remover(objeto, "Disciplina", "F",loader); }
    };

    angular.module('DisciplinaOfertadaService',[]).service('DisciplinaOfertadaService',DisciplinaOfertadaService);
    DisciplinaOfertadaService.$inject = ["BaseService"];
})();
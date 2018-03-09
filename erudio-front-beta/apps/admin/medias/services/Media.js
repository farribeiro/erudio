(function (){
    'use strict';    
    class MediaService {        
        constructor(rest){
            this.rest = rest;
            this.url = 'medias';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { disciplinaCursada: {id: null}, faltas: null, nome: null, numero: null, valor: null }; }
        salvar(objeto) { return this.rest.salvar(this.url, objeto, "Média", "F"); }
        atualizar(objeto) { return this.rest.atualizar(objeto, "Média", "F"); }
        remover(objeto) { this.rest.remover(objeto, "Média", "F"); }
    };

    angular.module('MediaService',[]).service('MediaService',MediaService);
    MediaService.$inject = ["BaseService"];
})();
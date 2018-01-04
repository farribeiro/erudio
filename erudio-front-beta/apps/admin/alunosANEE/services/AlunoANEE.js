(function (){
    'use strict';    
    class AlunoANEEService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.url = this.erudioConfig.urlRelatorios+'/alunos/anee-nominal-unidade';
            this.urlPorInstituicao = this.erudioConfig.urlRelatorios+'/alunos/anee-nominal-instituicao';
        }

        getURL(unidade){ return this.url+'?unidade='+unidade; }
        getURLPorInstituicao(instituicao){ return this.urlPorInstituicao+'?instituicao='+instituicao; }
    };
    
    angular.module('AlunoANEEService',['erudioConfig']).service('AlunoANEEService',AlunoANEEService);
    AlunoANEEService.$inject = ["BaseService","ErudioConfig"];
})();
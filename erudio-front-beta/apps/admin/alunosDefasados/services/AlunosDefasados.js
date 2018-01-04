(function (){
    'use strict';    
    class AlunosDefasadosService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.url = this.erudioConfig.urlRelatorios+'/alunos/defasados-nominal';
        }

        getURL(curso){ return this.url+'?curso='+curso; }
    };
    
    angular.module('AlunosDefasadosService',['erudioConfig']).service('AlunosDefasadosService',AlunosDefasadosService);
    AlunosDefasadosService.$inject = ["BaseService","ErudioConfig"];
})();
(function (){
    /*
     * @ErudioDoc Alunos Defasados Service
     * @Module alunosDefasados
     * @Controller AlunosDefasadosService
     */
    'use strict';    
    class AlunosDefasadosService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.url = this.erudioConfig.urlRelatorios+'/alunos/defasados-nominal';
        }
        /*
         * @method getURL
         * @methodReturn String
         * @methodParams curso|Int
         * @methodDescription Busca a url para gerar o relatório de alunos defasados por curso.
         */
        getURL(curso){ return this.url+'?curso='+curso; }
    };
    
    angular.module('AlunosDefasadosService',['erudioConfig']).service('AlunosDefasadosService',AlunosDefasadosService);
    AlunosDefasadosService.$inject = ["BaseService","ErudioConfig"];
})();
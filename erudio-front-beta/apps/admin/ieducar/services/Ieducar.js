(function (){
    'use strict';    
    class IeducarService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.preUrl = this.erudioConfig.urlServidor.replace('api','integracoes/ieducar/');
            this.url = 'alunos';
        }
        
        getAll(opcoes,loader){ 
            return this.rest.buscarHTTP(this.preUrl+this.url+'?nome='+opcoes.nome+'&dataNascimento='+opcoes.dataNascimento);
        }

        getURLHistorico(aluno){
            return this.preUrl+'historicos-impressos?aluno='+aluno.cod_aluno;
        }
    };
    
    angular.module('IeducarService',['erudioConfig']).service('IeducarService',IeducarService);
    IeducarService.$inject = ["BaseService","ErudioConfig"];
})();
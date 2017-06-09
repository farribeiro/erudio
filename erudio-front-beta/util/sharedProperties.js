(function (){
    var shared = angular.module('shared',['erudioConfig', 'ngMaterial']);
    
    shared.service('Shared', ['$timeout', 'ErudioConfig', function($timeout, ErudioConfig) {
        
        //PARAMETRO ETAPA PARA ABRIR NO MODULO VIA CURSO
        this.cursoEtapa = null;
        this.setCursoEtapa = function (curso){ this.cursoEtapa = curso; };
        this.getCursoEtapa = function (){ return this.cursoEtapa; };
        this.etapaDisciplina = null;
        this.setEtapaDisciplina = function (etapa){ this.etapaDisciplina = etapa; };
        this.getEtapaDisciplina = function (){ return this.etapaDisciplina; };
    }]);
})();
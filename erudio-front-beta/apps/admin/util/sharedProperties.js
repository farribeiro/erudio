(function (){
    /*
     * @Module shared
     * @Service Shared
     */
    var shared = angular.module('shared',['erudioConfig', 'ngMaterial']);
    
    shared.service('Shared', ['$timeout', 'ErudioConfig', function($timeout, ErudioConfig) {
        /*
         * @attr cursoEtapa
         * @attrType int
         * @attrDescription Id do curso para buscar etapa.
         * @attrExample 
         */
        this.cursoEtapa = null;
        
        /*
         * @attr etapaDisciplina
         * @attrType int
         * @attrDescription Id da etapa para buscar disciplina.
         * @attrExample 
         */
        this.etapaDisciplina = null;
        
        /*
         * @method setCursoEtapa
         * @methodReturn void
         * @methodParams curso|int
         * @methodDescription Guarda id do curso para buscar etapas do mesmo.
         */
        this.setCursoEtapa = function (curso){ this.cursoEtapa = curso; };
        
        /*
         * @method getCursoEtapa
         * @methodReturn int
         * @methodDescription Retorna id do curso para buscar etapas do mesmo.
         */
        this.getCursoEtapa = function (){ return this.cursoEtapa; };
        
        /*
         * @method setEtapaDisciplina
         * @methodReturn void
         * @methodParams etapa|int
         * @methodDescription Guarda id da etapa para buscar disciplinas da mesma.
         */
        this.setEtapaDisciplina = function (etapa){ this.etapaDisciplina = etapa; };
        
        /*
         * @method getEtapaDisciplina
         * @methodReturn int
         * @methodDescription Retorna id da etapa para buscar disciplinas da mesma.
         */
        this.getEtapaDisciplina = function (){ return this.etapaDisciplina; };
    }]);
})();
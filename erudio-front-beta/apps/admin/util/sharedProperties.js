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
         * @attr avaliacaoAbrir
         * @attrType int
         * @attrDescription Id da avaliação a ser aberta no app de professor.
         * @attrExample
         */
        this.avaliacaoAbrir = {id:null};

        /*
         * @attr trocaAbaHome
         * @attrType string
         * @attrDescription Nome da aba ativa na tela de professor
         * @attrExample
         */
        this.abaHome = 'aulas';

        /*
         * @attr cursada
         * @attrType object
         * @attrDescription Objeto disciplina cursada
         * @attrExample
         */
        this.cursada = null;

        /*
         * @attr avaliacaoNota
         * @attrType float
         * @attrDescription Nota da avaliação
         * @attrExample
         */
        this.avaliacaoNota = null;

        /*
         * @attr retornarParaMedias
         * @attrType boolean
         * @attrDescription Flag de controle da tela de médias finais
         * @attrExample
         */
        this.retornarParaMedias = false;

         /*
         * @method setAbaHome
         * @methodReturn void
         * @methodParams aba|string
         * @methodDescription Nome da aba ativa na tela de professor
         */
        this.setAbaHome = function (aba){ this.abaHome = aba; };

        /*
         * @method getAbaHome
         * @methodReturn string
         * @methodDescription Retorna string da aba ativa na tela de professor
         */
        this.getAbaHome = function (){ return this.abaHome; };

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

        /*
         * @method setAvaliacao
         * @methodReturn void
         * @methodParams avaliacao|int
         * @methodDescription Guarda id da avaliação a ser mostrada.
         */
        this.setAvaliacao = function (avaliacao){ this.avaliacaoAbrir = avaliacao; };

        /*
         * @method getAvaliacaoNota
         * @methodReturn float
         * @methodDescription Retorna nota da avaliação a ser mostrada.
         */
        this.getAvaliacaoNota = function (){ return this.avaliacaoNota; };

        /*
         * @method setAvaliacaoNota
         * @methodReturn float
         * @methodParams avaliacaoNota|float
         * @methodDescription Guarda nota da avaliação a ser mostrada.
         */
        this.setAvaliacaoNota = function (avaliacaoNota){ this.avaliacaoNota = avaliacaoNota; };

        /*
         * @method getAvaliacao
         * @methodReturn int
         * @methodDescription Retorna id da avaliação a ser mostrada.
         */
        this.getAvaliacao = function (){ return this.avaliacaoAbrir; };

        /*
         * @method setCursada
         * @methodReturn void
         * @methodParams avaliacao|int
         * @methodDescription Guarda id da disciplina cursada.
         */
        this.setCursada = function (cursada){ this.cursada = cursada; };

        /*
         * @method getCursada
         * @methodReturn object
         * @methodDescription Retorna id da disciplina cursada.
         */
        this.getCursada = function (){ return this.cursada; };

        /*
         * @method retornarMedias
         * @methodReturn void
         * @methodParams boolean
         * @methodDescription Redireciona para a tela de médias.
         */
        this.retornarMedias = function (valor){ this.retornarParaMedias = valor; };
    }]);
})();
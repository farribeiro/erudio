(function (){
    'use strict';    
    class AlunosEnturmadosService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.url = this.erudioConfig.urlRelatorios+'/enturmacoes/nominal-turma';
            this.urlPorUnidade = this.erudioConfig.urlRelatorios+'/enturmacoes/nominal-unidade';
            this.urlQuantiInstituicao = this.erudioConfig.urlRelatorios+'/enturmacoes/quantitativo-instituicao';
            this.urlQuantiPorUnidade = this.erudioConfig.urlRelatorios+'/enturmacoes/quantitativo-unidade';
        }

        getURL(turma){ return this.url+'?turma='+turma; }
        getURLNominalUnidade(unidade){ return this.urlPorUnidade+'?unidade='+unidade; }
        getURLQuantiInstituicao(instituicao, curso) { return this.urlQuantiInstituicao+'?instituicao='+instituicao+'&curso='+curso; }
        getURLQuantiPorUnidade (unidade) { return this.urlQuantiPorUnidade+'?unidadeEnsino='+unidade; }
    };
    
    angular.module('AlunosEnturmadosService',['erudioConfig']).service('AlunosEnturmadosService',AlunosEnturmadosService);
    AlunosEnturmadosService.$inject = ["BaseService","ErudioConfig"];
})();
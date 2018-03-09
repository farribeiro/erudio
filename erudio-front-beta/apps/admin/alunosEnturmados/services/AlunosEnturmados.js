/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
(function (){
    /*
     * @ErudioDoc Alunos Enturmados Service
     * @Module alunosEnturmados
     * @Controller AlunosEnturmadosService
     */
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
        /*
         * @method getURL
         * @methodReturn String
         * @methodParams turma|Int
         * @methodDescription Busca a url para gerar o relatório de alunos enturmados por turma.
         */
        getURL(turma){ return this.url+'?turma='+turma; }
        /*
         * @method getURLNominalUnidade
         * @methodReturn String
         * @methodParams unidade|Int
         * @methodDescription Busca a url para gerar o relatório nominal de alunos enturmados por unidade.
         */
        getURLNominalUnidade(unidade){ return this.urlPorUnidade+'?unidade='+unidade; }
        /*
         * @method getURLQuantiInstituicao
         * @methodReturn String
         * @methodParams instituicao|Int, curso|Int
         * @methodDescription Busca a url para gerar o relatório quantitativo de alunos enturmados por instituição e curso.
         */
        getURLQuantiInstituicao(instituicao, curso) { return this.urlQuantiInstituicao+'?instituicao='+instituicao+'&curso='+curso; }
        /*
         * @method getURLQuantiPorUnidade
         * @methodReturn String
         * @methodParams unidade|Int
         * @methodDescription Busca a url para gerar o relatório quantitativo de alunos enturmados por unidade.
         */
        getURLQuantiPorUnidade (unidade) { return this.urlQuantiPorUnidade+'?unidadeEnsino='+unidade; }
    };
    
    angular.module('AlunosEnturmadosService',['erudioConfig']).service('AlunosEnturmadosService',AlunosEnturmadosService);
    AlunosEnturmadosService.$inject = ["BaseService","ErudioConfig"];
})();
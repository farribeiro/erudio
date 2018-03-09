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
     * @ErudioDoc Alunos ANEE Service
     * @Module alunosANEE
     * @Service AlunoANEEService
     */
    'use strict';
    class AlunoANEEService {        
        constructor(rest,erudioConfig){
            this.rest = rest;
            this.erudioConfig = erudioConfig;
            this.url = this.erudioConfig.urlRelatorios+'/alunos/anee-nominal-unidade';
            this.urlPorInstituicao = this.erudioConfig.urlRelatorios+'/alunos/anee-nominal-instituicao';
        }

        /*
         * @method getURL
         * @methodReturn String
         * @methodParams unidade|Int
         * @methodDescription Busca a url para gerar o relatório de ANEE por unidade.
         */
        getURL(unidade){ return this.url+'?unidade='+unidade; }
        /*
         * @method getURLPorInstituicao
         * @methodReturn String
         * @methodParams instituicao|Int
         * @methodDescription Busca a url para gerar o relatório de ANEE por instituição.
         */
        getURLPorInstituicao(instituicao){ return this.urlPorInstituicao+'?instituicao='+instituicao; }
    };
    
    angular.module('AlunoANEEService',['erudioConfig']).service('AlunoANEEService',AlunoANEEService);
    AlunoANEEService.$inject = ["BaseService","ErudioConfig"];
})();
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
    var pessoaDirectives = angular.module('pessoaDirectives', []);
    
    /* Diretivas de Pessoa */
    
    pessoaDirectives.directive('modalPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/modal.html' };
    });
    pessoaDirectives.directive('partiPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-particularidades.html' };
    });
     pessoaDirectives.directive('necessPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-necessidades.html' };
    });
    pessoaDirectives.directive('endPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-endereco.html' };
    });
    
    pessoaDirectives.directive('docsPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-documentos.html' };
    });

    pessoaDirectives.directive('contatosPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-contatos.html' };
    });     
    
    pessoaDirectives.directive('controlePessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/controle.html' };
    });
    
    pessoaDirectives.directive('buscaPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form-busca.html' };
    });
    
    pessoaDirectives.directive('formPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/form.html' };
    });
    
    /*pessoaDirectives.directive('erroBuscaPessoa', function (){
        return { restrict: 'E', templateUrl: 'app/modules/pessoas/partials/erro.html' };
    });*/
})();
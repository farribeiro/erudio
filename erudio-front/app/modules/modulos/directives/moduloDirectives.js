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
    var moduloDirectives = angular.module('moduloDirectives', []);
    
    /* Diretivas de Modulo */
    
    moduloDirectives.directive('modalModulo', function (){
        return { restrict: 'E', templateUrl: 'app/modules/modulos/partials/modal.html' };
    });
    
    moduloDirectives.directive('controleModulo', function (){
        return { restrict: 'E', templateUrl: 'app/modules/modulos/partials/controle.html' };
    });
    
    moduloDirectives.directive('listaModulo', function (){
        return { restrict: 'E', templateUrl: 'app/modules/modulos/partials/lista.html' };
    });
    
    moduloDirectives.directive('formModulo', function (){
        return { restrict: 'E', templateUrl: 'app/modules/modulos/partials/form.html' };
    });
    
    moduloDirectives.directive('erroBuscaModulo', function (){
        return { restrict: 'E', templateUrl: 'app/modules/modulos/partials/erro.html' };
    });
})();
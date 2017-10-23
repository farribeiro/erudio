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
    var regimeDirectives = angular.module('regimeDirectives',[]);
    
    /* Diretivas de Regimes */
    
    regimeDirectives.directive('modalRegime', function (){
        return { restrict: 'E', templateUrl: 'app/modules/regimes/partials/modal.html' };
    });
    
    regimeDirectives.directive('controleRegime', function (){
        return { restrict: 'E', templateUrl: 'app/modules/regimes/partials/controle.html' };
    });

    regimeDirectives.directive('listaRegime', function (){
        return { restrict: 'E', templateUrl: 'app/modules/regimes/partials/lista.html' };
    });

    regimeDirectives.directive('formRegime', function (){
        return { restrict: 'E', templateUrl: 'app/modules/regimes/partials/form.html' };
    });

    regimeDirectives.directive('erroBuscaRegime', function (){
        return { restrict: 'E', templateUrl: 'app/modules/regimes/partials/erro.html' };
    });
    
})();
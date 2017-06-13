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
    var mainDirectives = angular.module('mainDirectives', []);
    
    mainDirectives.directive('mainCalendario', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/calendario.html' };
    });
    
    mainDirectives.directive('mainPerfil', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/perfil.html' };
    });
    
    mainDirectives.directive('mainModal', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/modal.html' };
    });
    
    mainDirectives.directive('mainDiarioFrequencia', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/diario-frequencia.html' };
    });
    
    mainDirectives.directive('mainChamada', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/chamada.html' };
    });    
    
    mainDirectives.directive('mainDiarioNota', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/diario-nota.html' };
    });    
    
    mainDirectives.directive('mainInicio', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/inicio.html' };
    });
    
    mainDirectives.directive('mainAvaliacoes', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/avaliacoes.html' };
    });
    
    mainDirectives.directive('mainControle', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/controle.html' };
    });
    
    mainDirectives.directive('mainObservacoes', function (){
        return { restrict: 'E', templateUrl: 'app/modules/main/partials/observacoes.html' };
    });
    
    /* Type: Attribute */
    /*mainDirectives.directive('instituicao', function (){
        return { restrict: 'A', templateUrl: 'app/modules/admin/partials/instituicao.html' };
    });*/
})();

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
    var app = angular.module('erudio', ['ngRoute', 'restangular', 'mainModule']);
    
    app.defaultHeaders = '"Content-type":"application/json"';
    
    app.config(['$routeProvider', 'RestangularProvider', '$httpProvider', '$logProvider', '$locationProvider', function($routeProvider, RestangularProvider, $httpProvider, $logProvider, $locationProvider){
        //$httpProvider.defaults.headers["delete"] = {'Content-Type': 'application/json;charset=utf-8'};
        delete $httpProvider.defaults.headers.common['X-Requested-With'];
        $logProvider.debugEnabled(true);
        $routeProvider.when('/',{
            templateUrl: 'app/modules/home/partials/blank.html'
        });
        RestangularProvider.setBaseUrl('http://10.1.6.86/erudio/erudio-server/web/api');
    }]);

    app.controller('AppController',['$timeout', '$templateCache', function($timeout, $templateCache){
        $templateCache.removeAll();
        
        sessionStorage.setItem('baseUrl','http://10.1.6.86/erudio/erudio-server/web/api');
        sessionStorage.setItem('baseUploadUrl','http://10.1.6.86/erudio/erudio-server/web/bundles/assets/uploads/');
        var sessionId = sessionStorage.getItem('sessionId');
        var username = sessionStorage.getItem('username');
        var nome = sessionStorage.getItem('nome');
        var key = sessionStorage.getItem('key');
        
        if (!sessionId) {
            window.location = 'login.html';
        } else {
            $timeout(function(){
               $('.username').html(nome);
            },500);
        }
        
        var width = $(window).width();
        if (width < 992) {
            $('head').append('<script type="text/javascript" src="lib/js/jquery/jquery.mobile.min.js"></script>');
        }
        
        /*$(window).resize(function(){
            var width = $(window).width();
            if (width < 992) {
                $('head').append('<script type="text/javascript" src="lib/js/jquery/jquery.mobile.min.js"></script>');
            }
        });*/
    }]);
})();

(function (){
    var erudioLogin = angular.module('ErudioLogin',['ngMaterial', 'restangular', 'erudioConfig', 'ngRoute', 'util', 'validator', 'pascalprecht.translate', 'appDirectives', 'rest']);
    erudioLogin.config(['$mdThemingProvider', '$routeProvider', 'RestangularProvider', 'ErudioConfigProvider', '$translateProvider', function ($mdThemingProvider, $routeProvider, RestangularProvider, ErudioConfigProvider, $translateProvider) {
        
        //DEFININDO TEMA - CORES PRIMARIAS E SECUNDARIAS
        var tema = $mdThemingProvider.theme('default'); 
        tema.primaryPalette("blue",{ 'default': '800', 'hue-1': '700', 'hue-2': '500' }); tema.accentPalette('pink');
        
        $routeProvider.when('/',{ controller: 'LoginController' });
        
        //DEFININDO TRADUÇÕES
        $translateProvider.useStaticFilesLoader({ prefix:ErudioConfigProvider.$get().dominio+'/util/translations/locale-', suffix: '.json' });
        $translateProvider.translations('en'); 
        $translateProvider.translations('ptbr');
        $translateProvider.preferredLanguage('ptbr');
        
        //DEFININDO URL DO SERVIDOR REST
        RestangularProvider.setBaseUrl(ErudioConfigProvider.$get().urlServidor);
    }]);
    
    erudioLogin.controller('LoginController',['$scope', '$mdSidenav', '$mdDialog', 'Util', '$translate', '$timeout', 'ErudioConfig', 'Restangular', 'REST', function($scope, $mdSidenav, $mdDialog, Util, $translate, $timeout, ErudioConfig, Restangular, REST){
        
        //RESETANDO SESSÃO
        sessionStorage.clear();
        
        //ATRIBUTOS
        $scope.progresso = false;    
        $scope.auth = {username: null, password: null};
        
        $scope.autenticar = function (){
            if (Util.validar("authForm")) {
                $scope.auth.password = btoa($scope.auth.password);
                $('.cortinaBranca').show(); $scope.progresso = true;
                var promise = Restangular.setFullResponse(true).all('tokens');
                promise.post($scope.auth).then(function(response){
                    sessionStorage.setItem('username',$scope.auth.username);
                    sessionStorage.setItem('pass',$scope.auth.password);
                    sessionStorage.setItem('token',response.data.token);
                    sessionStorage.setItem('autenticado',true);
                    var callback = function(response){
                        var callbackAttr = function (response) {
                            sessionStorage.setItem('atribuicoes',JSON.stringify(response.data));
                            $timeout(function(){ window.location = ErudioConfig.dominio; },500);
                        };
                        REST.um('users/'+response.data[0].id+'?view=ROLES',null,callbackAttr);
                    };
                    var promise = REST.buscar('users',{username: $scope.auth.username},callback);
                },function(error){ if (error.data.error.code === 401) { Util.toast("Usuário ou Senha incorreta, verifique e tente novamente."); } $('.cortinaBranca').hide(); $scope.progresso = false; });
            } else { Util.toast('Certifique-se que os campos estão preenchidos antes de autenticar.'); }
        };
    }]);
})();
(function (){
    var auth = angular.module('auth',['structure', 'validator', 'angular-md5', 'angular-sha1', 'erudioConfig', 'ngMaterial']);

    auth.service('Auth', ['$timeout', 'Structure', 'Validator', 'Restangular', 'md5', 'sha1', 'ErudioConfig', '$mdToast', '$filter', '$http', function($timeout, Structure, Validator, Restangular, md5, sha1, ErudioConfig, $mdToast, $filter, $http) {

        //RECUPERA TOKEN PARA OPERACOES DE BUSCAR POR ID
        this.retrieveAndRetry = function (rest) {
            var result = this.recuperaToken(rest);
            result.then(function(response){ sessionStorage.setItem('token',response.data.token); });
            return result;
        };

        //RECUPERA TOKEN
        this.recuperaToken = function (rest) {
            this.retorno = null;
            var auth = {username: sessionStorage.getItem('username'), password: sessionStorage.getItem('pass')}; var promise = rest.all('tokens');
            return promise.post(auth);
        };

    }]);
})();
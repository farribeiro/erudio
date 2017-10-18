(function (){
    var home = angular.module('home',['ngMaterial', 'util', 'erudioConfig']);
    
    home.controller('HomeController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        //SETA O TITULO
        Util.setTitulo('Início');
        $scope.professorTemplate = ErudioConfig.dominio+"/apps/professor/index/partials/index.html";
    }]);
})();
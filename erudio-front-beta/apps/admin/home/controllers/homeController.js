(function (){
    var home = angular.module('home',['ngMaterial', 'util', 'erudioConfig']);
    
    home.controller('HomeController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        //SETA O TITULO
        Util.setTitulo('');
        var attrAtiva = JSON.parse(sessionStorage.getItem("atribuicao-ativa"));
        if (attrAtiva.grupo.nome === "Professor") { 
            $scope.professorTemplate = ErudioConfig.dominio+"/apps/professor/index/partials/index.html";
        } else {
            $scope.professorTemplate = "";
        }
    }]);
})();
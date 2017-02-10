(function (){
    var historicoEscolarDirectives = angular.module('historicoEscolarDirectives', []);
    
     historicoEscolarDirectives.directive('formHistoricoEscolar', function() {
        return { restrict: 'E', templateUrl: 'app/modules/historicoEscolar/partials/form-historicoEscolar.html' };
    });
    
      historicoEscolarDirectives.directive('telaHistoricoEscolar', function() {
        return { restrict: 'E', templateUrl: 'app/modules/historicoEscolar/partials/tela-historicoEscolar.html' };
    });
    
    historicoEscolarDirectives.directive('controleHistoricoEscolar', function() {
        return { restrict: 'E', templateUrl: 'app/modules/historicoEscolar/partials/controle.html' };
    });
    
    
})();
(function (){
    var registroMatriculaDirectives = angular.module('registroMatriculaDirectives', []);
    
    registroMatriculaDirectives.directive('formRegistroMatricula', function() {
        return { restrict: 'E', templateUrl: 'app/modules/registroMatriculas/partials/form.html' };
    });
    
    registroMatriculaDirectives.directive('controleRegistroMatricula', function() {
        return { restrict: 'E', templateUrl: 'app/modules/registroMatriculas/partials/controle.html' };
    });
    
    registroMatriculaDirectives.directive('listaRegistroMatricula', function() {
        return { restrict: 'E', templateUrl: 'app/modules/registroMatriculas/partials/lista.html' };
    });
    
    registroMatriculaDirectives.directive('visualizarRegistroMatricula', function() {
        return { restrict: 'E', templateUrl: 'app/modules/registroMatriculas/partials/visualizar-pdf.html' };
    });
    
})();
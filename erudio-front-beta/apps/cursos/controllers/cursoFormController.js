(function (){
    var cursosForm = angular.module('cursosForm',['ngMaterial', 'util', 'erudioConfig']);
    cursosForm.controller('CursoFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Cursos');
        
        //CURSO EM USO
        $scope.curso = Util.getEstrutura('curso');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações do Curso'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('cursos','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'cursoForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/cursos/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO CURSO
        $scope.buscarCurso = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('cursos',$routeParams.id);
                promise.then(function(response){ $scope.curso = response.data; $scope.buscarModalidades(); $timeout(function(){ Util.aplicarMascaras(); },300); });
            } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarModalidades(); },300); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //BUSCANDO MODALIDADES
        $scope.buscarModalidades = function () { var promise = Util.buscar('modalidades-ensino',null); promise.then(function(response){ $scope.modalidades = response.data; }); };
                
        //SALVAR UNIDADE
        $scope.salvar = function () { if ($scope.validar('cursoForm')) { var resultado = Util.salvar($scope.curso,'cursos'); resultado.then(function (){ Util.redirect($scope.fab.href); }); } };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarCurso();
        Util.mudarImagemToolbar('cursos/assets/images/cursos.jpg');
    }]);
})();
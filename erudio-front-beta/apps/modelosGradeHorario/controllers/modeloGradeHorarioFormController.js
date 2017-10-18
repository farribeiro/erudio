(function (){
    var modelosGradeHorarioForm = angular.module('modelosGradeHorarioForm',['ngMaterial', 'util', 'erudioConfig']);
    modelosGradeHorarioForm.controller('ModeloGradeHorarioFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Modelos de Grade de Horário');
        
        //MODELO EM USO
        $scope.modelo = Util.getEstrutura('modeloGradeHorario');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('modelosGradeHorario','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'modeloForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/modelos-horario/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO MODELO
        $scope.buscarModelo = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('modelo-quadro-horarios',$routeParams.id);
                promise.then(function(response){ $scope.modelo = response.data; Util.aplicarMascaras(); $scope.buscarCursos(); });
            } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarCursos(); },300); }
        };
        
        //BUSCANDO CURSO
        $scope.buscarCursos = function () { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; }); };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR MODELO
        $scope.salvar = function () {
            if ($scope.validar('modeloForm')) {
                var resultado = Util.salvar($scope.modelo,'modelo-quadro-horarios','Modelo de Grade de Horario','M');
                resultado.then(function (){ Util.redirect($scope.fab.href); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarModelo();
        Util.mudarImagemToolbar('modelosGradeHorario/assets/images/modelosGradeHorario.jpg');
    }]);
})();
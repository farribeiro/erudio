(function (){
    var modulosForm = angular.module('modulosForm',['ngMaterial', 'util', 'erudioConfig']);
    modulosForm.controller('ModuloFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Módulos');
        
        //MODULO EM USO
        $scope.modulo = Util.getEstrutura('modulo');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('modulos','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'moduloForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/modulos/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO MODULO
        $scope.buscarModulo = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('modulos',$routeParams.id);
                promise.then(function(response){ $scope.modulo = response.data; Util.aplicarMascaras(); $scope.buscarCursos(); });
            } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarCursos(); },300); }
        };
        
        //BUSCANDO CURSO
        $scope.buscarCursos = function () { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; }); };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR INSTITUICAO
        $scope.salvar = function () {
            if ($scope.validar('moduloForm')) {
                var resultado = Util.salvar($scope.modulo,'modulos','Módulo','M');
                resultado.then(function (){ Util.redirect($scope.fab.href); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarModulo();
        Util.mudarImagemToolbar('modulos/assets/images/modulos.jpeg');
    }]);
})();
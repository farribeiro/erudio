(function (){
    var regimesForm = angular.module('regimesForm',['ngMaterial', 'util', 'erudioConfig']);
    regimesForm.controller('RegimeFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Regimes de Ensino');
        
        //TIPO EM USO
        $scope.regime = Util.getEstrutura('regime');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('regimes','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'regimeForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/regimes/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO TIPO
        $scope.buscarRegime = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('regimes',$routeParams.id);
                promise.then(function(response){ $scope.regime = response.data; Util.aplicarMascaras(); });
            } else { $timeout(function(){ Util.aplicarMascaras(); },300); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR INSTITUICAO
        $scope.salvar = function () {
            if ($scope.validar('regimeForm')) {
                var resultado = Util.salvar($scope.regime,'regimes','Regime de Ensino','M');
                resultado.then(function (){ Util.redirect($scope.fab.href); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarRegime();
        Util.mudarImagemToolbar('regimes/assets/images/regimes.png');
    }]);
})();
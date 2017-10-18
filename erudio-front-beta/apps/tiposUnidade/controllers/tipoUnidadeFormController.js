(function (){
    var tipoUnidadeForm = angular.module('tipoUnidadeForm',['ngMaterial', 'util', 'erudioConfig']);
    tipoUnidadeForm.controller('TipoUnidadeFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Tipos de Unidade');
        
        //TIPO EM USO
        $scope.tipo = Util.getEstrutura('tipoUnidade');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('tiposUnidade','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'tiposUnidadeForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/tipos-unidade/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO TIPO
        $scope.buscarTipo = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('tipos-unidade-ensino',$routeParams.id);
                promise.then(function(response){ $scope.tipo = response.data; Util.aplicarMascaras(); });
            } else { $timeout(function(){ Util.aplicarMascaras(); },300); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR INSTITUICAO
        $scope.salvar = function () {
            if ($scope.validar('tiposUnidadeForm')) {
                var resultado = Util.salvar($scope.tipo,'tipos-unidade-ensino','Tipo de Unidade','F');
                resultado.then(function (){ Util.redirect($scope.fab.href); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarTipo();
        Util.mudarImagemToolbar('tiposUnidade/assets/images/tiposUnidade.jpeg');
    }]);
})();
(function (){
    var turnosForm = angular.module('turnosForm',['ngMaterial', 'util', 'erudioConfig']);
    turnosForm.controller('TurnoFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Turnos');
        
        //TURNO EM USO
        $scope.turno = Util.getEstrutura('turno');
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('turnos','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'turnoForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/turnos/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO TURNO
        $scope.buscarTurno = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('turnos',$routeParams.id);
                promise.then(function(response){ 
                    $scope.turno = response.data;
                    var inicioArr = $scope.turno.inicio.split(":"); var terminoArr = $scope.turno.termino.split(":");
                    $scope.turno.inicio = inicioArr[0]+":"+inicioArr[1]; $scope.turno.termino = terminoArr[0]+":"+terminoArr[1];
                    Util.aplicarMascaras();
                });
            } else { $timeout(function(){ Util.aplicarMascaras(); },300); }
        };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR INSTITUICAO
        $scope.salvar = function () {
            if ($scope.validar('turnoForm')) {
                $scope.turno.inicio += ":00"; $scope.turno.termino += ":00";
                var resultado = Util.salvar($scope.turno,'turnos','Turnos','M');
                resultado.then(function (){ Util.redirect($scope.fab.href); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarTurno();
        Util.mudarImagemToolbar('turnos/assets/images/turnos.jpg');
    }]);
})();
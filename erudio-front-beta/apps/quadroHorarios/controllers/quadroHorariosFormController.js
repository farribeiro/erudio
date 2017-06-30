(function (){
    var quadroHorariosForm = angular.module('quadroHorariosForm',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    quadroHorariosForm.controller('QuadroHorarioFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', 'Shared', function($scope, Util, ErudioConfig, $routeParams, $timeout, Shared){
        
        //SETA O TITULO
        Util.setTitulo('Quadros de Horários');
        
        //QUADRO EM USO
        $scope.quadro = Util.getEstrutura('quadroHorario'); $scope.turnos = [];
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações do Quadro de Horário'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('quadroHorarios','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'quadroForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/quadro-horarios/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO QUADRO
        $scope.buscarQuadro = function () {
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('quadro-horarios',$routeParams.id);
                promise.then(function(response){ $scope.quadro = response.data; $timeout(function(){ Util.aplicarMascaras(); },500); });
            } else { $timeout(function(){ Util.aplicarMascaras(); },500); }
        };
        
        //BUSCAR UNIDADES
        $scope.buscarUnidades = function (){
            var promise = Util.buscar('unidades-ensino',null);
            promise.then(function(response){ $scope.items = response.data; });
        };
        
        //FILTRANDO AUTOCOMPLETE
        $scope.filtrar = function (query){ return Util.filtrar(query, $scope.items); };
        
        //SELECIONAR AUTOCOMPLETE
        $scope.selecionar = function (item) { if (Util.isVazio(item.id)) { $scope.quadro.unidadeEnsino.id = null; } else { $scope.quadro.unidadeEnsino.id = item.id; } };
        
        //BUSCANDO TURNOS
        $scope.buscarTurnos = function () { var promise = Util.buscar('turnos',null); promise.then(function(response){ $scope.turnos = response.data; }); };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //BUSCANDO MODELO DE QUADRO DE HORARIO
        $scope.buscarModeloQuadro = function () { var promise = Util.buscar('modelo-quadro-horarios',null); promise.then(function(response){ $scope.quadros = response.data; }); };
            
        //SALVAR QUADRO
        $scope.salvar = function () { 
            if ($scope.validar('quadroForm')) { 
                $scope.quadro.inicio += ":00"; var diasSemana = angular.copy($scope.quadro.diasSemana);
                $scope.quadro.diasSemana.forEach(function(dia,i){ if (dia.diaSemana) { dia.diaSemana = i+2; } else { $scope.quadro.diasSemana.splice(i,1); } });
                var resultado = Util.salvar($scope.quadro,'quadro-horarios'); resultado.then(function (){ Util.redirect($scope.fab.href); }); 
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarQuadro(); $scope.buscarUnidades(); $scope.buscarTurnos();
        $timeout(function(){$('md-autocomplete-wrap').removeClass('md-whiteframe-z1');},500); $scope.buscarModeloQuadro();
        Util.mudarImagemToolbar('quadroHorarios/assets/images/quadroHorarios.jpg');
    }]);
})();
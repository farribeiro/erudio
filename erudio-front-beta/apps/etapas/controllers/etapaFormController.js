(function (){
    var etapasForm = angular.module('etapasForm',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    etapasForm.controller('EtapaFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', 'Shared', function($scope, Util, ErudioConfig, $routeParams, $timeout, Shared){
        
        //SETA O TITULO
        Util.setTitulo('Etapas');
        
        //ETAPA EM USO
        $scope.etapa = Util.getEstrutura('etapa'); $scope.curso = {id: null};
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações da Etapa'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('etapas','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'etapaForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/etapas/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO CURSO
        $scope.buscarEtapa = function () {
            $scope.etapa.curso.id = Shared.getCursoEtapa();
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('etapas',$routeParams.id);
                promise.then(function(response){ $scope.etapa = response.data; $timeout(function(){ Util.aplicarMascaras(); $scope.buscarCursos(); $scope.buscarModeloQuadro(); $scope.buscarModulos(); $scope.buscarSistemaAvaliacao(); },500); });
            } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarModeloQuadro(); $scope.buscarModulos(); $scope.buscarSistemaAvaliacao(); $scope.buscarCursos(); },500); }
        };
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; }); };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //BUSCANDO MODALIDADES
        $scope.buscarModulos = function () { var promise = Util.buscar('modulos',null); promise.then(function(response){ $scope.modulos = response.data; }); };
        
        //BUSCANDO SISTEMAS DE AVALIACAO
        $scope.buscarSistemaAvaliacao = function () { var promise = Util.buscar('sistemas-avaliacao',null); promise.then(function(response){ $scope.sistemas = response.data; }); };
        
        //BUSCANDO MODELO DE QUADRO DE HORARIO
        $scope.buscarModeloQuadro = function () { var promise = Util.buscar('modelo-quadro-horarios',null); promise.then(function(response){ $scope.quadros = response.data; }); };
            
        //SALVAR UNIDADE
        $scope.salvar = function () { if ($scope.validar('etapaForm')) { var resultado = Util.salvar($scope.etapa,'etapas'); resultado.then(function (){ Util.redirect($scope.fab.href); }); } };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarEtapa();
        Util.mudarImagemToolbar('etapas/assets/images/etapas.jpg');
    }]);
})();
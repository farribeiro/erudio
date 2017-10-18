(function (){
    var disciplinasForm = angular.module('disciplinasForm',['ngMaterial', 'util', 'erudioConfig', 'shared']);
    disciplinasForm.controller('DisciplinaFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', 'Shared', function($scope, Util, ErudioConfig, $routeParams, $timeout, Shared){
        
        //SETA O TITULO
        Util.setTitulo('Disciplinas');
        
        //DISCIPLINA EM USO
        $scope.disciplina = Util.getEstrutura('disciplina');
        $scope.cursos = []; $scope.etapas = [];
        
        //SETA SUBHEADER DO FORM
        $scope.subheaders =[{label: 'Informações da Disciplina'}];
        
        //TEMPLATE DOS BLOCOS DE INPUTS
        $scope.inputs = [{ href: Util.getInputBlockCustom('disciplinas','inputs') }];
        
        //CRIAR FORMS
        $scope.forms = [{ nome: 'disciplinaForm', subheaders: $scope.subheaders }];
        
        //OPCOES DO BOTAO VOLTAR
        $scope.link = '/#!/disciplinas/';
        $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
        
        //BUSCANDO DISCIPLINA
        $scope.buscarDisciplina = function () {
            var etapa = Shared.getEtapaDisciplina();
            if (!Util.isVazio(etapa)) { var promise = Util.um('etapas',etapa); promise.then(function(response){ $scope.disciplina.curso.id = response.data.curso.id; $scope.disciplina.etapa.id = response.data.id; }); }
            if (!Util.isNovo($routeParams.id)) {
                var promise = Util.um('disciplinas',$routeParams.id);
                promise.then(function(response){ $scope.disciplina = response.data; $timeout(function(){ Util.aplicarMascaras(); $scope.buscarCursos(); $scope.buscarEtapas(); },500); });
            } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarCursos(); if (!Util.isVazio($scope.disciplina.etapa.id)) { $scope.buscarEtapas(); } },500); }
        };
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () { var promise = Util.buscar('cursos',null); promise.then(function(response){ $scope.cursos = response.data; }); };
        
        //BUSCANDO ETAPAS
        $scope.buscarEtapas = function () { var promise = Util.buscar('etapas',{ curso: $scope.disciplina.curso.id }); promise.then(function(response){ $scope.etapas = response.data; }); };
        
        //VALIDA CAMPO
        $scope.validaCampo = function () { Util.validaCampo(); };
        
        //SALVAR UNIDADE
        $scope.salvar = function () { 
            if ($scope.validar('disciplinaForm')) { 
                var resultado = Util.salvar($scope.disciplina,'disciplinas'); resultado.then(function (){  Util.redirect($scope.fab.href); Shared.setEtapaDisciplina($scope.disciplina.etapa.id); });
            }
        };
        
        //VALIDAR FORM
        $scope.validar = function (formId) { return Util.validar(formId); };
        
        //INICIANDO
        $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarDisciplina();
        Util.mudarImagemToolbar('disciplinas/assets/images/disciplinas.jpg');
    }]);
})();
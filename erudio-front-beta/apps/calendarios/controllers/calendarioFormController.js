(function (){
    var calendariosForm = angular.module('calendariosForm',['ngMaterial', 'util', 'erudioConfig']);
    calendariosForm.controller('CalendarioFormController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, ErudioConfig, $routeParams, $timeout){
        
        //VERIFICA PERMISSOES
        $scope.permissao = Util.verificaPermissao('CALENDARIO');
        
        //VALIDANDO PERMISSAO
        if ($scope.permissao) {
            
            Util.comPermissao(); $scope.escrita = Util.verificaEscrita('CALENDARIO');
            $scope.validarEscrita = function (opcao) { return Util.validarEscrita(opcao, $scope.opcoes, $scope.escrita); };
            $scope.isAdmin = Util.isAdmin(); $scope.mostraAutocomplete = true; $scope.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
            $scope.disabledSelect = false;
            
            //SETA O TITULO
            Util.setTitulo('Calendários');
            
            //ATRIBUTOS EXTRAS - autocomplete
            $scope.items = []; $scope.item = {id: null}; $scope.itemBusca = ''; $scope.autoPlaceholder = '';

            //BUSCAR UNIDADES - autocomplete
            $scope.buscarUnidades = function (){
                if (!Util.isVazio($scope.calendario.id)) { $scope.selecionar($scope.calendario.instituicao); $timeout(function(){ Util.desabilitaForm('#calendariosForm'); $scope.disabledSelect = true; },500); }
                if ($scope.isAdmin) { var promise = Util.buscar('unidades-ensino',null); promise.then(function(response){ $scope.items = response.data; });
                } else {
                    if ($scope.attr.atribuicoes.length > 1) { $scope.attr.forEach(function(a){ $scope.items.push(a.instituicao); }); $scope.mostraAutocomplete = true; }
                    else { $scope.item.id = $scope.attr.atribuicoes[0].instituicao.id; $scope.calendario.instituicao.id = $scope.attr.atribuicoes[0].instituicao.id; $scope.mostraAutocomplete = false; }
                }
            };
            
            //FILTRANDO AUTOCOMPLETE - autocomplete
            $scope.filtrar = function (query){ return Util.filtrar(query, $scope.items); };

            //SELECIONAR AUTOCOMPLETE
            $scope.selecionar = function (item) { if (Util.isVazio(item.id)) { $scope.objetos = []; } else { $scope.itemBusca = item.nomeCompleto; $scope.item.id = $scope.attr.atribuicoes[0].instituicao.id; $scope.calendario.instituicao.id = $scope.attr.atribuicoes[0].instituicao.id; } };

            //BUSCAR SISTEMAS DE AVALIACAO
            $scope.buscarSistemas = function (){
                var promise = Util.buscar('sistemas-avaliacao',null);
                promise.then(function(response) { $scope.sistemas = response.data; });
            };

            //CALENDARIO EM USO
            $scope.calendario = Util.getEstrutura('calendario');

            //SETA SUBHEADER DO FORM
            $scope.subheaders =[{label: 'Informações do Calendário'}];

            //TEMPLATE DOS BLOCOS DE INPUTS
            $scope.inputs = [{ href: Util.getInputBlockCustom('calendarios','inputs') }];

            //CRIAR FORMS
            $scope.forms = [{ nome: 'calendariosForm', subheaders: $scope.subheaders }];

            //OPCOES DO BOTAO VOLTAR
            $scope.link = '/#!/calendarios/';
            $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};

            //BUSCANDO CALENDARIO
            $scope.buscarCalendario = function () {
                if (!Util.isNovo($routeParams.id)) {
                    var promise = Util.um('calendarios',$routeParams.id);
                    promise.then(function(response){ 
                        $scope.calendario = response.data; $scope.calendario.dataInicio = Util.formataData(response.data.dataInicio); $scope.calendario.dataTermino = Util.formataData(response.data.dataTermino);
                        $timeout(function(){ Util.aplicarMascaras(); $scope.buscarUnidades(); $scope.buscarSistemas(); $scope.buscarBase(); },300); $timeout(function(){ $("md-autocomplete-wrap").removeClass('md-whiteframe-z1').addClass('md-whiteframe-z0'); $("md-autocomplete-wrap").css('border-bottom','1px solid #eee'); },600); 
                    });
                } else { $timeout(function(){ Util.aplicarMascaras(); $scope.buscarUnidades(); $scope.buscarSistemas(); $scope.buscarBase(); },300); $timeout(function(){ $("md-autocomplete-wrap").removeClass('md-whiteframe-z1').addClass('md-whiteframe-z0'); $("md-autocomplete-wrap").css('border-bottom','1px solid #eee'); },600); }
            };

            //BUSCANDO CALEMDARIO BASE
            $scope.buscarBase = function () {
                var attrAtual = JSON.parse(sessionStorage.getItem('atribuicao-ativa'));
                var promise = Util.buscar('calendarios',{instituicao: attrAtual.instituicao.id});
                promise.then(function(response) { $scope.calendarios = response.data; });
            };

            //VALIDA CAMPO
            $scope.validaCampo = function () { Util.validaCampo(); };

            //SALVAR CALENDARIO
            $scope.salvar = function () { 
                if ($scope.validar('calendariosForm')) { 
                    $scope.calendario.calendarioBase.id = parseInt($scope.calendario.calendarioBase.id); $scope.calendario.sistemaAvaliacao.id = parseInt($scope.calendario.sistemaAvaliacao.id);
                    $scope.calendario.dataInicio = Util.converteData($scope.calendario.dataInicio); $scope.calendario.dataTermino = Util.converteData($scope.calendario.dataTermino);
                    $timeout(function(){ var resultado = Util.salvar($scope.calendario,'calendarios'); resultado.then(function (){ Util.redirect($scope.fab.href); }); },500);
                }
            };

            //VALIDAR FORM
            $scope.validar = function (formId) { return Util.validar(formId); };

            //INICIANDO
            $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarCalendario();
            Util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
            
        } else { Util.semPermissao(); }            
    }]);
})();
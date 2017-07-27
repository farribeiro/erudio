(function (){
    var calendariosView = angular.module('calendariosView',['ngMaterial', 'util', 'erudioConfig']);
    calendariosView.controller('CalendarioViewController',['$scope', 'Util', 'ErudioConfig', '$routeParams', '$timeout', '$mdDialog', function($scope, Util, ErudioConfig, $routeParams, $timeout, $mdDialog){
        
        //VERIFICA PERMISSOES
        $scope.permissao = Util.verificaPermissao('CALENDARIO');
        
        //VALIDANDO PERMISSAO
        if ($scope.permissao) {
            
            Util.comPermissao(); $scope.escrita = Util.verificaEscrita('CALENDARIO'); $scope.isAdmin = Util.isAdmin();
            $scope.validarEscrita = function (opcao) { return Util.validarEscrita(opcao, $scope.opcoes, $scope.escrita); }; 
            //$scope.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
            
            //ATRIBUTOS
            $scope.mesCalendario = []; $scope.semanaCalendario = [];
            
            //SETA O TITULO
            Util.setTitulo('Calendários');

            //OPCOES DO BOTAO VOLTAR
            $scope.link = '/#!/calendarios/';
            $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};

            //BUSCANDO CALENDARIO
            $scope.buscarCalendario = function () {
                if (!Util.isNovo($routeParams.id)) {
                    var promise = Util.um('calendarios',$routeParams.id);
                    promise.then(function(response){ $scope.calendario = response.data; $scope.preparaCalendario(); });
                }
            };
            
            //RESET CALENDARIO
            $scope.resetCalendario = function () { $scope.mesCalendario = []; $scope.semanaCalendario = []; };
            
            //PREPARA CALENDARIO
            $scope.preparaCalendario = function (mes,ano) {
                if (Util.isVazio(mes) && Util.isVazio(ano)) {
                    var dateBase = new Date(); $scope.mes = dateBase.getMonth(); $scope.ano = dateBase.getFullYear();
                    $scope.preparaCalendario($scope.mes, $scope.ano);
                } else {
                    $scope.diaS = 1; $scope.mes = mes; $scope.ano = ano; $scope.diaSemana = new Date($scope.ano,$scope.mes,$scope.diaS).getDay();
                    $scope.counterCalendario = $scope.diaSemana; $scope.gapInicio = $scope.diaSemana;
                }
                $scope.diasMes = Util.diasNoMes($scope.mes,$scope.ano); $scope.semanaCalendario = new Array($scope.gapInicio); $scope.nomeMes = Util.nomeMes($scope.mes);
                $timeout(function(){ $scope.linkPaginacao(); },500); $scope.buscarEventos();
            };
            
            //ABRIR DIA MODAL
            $scope.abrirDia = function (dia) {
                $scope.dia = dia;
                $mdDialog.show({locals: {dia: {dia: dia, config: ErudioConfig} }, controller: ModalControl, templateUrl: ErudioConfig.dominio+'/apps/calendarios/partials/dia.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
                $timeout(function(){
                    $('.dia-item').click(function(){
                        var val = $(this).attr('data-value');
                        switch (val) {
                            case 'E': 
                                $scope.dia.letivo = true; $scope.dia.efetivo = true;
                                Util.salvarLote([$scope.dia],'calendarios/'+$scope.calendario.id+'/dias');
                                break;
                            case 'L': 
                                $scope.dia.letivo = true; $scope.dia.efetivo = false;
                                Util.salvarLote([$scope.dia],'calendarios/'+$scope.calendario.id+'/dias');
                                break;
                            default: 
                                $scope.dia.letivo = false; $scope.dia.efetivo = false;
                                Util.salvarLote([$scope.dia],'calendarios/'+$scope.calendario.id+'/dias');
                                break;
                        }
                    });
                },500);
            };
            
            //FUNCAO DO MODAL - CRIANDO ESCOPO
            function ModalControl($scope, dia) { 
                $scope.dia = dia.dia; $scope.config = dia.config;
                $scope.abreMenu = function ($mdMenu, ev) { var origemEv = ev; $mdMenu.open(ev); };
            }
            
            //BUSCAR EVENTOS
            $scope.buscarEventos = function () {
                var promise = Util.buscar('calendarios/'+$scope.calendario.id+'/meses/'+($scope.mes+1));
                promise.then(function(response){ 
                    $scope.dias = response.data;
                    for (var i=0; i<$scope.diasMes; i++) {                        
                        $scope.counterCalendario++; $scope.semanaCalendario.push($scope.dias[i]);
                        if ($scope.counterCalendario === 7) { $scope.mesCalendario.push($scope.semanaCalendario); $scope.counterCalendario = 0; $scope.semanaCalendario = []; }
                        if (i === $scope.diasMes-1) {
                            var dataFinal = new Date($scope.ano,$scope.mes,i+1); $scope.gapFinal = 6 - dataFinal.getDay();
                            for (var j=0; j<$scope.gapFinal; j++) { $scope.semanaCalendario.push(null); if (j === $scope.gapFinal-1) { $scope.mesCalendario.push($scope.semanaCalendario); } }
                        }
                    }
                });
            };
            
            //LINKS PAGINACAO
            $scope.linkPaginacao = function () {
                $scope.proximoMes = new Date($scope.ano,$scope.mes,1); $scope.proximoMes.setMonth($scope.proximoMes.getMonth()+1);
                $scope.mesAnterior = new Date($scope.ano,$scope.mes,1); $scope.mesAnterior.setMonth($scope.mesAnterior.getMonth()-1);
            };
            
            //VERIFICA TIPO DE DIA - LETIVO/EFETIVO OU NAO LETIVO
            $scope.classeTipoDia = function (dia){ if (!Util.isVazio(dia)) { if (dia.efetivo) { return 'calendario-dia-efetivo'; } else if (dia.letivo) { return 'calendario-dia-letivo'; } else { return 'calendario-dia-nao-letivo'; } } };
            
            //PAGINANDO
            $scope.paginaProxima = function (){ $scope.resetCalendario(); $scope.preparaCalendario($scope.proximoMes.getMonth(),$scope.proximoMes.getFullYear()); };
            $scope.paginaAnterior = function (){ $scope.resetCalendario(); $scope.preparaCalendario($scope.mesAnterior.getMonth(),$scope.mesAnterior.getFullYear()); };

            //INICIANDO
            $scope.form = Util.getTemplateForm(); Util.inicializar(); $scope.buscarCalendario();
            Util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
            
        } else { Util.semPermissao(); }            
    }]);
})();
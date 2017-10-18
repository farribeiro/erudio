(function (){
    var turnos = angular.module('turnos',['ngMaterial', 'util', 'erudioConfig']);
    turnos.controller('TurnoController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', '$filter', function($scope, Util, $mdDialog, ErudioConfig, $timeout, $filter){
        
        //SETA O TITULO
        Util.setTitulo('Turnos');
        
        //TURNO EM USO
        $scope.turno = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Turno'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/turnos/';
        $scope.fab = {tooltip: 'Adicionar Turno', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO TURNOS
        $scope.buscarTurnos = function () {
            var promise = Util.buscar('turnos',{page: $scope.pagina});
            promise.then(function(response){ 
                $scope.objetos = response.data;
                $scope.objetos.forEach(function(objeto, i) {
                    var inicioArr = objeto.inicio.split(":"); var terminoArr = objeto.termino.split(":");
                    objeto.inicio = inicioArr[0]+":"+inicioArr[1]; objeto.termino = terminoArr[0]+":"+terminoArr[1];
                });
            });
        };
        $scope.buscarTurnos();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('turnos');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.turno = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Turno', 'Deseja remover este Turno?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.turno, 'Turno', 'm'); remocao.then(function(){ $scope.buscarTurnos(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarTurnos(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('turnos',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarTurnos(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarTurnos(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('turnos/assets/images/turnos.jpg');
    }]);
})();
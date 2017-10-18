(function (){
    var regimes = angular.module('regimes',['ngMaterial', 'util', 'erudioConfig']);
    regimes.controller('RegimeController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Regimes de Ensino');
        
        //REGIME EM USO
        $scope.regime = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Regime'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/regimes/';
        $scope.fab = {tooltip: 'Adicionar Regime de Ensino', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO REGIMES
        $scope.buscarRegimes = function () {
            var promise = Util.buscar('regimes',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarRegimes();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('regimes');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.regime = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Regime de Ensino', 'Deseja remover este Regime de Ensino?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.regime, 'Regime de Ensino', 'm'); remocao.then(function(){ $scope.buscarRegimes(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarRegimes(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('regimes',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarRegimes(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarRegimes(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('regimes/assets/images/regimes.png');
    }]);
})();
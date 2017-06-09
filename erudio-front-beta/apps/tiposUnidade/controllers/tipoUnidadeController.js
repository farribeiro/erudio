(function (){
    var tipoUnidade = angular.module('tipoUnidade',['ngMaterial', 'util', 'erudioConfig']);
    tipoUnidade.controller('TipoUnidadeController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Tipos de Unidade');
        
        //TIPO EM USO
        $scope.tipo = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Tipo'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/tipos-unidade/';
        $scope.fab = {tooltip: 'Adicionar Tipo de Unidade', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO TIPOS
        $scope.buscarTipos = function () {
            var promise = Util.buscar('tipos-unidade-ensino',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarTipos();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('tiposUnidade');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.tipo = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Tipo de Unidade', 'Deseja remover este Tipo de Unidade?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.tipo, 'Tipo de Unidade', 'f'); remocao.then(function(){ $scope.buscarTipos(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarTipos(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('tipos-unidade-ensino',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarTipos(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarTipos(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('tiposUnidade/assets/images/tiposUnidade.jpeg');
    }]);
})();
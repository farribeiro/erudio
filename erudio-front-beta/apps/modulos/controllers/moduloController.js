(function (){
    var modulos = angular.module('modulos',['ngMaterial', 'util', 'erudioConfig']);
    modulos.controller('ModuloController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Módulos');
        
        //MODULO EM USO
        $scope.modulo = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Módulo'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/modulos/';
        $scope.fab = {tooltip: 'Adicionar Módulo', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO MODULOS
        $scope.buscarModulos = function () {
            var promise = Util.buscar('modulos',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarModulos();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('modulos');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) { $scope.modulo = objeto; $scope.modalExclusão(event); };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Módulo', 'Deseja remover este Módulo?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.modulo, 'Módulo', 'm'); remocao.then(function(){ $scope.buscarModulos(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarModulos(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('modulos',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarModulos(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarModulos(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('modulos/assets/images/modulos.jpeg');
    }]);
})();
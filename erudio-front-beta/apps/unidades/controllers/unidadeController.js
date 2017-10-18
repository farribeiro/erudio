(function (){
    var unidades = angular.module('unidades',['ngMaterial', 'util', 'erudioConfig']);
    unidades.controller('UnidadeController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Unidades de Ensino');
        
        //UNIDADE EM USO
        $scope.unidade = null; $scope.unidadeInfo = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome da Unidade'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Informações', icone: 'info', opcao: 'info'},{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/unidades/';
        $scope.fab = {tooltip: 'Adicionar Unidade', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO UNIDADES
        $scope.buscarUnidades = function () {
            var promise = Util.buscar('unidades-ensino',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarUnidades();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('unidades');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) {
            switch (opcao.opcao) {
                case 'remover': $scope.unidade = objeto; $scope.modalExclusão(event); break;
                case 'info': $scope.verInformacoes(event, objeto); break;
                default: return false; break;
            }
        };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Unidade de Ensino', 'Deseja remover esta Unidade de Ensino?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.unidade, 'Unidade', 'f'); remocao.then(function(){ $scope.buscarUnidades(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarUnidades(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('unidades-ensino',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //ABRIR INFORMACOES
        $scope.verInformacoes = function (event, objeto) {
            $scope.unidadeInfo = null; var promise = Util.um('unidades-ensino',objeto.id);
            promise.then(function (response){ 
                $scope.unidadeInfo = response.data;
                $mdDialog.show({locals: {unidade: $scope.unidadeInfo}, controller: ModalControl, templateUrl: ErudioConfig.dominio+'/apps/unidades/partials/informacoes.html', parent: angular.element(document.body), targetEvent: event, clickOutsideToClose: true});
            });
        };
        
        //FUNCAO DO MODAL - CRIANDO ESCOPO
        function ModalControl($scope, unidade) { $scope.unidadeInfo = unidade; }
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarUnidades(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarUnidades(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('unidades/assets/images/unidades.jpg');
    }]);
})();
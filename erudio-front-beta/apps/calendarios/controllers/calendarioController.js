(function (){
    var calendarios = angular.module('calendarios',['ngMaterial', 'util', 'erudioConfig','shared']);
    calendarios.controller('CalendarioController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', 'Shared', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, Shared, $timeout){
        
        //VERIFICA PERMISSOES
        $scope.permissao = Util.verificaPermissao('CALENDARIO');
        
        //VALIDANDO PERMISSAO
        if ($scope.permissao) {
            
            Util.comPermissao();
            $scope.escrita = Util.verificaEscrita('CALENDARIO');
            $scope.validarEscrita = function (opcao) { return Util.validarEscrita(opcao, $scope.opcoes, $scope.escrita); };
            
            //SETA O TITULO
            Util.setTitulo('Calendários');

            //CALENDARIO EM USO
            $scope.calendario = null;

            //SETA COLUNAS DA LISTA
            $scope.subheaders =[{label: 'Nome do Calendário'}];

            //OPCOES
            $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];

            //OPCOES DO BOTAO FAB
            $scope.link = ErudioConfig.dominio + '/#!/calendarios/view/';
            $scope.fab = {tooltip: 'Adicionar Calendário', icone: 'add', href: $scope.link+'novo'};

            //PAGINA DA LISTA
            $scope.pagina = 0;

            //BUSCANDO CALENDARIO - inserir unidade
            $scope.buscarCalendarios = function () {
                var promise = Util.buscar('calendarios',{page: $scope.pagina});
                promise.then(function(response){ $scope.objetos = response.data; });
            };
            $scope.buscarCalendarios();

            //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
            $scope.lista = Util.getTemplateLista();
            $scope.listaEspecifica = Util.getTemplateListaEspecifica('calendarios');

            //BUSCANDO TEMPLATE DA BUSCA SIMPLES
            $scope.buscaSimples = Util.getTemplateBuscaSimples();

            //EXECUTANDO OPCOES
            $scope.executarOpcao = function (event, opcao, objeto) {
                $scope.calendario = objeto;
                switch (opcao.opcao) {
                    case 'remover': $scope.modalExclusão(event); break;
                    default: return false; break;
                }
            };

            //ABRINDO MODAL DE EXCLUSAO
            $scope.modalExclusão = function (event) {
                var confirm = Util.modalExclusao(event, 'Remover Calendário', 'Deseja remover este Calendário?', 'remover', $mdDialog);
                $mdDialog.show(confirm).then(function() { 
                    var remocao = Util.remover($scope.calendario, 'Calendário', 'm'); remocao.then(function(){ $scope.buscarCalendarios(); });
                }, function() {});
            };

            //ESCUTANDO VARIAVEL DE BUSCA
            $scope.busca = '';
            $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarCalendarios(); } else { $scope.executarBusca(query); } };

            //BUSCA COM PARAMETRO
            $scope.executarBusca = function (query) {
                $timeout.cancel($scope.delayBusca);
                $scope.delayBusca = $timeout(function(){
                    if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                    if (tamanho > 3) {
                        var res = Util.buscar('calendarios',{'nome':query});
                        res.then(function(response){ $scope.objetos = response.data; });
                    }
                }, 800);
            };

            //PAGINANDO
            $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarCalendarios(); };
            $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarCalendarios(); } };

            //INICIAR
            Util.inicializar();
            Util.mudarImagemToolbar('calendarios/assets/images/calendarios.jpg');
            
        } else { Util.semPermissao(); }
    }]);
})();
(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    var instituicoes = angular.module('instituicoes',['ngMaterial', 'util', 'erudioConfig', 'instituicaoDirectives']);
    instituicoes.controller('InstituicaoController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, $timeout){
        /*
         * @attr permissao
         * @attrType boolean
         * @attrDescription Flag para verificar permissão do módulo.
         * @attrExample 
         */
        $scope.permissao = Util.verificaPermissao('INSTITUICOES');
        if ($scope.permissao) {
            Util.comPermissao();
            //SETA O TITULO
            Util.setTitulo('Instituições');
            //ATRIBUTOS EXTRAS
            $scope.instituicao = null;
            $scope.subheaders =[{label: 'Nome da Instituição'}];
            $scope.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
            $scope.link = ErudioConfig.dominio + '/#!/instituicoes/';
            $scope.fab = {tooltip: 'Adicionar Instituição', icone: 'add', href: $scope.link+'novo'};
            $scope.pagina = 0;
            $scope.busca = '';
            /*
            * @attr lista
            * @attrType string
            * @attrDescription URL do template de lista.
            * @attrExample 
            */
            $scope.lista = Util.getTemplateLista();
            /*
            * @attr listaEspecifica
            * @attrType string
            * @attrDescription URL da página de lista.
            * @attrExample 
            */
            $scope.listaEspecifica = Util.getTemplateListaEspecifica('instituicoes');
            /*
            * @attr buscaSimples
            * @attrType string
            * @attrDescription URL do template de busca.
            * @attrExample 
            */
            $scope.buscaSimples = Util.getTemplateBuscaSimples();
            /*
            * @attr escrita
            * @attrType boolean
            * @attrDescription Flag para verificar escrita no módulo.
            * @attrExample 
            */
            $scope.escrita = Util.verificaEscrita('INSTITUICOES');
            /*
            * @method validarEscrita
            * @methodReturn boolean
            * @methodParams opcao|string
            * @methodDescription Valida permissão de escrita para opções da lista.
            */
            $scope.validarEscrita = function (opcao) { return Util.validarEscrita(opcao, $scope.opcoes, $scope.escrita); };
            /*
            * @method buscarInstituicoes
            * @methodReturn void
            * @methodDescription Busca instituições cadastradas.
            */
            $scope.buscarInstituicoes = function () {
                var promise = Util.buscar('instituicoes',{page: $scope.pagina});
                promise.then(function(response){ $scope.objetos = response.data; });
            };
            $scope.buscarInstituicoes();
            /*
            * @method executarOpcao
            * @methodReturn void
            * @methodParams event|object,opcao|string,objeto|object
            * @methodDescription Execute uma das opções da lista.
            */
            $scope.executarOpcao = function (event, opcao, objeto) { $scope.instituicao = objeto; $scope.modalExclusão(event); };
            /*
            * @method modalExclusão
            * @methodReturn void
            * @methodParams event|object
            * @methodDescription Abre o modal de exclusão.
            */
            $scope.modalExclusão = function (event) {
                var confirm = Util.modalExclusao(event, 'Remover Instituição', 'Deseja remover esta instituição?', 'remover', $mdDialog);
                $mdDialog.show(confirm).then(function() { 
                    var remocao = Util.remover($scope.instituicao, 'Instituicão', 'f'); remocao.then(function(){ $scope.buscarInstituicoes(); });
                }, function() {});
            };
            /*
            * @method verificaBusca
            * @methodReturn void
            * @methodParams query|string
            * @methodDescription Verifica o campo de busca, se está vazio ou não.
            */
            $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarInstituicoes(); } else { $scope.executarBusca(query); } };
            /*
            * @method executarBusca
            * @methodReturn void
            * @methodParams query|string
            * @methodDescription Busca insituição de acordo com o nome.
            */
            $scope.executarBusca = function (query) {
                $timeout.cancel($scope.delayBusca);
                $scope.delayBusca = $timeout(function(){
                    if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                    if (tamanho > 3) {
                        var res = Util.buscar('instituicoes',{'nome':query});
                        res.then(function(response){ $scope.objetos = response.data; });
                    }
                }, 800);
            };
            /*
            * @method paginaProxima
            * @methodReturn void
            * @methodDescription Avança a página na paginação.
            */
            $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarInstituicoes(); };
            /*
            * @method paginaAnterior
            * @methodReturn void
            * @methodDescription Retrocede a página na paginação.
            */
            $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarInstituicoes(); } };
            //INICIANDO
            Util.inicializar();
            Util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
        } else { Util.semPermissao(); }
    }]);
})();
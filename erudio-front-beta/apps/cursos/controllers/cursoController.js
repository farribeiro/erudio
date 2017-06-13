(function (){
    var cursos = angular.module('cursos',['ngMaterial', 'util', 'erudioConfig','shared']);
    cursos.controller('CursoController',['$scope', 'Util', '$mdDialog', 'ErudioConfig', 'Shared', '$timeout', function($scope, Util, $mdDialog, ErudioConfig, Shared, $timeout){
        
        //SETA O TITULO
        Util.setTitulo('Cursos');
        
        //CURSO EM USO
        $scope.curso = null;
        
        //SETA COLUNAS DA LISTA
        $scope.subheaders =[{label: 'Nome do Curso'}];
        
        //OPCOES
        $scope.opcoes = [{tooltip: 'Etapas', icone: 'chrome_reader_mode', opcao: 'etapas'},{tooltip: 'Remover', icone: 'delete', opcao: 'remover'}];
        
        //OPCOES DO BOTAO FAB
        $scope.link = ErudioConfig.dominio + '/#!/cursos/';
        $scope.fab = {tooltip: 'Adicionar Curso', icone: 'add', href: $scope.link+'novo'};
        
        //PAGINA DA LISTA
        $scope.pagina = 0;
        
        //BUSCANDO CURSOS
        $scope.buscarCursos = function () {
            var promise = Util.buscar('cursos',{page: $scope.pagina});
            promise.then(function(response){ $scope.objetos = response.data; });
        };
        $scope.buscarCursos();
        
        //BUSCANDO TEMPLATE DA LISTA GERAL E ESPECIFICA
        $scope.lista = Util.getTemplateLista();
        $scope.listaEspecifica = Util.getTemplateListaEspecifica('cursos');
        
        //BUSCANDO TEMPLATE DA BUSCA SIMPLES
        $scope.buscaSimples = Util.getTemplateBuscaSimples();
        
        //EXECUTANDO OPCOES
        $scope.executarOpcao = function (event, opcao, objeto) {
            $scope.curso = objeto;
            switch (opcao.opcao) {
                case 'remover': $scope.modalExclusão(event); break;
                case 'etapas': $scope.verEtapas(); break;
                default: return false; break;
            }
        };
        
        //ABRINDO MODAL DE EXCLUSAO
        $scope.modalExclusão = function (event) {
            var confirm = Util.modalExclusao(event, 'Remover Curso', 'Deseja remover este Curso?', 'remover', $mdDialog);
            $mdDialog.show(confirm).then(function() { 
                var remocao = Util.remover($scope.curso, 'Curso', 'm'); remocao.then(function(){ $scope.buscarCursos(); });
            }, function() {});
        };
        
        //ESCUTANDO VARIAVEL DE BUSCA
        $scope.busca = '';
        $scope.verificaBusca = function (query) { if(Util.isVazio(query)){ $scope.buscarCursos(); } else { $scope.executarBusca(query); } };
        
        //BUSCA COM PARAMETRO
        $scope.executarBusca = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (Util.isVazio(query)) { query = ''; } var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Util.buscar('cursos',{'nome':query});
                    res.then(function(response){ $scope.objetos = response.data; });
                }
            }, 800);
        };
        
        //VAI PARA O MODULO DE ETAPAS DO CURSO
        $scope.verEtapas = function () {
            Shared.setCursoEtapa($scope.curso.id);
            Util.redirect(ErudioConfig.dominio + '/#!/etapas');
        };
        
        //PAGINANDO
        $scope.paginaProxima = function (){ $scope.pagina++; $scope.buscarCursos(); };
        $scope.paginaAnterior = function (){ if ($scope.pagina > 0) { $scope.pagina = $scope.pagina - 1; $scope.buscarCursos(); } };
        
        //INICIAR
        Util.inicializar();
        Util.mudarImagemToolbar('cursos/assets/images/cursos.jpg');
    }]);
})();
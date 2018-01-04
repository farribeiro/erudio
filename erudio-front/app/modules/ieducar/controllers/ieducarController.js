/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

(function () {
    var ieducarModule = angular.module('ieducarModule', ['servidorModule', 'ieducarDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    ieducarModule.controller('IeducarController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('RELATORIOS'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('ieducar'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "iEducar"; $scope.progresso = false; $scope.cortina = false;  $scope.unidades = []; $scope.unidade = {id:null};
        $scope.alunos = []; $scope.alunoNome = ''; $scope.dataNascAluno = ''; $scope.nomeUnidade = '';
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
        //REINICIA A BUSCA
        $scope.reiniciarBusca = function () {
            $scope.unidades = []; $scope.unidade = {id:null}; $scope.nomeUnidade = '';
            $scope.alunos = []; $scope.alunoNome = ''; $scope.dataNascAluno = '';
            $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 100);
        };
        
        $scope.buscarUnidades = function (unidade) {
            $('.data').mask('00/00/0000');
            $scope.mostraProgresso(); $scope.unidades = [];
            var endereco = ErudioConfig.urlServidor.replace('api','integracoes/ieducar');
            //if (!$scope.isAdmin) { unidade = JSON.parse(sessionStorage.getItem('atribuicao-ativa')).instituicao.nome; }
            var promise = Servidor.buscarIeducar(endereco+'/unidades-ensino?nome='+unidade);
            promise.success(function(response) {
                $scope.unidades = response;
                $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
            });
        };
        
        $scope.selecionaUnidade = function (unidade){
            $scope.unidade.id = unidade.cod_escola;
            $scope.nomeUnidade = unidade.esco_nome;
        };
        
        $scope.buscarAlunos = function(){ console.log($scope.unidade);
            if ($scope.alunoNome !== undefined && $scope.alunoNome.length > 0 && $scope.dataNascAluno !== undefined && $scope.dataNascAluno.length > 0) {
                $scope.mostraProgresso(); $scope.alunos = [];
                var endereco = ErudioConfig.urlServidor.replace('api','integracoes/ieducar');
                var promise = Servidor.buscarIeducar(endereco+'/alunos?nome='+$scope.alunoNome+'&dataNascimento='+$scope.dataNascAluno);
                promise.success(function(response) { 
                    if (response.length > 0) {
                        $scope.alunos = response; $scope.fechaProgresso(); 
                    } else { Servidor.customToast("Nenhum resultado encontrado."); $scope.fechaProgresso(); }
                }).error(function(){ $scope.fechaProgresso(); });
            } else { Servidor.customToast("Todos os campos são obrigatórios."); }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function () {
                $('.tooltipped').tooltip({delay: 50}); $('ul.tabs').tabs(); $('#modal-ajuda-turma').leanModal(); 
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('select').material_select('destroy'); $('select').material_select();
            }, 1000);
        };
        
        //GERAR BOLETIM
        $scope.gerarHistorico = function (aluno) {
            var endereco = ErudioConfig.urlServidor.replace('api','integracoes/ieducar');
            //var url = endereco+'/historicos-impressos?aluno='+aluno.cod_aluno+"&unidadeEnsino="+$scope.unidade.id;
            var url = endereco+'/historicos-impressos?aluno='+aluno.cod_aluno;
            $scope.getHistorico(url);
        };
        
        $scope.setNumero = function(numero){ $scope.mediaNumero = numero; };
        
        $scope.getHistorico = function (url) {
            $scope.mostraProgresso();
            if (url !== undefined){ var promise = Servidor.getPDF(url,'_blank'); }
            promise.then(function(){
                $scope.fechaProgresso();
            },function(){ $scope.fechaProgresso(); });
        };

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();

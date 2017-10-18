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
    var relatorioModule = angular.module('relatorioModule', ['servidorModule', 'relatorioDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    relatorioModule.controller('RelatoriosController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('RELATORIOS'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('relatorios'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Alunos Enturmados"; $scope.progresso = false; $scope.cortina = false; $scope.ativo = 'nominal'; $scope.nomeUnidade = null; $scope.turmas = []; $scope.etapas = []; $scope.unidades = [];
        $scope.unidade = {id:null}; $scope.etapa = {id:null}; $scope.unidade2 = {id:null}; $scope.turma = {id:null}; $scope.instituicao = {id:null}; $scope.instituicoes =[]; $scope.nomeUnidadeRelatorioUnidade = null;
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-relatorio').openModal(); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //RESET DROPDOWN
        $scope.resetDropdown = function (){ $timeout(function(){ $('select').material_select('destroy'); $('select').material_select(); $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); }, 800); };
        //TROCA DO SELECT ETAPA
        $scope.$watch("etapa.id", function(query){ $scope.selecionaEtapa(query); });
        //TROCA DO SELECT INSTITUICAO
        $scope.$watch("instituicao.id", function(query){ $scope.selecionaInstituicao(query); });
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function (unidade) {
            $scope.mostraProgresso();
            if ($scope.ativo === 'unidade') { 
                $scope.nomeUnidadeRelatorioUnidade = unidade;
                if ($scope.nomeUnidadeRelatorioUnidade !== undefined && $scope.nomeUnidadeRelatorioUnidade !== null) {
                    if ($scope.nomeUnidadeRelatorioUnidade.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidadeRelatorioUnidade); } else { $scope.unidades = []; $scope.fechaProgresso(); }
                } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
            } else { 
                $scope.nomeUnidade = unidade; 
                if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                    if ($scope.nomeUnidade.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidade); } else { $scope.unidades = []; $scope.fechaProgresso(); }
                } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
            } 
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            $scope.mostraProgresso(); if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; } 
            if ($scope.ativo === 'unidade') { $scope.unidade2 = unidade; $scope.nomeUnidadeRelatorioUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $('#unidadeTurmaAutoComplete').val($scope.nomeUnidadeRelatorioUnidade); } else { $scope.unidade = unidade; $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $('#unidadeTurmaAutoComplete').val($scope.nomeUnidade); } 
            $timeout(function(){ $scope.buscarEtapas(unidade); Servidor.verificaLabels(); $scope.fechaProgresso(); },100);
        };
        
        //BUSCA DISCIPLINAS OFERTADAS
        $scope.buscarEtapas = function (unidade){
            $scope.mostraProgresso();
            var promise = Servidor.buscar('etapas-ofertadas',{'unidadeEnsino': unidade.id});
            promise.then(function(response){ $scope.etapas = response.data; $timeout(function() { $('select').material_select(); }, 500); $scope.fechaProgresso(); });
        };
        
        //SELECIONA ETAPA
        $scope.selecionaEtapa = function (id) {
            if (id !== null && id !== undefined) {
                $scope.etapa.id = id; var promise = Servidor.buscar('turmas',{unidadeEnsino:$scope.unidade.id, etapa: id});
                promise.then(function(response){ $scope.turmas = response.data; $timeout(function(){ $('select').material_select('destroy'); $('select').material_select(); },500); });
            }
        };
        
        //VERIFICA SE HÁ ALOCAÇÃO SELECIONADA
        $scope.verificaAlocacao = function (nomeUnidade) {
            //var alocacao = sessionStorage.getItem('alocacao');
            if ($scope.isAdmin) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                promise.then(function (response) {
                    $scope.unidades = response.data; $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                });
            } else {
                if (Servidor.verificarPermissoes('RELATORIOS')) {
                    //var promise = Servidor.buscar('users',{username:sessionStorage.getItem('username')});
                    var promise = Servidor.buscarUm('users',sessionStorage.getItem('pessoaId'));
                    promise.then(promise.then(function(response){
                        var user = response.data[0];
                        for (var i=0; i<user.atribuicoes.length; i++) {
                            if (user.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push(user.atribuicoes[i].instituicao); }
                            if (i === user.atribuicoes.length-1) {                                
                                if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.selecionaUnidade($scope.unidade); }
                                $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                            }
                        }
                    }));
                    /*var promise = Servidor.buscarUm('alocacoes', alocacao);
                    promise.then(function (response) {
                        $scope.alocacao = response.data; $scope.unidades = [$scope.alocacao.instituicao];
                        if ($scope.unidades.length === 1) { $scope.unidade = $scope.alocacao.instituicao; $scope.selecionaUnidade($scope.unidade); }
                        $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                    });*/
                } 
                $scope.fechaProgresso();
            }
        };
        
        //BUSCANDO INSTITUICOES
        $scope.buscarInstituicoes = function() {
            var promise = Servidor.buscar('instituicoes');
            promise.then(function (response){
                if (response.data.length > 0) {
                    $scope.instituicoes = response.data;
                    $timeout(function (){ 
                        $('select').material_select('destroy'); $('select').material_select();
                        if (response.data.length === 1) { $scope.selecionaInstituicao(response.data[0].id); } 
                    },500);
                }
            });
        };
        
        //SELECIONA INSTITUICAO
        $scope.selecionaInstituicao = function(instituicao) {
            $scope.mostraProgresso(); $scope.instituicao.id = instituicao;
            $timeout(function(){ $('select').material_select('destroy'); $('select').material_select(); $scope.fechaProgresso(); Servidor.verificaLabels(); },500);
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function(){
                $('#modal-ajuda-relatorio').leanModal(); $('select').material_select('destroy'); $('select').material_select(); Servidor.verificaLabels();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            },500);
        };

        //INICIALIZANDO
        if (!$scope.isAdmin) { $scope.buscarUnidades(); } $scope.inicializar();
    }]);
})();

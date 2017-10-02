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
    var relatoriosDefasadosModule = angular.module('relatoriosDefasadosModule', ['servidorModule', 'relatoriosDefasasdosDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    relatoriosDefasadosModule.controller('RelatorioDefasadosController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('RELATORIOS'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('relatorios'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Alunos Defasados"; $scope.progresso = false; $scope.cortina = false; $scope.nomeUnidade = null; $scope.cursosOfertados = []; $scope.cursoOfertado = {id:null}; $scope.unidade = {id:null};  $scope.height = $(window).height()-150;
        $scope.unidades = [];
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-relatorio-defasado').openModal(); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //RESET DROPDOWN
        $scope.resetDropdown = function (){ $timeout(function(){ $('select').material_select('destroy'); $('select').material_select(); $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); }, 800); };
        //TROCA DO SELECT UNIDADE
        $scope.$watch("unidade.id", function(query){ $scope.selecionaUnidade(query); });
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function (unidade) {
            $scope.mostraProgresso();
            $scope.nomeUnidade = unidade;
            if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                if ($scope.nomeUnidade.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidade); } else { $scope.unidades = []; $scope.fechaProgresso(); }
            } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
        };
        
        //getpdf
        $scope.getPDF = function (url){
            $scope.mostraProgresso();
            var promise = Servidor.getPDF(url);
            promise.then(function(){ $scope.fechaProgresso(); });
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            if (unidade !== null && unidade !== undefined) {
                var promise = Servidor.buscarUm('unidades-ensino',unidade);
                promise.then(function(response){
                    unidade = response.data; $scope.mostraProgresso(); if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; } 
                    $scope.unidade = unidade; $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $('#unidadeTurmaAutoComplete').val($scope.nomeUnidade);
                    $timeout(function(){ 
                        var promise = Servidor.buscar('cursos-ofertados',{'unidadeEnsino':$scope.unidade.id});
                        promise.then(function(response){ 
                            $scope.cursosOfertados = response.data; $timeout(function () { $('#curso').material_select('destroy'); $('#curso').material_select(); $scope.fechaProgresso(); }, 500);
                            if (response.data.length === 1) { $scope.cursoOfertado.id = response.data[0].id; }
                        });
                        Servidor.verificaLabels(); $scope.fechaProgresso();
                    },100);
                });
            }
        };
        
        //VERIFICA SE HÁ ALOCAÇÃO SELECIONADA
        $scope.verificaAlocacao = function (nomeUnidade) {
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
                        var user = response.data;
                        for (var i=0; i<user.atribuicoes.length; i++) {
                            if (user.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push(user.atribuicoes[i].instituicao); } else { $scope.isAdmin = true; }
                            if (i === user.atribuicoes.length-1) {
                                if (i === user.atribuicoes.length-1) {
                                    if ($scope.isAdmin) {
                                        $scope.verificaAlocacao();
                                    } else {
                                        if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.selecionaUnidade($scope.unidade); }
                                        $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                                    }
                                }
                            }
                        }
                    }));
                } 
                $scope.fechaProgresso();
            }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function(){
                $('#modal-ajuda-relatorio-defasado').leanModal(); 
                //$('#modal-ajuda-relatorio').modal(); 
                $('select').material_select('destroy'); $('select').material_select();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            },500);
        };

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();

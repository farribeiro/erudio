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
    var espelhoModule = angular.module('espelhoModule', ['servidorModule', 'espelhoDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    espelhoModule.controller('EspelhoController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('RELATORIOS'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('relatorios'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Espelho de Notas"; $scope.progresso = false; $scope.cortina = false; $scope.ativo = 'nominal'; $scope.nomeUnidade = null; $scope.turmas = []; $scope.etapas = []; $scope.unidades = []; $scope.cursos = []; $scope.height = $(window).height()-205;
        $scope.unidade = {id:null}; $scope.etapa = {id:null}; $scope.unidade2 = {id:null}; $scope.turma = {id:null}; $scope.instituicao = {id:null}; $scope.instituicoes =[]; $scope.nomeUnidadeRelatorioUnidade = null; $scope.curso = {id:null};
        $scope.unidadesNota = []; $scope.nomeUnidadeN = null;
        $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}};
        $scope.notasBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.unidadeN = {id:null}; $scope.tipoAvaliacao = null;
        $scope.avaliacaoQualitativa = {nome: null, media: null, disciplina: {id: null}, tipo: 'FINAL'}; $scope.notaQualitativa = {avaliacao: null, media: null, habilidadesAvaliadas: null, fechamentoMedia: 1};
        $scope.notaHabilidade = { habilidade: null, conceito: null, avaliacao: null }; $scope.habilidades = []; $scope.conceitos = []; $scope.enturmacao = null; $scope.qualitativas = [];
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-relatorio').openModal(); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //RESET DROPDOWN
        $scope.resetDropdown = function (){ $timeout(function(){ 
            $scope.unidades = []; $scope.cursos = []; $scope.etapas = []; $scope.turmas = []; $scope.medias = [];
            $timeout(function(){
                $('select').material_select('destroy'); $('select').material_select(); 
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' }); }, 800); 
            },500);
        };
        
        //getpdf
        $scope.getPDF = function (url){
            $scope.mostraProgresso();
            var promise = Servidor.getPDF(url);
            promise.then(function(){ $scope.fechaProgresso(); });
        };
        
        //CARREGA SELECT CURSOS
        $scope.buscarCursos = function () {
            $scope.mostraProgresso(); var promise = null; $scope.turmaBusca.curso.id = null;
            promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.unidade.id});
            promise.then(function(response) {
                $scope.cursos = response.data;
                if(response.data.length) {
                    if($scope.cursos.length === 1) { $scope.turmaBusca.curso.id = $scope.cursos[0].curso.id; $scope.buscarEtapas($scope.cursos[0].curso.id); }
                    $timeout(function () { $('#curso').material_select('destroy'); $('#curso').material_select(); $('#cursoNota').material_select('destroy'); $('#cursoNota').material_select(); $scope.fechaProgresso(); }, 500);
                } else { $scope.fechaProgresso(); Servidor.customToast('Não há cursos nesta unidade.'); }
            });
        };
        
        //REINICIA A BUSCA
        $scope.reiniciarBusca = function () {
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; if ($scope.isAdmin) { $scope.cursos = []; }
            $scope.nomeUnidade = null; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.etapas = []; $scope.disciplinasTurma = [];
            $scope.notasBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.turmas = []; $scope.medias = [];
            $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 100);
        };
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function (nomeUnidade) {
            $scope.nomeUnidade = nomeUnidade;
            if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null && $scope.nomeUnidade !== "") {
                if ($scope.nomeUnidade.length > 4) { $scope.mostraProgresso(); $scope.verificaAlocacao($scope.nomeUnidade); } else { $scope.unidades = []; }
            } else { $scope.mostraProgresso(); $scope.verificaAlocacao(null); }
        };
        
        //VERIFICA SE HÁ ALOCAÇÃO SELECIONADA
        $scope.verificaAlocacao = function (nomeUnidade) {
            if ($scope.isAdmin) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                promise.then(function (response) {
                    $scope.unidades = response.data; $scope.unidadesNota = response.data; $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                });
            } else {
                if (Servidor.verificarPermissoes('TURMA')) {
                    //var promise = Servidor.buscar('users',{username:sessionStorage.getItem('username')});
                    var promise = Servidor.buscarUm('users',sessionStorage.getItem('pessoaId'));
                    promise.then(function(response) {
                        var user = response.data; $scope.atribuicoes = user.atribuicoes;
                        $timeout(function () {
                            for (var i=0; $scope.atribuicoes.length; i++) {
                                if ($scope.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push($scope.atribuicoes[i].instituicao); } else { $scope.isAdmin = true; }
                                if (i === $scope.atribuicoes.length-1) {
                                    if ($scope.isAdmin) {
                                        $scope.verificaAlocacao();
                                    } else {
                                        if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.buscarCursos(); }
                                        $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                                    }
                                }
                            }
                        },500);
                    });
                }
            }
        };
        
        //CARREGA MEDIAS
        $scope.medias = [];
        $scope.buscarMedias = function (id) {
            var promise = Servidor.buscarUm('etapas',id);
            promise.then(function(response){
                $('.save').show(); $scope.tipoAvaliacao = response.data.sistemaAvaliacao.tipo; $scope.turmaBusca.media.id = null;
                //if ($scope.tipoAvaliacao === "QUANTITATIVO") {
                    if (response.data.frequenciaUnificada) { $scope.frequenciaUnificada = true; } else { $scope.frequenciaUnificada = false; }
                    var unidade = response.data.sistemaAvaliacao.regime.unidade; var medias = [];
                    var qtdeMedias = response.data.sistemaAvaliacao.quantidadeMedias;
                    for (var i=0; i<qtdeMedias; i++) { 
                        var label = (i+1)+"º "+unidade; medias.push({id:i+1, nome: label}); 
                        if (i === qtdeMedias-1) { 
                            $timeout(function (){ $scope.medias = medias; },100); $timeout(function () { $('#media').material_select('destroy'); $('#media').material_select(); $('#mediaNota').material_select('destroy'); $('#mediaNota').material_select();  }, 500);
                        }
                    }
                /*} else {
                    Servidor.customToast("Os espelhos de turmas com sistema qualitativo ainda não foram implementados."); $('.save').hide();
                }*/
            });
        };
        
        //VERIFICA TIPO DE AVALIACAO
        $scope.verificaTipoAvaliacao = function (){ var retorno = false; if ($scope.tipoAvaliacao === "QUANTITATIVO") { retorno = true; } else { retorno = false; } return retorno; };
        
        //CARREGA O SELECT DE ETAPAS
        $scope.buscarEtapas = function (id) {
            if (id) {
                $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.disciplinasTurma = []; $scope.turmaBusca.disciplina.id = null; var promise = Servidor.buscar('etapas', {'curso': id});
                promise.then(function (response) {
                    $scope.turmaBusca.etapa.id = null;
                    $timeout(function () { $scope.etapas = response.data; if ($scope.etapas.length === 0) { Materialize.toast('Nenhuma etapa cadastrada', 1500); } },100);
                    $timeout(function () { $('#etapa').material_select('destroy'); $('#etapa').material_select(); $('#etapaNota').material_select('destroy'); $('#etapaNota').material_select(); }, 500);
                    $timeout(function () { $('#disciplinaNota').material_select('destroy'); $('#disciplinaNota').material_select(); $('#disciplina').material_select('destroy'); $('#disciplina').material_select(); }, 500);
                });
            }
        };
        
        //BUSCA DE TURMAS
        $scope.buscarTurmas = function (origem) {
            $scope.mostraProgresso(); $scope.turmaBusca.turma.id = null;
                if (!$scope.unidade.id && origem === 'formBusca') {
                    Servidor.customToast('Selecione uma unidade para realizar a busca'); $scope.fechaProgresso(); $scope.turmas = [];
                } else if ($scope.unidade.id) {
                    var promise = Servidor.buscar('turmas', {'unidadeEnsino': $scope.unidade.id, 'etapa': $scope.turmaBusca.etapa.id, 'curso': $scope.turmaBusca.curso.id});
                    promise.then(function (response) {
                        $scope.turmas = response.data;
                        if ($scope.turmas.length > 0) {
                            if ($scope.turmas.length === 1) { $scope.turmaBusca.turma.id = response.data[0].id; $scope.selecionarTurma(response.data[0].id); }
                            $timeout(function () { $('.modal-trigger').leanModal(); $('.tooltipped').tooltip({delay: 50}); }, 500);
                            $timeout(function () { $('#turma').material_select('destroy'); $('#turma').material_select(); $('#turmaNota').material_select('destroy'); $('#turmaNota').material_select(); }, 100);
                        } else { Servidor.customToast('Nenhuma turma encontrada. Verifique os parâmetros de busca e tente novamente.'); $scope.turmas = []; $timeout(function () { $('#turma').material_select('destroy'); $('#turma').material_select(); $('#turmaNota').material_select('destroy'); $('#turmaNota').material_select(); }, 100); } $scope.fechaProgresso();
                    });
                } else { $scope.reiniciarBusca(); }
        };
        
        //SELECAO DE ETAPA
        $scope.frequenciaUnificada = true;
        $scope.selecionarEtapa = function(etapaId) { $scope.buscarMedias(etapaId); $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.buscarTurmas('formBusca'); };
        
        //SELECIONA TURMA
        $scope.selecionarTurma = function (turmaId) {
            $scope.turmaBusca.disciplina.id = null;
            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: turmaId});
            promise.then(function(response) {
                $scope.disciplinasTurma = response.data;
                $timeout(function () { $('#disciplinaNota').material_select('destroy'); $('#disciplinaNota').material_select(); $('#disciplina').material_select('destroy'); $('#disciplina').material_select(); }, 100);
            });
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            if (unidade !== null && unidade !== undefined && unidade !== ""){
                $('#dropUnidadesTurmaBusca').hide(); $scope.enturmacoes = []; $scope.enturmacoesNotas = [];
                if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; }
                $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $scope.unidade = unidade;
                $('#unidadeTurmaAutoComplete').val($scope.nomeUnidade); $('#unidadeTurmaAutoComplete').val($scope.nomeUnidade);
                $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); },100);
            }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.title-module').html($scope.titulo); $('.material-tooltip').remove();
            $timeout(function(){
               $('#modal-ajuda-relatorio').leanModal(); 
                //$('#modal-ajuda-relatorio').modal(); 
                $('select').material_select('destroy'); $('select').material_select();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            },500);
        };

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();

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
    var ataModule = angular.module('ataModule', ['servidorModule', 'ataDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    ataModule.controller('AtaController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('BOLETIM_ESCOLAR'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('ataFinal'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Ata Final"; $scope.progresso = false; $scope.cortina = false; $scope.unidades = []; $scope.unidadesNota = []; $scope.cursos = []; $scope.etapas = []; $scope.nomeUnidadeN = null;
        $scope.turmas = []; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}, aluno:{id:null}}; $scope.nomeUnidade = null; $scope.unidade = {id:null};
        $scope.notasBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.ativo = "faltas"; $scope.unidadeN = {id:null}; $scope.tipoAvaliacao = null;
        $scope.mostraI = false;
        //ABRE AJUDA
        //$scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
        //MOSTRA VALOR CHECK
        $scope.mostraIndividual = function () { if ($("#individual").is(":checked")) { $scope.mostraI = true; $timeout(function(){ $("#alunos").material_select('destroy'); $("#alunos").material_select(); },500); } else { $scope.mostraI = false; } };
        
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
        
        //PREPARAÇÃO PARA REMOÇÃO DE TURMA
        $scope.prepararRemover = function (turma) {
            if(turma.quantidadeAlunos > 0) { Servidor.customToast('Esta turma possui enturmações, portanto não é possível removê-la.');
            } else { $scope.turmaRemover = turma; $('#remove-modal-turma').openModal(); }                
        };

        //REMOÇÃO DE TURMA
        $scope.remover = function () {
            $scope.mostraProgresso(); Servidor.remover($scope.turmaRemover, 'Turma');
            $timeout(function () { $scope.buscarTurmas(); $scope.fechaProgresso(); }, 500);
        };
        
        //REINICIA A BUSCA
        $scope.reiniciarBusca = function () {
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.alunos = []; $scope.totalEnturmacoes = []; if ($scope.isAdmin) { $scope.cursos = []; }
            $scope.nomeUnidade = null; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}, aluno:{id:null}}; $scope.etapas = []; $scope.disciplinasTurma = [];
            $scope.notasBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.turmas = []; $scope.medias = [];
            $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 100);
        };
        
        //CARREGA O SELECT DE UNIDADES
        $scope.buscarUnidades = function () {
            if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
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
                            for (var i=0; i<$scope.atribuicoes.length; i++) {
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
                    var promise = Servidor.buscar('turmas', {'unidadeEnsino': $scope.unidade.id, 'etapa': $scope.turmaBusca.etapa.id, 'curso': $scope.turmaBusca.curso.id, 'encerrado': 1});
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
        $scope.mostraMedia = false;
        $scope.selecionarEtapa = function(etapaId) { 
            var promise = Servidor.buscarUm('etapas',etapaId);
            promise.then(function(response){
                if (response.data.sistemaAvaliacao.tipo === "QUALITATIVO") {
                    $scope.mostraMedia = true;
                } else { $scope.mostraMedia = false; }
            });
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.buscarTurmas('formBusca');
        };
        
        //SELECIONA TURMA
        $scope.alunos = [];
        $scope.selecionarTurma = function (turmaId) { 
            $scope.turmaBusca.disciplina.id = null;
            /*var promise = Servidor.buscar("enturmacoes",{turma: turmaId});
            promise.then(function(response){
                $scope.alunos = response.data;
                $timeout(function(){ $("#alunos").material_select('destroy'); $("#alunos").material_select(); },500);
            });*/
        };
        
        //BUSCA ALUNOS
        $scope.enturmacoes = []; $scope.enturmacoesNotas = [];
        $scope.buscarBoletins = function (tipo) {
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.mostraProgresso();
            if ($scope.unidade !== null && $scope.turmaBusca.curso.id !== null && $scope.turmaBusca.etapa.id !== null && $scope.turmaBusca.turma.id !== null && $scope.turmaBusca.media.id !== null) {
                var promise = Servidor.buscar('medias/faltas', {'turma': $scope.turmaBusca.turma.id, 'numero': $scope.turmaBusca.media.id});
                promise.then(function (response) {
                    for (var i=0; i<response.data.length; i++) { $scope.enturmacoes.push(response.data[i]); }
                    $('label').click(function(){ var id = $(this).attr('for'); $('#'+id).trigger('click'); }); 
                    $timeout(function(){Servidor.verificaLabels(); $scope.fechaProgresso();},500);
                    if ($scope.enturmacoes.length === 0) { Servidor.customToast("Não há alunos matriculados nesta turma."); }
                });
            } else { Servidor.customToast('Preencha todos os parâmetros de busca para encontrar os alunos.'); $scope.fechaProgresso(); }
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            $('#dropUnidadesTurmaBusca').hide(); $scope.enturmacoes = []; $scope.enturmacoesNotas = [];
            if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; } $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $scope.unidade = unidade;
            $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); },100);
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
        $scope.mediaNumero = null;
        $scope.gerarAta = function (url,tipo) {
            if (url === undefined) {
                url = ErudioConfig.urlRelatorios+'/ata-resultado-final?turma='+$scope.turmaBusca.turma.id;
                $scope.getAta(url);
            } else {
                Servidor.customToast('Selecione uma média antes de gerar o boletim.');
            }
                
            
            //window.open(ErudioConfig.urlServidor+'/report/boletins?turma='+$scope.turmaBusca.turma.id,'_blank');
            
            //if ($("#individual").is(":checked") && $scope.turmaBusca.aluno.id !== null) { window.open(ErudioConfig.urlServidor+'/report/boletim?enturmacao='+$scope.turmaBusca.aluno.id,'_blank');
            //} else { window.open(ErudioConfig.urlServidor+'/report/boletins?turma='+$scope.turmaBusca.turma.id,'_blank'); }
        };
        
        $scope.setNumero = function(numero){ $scope.mediaNumero = numero; };
        
        $scope.getAta = function (url) {
            $scope.mostraProgresso();
            if (url !== undefined){
                var promise = Servidor.getPDF(url,'_blank');
            }
            promise.then(function(){
                $scope.fechaProgresso();
            });
        };
        
        $scope.nomeAluno = null;
        $scope.buscarAlunos = function (nome) {
            $timeout.cancel($scope.timeBuscaAlunos); $scope.alunos = []; $timeout(function(){ $("#alunos").material_select('destroy'); $("#alunos").material_select(); },100);
            $scope.timeBuscaAlunos = $timeout(function(){
                if (nome !== null && nome !== undefined && nome !== "" && nome.length > 2) {
                    var promise = Servidor.buscar("matriculas",{aluno_nome: nome});
                    promise.then(function(response){
                        for (var i=0; i<response.data.length; i++) { 
                            $scope.alunos.push(response.data[i]);
                            if (i === response.data.length-1) { $timeout(function(){ $("#alunos").material_select('destroy'); $("#alunos").material_select(); },500); }
                        }
                        /*var matriculas = response.data;
                        matriculas.forEach(function(m){                        
                            
                        });*/
                    });
                }
            },300);
        };
        
        $scope.totalEnturmacoes = [];
        $scope.selecionaAluno = function (aluno) {
            $scope.nomeAluno = aluno.nomeAluno;
            var promise = Servidor.buscar("enturmacoes",{matricula: aluno.id, encerrado: ''});
            promise.then(function(response){
                $scope.totalEnturmacoes = response.data;
                $scope.totalEnturmacoes.forEach(function(enturmacao){
                    var promiseE = Servidor.buscarUm('etapas',enturmacao.matricula.etapaAtual.id);
                    promiseE.then(function(responseE){
                        enturmacao.matricula.etapaAtual = responseE.data;
                    });
                });
            });
        };

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();

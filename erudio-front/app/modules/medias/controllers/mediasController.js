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
    var mediaModule = angular.module('mediaModule', ['servidorModule', 'mediaDirectives','erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    mediaModule.controller('MediasController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('MEDIAS'); $scope.isAdmin = Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('medias'); $scope.lista = true;
        $scope.telaNotas = ErudioConfig.getTemplateCustom('medias','notas');
        //ATRIBUTOS
        $scope.titulo = "Notas e Faltas"; $scope.progresso = false; $scope.cortina = false; $scope.unidades = []; $scope.unidadesNota = []; $scope.cursos = []; $scope.etapas = []; $scope.nomeUnidadeN = null;
        $scope.turmas = []; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.nomeUnidade = null; $scope.unidade = {id:null};
        $scope.notasBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.ativo = "faltas"; $scope.unidadeN = {id:null}; $scope.tipoAvaliacao = null;
        $scope.avaliacaoQualitativa = {nome: null, media: null, disciplina: {id: null}, tipo: 'FINAL'}; $scope.notaQualitativa = {avaliacao: null, media: null, habilidadesAvaliadas: null, fechamentoMedia: 1};
        $scope.notaHabilidade = { habilidade: null, conceito: null, avaliacao: null }; $scope.habilidades = []; $scope.conceitos = []; $scope.enturmacao = null; $scope.qualitativas = [];
        //ABRE AJUDA
        //$scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
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
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; if ($scope.isAdmin) { $scope.cursos = []; } $scope.umaMediaEtapa = true;
            $scope.nomeUnidade = null; $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}, turma:{id:null}, media:{id:null}, disciplina:{id:null}}; $scope.etapas = []; $scope.disciplinasTurma = [];
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
                                if ($scope.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push($scope.atribuicoes[i].instituicao); } 
                                if (i === $scope.atribuicoes.length-1) {
                                    if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.buscarCursos(); }
                                    $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $scope.fechaProgresso(); }, 500);
                                }
                            }
                        },500);
                    });
                }
            }
        };
        
        //CARREGA MEDIAS
        $scope.medias = []; $scope.mostraMedia = 1; $scope.umaMediaEtapa = true;
        $scope.buscarMedias = function (id) {
            var promise = Servidor.buscarUm('etapas',id);
            promise.then(function(response){
                if (response.data.sistemaAvaliacao.quantidadeMedias === 1) { $scope.umaMediaEtapa = true; } else { $scope.umaMediaEtapa = false; }
                $scope.tipoAvaliacao = response.data.sistemaAvaliacao.tipo; $scope.mostraMedia = response.data.sistemaAvaliacao.quantidadeMedias-1; $scope.turmaBusca.media.id = null;
                if (response.data.frequenciaUnificada) { $scope.frequenciaUnificada = true; } else { $scope.frequenciaUnificada = false; }
                var unidade = response.data.sistemaAvaliacao.regime.unidade; var medias = [];
                var qtdeMedias = response.data.sistemaAvaliacao.quantidadeMedias;
                if (qtdeMedias > 1) {
                    for (var i=0; i<qtdeMedias; i++) { 
                        var label = (i+1)+"º "+unidade; medias.push({id:i+1, nome: label}); 
                        if (i === qtdeMedias-1) { 
                            $timeout(function (){ $scope.medias = medias; },100); $timeout(function () { $('#media').material_select('destroy'); $('#media').material_select(); $('#mediaNota').material_select('destroy'); $('#mediaNota').material_select();  }, 500);
                        }
                    }
                } else { $scope.turmaBusca.media.id = 1; }
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
        
        //BUSCA ALUNOSw
        $scope.enturmacoes = []; $scope.enturmacoesNotas = [];
        $scope.buscarAlunos = function (tipo) {
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.mostraProgresso();
            if ($scope.ativo === 'faltas') {
                if ($scope.frequenciaUnificada) {
                    if ($scope.unidade !== null && $scope.turmaBusca.curso.id !== null && $scope.turmaBusca.etapa.id !== null && $scope.turmaBusca.turma.id !== null) {
                        var promise = Servidor.buscar('medias/faltas', {'turma': $scope.turmaBusca.turma.id, 'numero': $scope.turmaBusca.media.id});
                        promise.then(function (response) {
                            for (var i=0; i<response.data.length; i++) { $scope.enturmacoes.push(response.data[i]); }
                            $('label').click(function(){ var id = $(this).attr('for'); $('#'+id).trigger('click'); }); 
                            $timeout(function(){Servidor.verificaLabels(); $scope.fechaProgresso();},500);
                            if ($scope.enturmacoes.length === 0) { Servidor.customToast("Não há alunos matriculados nesta turma."); }
                        });
                    } else { Servidor.customToast('Preencha todos os parâmetros de busca para encontrar os alunos.'); $scope.fechaProgresso(); }
                } else {
                    if ($scope.unidade !== null && $scope.turmaBusca.curso.id !== null && $scope.turmaBusca.etapa.id !== null && $scope.turmaBusca.turma.id !== null && $scope.turmaBusca.disciplina.id !== null) {
                        var promise = Servidor.buscar('medias', {'disciplinaOfertada': $scope.turmaBusca.disciplina.id, 'numero': $scope.turmaBusca.media.id});
                        promise.then(function (response) {
                            for (var i=0; i<response.data.length; i++) { $scope.enturmacoes.push(response.data[i]); }
                            $('label').click(function(){ var id = $(this).attr('for'); $('#'+id).trigger('click'); });
                            $timeout(function(){Servidor.verificaLabels(); $scope.fechaProgresso();},500);
                            if (response.data.length === 0) { Servidor.customToast("Não há alunos matriculados nesta turma."); }
                        });
                    } else { Servidor.customToast('Preencha todos os parâmetros de busca para encontrar os alunos.'); $scope.fechaProgresso(); }
                }
            } else {
                if ($scope.unidade !== null && $scope.turmaBusca.curso.id !== null && $scope.turmaBusca.etapa.id !== null && $scope.turmaBusca.turma.id !== null && $scope.turmaBusca.disciplina.id !== null) {
                        var promise = Servidor.buscar('medias', {'disciplinaOfertada': $scope.turmaBusca.disciplina.id, 'numero': $scope.turmaBusca.media.id});
                        promise.then(function (response) {
                            for (var i=0; i<response.data.length; i++) { 
                                if (response.data[i].valor === undefined) { response.data[i].valor = null; } $scope.enturmacoesNotas.push(response.data[i]);
                            }
                            $('label').click(function(){ var id = $(this).attr('for'); $('#'+id).trigger('click'); });
                            $timeout(function(){Servidor.verificaLabels(); $scope.fechaProgresso();},500);
                            if (response.data.length === 0) { Servidor.customToast("Não há alunos matriculados nesta turma."); }
                            else {
                                var promiseDisc = Servidor.buscarUm('disciplinas-ofertadas',$scope.turmaBusca.disciplina.id);
                                promiseDisc.then(function(response){
                                    var id = response.data.disciplina.id;
                                    var promiseH = Servidor.buscar('avaliacoes-qualitativas/habilidades',{media: $scope.turmaBusca.media.id, disciplina: id});
                                    promiseH.then(function(response){ $scope.habilidades = response.data; $timeout(function(){ $('.alunos').material_select('destroy'); $('.alunos').material_select(); }, 500); });
                                    var promiseC = Servidor.buscar('avaliacoes-qualitativas/conceitos',null);
                                    promiseC.then(function(response){ $scope.conceitos = response.data; $timeout(function(){ $('.tooltipped').tooltip(); }, 500); });
                                    $scope.fechaProgresso();
                                });
                            }
                        });
                } else { Servidor.customToast('Preencha todos os parâmetros de busca para encontrar os alunos.'); $scope.fechaProgresso(); }
            } 
        };
        
        //ABRINDO TELA DE CONCEITO
        $scope.editando = false; $scope.avaliacaoQualitativaAtual = []; $scope.notaQualitativaAtual = [];
        $scope.conceituar = function (enturmacao) {
            $scope.enturmacao = enturmacao; $scope.qualitativas = []; $scope.editando = false; $scope.mostraProgresso();
            $scope.notaQualitativaAtual = [];
            $('.chip').each(function(){ if ($(this).hasClass('chip-active')) { $(this).removeClass('chip-active'); } });
            $('.cortina-auxiliar').show(); $('.fakeModal').show();
            $('.cortina-auxiliar').click(function(){ $('.cortina-auxiliar').hide(); $('.fakeModal').hide(); });
            var promise = Servidor.buscar('avaliacoes-qualitativas',{fechamentoMedia:true, disciplina: $scope.turmaBusca.disciplina.id, media: $scope.turmaBusca.media.id});
            promise.then(function(response){
                if (response.data.length > 0) { 
                    $scope.editando = true;
                    var avaliacao = response.data[0]; $scope.avaliacaoQualitativaAtual = response.data[0];
                    var promiseNota = Servidor.buscar('notas-qualitativas',{media: $scope.enturmacao.id, avaliacao: avaliacao.id});
                    promiseNota.then(function(response){
                        if (response.data.length > 0) {
                            $scope.notaQualitativaAtual = response.data[0];
                            for (var i=0; i<response.data.length; i++) {
                                var habilidadesAvaliadas = response.data[i].habilidadesAvaliadas;
                                for (var j=0; j<habilidadesAvaliadas.length; j++) {
                                    $scope.qualitativas["c"+habilidadesAvaliadas[j].conceito.id+"h"+habilidadesAvaliadas[j].habilidade.id] = habilidadesAvaliadas[j];
                                    $(".c"+habilidadesAvaliadas[j].conceito.id+"h"+habilidadesAvaliadas[j].habilidade.id).addClass('chip-active');
                                }
                                $scope.fechaProgresso();
                            }
                        } else { $scope.fechaProgresso(); }
                    });
                } else { $scope.fechaProgresso(); }
            });
            
        };
        
        //SALVANDO CONCEITO
        $scope.prepararConceito = function (habilidade,conceito) {
            var idAtual = null;
            $('.h'+habilidade).each(function() {
                if (!$scope.editando) {
                    var c = $(this).attr('data-conceito'); var h = $(this).attr('data-habilidade');
                    if ($(this).hasClass('chip-active')) { var id = "c"+c+"h"+h; $('.h'+habilidade).removeClass('chip-active'); eval("delete $scope.qualitativas."+id+";"); }
                } else {
                    var c = $(this).attr('data-conceito'); var h = $(this).attr('data-habilidade');
                    if ($(this).hasClass('chip-active')) { idAtual = "c"+c+"h"+h; $('.h'+habilidade).removeClass('chip-active'); }
                }
            }).promise().done(function(){
                if ($scope.qualitativas["c"+conceito+"h"+habilidade] === undefined || $scope.qualitativas["c"+conceito+"h"+habilidade] === null){
                    if ($scope.editando) {
                        if ($scope.qualitativas[idAtual] !== null && $scope.qualitativas[idAtual] !== undefined) {
                            $scope.qualitativas["c"+conceito+"h"+habilidade] = $scope.qualitativas[idAtual];
                        } else {
                            $scope.qualitativas["c"+conceito+"h"+habilidade] = angular.copy($scope.notaHabilidade);
                        }
                        $scope.qualitativas["c"+conceito+"h"+habilidade].habilidade = {id: habilidade};
                        $scope.qualitativas["c"+conceito+"h"+habilidade].conceito = {id: conceito}; $(".c"+conceito+"h"+habilidade).addClass('chip-active');
                        eval("delete $scope.qualitativas."+idAtual+";");
                    } else {
                        var notaHabilidade = angular.copy($scope.notaHabilidade);
                        notaHabilidade.habilidade = {id: habilidade}; notaHabilidade.conceito = {id: conceito};
                        $scope.qualitativas["c"+conceito+"h"+habilidade] = notaHabilidade; $(".c"+conceito+"h"+habilidade).addClass('chip-active');
                    }
                    
                }
            });            
        };
        
        //SELECIONA UNIDADE DE ENSINO
        $scope.selecionaUnidade = function(unidade) {
            $('#dropUnidadesTurmaBusca').hide(); $scope.enturmacoes = []; $scope.enturmacoesNotas = [];
            if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; } $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome; $scope.unidade = unidade;
            $timeout(function(){ Servidor.verificaLabels(); $scope.buscarCursos(); },100);
        };
        
        //SALVAR NOTAS QUALITATIVAS
        $scope.salvarNotasQualitativas = function (){
            $scope.mostraProgresso();
            if ($scope.editando) {
                var notas = []; var tamanho = Object.keys($scope.qualitativas).length; var cont = 0;
                var tamanhoHabilidades = $scope.habilidades.length;
                if (tamanho === tamanhoHabilidades) {
                    for (var qualitativa in $scope.qualitativas) {
                        $scope.qualitativas[qualitativa].avaliacao = {id: $scope.avaliacaoQualitativaAtual.id}; notas.push($scope.qualitativas[qualitativa]);
                        if (cont === tamanho-1){
                            if ($scope.notaQualitativaAtual.length === 0) {
                                var notaQualitativa = angular.copy($scope.notaQualitativa);
                                notaQualitativa.avaliacao = {id: $scope.avaliacaoQualitativaAtual.id};
                                notaQualitativa.media = {id: $scope.enturmacao.id};
                            } else {
                                var notaQualitativa = angular.copy($scope.notaQualitativaAtual);
                            }
                            notaQualitativa.habilidadesAvaliadas = notas;
                            $timeout(function(){
                                var promiseNota = Servidor.finalizar(notaQualitativa, 'notas-qualitativas','Notas');
                                promiseNota.then(function(response){ 
                                    var promiseMedia = Servidor.buscarUm('medias',response.data.media.id);
                                    promiseMedia.then(function(response){
                                        if (response.data !== null && response.data !== undefined) { 
                                            var media = response.data; var result = Servidor.customPut(media,'medias/'+media.id+'?calcular=1',null);
                                            result.then(function(){
                                                $scope.buscarAlunos(); $scope.fechaProgresso(); 
                                            });
                                        } else { Servidor.customToast("Houve um problema ao salvar as notas."); }
                                    });
                                    $('.cortina-auxiliar').hide(); $('.fakeModal').hide();
                                });
                            },500);
                        } else { cont++; }
                    }
                } else {
                    Servidor.customToast("Existem habilidades ainda não preenchidas."); $scope.fechaProgresso();
                }
            } else {
                var avaliacaoQualitativa = angular.copy($scope.avaliacaoQualitativa); var notas = [];
                avaliacaoQualitativa.nome = 'Avaliação Final'; var tamanhoHabilidades = $scope.habilidades.length;
                avaliacaoQualitativa.media = $scope.turmaBusca.media.id; avaliacaoQualitativa.disciplina.id = $scope.turmaBusca.disciplina.id;
                var promise = Servidor.finalizar(avaliacaoQualitativa, 'avaliacoes-qualitativas', null);
                promise.then(function(response){
                    var tamanho = Object.keys($scope.qualitativas).length; var cont = 0;
                    if (tamanho === tamanhoHabilidades) {
                        for (var qualitativa in $scope.qualitativas) {
                            $scope.qualitativas[qualitativa].avaliacao = {id: response.data.id}; notas.push($scope.qualitativas[qualitativa]);
                            if (cont === tamanho-1){
                                var notaQualitativa = angular.copy($scope.notaQualitativa);
                                notaQualitativa.avaliacao = {id: response.data.id};
                                notaQualitativa.media = {id: $scope.enturmacao.id};
                                notaQualitativa.habilidadesAvaliadas = notas;
                                $timeout(function(){
                                    var promiseNota = Servidor.finalizar(notaQualitativa, 'notas-qualitativas','Notas');
                                    promiseNota.then(function(response){ 
                                        var promiseMedia = Servidor.buscarUm('medias',response.data.media.id);
                                        promiseMedia.then(function(response){
                                            if (response.data !== null && response.data !== undefined) { 
                                                var media = response.data; var result = Servidor.customPut(media,'medias/'+media.id+'?calcular=1',null);
                                                result.then(function(){
                                                    $scope.buscarAlunos(); $scope.fechaProgresso();
                                                });
                                            } else { Servidor.customToast("Houve um problema ao salvar as notas."); }
                                        });
                                        $('.cortina-auxiliar').hide(); $('.fakeModal').hide();
                                    });
                                },500);
                            } else { cont++; }
                        }
                    } else {
                        Servidor.customToast("Existem habilidades ainda não preenchidas."); $scope.fechaProgresso();
                    }
                });
            }
                
        };
        
        //SALVA FALTAS
        $scope.salvarFaltas = function () {
            $scope.mostraProgresso();
            if ($scope.frequenciaUnificada) {
                var faltas = []; var valido = true;
                $('.input-col').each(function(){
                    if ($(this).attr('data-item-id') !== "" && $(this).attr('data-item-id') !== null && $(this).attr('data-item-id') !== undefined) {
                        if ($(this).val() !== null && $(this).val() !== undefined) {
                            if ($(this).val() === "") {
                                valido = false;
                            } else {
                                faltas.push({enturmacao:{id:parseInt($(this).attr('data-item-id'))}, faltas: parseInt($(this).val()), media: parseInt($scope.turmaBusca.media.id)});
                            }
                        }
                    }
                }).promise().done(function(){ 
                    if (valido) {
                        var result = Servidor.finalizar({faltas: faltas},'medias/faltas','Faltas');
                        if (!result) { $scope.fechaProgresso(); } else { result.then(function(){$scope.fechaProgresso();}); }
                    } else {
                        Servidor.customToast("Existem faltas em branco, as faltas não foram salvas."); $scope.fechaProgresso();
                    }
                });
            } else {
                $('.input-col:not(.ng-hide)').each(function(i){
                    var val = $(this).val();
                    if (val !== "" && val !== null && val !== undefined) { $scope.enturmacoes[i].faltas = parseInt(val); }
                }).promise().done(function(){ 
                    var result = Servidor.salvarLote({medias: $scope.enturmacoes},'medias','Faltas');
                    if (!result) { $scope.fechaProgresso(); } else { result.then(function(){$scope.fechaProgresso();}); }
                });
            }
        };
        
        //SALVA NOTAS
        $scope.salvarNotas = function (){
            var notas = []; $scope.mostraProgresso();
            $('.input-col').each(function(i){
                var val = $(this).val(); if (val.indexOf(",") !== -1) { val = val.replace(",","."); }
                if (val !== "" && val !== null && val !== undefined) { $scope.enturmacoesNotas[i].valor = parseFloat(val); notas.push($scope.enturmacoesNotas[i]); }
                //else if (val === "" || val === null || val === undefined) { val = null; $scope.enturmacoesNotas[i].valor = val; notas.push($scope.enturmacoesNotas[i]); }
            }).promise().done(function(){ 
                var result = Servidor.salvarLote({medias: notas},'medias','Notas');
                if (!result) { $scope.fechaProgresso(); } else { result.then(function(){$scope.fechaProgresso();}); }
            });
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

        //INICIALIZANDO
        $scope.buscarUnidades(); $scope.inicializar();
    }]);
})();

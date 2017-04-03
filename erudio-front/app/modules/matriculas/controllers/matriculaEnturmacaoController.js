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
    var matriculaEnturmacaoModule = angular.module('matriculaEnturmacaoModule', ['matriculaDirectives', 'servidorModule', 'erudioConfig', 'elementosModule']);
    matriculaEnturmacaoModule.controller('MatriculaEnturmacaoController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'PessoaService', '$compile', 'dateTime', 'makePdf', 'ErudioConfig', 'Elementos' , '$sce', '$routeParams', '$rootScope', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, PessoaService, $compile, dateTime, makePdf, ErudioConfig, Elementos, $sce, $routeParams, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('MATRICULA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('matriculas','enturmacoes'); $scope.lista = false;
        //ATRIBUTOS
        $scope.titulo = "Enturmações"; $scope.buscaAvancada = false; $scope.enturmacoes = []; $scope.etapa = {id: null}; $scope.etapas = []; $scope.turmas = []; $scope.disciplinasCursadas = [];
        $scope.disciplinas = []; $scope.matriculaBusca = { 'aluno': '', 'status': '', 'codigo': '', 'curso': null, 'unidade': null }; $scope.etapasOfertadas = [];
        $scope.turmaMatricula = {'id': null}; $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} }; $scope.enturmacao = {'turma': {id: null}, 'matricula': {'id': null}};
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-turma').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //CADASTRO ENTURMACOES
        $scope.verificaCadastroEnturmacoes = function(enturmacoes) { var retorno = true; enturmacoes.forEach(function(e) { if(!e.encerrado) { retorno = false; } }); $scope.fechaProgresso(); return retorno; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/matriculas/'+$scope.matricula.id; };
        //VERIFICA DATA DISPONIVEL
        $scope.verificarVagaDisponivel = function(pessoaId, turmaId) { $scope.finalizarEnturmacao(); };
        
        //BUSCAR MEDIA FREQUENCIAS DO ALUNO
        $scope.buscarMediasFrequenciasAluno = function(index) {
            var botao = $('#btn-ent'+$scope.enturmacoes[index].id);
            if (botao.text() === "keyboard_arrow_down") { botao.text("keyboard_arrow_up"); } else { botao.text("keyboard_arrow_down"); }
            if ($scope.enturmacoes[index].matricula.disciplinas !== undefined) { return; } $scope.requisicoes++;
            var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: $scope.enturmacoes[index].id});
            promise.then(function(response) {
                $scope.requisicoes--; $scope.enturmacoes[index].matricula.disciplinas = response.data;
                $scope.enturmacoes[index].matricula.disciplinas.forEach(function(cursada) {
                    $scope.requisicoes++; var promise = Servidor.buscar('medias', {disciplinaCursada: cursada.id});
                    promise.then(function(response) { cursada.medias = response.data; $scope.requisicoes--; }); $scope.requisicoes++;
                    var promise = Servidor.buscar('frequencias', {disciplina: cursada.id});
                    promise.then(function(response) {                        
                        cursada.faltas = 0; var frequencias = response.data;
                        frequencias.forEach(function(frequencia) { if (frequencia.status === 'FALTA') { cursada.faltas++; } });
                        $scope.requisicoes--; $scope.fechaProgresso();
                    });
                });
            });
        };
        
        //BUSCAR SISITEMA DE AVALIACAO
        $scope.buscarSistemaAvaliacao = function(etapa) {
            $scope.requisicoes++; var promise = Servidor.buscarUm('etapas', etapa.id);
            promise.then(function(response) {                
                if (response.data.sistemaAvaliacao.tipo === 'QUALITATIVO') { $scope.sistemaAvaliacao = 'qualitativas'; } else { $scope.sistemaAvaliacao = 'quantitativas'; }
                $scope.requisicoes--; $scope.fechaProgresso();
            });
        };
        
        //PREPARA REMOVER ENTURMACAO
        $scope.prepararRemoverEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); $scope.mostraProgresso(); $('#collapsible-enturmacao-'+enturmacao.id).collapsible();
            var promise = Servidor.buscarUm('enturmacoes', enturmacao.id);
            promise.then(function(response) { $scope.enturmacao = enturmacao; $('#remover-enturmacao-modal').modal(); $scope.fechaProgresso(); $scope.fechaProgresso(); });
        };
        
        //REMOVER ENTURMACAO
        $scope.removerEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); $scope.mostraProgresso(); Servidor.remover(enturmacao, 'Enturmação');
            $scope.enturmacoes = $scope.enturmacoes.filter(function(e) { return e.id !== enturmacao.id; });
            $scope.fechaProgresso(); $scope.fechaProgresso(); $scope.fecharFormulario();
        };
        
        //CARREGA MEDIA
        $scope.carregaMedia = function (media) {
            $scope.media = media;
            if (media === 'SN') { Servidor.customToast('Esta média não possui notas.'); }
            if (!media.notas.length) {
                if ($scope.sistemaAvaliacao === 'qualitativas') { Servidor.customToast(media.nome + ' não possui nenhum conceito.');
                } else { Servidor.customToast(media.nome + ' não possui nenhuma nota.'); }
            } else {
                $scope.disciplinasCursadas.forEach(function (d) { if (d.id === parseInt($scope.disciplinaCursada.id)) { $scope.ofertada = d.disciplinaOfertada; } });
                if ($scope.sistemaAvaliacao === 'qualitativas') {
                    $scope.media.notas.forEach(function (n, $indexN) {
                        var promise = Servidor.buscarUm('notas-qualitativas', n.id);
                        promise.then(function (response) { $scope.media.notas[$indexN].habilidadesAvaliadas = response.data.habilidadesAvaliadas; });
                    });
                    $timeout(function () { $('#notas-disciplina').modal(); $('.collapsible').collapsible({accordion: false}); }, 500);
                } else {
                    $scope.media.notas.forEach(function (n, $index) {
                        var promise = Servidor.buscarUm('avaliacoes-' + $scope.sistemaAvaliacao, n.avaliacao.id);
                        promise.then(function (response) {
                            $scope.media.notas[$index].avaliacao = response.data;
                            if ($scope.media.notas[$index].valor.split('.')[1] < 1) { $scope.media.notas[$index].valor = $scope.media.notas[$index].valor.split('.')[0]; };
                            if ($index === $scope.media.notas.length - 1) { $('#notas-disciplina').modal(); }
                        });
                    });
                }
            }
        };
        
        //BUSCA TURMA ENTURMACAO
        $scope.buscarTurmasEnturmacao = function(etapa, sel) {
            var promise = Servidor.buscar('turmas', {etapa: etapa, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function(response) {
                $scope.turmas = response.data; if(!$scope.turmas.length) { Servidor.customToast('Esta etapa não possui nenhuma turma.'); }
                $scope.turmas.forEach(function(t, i) { $scope.enturmacoes.forEach(function(e) { if (t.id === e.turma.id) { $scope.turmas.splice(i, 1); } }); });
                setTimeout(function() { $(sel).material_select(); $scope.fechaProgresso(); }, 250);
            });
        };
        
        //BUSCA ETAPA ENTURMACAO
        $scope.buscarDisciplinasEnturmacao = function(etapa) {
            $scope.disciplinas = []; $scope.mostraProgresso();
            var promise = Servidor.buscar('disciplinas-cursadas', {etapa: etapa, matricula: $scope.matricula.id, status: 'CURSANDO'});
            promise.then(function(response) {
                if(!response.data.length) {
                    var promise = Servidor.buscar('disciplinas', {etapa: etapa});
                    promise.then(function(response) {
                        response.data.forEach(function(d) {
                            $scope.requisicoes++; var promise = Servidor.buscarUm('disciplinas', d.id);
                            promise.then(function(response) { $scope.disciplinas.push(response.data); if(--$scope.requisicoes === 0) { $scope.fechaProgresso(); } });
                        });
                    });
                } else { $scope.fechaProgresso(); }
            });
        };
        
        //FINALIZAR
        $scope.finalizarEnturmacao = function() {
            var enturmacao = { matricula: {id: $scope.matricula.id}, turma: {id: $scope.enturmacao.turma.id} }; $scope.requisicoes++;
            var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
            promise.then(function (response) {            
                $scope.enturmacao.matricula = $scope.matricula; $scope.enturmacao.id = response.data.id; $scope.enturmacoes.push(response.data);
                $scope.requisicoes--; $scope.fechaProgresso(); $scope.fecharFormularioEnturmacao($scope.matricula.id);
            }, function (response){ if (response.status === 400) { Servidor.customToast(response.data); $scope.fechaProgresso(); } });
        };
        
        //FINALIZAR FORM
        $scope.fecharFormularioEnturmacao = function (matricula) {
            var promise = Servidor.buscar('enturmacoes', {matricula: matricula});
            promise.then(function (response) {
                $scope.enturmacoes = response.data;
                $timeout(function () { $scope.buscarUnidades(); $scope.buscarTurmas(); $scope.buscarEnturmacoes(); $scope.matriculas = null; }, 500);
            });
        };
        
        //BUSCAR UNIDADES
        $scope.buscarUnidades = function (nomeUnidade) {
            var params = {nome: null}; var permissao = true;
            if (nomeUnidade !== undefined && nomeUnidade) { params.nome = nomeUnidade; if (nomeUnidade.length > 4) { permissao = true; } else { permissao = false; } }
            if(permissao) {
                var promise = null; if ($scope.isAdmin) { promise = Servidor.buscar('unidades-ensino', params); } else { promise = Servidor.buscarUm('unidades-ensino', $scope.unidadeAlocacao); }
                promise.then(function (response) {
                    if ($scope.isAdmin) { $scope.unidades = response.data;
                    } else { $scope.unidades.push(response.data); $scope.matriculaBusca.unidade = response.data.id; }
                    $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 250);
                });
            }
        };
        
        //BUSCAR TURMAS
        $scope.buscarTurmas = function () {
            $scope.mostraProgresso(); $scope.enturmacoesMatricula = [];
            var promise = Servidor.buscar('enturmacoes', {'matricula': $scope.matricula.id, 'encerrado': $scope.ativo});
            promise.then(function (response) {
                if (!response.data.length && $scope.ativo === '0') { $scope.menssagemErro = 'Nenhuma turma ativa, clique no + para enturmar.'; $scope.nenhumaEncerrada = false; $scope.nenhumaTurma = true;
                } else if (!response.data.length && $scope.ativo === '1') { $scope.menssagemErro = 'Nenhuma turma encerrada.'; }
                $scope.enturmacoesMatricula = response.data;
                $scope.enturmacoesMatricula.forEach(function (enturmacao, indexE) {
                    var promiseE = Servidor.buscarUm('enturmacoes', enturmacao.id);
                    promiseE.then(function (responseE) {
                        $scope.enturmacoesMatricula[indexE] = responseE.data;
                        if (indexE === response.data.length - 1) {
                            if ($scope.ativo === '1' && response.data.length === 0) { $scope.nenhumaEncerrada = true;
                            } else {
                                $scope.nenhumaEncerrada = false; $scope.nenhumaTurma = false;
                                $scope.enturmacoesMatricula.forEach(function (e, index) {
                                    var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao:e.id});
                                    promise.then(function (response) {
                                        if (response.data.length) {
                                            $scope.enturmacoesMatricula[index].disciplinasCursadas = response.data;
                                            $scope.enturmacoesMatricula[index].disciplinasCursadas.forEach(function (d, $indexD) {
                                                var promiseD = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'disciplina': d.id});
                                                promiseD.then(function (responseD) {
                                                    if (responseD.data.length) { $scope.enturmacoesMatricula[index].disciplinasCursadas[$indexD].frequencia = responseD.data.length; }
                                                    if (index === $scope.enturmacoesMatricula.length - 1 && $indexD === $scope.enturmacoesMatricula[index].disciplinasCursadas.length - 1) { $scope.carregarDisciplinas(); $scope.fechaProgresso(); }
                                                });
                                            });
                                        } else { if (index === $scope.enturmacoesMatricula.length - 1) { $scope.carregarDisciplinas(); $scope.fechaProgresso(); } }
                                    });
                                });
                            } $scope.mostraEnturmar = true;
                        }
                    });
                });
            }); $scope.fechaProgresso();
        };
        
        //BUSCAR ENTURMACOES
        $scope.buscarEnturmacoes = function () {
            $scope.mostraProgresso(); var promise = Servidor.buscar('enturmacoes', {'matricula': $scope.enturmacao.matricula.id, 'encerrado': false});
            promise.then(function (response) { $scope.enturmacoes = response.data; $timeout(function () { $scope.fechaProgresso(); }, 1000); });
        };
        
        //CARREGAR DISCIPLINAS
        $scope.carregarDisciplinas = function () {
            $scope.mostraListaDisciplinas = false; var cont = 0;
            $timeout(function () {
                $scope.enturmacoesMatricula.forEach(function (e, index) {
                    var promise = Servidor.buscarUm('etapas', e.turma.etapa.id);
                    promise.then(function (response) {
                        cont++; if (response.data.sistemaAvaliacao.tipo === 'QUANTITATIVO') { $scope.sistemaAvaliacao = 'quantitativas'; } else { $scope.sistemaAvaliacao = 'qualitativas'; }
                        if (e.disciplinasCursadas) {
                            e.disciplinasCursadas.forEach(function (d, indexD) {
                                var promise = Servidor.buscar('medias', {'disciplinaCursada': d.id});
                                promise.then(function (response) {
                                    if (response.data.length) {
                                        e.disciplinasCursadas[indexD].medias = response.data;
                                        $scope.enturmacoesMatricula[index].disciplinasCursadas[indexD].medias.forEach(function (m, indexM) {
                                            if (m.valor) {
                                                if (m.valor.split('.')[1] < 1) { $scope.enturmacoesMatricula[index].disciplinasCursadas[indexD].medias[indexM].valor = m.valor.split('.')[0]; $scope.fechaProgresso(); }
                                            }
                                            if (cont === $scope.enturmacoesMatricula.length) { $scope.fechaProgresso(); $scope.mostraListaDisciplinas = true; }
                                        });
                                    } else { $scope.fechaProgresso(); $scope.mostraListaDisciplinas = true; }
                                });
                            });
                        } else if (cont === $scope.enturmacoesMatricula.length) { $scope.fechaProgresso(); $scope.mostraListaDisciplinas = true; }
                    });
                });
            }, 100);
        };
        
        //BUSCAS TURMAS
        $scope.turmasCompativeis = function (id, cursadas) {
            if (!id) { id = $scope.etapa.id; } $scope.turmas = [];
            var requisicoesTurmasCompativeis = 0; $scope.requisicoes++;
            var promise = Servidor.buscar('turmas', {'etapa': id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function (response) {
                $scope.turmas = response.data; $scope.fechaProgresso();
                $timeout(function(){
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('destroy');
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('');                                
                },50);
            });
        };
        
        //BUSCA DISCIPLINA DA TURMA
        $scope.buscarTurmasDisciplinas = function (id) {
            var promise = Servidor.buscar('turmas', {'etapa':id, 'unidadeEnsino':$scope.matricula.unidadeEnsino.id});
            promise.then(function(response){ $scope.turmas = response.data; $timeout(function(){ $('select').material_select('destroy'); $('select').material_select(); },500); });
            $scope.turmaMatricula.id = null; $scope.disciplinasOfertadas = []; $scope.mostraProgresso(); $scope.buscarEtapaCurso(id);
            var promiseD = Servidor.buscar('disciplinas', {'curso': $scope.matricula.curso.id, 'etapa': id});
            promiseD.then(function (responseD) {
                if (responseD.data.length) { $scope.disciplinasCurso = responseD.data; $scope.selecionarTodasDisciplinas();
                } else { $scope.fechaProgresso(); Servidor.customToast('Nao ha disciplinas nesta etapa.'); }
            });
        };
        
        //BUSCA ETAPA CURSO
        $scope.buscarEtapaCurso = function (id) {
            $scope.mostraProgresso(); var promise = Servidor.buscarUm('etapas', id);
            promise.then(function (response) { $scope.fechaProgresso(); $scope.etapaCurso = response.data; });
        };
        
        //SELECIONAR TODAS DISCIPLINAS
        $scope.selecionarTodasDisciplinas = function () {
            $scope.mostraProgresso(); $scope.disciplinasCursadas = []; var requisicoes = 0;
            var disciplinaCursada = { 'matricula': $scope.matricula.id, 'disciplina': null, 'id': null }; 
            if(!$scope.disciplinasCurso.length) { $scope.fechaProgresso(); }
            $scope.disciplinasCurso.forEach(function (d, index) {                                
                requisicoes++; var promise = Servidor.buscarUm('disciplinas', d.id);
                promise.then(function (response) {
                    disciplinaCursada.disciplina = angular.copy(response.data);
                    if (response.data.opcional) { $scope.disciplinasCursadas.push(angular.copy(disciplinaCursada));
                    } else { d = disciplinaCursada; }
                    if(--requisicoes === 0) { $scope.fechaProgresso(); }
                });
            });
        };
        
        //SELECIONA DISCIPLINAS
        $scope.selecionaDisciplina = function (disciplina) {
            var qtd = $scope.disciplinasCurso.length;
            $scope.disciplinasCurso.forEach(function (d, index){ if(d.id === disciplina.disciplina.id) { $scope.disciplinasCurso.splice(index, 1); } });
            if(qtd === $scope.disciplinasCurso.length) { $scope.disciplinasCurso.push(disciplina.disciplina); }
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $scope.mostraProgresso();  var promise = Servidor.buscarUm('matriculas', $routeParams.id);
            promise.then(function (response) { 
                $scope.matricula = response.data;
                var promise = Servidor.buscar('etapas-ofertadas',{'unidadeEnsino': $scope.matricula.unidadeEnsino.id});
                promise.then(function(response){ $scope.etapasOfertadas = response.data; $timeout(function() { $('select').material_select(); }, 50); });
                var promise = Servidor.buscar('enturmacoes', {matricula: $scope.matricula.id});
                promise.then(function(response) {                        
                    $scope.enturmacoes = response.data;
                    var enturmacoesAtivas = $scope.enturmacoes.filter(function(enturmacao) { return !enturmacao.encerrado; });
                    var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id, status:'CURSANDO'});
                    promise.then(function(response) {
                        var disciplinasAtivas = response.data; $scope.requisicoes--;
                        if(!disciplinasAtivas.length && !enturmacoesAtivas.length) { $scope.selecionaOpcao('disciplinas');
                        } else {
                            if($rootScope.enturmacaoMedias !== null && $rootScope.enturmacaoMedias !== undefined) {
                                $rootScope.enturmacaoMedias = null; var index = $scope.enturmacoes.length-1; $scope.buscarMediasFrequenciasAluno(index);
                                $scope.buscarSistemaAvaliacao($scope.enturmacoes[index].turma.etapa);
                            }
                            if($scope.matricula.curso.especializado) {
                                $scope.requisicoes++; var promise = Servidor.buscar('etapas', {curso:$scope.matricula.curso.id});
                                promise.then(function(response) {
                                    $scope.etapas = response.data; $scope.turmas = []; var achou;
                                    for(var i = 0; i < $scope.etapas.length; i++) {
                                        achou = false;
                                        for(var j = 0; j < $scope.enturmacoes.length; j++) {
                                            if($scope.etapas[i].id === $scope.enturmacoes[j].turma.etapa.id) { achou = true; }
                                            if(achou) { $scope.etapas.splice(i, 1); }
                                        }
                                    } setTimeout(function() { $('#enturmacaoEtapa').material_select(); $scope.requisicoes--; }, 500);
                                });
                            }
                            if (!$scope.enturmacoes.length || $scope.verificaCadastroEnturmacoes($scope.enturmacoes)) {
                                Servidor.customToast('Este aluno não possui enturmações.'); $scope.enturmacao = { matricula: {id: $scope.matricula.id}, etapa: {id: null}, turma: {id: null} };
                                if (disciplinasAtivas.length) {
                                    $scope.requisicoes++; var promise = Servidor.buscarUm('disciplinas', disciplinasAtivas[0].disciplina.id);
                                    promise.then(function(response) {
                                        $scope.etapa = response.data.etapa;
                                        if($scope.matricula.curso.especializado) {
                                            var promise = Servidor.buscar('turmas', {etapa: $scope.etapa.id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
                                            promise.then(function(response) { $scope.turmas = response.data; $timeout(function() { $scope.requisicoes--; $('#enturmacaoTurma').material_select(); }, 500); });
                                        } else { $scope.requisicoes--; $scope.turmasCompativeis(null, disciplinasAtivas); }                                        
                                    });
                                } else { Servidor.customToast('Este aluno não está cursando nenhuma disciplina.'); }
                            }
                            $scope.mostraEnturmacoes = true; $scope.fechaProgresso();
                            $timeout(function() { $('.collapsible').collapsible({ accordion : false }); $('.tooltipped').tooltip({delay:50}); }, 250);
                        }
                    });
                }); $scope.fechaProgresso();
            });
            $('.title-module').html($scope.titulo); $('#modal-ajuda-matricula').modal(); $('.material-tooltip').remove();
            $timeout(function () {
                $('select').material_select('destroy'); $('select').material_select();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $('.tooltipped').tooltip({delay: 50});
                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            }, 300);
        };

        $scope.inicializar();
    }]);
})();

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
    var historicoMovimentacoesModule = angular.module('movimentacoesModule', ['servidorModule', 'movimentacoesDirectives']);

    historicoMovimentacoesModule.controller('movimentacoesController', ['$scope', 'Servidor', 'Restangular', '$timeout', 'dateTime', '$templateCache',function ($scope, Servidor, Restangular, $timeout, dateTime, $templateCache) {
        $templateCache.removeAll();
        $scope.escrita = Servidor.verificaEscrita('MOVIMENTACAO');
        $scope.isAdmin = Servidor.verificaAdmin();
        var unidadeEnsino = JSON.parse(sessionStorage.getItem('unidade'));
        $scope.unidade = {id: unidadeEnsino.id};
        $scope.visaoGeral = sessionStorage.getItem('visãoGeral'); if ($scope.visaoGeral === null || $scope.visaoGeral === undefined) { $scope.visaoGeral = true; }
        
        /* ------------- Históricos --------------*/

        $scope.cursadas = [];
        $scope.transferencias = [];
        $scope.ofertadas = [];
        $scope.cursadas = [];
        $scope.matriculas = [];

        $scope.buscaAluno = '';
        $scope.turmaDestino = {'id': null};
        $scope.turma = {'id': null};
        $scope.etapa = {'id': null};
        $scope.curso = {'id': null};
        $scope.transferencia = {
            'justificativa': null,
            'resposta': null,
            'dataAgendamento': null,
            'matricula': {'id': null},
            'unidadeEnsinoDestino': {'id': null},
            'unidadeEnsinoOrigem': {'id': null}
        };

        /* ------------- Movimentações --------------*/

        // Atributos Específicos
        $scope.transferencias = [];
        $scope.detalhesMovimentacoes =  false;
        $scope.mostraListaMovimentacoes =  false;
        $scope.cursos = [];
        $scope.unidades = [];
        $scope.matriculas = [];
        $scope.turmas = [];
        $scope.enturmadas = [];
        $scope.ofertadas = [];
        $scope.cursadas = [];
        $scope.predicate = null;
        $scope.turmaDestino = {'id': null};
        $scope.turma = {'id': null};
        $scope.enturmada = 0;
        $scope.unidadeId = null;
        $scope.buscaAluno = '';
        $scope.cursoId = null;
        $scope.codigo = null;
        $scope.turmaId = '';
        $scope.erro = '';
        $scope.curso = {'id': null};
        $scope.permissao = false;
        $scope.aceitar = true;
        $scope.mostraEnturmacao = false;
        $scope.resposta = '';
        $scope.opcaoEnvio = '';
        $scope.nomeUnidade = null;
        $scope.paginaAtual = 1;
        $scope.quantidadePaginas = 0;
        $scope.nomeUnidadeBusca = '';
        $scope.tipoConsulta = 'RECEBIDAS';

        $scope.matricula = {
            'id': null,
            'unidadeEnsino': {'id': null},
            'aluno': {},
            'codigo': null,
            'status': null
        };
        $scope.transferencia = {
            'justificativa': null,
            'resposta': null,
            'dataAgendamento': null,
            'matricula': {'id': null},
            'unidadeEnsinoDestino': {'id': null}
        };
        $scope.desligamento = {
            'matricula': {},
            'justificativa': null,
            'destino': null,
            'motivo': null
        };
        $scope.movimentacao = {
            'matricula': {},
            'enturmacaoOrigem': {},
            'justificativa': null,
            'turmaDestino': {}
        };

        // Controle de Página
        $scope.editando = false;
        $scope.limparTransferencia = function () {
            $scope.transferencia = {
                'justificativa': null,
                'resposta': null,
                'curso':null,
                'dataAgendamento': null,
                'matricula': {'id': null},
                'unidadeEnsinoDestino': $scope.transferencia.unidadeEnsinoDestino
            };
        };

        $scope.limparDesligamento = function () {
            $scope.desligamento = {
                'matricula': null,
                'justificativa': null,
                'destino': null,
                'motivo': null
            };
        };

        $scope.limparMatricula = function () {
            $scope.matricula = {
                'id': null,
                'unidadeEnsino': {'id': null},
                'aluno': {},
                'codigo': null,
                'status': null
            };
        };

        $scope.permissao = false;
        $scope.editando = false;
        $scope.mostraEnturmacao = false;
        $scope.aceitar = true;
        $scope.enturmar = false;

        $scope.prepararRemoverEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); 
            var promise = Servidor.buscarUm('enturmacoes', enturmacao);
            promise.then(function(response) {
                $scope.enturmacao = response.data; console.log($scope.enturmacao);
                $scope.buscarEtapasTurma(response.data.matricula.curso.id);
                $scope.fechaProgresso();
            });
        };
        
        $scope.etapaTurma = {id:null}; $scope.turmaNova = {id:null};
        $scope.buscarTurmasEnt = function (id) {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('turmas',{etapa: id, curso: $scope.matricula.curso.id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function(response){
                $scope.turmasEnturmacao = response.data; $timeout(function(){ $('#enturmacaoTurma').material_select(); $('#enturmacaoTurma').material_select(); $scope.fechaProgresso();  },500);
            });
        };
        
        $scope.verificarVagaDisponivel = function(finalizar) {
            if ($scope.temEnturmacao && finalizar !== true) {
                $('#remover-enturmacao-modal').openModal();
            } else {
                var enturmacao = { matricula: {id: $scope.matriculaEmUso.id}, turma: {id: $scope.turmaNova.id} };
                $scope.requisicoes++; $scope.mostraProgresso();
                var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
                promise.then(function (response) {            
                    $scope.fechaProgresso(); $scope.fecharFormulario();
                }, function (response){
                    if (response.status === 400) { Servidor.customToast(response.data); $scope.buscarMatriculas($scope.matriculaMovimentacoes,'','botao'); $scope.fechaProgresso(); }
                });
            }
        };
        
         /*Busca de etapas no Curso*/
        $scope.buscarEtapasTurma = function (id) {
            $scope.etapas = [];
            var promise = Servidor.buscar('etapas', {'curso': id});
            promise.then(function (response) {
                $scope.etapas = response.data;
                $scope.editando = true;
                $scope.fechaProgresso(); $timeout(function(){ $('#etapaTurma').material_select(); $('#etapaTurma').material_select(); $scope.fechaProgresso(); $('#enturmacaoTurma').material_select(); $('#enturmacaoTurma').material_select();  },500);
            });
        };
        
        $scope.alterarEtapa = false;
        $scope.removerEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); 
            Servidor.remover(enturmacao, null);
            $scope.alterarEtapa = true;
            $scope.verificarVagaDisponivel(true);
            $scope.fechaProgresso(); 
        };

        $scope.buscarUnidades = function () {
            var promise = Servidor.buscar('unidades-ensino', null);
            promise.then(function (response) {
                $scope.unidadesOrigem = response.data;
                $scope.unidadesDestino = angular.copy(response.data);
                $scope.escondeUnidade(); 
                $scope.unidades = response.data;
                if (!$scope.isAdmin) {
                    response.data.forEach(function(u) {
                        if($scope.unidade.id === u.id) {
                            $scope.matriculaMovimentacoes.unidadeEnsino = u.id;
                            $scope.nomeUnidadeBusca = u.nomeCompleto;
                            $timeout(function(){ $scope.buscarCursos(); Servidor.verificaLabels(); }, 50);
                        }
                    });                                      
                }                
                $timeout(function(){
                    $('#buscaUnidadeMovimentar').material_select();
                    $('#unidadeDestino').material_select('destroy');
                    $('#unidadeDestino').material_select();
                    $('.unidade-destino').find('.disabled').hide();
                }, 50);
            });
        };

        $scope.buscarUnidadesOrigem = function(nomeUnidade){
            if (nomeUnidade) { $scope.nomeUnidade = nomeUnidade; }
            if($scope.nomeUnidade !== undefined && $scope.nomeUnidade.length > 4) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': $scope.nomeUnidade});
                promise.then(function(response){
                    $scope.unidadesAutoComplete = response.data;
                });
            } else {
                $scope.unidadesAutoComplete = [];
            }
        };

        $scope.selecionarUnidade = function(unidade) {
            if ($scope.opcaoEnvio === 'Transferência') {
                if(unidade.id === $scope.matricula.unidadeEnsino.id) {
                    Servidor.customToast('O aluno já se encontra nesta unidade.');
                } else {
                    $scope.transferencia.unidadeEnsinoDestino = angular.copy(unidade);                
                }                
            } else {
                $scope.matriculaMovimentacoes.unidadeEnsino = unidade.id;
                $scope.nomeUnidadeBusca = unidade.nomeCompleto;
                $timeout(function(){ Servidor.verificaLabels(); }, 50);
                
                $scope.transferencia.unidadeEnsinoOrigem = unidade;
                $scope.nomeUnidade = unidade.nomeCompleto;
            }
            $timeout(function(){
              Servidor.verificaLabels(); $scope.buscarCursos();
            },50);
        };

        $scope.buscarDisciplinasCursadas = function (matricula) {
            var promise = Servidor.buscar('disciplinas-cursadas', {'matricula': matricula.id});
            promise.then(function (response) {
                $scope.cursadas = response.data;
            });
        };

        $scope.enturmarAluno = function () {
            if($scope.turmas.length === 1) { $scope.turma = $scope.turmas[0]; }
            $timeout(function(){
                var enturmacao = {
                    'matricula':{ 'id': $scope.transferencia.matricula.id },
                    'turma': {'id': parseInt($scope.turma.id) }
                };
                var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmaçao');
                promise.then(function(response) {
                    $scope.enturmacao = response.data;
    //                $scope.desativarDisciplinasCursadas();
                    if ($scope.etapa.ordem > $scope.transferencia.matricula.etapaAtual) {
                        $scope.salvarDisciplinasCursadas();
                        $timeout(function(){ $scope.validarBusca(); },800);
                    } else {
    //                    $scope.clonarDisciplinasCursadas();
                    }
                });
            },500);
        };

        $scope.salvarDisciplinasCursadas = function() {
            $scope.requisicoes = 0;
            $scope.disciplinas.forEach(function(d) {
                if (!d.opcional || $('#dis'+d.id).prop('checked')) {
                    $scope.requisicoes++;
                    var promise = Servidor.finalizar({
                        matricula: {id:$scope.enturmacao.matricula.id},
                        enturmacao: {id:$scope.enturmacao.id},
                        disciplina: {id:d.disciplina.id},
                        disciplinaOfertada: {id:d.disciplinaOfertada.id}
                    }, 'disciplinas-cursadas', null);
                    promise.then(function() {
                        if (--$scope.requisicoes === 0) {
                            $scope.enturmarAluno();
                        }
                    });
                }
            });
        };

        $scope.clonarDisciplinasCursadas = function() {
            $scope.requisicoes = 0;
            $scope.cursadas.forEach(function(d) {
                $scope.requisicoes++;
                var cursada = {
                    matricula: {id:$scope.enturmacao.matricula.id},
                    enturmacao: {id:$scope.enturmacao.id},
                    disciplina: {id:d.disciplina.id},
                    disciplinaOfertada: {id:d.disciplinaOfertada.id}
                };
                var promise = Servidor.finalizar(cursada, 'disciplinas-cursadas', null);
                if (d.medias.length) {
                    promise.then(function(response) {
                       var promise = Servidor.buscar('medias', {disciplinaCursada: response.data.id});
                       promise.then(function(response) {
                           var medias = response.data;
                           d.medias.forEach(function(ma) {
                               medias.forEach(function(mn) {
                                    if (ma.numero === mn.numero) {
                                        mn.valor = ma.valor;
                                        Servidor.finalizar(mn, 'medias', '');
                                    }
                               });
                           });
                       });
                    });
                }
            });
        };

        $scope.selecionarEtapa = function(id) {
            var promise = Servidor.buscarUm('etapas', id);
            $scope.contadorDisciplinaMov = true;
            promise.then(function(response) {
                $scope.etapa = response.data;
                $scope.disciplinas = [];
                if ($scope.transferencia.matricula.etapaAtual !== undefined && $scope.transferencia.matricula.etapaAtual !== null) {
                    if ($scope.etapa.ordem > $scope.transferencia.matricula.etapaAtual.ordem) {
                        var promise = Servidor.buscar('disciplinas', {etapa: response.data.id});
                        promise.then(function(response) {
                            var disciplinas = response.data;
                            disciplinas.forEach(function(d) {
                                var promise = Servidor.buscarUm('disciplinas', d.id);
                                promise.then(function(response) {
                                    $scope.disciplinas.push(response.data);
                                });
                            });
                        });
                    }
                } else {
                    if ($scope.contadorDisciplinaMov) {
                        $scope.contadorDisciplinaMov = false;
                        var promise = Servidor.buscar('disciplinas', {etapa: response.data.id});
                        promise.then(function(response) {
                            var disciplinas = response.data;
                            disciplinas.forEach(function(d) {
                                var promise = Servidor.buscarUm('disciplinas', d.id);
                                promise.then(function(response) {
                                    $scope.disciplinas.push(response.data);
                                });
                            });
                        });
                    }
                }
            });
        };

        $scope.verificaTransferencia = function () {
            if($scope.enturmar){
                if($scope.turma.id){
                    $scope.permissao = true;
                    $scope.transferir();
                }else{
                    Servidor.customToast('Verifique os campos para enturmar o aluno');
                }
            }else{
                $scope.permissao = true;
                $scope.transferir();
            }
        };

        $scope.prepararAceitar = function(transferencia) {
            var promise = Servidor.buscar('enturmacoes', {matricula: transferencia.matricula.id, encerrado: 0});
            promise.then(function(response) {
                $scope.enturmacao = response.data[0];
                $scope.transferencia = transferencia;
                $scope.buscarEtapas();
                $scope.aceitandoTransferencia = true;
                $scope.turma.id = null;
                $scope.disciplinas = [];
                $scope.turmaModal = null;
                $('#aceitar-transferencia').openModal();
                /*var promise = Servidor.buscar('disciplinas-cursadas', {matricula: transferencia.matricula.id, status: 'CURSANDO'});
                promise.then(function(response) {
                    $scope.cursadas = response.data;
                    $scope.cursadas.forEach(function(c) {
                        c.medias = [];
                        var promise = Servidor.buscar('medias', {disciplinaCursada: c.id});
                        promise.then(function(response) {
                            response.data.forEach(function(m) {
                                if (m.valor) {
                                    c.medias.push(m);
                                }
                            });
                        });
                    });
                    //$('#aceitar-transferencia').modal('open');
                    $('#aceitar-transferencia').openModal();
                    //$('#selectTurmaTransferencia').material_select();
                });*/
            });
        };
        
        $scope.etapaModal = null;
        $scope.trocaEtapaModal = function (etapa) {
            $scope.buscarTurmas(etapa.id); $scope.selecionarEtapa(etapa.id);
        };
        
        $scope.turmaModal = null;
        $scope.trocaTurmaModal = function (turmaId) {
            $scope.turmaModal = turmaId;
            $scope.buscarDisciplinasOfertadasDestino(turmaId);
            $scope.turma.id = turmaId;
        };

        $scope.inicializarControladorSelect = function() {
            $scope.buscarTurmas($scope.etapas[0].id); $scope.selecionarEtapa($scope.etapas[0].id);
            /* CONTROLADORES DOS SELECTS*/
            var controlaSelect = $scope.etapas.length; var index = 1;
            $('.arrow_up').click(function (){
                if (index < controlaSelect) {
                    var id = $(this).attr('id'); var altura = $('.'+id).css('margin-top');
                    altura = parseInt(altura.replace('px','')); var novaAltura = altura - 32;
                    $('.'+id).css('margin-top',novaAltura+'px'); index++;
                    var etapa = JSON.parse($('.movimentacaoCursoSelect li:nth-child(' + index + ')').attr('data-value'));
                    $scope.buscarTurmas(etapa.id); $scope.selecionarEtapa(etapa.id);
                }
            });

            $('.arrow_down').click(function (){
                if (index > 1) {
                    var id = $(this).attr('id'); var altura = $('.'+id).css('margin-top');
                    altura = parseInt(altura.replace('px','')); var novaAltura = altura + 32;
                    $('.'+id).css('margin-top',novaAltura+'px'); index--;
                    var etapa = JSON.parse($('.movimentacaoCursoSelect li:nth-child(' + index + ')').attr('data-value'));
                    $scope.buscarTurmas(etapa.id); $scope.selecionarEtapa(etapa.id);
                }
            });

            var controlaSelectTurma = 1; var indexTurma = 1;
            $('.turma_arrow_up').click(function (){
                controlaSelectTurma = $scope.turmas.length;
                if (indexTurma < controlaSelectTurma) {
                    var id = $(this).attr('id'); var altura = $('.'+id).css('margin-top');
                    altura = parseInt(altura.replace('px','')); var novaAltura = altura - 32;
                    $('.'+id).css('margin-top',novaAltura+'px'); indexTurma++;
                    var idTurma = parseInt($('.movimentacaoTurmaSelect li:nth-child(' + indexTurma + ')').attr('data-value'));
                    $scope.buscarDisciplinasOfertadasDestino(idTurma);
                    $scope.turma.id = idTurma;
                }
            });

            $('.turma_arrow_down').click(function (){
                controlaSelectTurma = $scope.turmas.length;
                if (indexTurma > 1) {
                    var id = $(this).attr('id'); var altura = $('.'+id).css('margin-top');
                    altura = parseInt(altura.replace('px','')); var novaAltura = altura + 32;
                    $('.'+id).css('margin-top',novaAltura+'px'); indexTurma--;
                    var idTurma = parseInt($('.movimentacaoTurmaSelect li:nth-child(' + indexTurma + ')').attr('data-value'));
                    $scope.buscarDisciplinasOfertadasDestino(idTurma);
                    $scope.turma.id = idTurma;
                }
            });
        };

        $scope.desativarDisciplinasCursadas = function() {
//            $scope.cursadas.forEach(function(c) {
//                if (c.status === "CURSANDO") {
//                    c.status = "INCOMPLETO";
//                    Servidor.finalizar(c, 'disciplinas-cursadas', '');
//                }
//            });
        };
        
        $scope.abrirModalTransferenciaLocal = function(matricula) {
            $scope.matricula = matricula;
            //$('#transferencia-local-movimentacoes-modal').modal('open');
            $('#transferencia-local-movimentacoes-modal').openModal();
            $scope.limparTransferencia();
        };
        
        $scope.solicitarTransferirenciaLocal = function(matricula, justificativa) {
            if(justificativa !== undefined && justificativa.length) {
                var transferencia = {                
                    matricula: {id: matricula.id},
                    status: 'ACEITO',
                    justificativa: $scope.transferencia.justificativa,
                    unidadeEnsinoDestino: {id: $scope.unidade.id},
                    unidadeEnsinoOrigem: {id: matricula.unidadeEnsino.id}
                };
                $scope.progresso = true;
                var promise = Servidor.finalizar(transferencia, 'transferencias', 'Transferência');
                promise.then(function(response) {
                    //$('#transferencia-local-movimentacoes-modal').modal('close');
                    $('#transferencia-local-movimentacoes-modal').closeModal();
                    $scope.progresso = false;
                });
            } else {
                return Servidor.customToast('Há campos obrigatórios nao preenchidos.');
            }                
        };

        $scope.transferir = function (transferencia, aceito) {
            var resposta = $scope.transferencia.resposta;
            var etapaAtual = $scope.transferencia.matricula.etapaAtual;
            var promise = Servidor.buscarUm('transferencias', transferencia.id);
            promise.then(function (response) {
                $scope.transferencia = response.data;
                if (aceito) {
                    if ($scope.turmaModal !== null) {
                        $scope.transferencia.status = 'ACEITO';
                        if (!$scope.transferencia.unidadeEnsinoOrigem.id) {
                            $scope.transferencia.unidadeEnsinoOrigem = {id: $scope.matricula.unidadeEnsino.id};
                        }
                        var matricula = $scope.transferencia.matricula;
                        $scope.transferencia.matricula = {id: matricula.id};
                        $scope.transferencia.unidadeEnsinoDestino = {id: $scope.transferencia.unidadeEnsinoDestino.id};
                        $scope.transferencia.unidadeEnsinoOrigem = {id: $scope.transferencia.unidadeEnsinoOrigem.id};
                        var promise = Servidor.finalizar($scope.transferencia, 'transferencias', 'Transferencia');
                        promise.then(function (response) {
                            $scope.liberarVaga(matricula.aluno.id);
                            $scope.transferencias.forEach(function (t) {
                                if (t.id === response.data.id) {
                                    t.status = response.data.status;
                                }
                            });
                            if($scope.turmas.length) {
                                $scope.enturmarAluno();
                            }                        
                        });
                    } else {
                        Servidor.customToast('Selecione uma turma antes de realizar a transferência.');
                    }
                } else {
                    $scope.transferencia.status = 'RECUSADO';
                    if (resposta === '') {
                        Servidor.customToast('Justificativa Inválida');
                    } else {
                        $scope.transferencia.resposta = resposta;
                        if (!$scope.transferencia.unidadeEnsinoOrigem.id) {
                            $scope.transferencia.unidadeEnsinoOrigem = { id: $scope.matricula.unidadeEnsino.id };
                        }
                        $scope.transferencia.matricula = {id: $scope.transferencia.matricula.id};
                        $scope.transferencia.unidadeEnsinoDestino = {id: $scope.transferencia.unidadeEnsinoDestino.id};
                        $scope.transferencia.unidadeEnsinoOrigem = {id: $scope.transferencia.unidadeEnsinoOrigem.id};
                        var promise = Servidor.finalizar($scope.transferencia, 'transferencias', 'Transferencia');
                        promise.then(function (response) {
                            $scope.transferencia = response.data;
                            $scope.transferencias.forEach(function (t) {
                                if (t.id === response.data.id) {
                                    t.status = response.data.status;
                                }
                            });
                            //$('#recusar-movimentacao').modal('close');
                            $('#recusar-movimentacao').closeModal();
                        });
                    }
                }
            });
        };

        /*$scope.buscarCursos = function () {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response) {
                $scope.cursos = response.data;
            });
        };*/

        $scope.escondeUnidade = function(origem) {
            if (origem) {
                var id = parseInt($scope.transferencia.unidadeEnsinoOrigem.id);
                $scope.transferencia.unidadeEnsinoDestino.id = null;
                var seletor = '#unidadeDestino';
            } else {
                id = parseInt($scope.transferencia.unidadeEnsinoDestino.id);
                $scope.transferencia.unidadeEnsinoOrigem.id = null;
            }
            var promise = Servidor.buscar('unidades-ensino');
            promise.then(function(response) {
                response.data.forEach(function(unidade, i) {
                    if (unidade.id === id) {
                        response.data.splice(i, 1);
                        if (origem) {
                            $scope.unidadesDestino = response.data;
                        } else {
                            $scope.unidadesOrigem = response.data;
                        }
                        $timeout(function() {
                            $(seletor).material_select('destroy');
                            $(seletor).material_select();
                        }, 250);
                        return true;
                    }
                });
            });
        };

        $scope.verificaCurso = function (id) {
            if($scope.transferencia.id){
                if(id === $scope.transferencia.matricula.curso.id){
                    return true;
                }
            }
        };

        $scope.buscarEtapas = function () {
            $scope.etapa.id = null;
            $scope.turmas = [];
            $scope.turma = {id: null};
            if ($scope.transferencia.matricula.etapaAtual === null || $scope.transferencia.matricula.etapaAtual === undefined) {
                var promiseEtapa = Servidor.buscar('etapas', {curso: $scope.transferencia.matricula.curso.id});
                promiseEtapa.then(function(responseEtapa){
                    var etapas = responseEtapa.data;
                    if ($scope.aceitandoTransferencia) {
                        if($scope.transferencia.matricula.etapaAtual !== undefined && $scope.transferencia.matricula.etapaAtual) {
                            $scope.etapas = []; var contaEtapa = 0;
                            etapas.forEach(function(e) {
                                if(e.ordem >= $scope.transferencia.matricula.etapaAtual.ordem) {
                                    if (contaEtapa === 0) { $scope.etapaModal = e; contaEtapa++; }
                                    $scope.etapas.push(e);
                                }
                            });
                        } else {
                            $scope.etapas = etapas;
                        }
                        $scope.inicializarControladorSelect();
                    } else {
                        $scope.etapas = etapas;
                    }
                });
            } else {
                var promiseEtapa = Servidor.buscarUm('etapas', $scope.transferencia.matricula.etapaAtual.id);
                promiseEtapa.then(function(responseEtapa){
                    var etapa = responseEtapa.data;
                    var promise = Servidor.buscar('etapas', {curso: etapa.curso.id});
                    promise.then(function(response) {
                        var etapas = response.data;
                        if ($scope.aceitandoTransferencia) {
        //                    $scope.etapas.forEach(function(etapa) {
        //                        if (etapa.ordem === $scope.transferencia.matricula.etapaAtual.ordem) {
        //                            $scope.buscarTurmas(etapa.id);
        //                            $scope.selecionarEtapa(etapa.id);
        //                        }
        //                    });
                            if($scope.transferencia.matricula.etapaAtual !== undefined && $scope.transferencia.matricula.etapaAtual) {
                                $scope.etapas = []; var contaEtapa = 0;
                                etapas.forEach(function(e) {
                                    if(e.ordem >= $scope.transferencia.matricula.etapaAtual.ordem) {
                                        if (contaEtapa === 0) { $scope.etapaModal = e; contaEtapa++; }
                                        $scope.etapas.push(e);
                                    }
                                });
                            } else {
                                $scope.etapas = etapas;
                            }
                            $scope.inicializarControladorSelect();
                        } else {
                            $scope.etapas = etapas;
                        }
                    });
                });
            }
        };

        $scope.preparaRecusar = function(transferencia){
          $scope.transferencia = transferencia;
          $('#recusar-movimentacao').openModal();
          //$('#recusar-movimentacao').modal();
        };

        $scope.abrirEnturmacao = function() {
            $scope.turma = {'id': null};
            $scope.etapa = {'id': null};
            $scope.curso = {'id': null};
            $scope.reparaSelect('turmaEnturmacao');
            $scope.reparaSelect('cursoEnturmacao');
            $scope.reparaSelect('etapaEnturmacao');
            if ($scope.aceitar) { $scope.aceitar = false; } else { $scope.aceitar = true; }
        };

        $scope.reiniciarBuscaTransferencia = function() {
            var destino = $scope.transferencia.unidadeEnsinoDestino;
            $scope.transferencia = {
                'justificativa': '',
                'resposta': '',
                'dataAgendamento': null,
                'matricula': {'id': null},
                'unidadeEnsinoDestino': {'id': null},
                'unidadeEnsinoOrigem': {'id': null}
            };
            if(!$scope.isAdmin) {
                $scope.transferencia.unidadeEnsinoDestino = destino;                
            }
            $scope.nomeUnidade = null;
            $scope.buscaAluno = null;
            $scope.reparaSelect('statusTransferencia');
            $scope.reparaSelect('unidadeDestino');
        };

        $scope.validarBusca = function () {
            if($scope.isAdmin) {
                if($scope.transferencia.unidadeEnsinoDestino.id || $scope.transferencia.status || $scope.buscaAluno){
                    $scope.buscarTransferencias();
                }else{
                    Servidor.customToast('Escolha pelo menos uma das opções de busca.');
                }
            } else {
                $scope.buscarTransferenciasPorTipoConsulta($scope.tipoConsulta);
            }
        };

        $scope.buscarTransferenciasPorTipoConsulta = function(tipo) {
            $scope.transferencias = [];
            //var unidade = sessionStorage.getItem('unidade');
            switch(tipo) {
                case 'EFETUADAS':
                    buscarTransferencias($scope.unidade, null);
                break;
                case 'RECEBIDAS':
                    buscarTransferencias(null, $scope.unidade);
                break;
                default:
                    buscarTransferencias($scope.unidade, null);
                    buscarTransferencias(null, $scope.unidade);
                break;
            }
        };

        function buscarTransferencias (origem, destino) {
            if (origem === null) { origem = {id:null}; } if (destino === null) { destino = {id:null}; }
            var promise = Servidor.buscar('transferencias', {unidadeEnsinoOrigem: origem.id, unidadeEnsinoDestino: destino.id, status: $scope.transferencia.status, matricula_aluno_nome: $scope.buscaAluno});
            promise.then(function(response) {
                if(!response.data.length) {                    
                    return Servidor.customToast('Não há transferências ' + ((origem) ? 'efetuadas' : 'recebidas'));
                } else {
                    setTimeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                }
                response.data.forEach(function(t) {
                    t.matricula.etapaAtual = $scope.buscarEtapaAtual(t.matricula);
                    $scope.transferencias.push(t);
                });
            });
        };

        $scope.buscarTransferencias = function () {
            var params = {
                'matricula_aluno_nome': $scope.buscaAluno,
                'status': $scope.transferencia.status
            };
            var endereco = 'transferencias';
            if($scope.transferencia.unidadeEnsinoOrigem !== undefined && $scope.transferencia.unidadeEnsinoOrigem !== null){
                params.unidadeEnsinoOrigem = $scope.transferencia.unidadeEnsinoOrigem.id;
            }else{
                params.unidadeEnsinoOrigem = null;
            }
            if($scope.transferencia.unidadeEnsinoDestino.id !== undefined && $scope.transferencia.unidadeEnsinoDestino.id !== null){
                params.unidadeEnsinoDestino = $scope.transferencia.unidadeEnsinoDestino.id;
            }else{
                params.unidadeEnsinoDestino = null;
            }
            if (!$scope.isAdmin) {
                params.unidadeEnsinoDestino = sessionStorage.getItem('unidade');
            }
            var promise = Servidor.buscar(endereco, params);
            promise.then(function (response) {
                $scope.transferencias = response.data;
                if ($scope.transferencias.length === 0) {
                    Servidor.customToast('Não há transferencias.');
                    return $scope.reiniciarBusca();
                }
                /*$scope.transferencias.forEach(function(t) {
                    var promise = Servidor.buscar('disciplinas-cursadas', {matricula: t.matricula.id, encerrado:0});
                    promise.then(function(response) {
                        if(response.data.length) {
                            var promise = Servidor.buscarUm('disciplinas', response.data[0].disciplina.id);
                            promise.then(function(response) {
                                t.matricula.etapaAtual = response.data.etapa;
                            });
                        } else {
                            t.matricula.etapaAtual = {id:null, nomeExibicao: ''};
                        }
                    });
                });*/
                $('.tooltipped').tooltip('remove');
                $timeout(function () {
                    $('.tooltipped').tooltip({delay: 50});
                    /*Inicializando controles via Jquery Mobile */
                    if ($(window).width() < 993) {
                        $(".swipeable").on("swiperight", function () {
                            $('.swipeable').removeClass('move-right');
                            $(this).addClass('move-right');
                        });
                        $(".swipeable").on("swipeleft", function () {
                            $('.swipeable').removeClass('move-right');
                        });
                    }
                    $timeout(function () {
                        Servidor.entradaSequencialIn('.card-result', $scope.transferencias.length);
                    }, 150);
                }, 500);
            });
        };

        $scope.reparaSelect = function (id) {
            $timeout(function () {
                $('#' + id).material_select('destroy');
                $('#' + id).material_select();
            }, 50);
        };

        $scope.fecharFormularioMovimentacoes = function () {
//            $scope.cursadas = [];
//            $scope.ofertadas = [];
//            $scope.transferencias = [];
//            $scope.reiniciarBusca();
//            $scope.transferencia.status = '';
//            $scope.turma = {'id': null};
//            $scope.etapa = {'id': null};
//            $scope.curso = {'id': null};
//            $scope.reparaSelect('turmaEnturmacao');
//            $scope.reparaSelect('cursoEnturmacao');
//            $scope.reparaSelect('etapaEnturmacao');
            $scope.aceitar = true;
            $scope.editando = false;
            $scope.progresso = false;
            $scope.detalhesMovimentacoes =  false;
            $scope.limparDesligamento();
            $scope.limparMatricula();
//            $scope.mostraListaMovimentacoes = false;
            $scope.limparTransferencia();
        };

        $scope.carregarTransferencia = function (transferencia) {
            $scope.detalhesTransferencia = true;
            $('.collapsible').collapsible({accordion: false});
            var promise = Servidor.buscarUm('transferencias', transferencia.id);
            promise.then(function (response) {
                $scope.transferencia = response.data;
                var promise = Servidor.buscar('enturmacoes', {matricula: transferencia.matricula.id, encerrado: 0});
                promise.then(function(response) {
                    if (response.data.length) {
                        $scope.transferencia.matricula.etapaAtual = response.data[0].turma.etapa;
                        $scope.transferencia.matricula.turma = response.data[0].turma;
                    } else {

                    }
                });
            });
        };

        $scope.trocarTab = function (tab) {
            var unidade = parseInt(sessionStorage.getItem('unidade'));
            switch (tab) {
                case 'historico':
                    $scope.mostraListaMovimentacoes = false;
                    $scope.transferencia.status = 'PENDENTE';
                    if(!$scope.isAdmin) {
                        $scope.tipoFiltroHistorico = 'RECEBIDAS';
                        //$scope.buscarTransferenciasPorTipoConsulta('RECEBIDAS');
                    } else {
                        //$scope.validarBusca();
                    }
                break;
                case 'movimentar':
                    if (!$scope.isAdmin && !$scope.matriculaMovimentacoes.unidadeEnsino) {
                        $scope.matriculaMovimentacoes.unidadeEnsino = unidade;
                        $scope.unidades.forEach(function(u) {
                            if(u.id === unidade.id) {
                                $scope.nomeUnidadeBusca = u.nomeCompleto;                                
                                $timeout(function(){ Servidor.verificaLabels(); }, 50);
                            }
                        });
                    }                    
                break;
            }
        };

        $scope.buscarEtapaAtual = function(matricula) {
            var promise = Servidor.buscar('disciplinas-cursadas', {matricula: matricula.id, encerrado: 0});
            promise.then(function(response) {
                if(!response.data.length) { return {}; }
                var promise = Servidor.buscarUm('disciplinas', response.data[0].disciplina.id);
                promise.then(function(response) {
                    matricula.etapaAtual = response.data.etapa;
                });
            });
        };

        /* -------------- Movimentações -----------------*/

        $scope.dataAtual = function(){
           var data = new Date();
           var dia = data.getDate();
           var mes = data.getMonth() + 1;
           var ano = data.getFullYear();
           var hora = data.getHours();
           var minutos = data.getMinutes();
           var segundos = data.getSeconds();
           if (mes < 10){mes = '0' + (data.getMonth() + 1);}
           if (dia < 10){dia = '0' + data.getDate();}
           $scope.transferencia.dataAgendamento = [dia, mes, ano].join('/');
           $timeout(function(){
               $('#diaAtual').prop('disabled', $('#diaAtual').prop('disabled'));
               Servidor.verificaLabels();
           }, 50);
        };

        $scope.historicoMatricula = function(nome){
            $scope.buscaAluno = nome;
            $scope.fecharFormularioMovimentacoes();
            $scope.mostraListaMovimentacoes = false;
            $('ul.tabs').tabs('select_tab', 'historicoMovimentacao');
            $timeout(function(){
                Servidor.verificaLabels();
            }, 50);
        };

        $scope.limparMovimentacao = function () {
            $scope.movimentacao = {
                'enturmacao': {},
                'justificativa': '',
                'turmaDestino': {}
            };
        };

        $scope.avisoMovimentacao = function () {
            if (!$scope.turmas.length) {
                Materialize.toast('Essa etapa só tem uma turma.', 1000);
            };
        };

        $scope.buscarCursos = function () {
            var promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.matriculaMovimentacoes.unidadeEnsino});
            promise.then(function (response) {
                $scope.cursos = response.data;
                $timeout(function() { $('#curso').material_select(); }, 50);
            });
        };
        
        $scope.etapasOfertadas = []; $scope.etapaBusca = null;
        $scope.selecionarCurso = function () {
            var promise = Servidor.buscar('etapas-ofertadas',{curso:$scope.matriculaMovimentacoes.curso, unidadeEnsino: $scope.matriculaMovimentacoes.unidadeEnsino});
            promise.then(function(response){
                $scope.etapasOfertadas = response.data; 
                $timeout(function(){$('#etapas-ofertadas').material_select('destroy'); $('#etapas-ofertadas').material_select();},100);
            });
        };

        $scope.carregar = function (matricula, opcao, indice) {
            $scope.detalhesMovimentacoes = true;
            $scope.mostraListaMovimentacoes =  true;
            if (matricula) {
                var promise = Servidor.buscarUm('matriculas', matricula.id);
                promise.then(function(response) {
                    $scope.matricula = response.data;
                    promise = Servidor.buscarUm('pessoas', response.data.aluno.id);
                    promise.then(function(response) {
                        $scope.matricula.aluno = response.data;
                        $scope.transferencia.unidadeEnsinoOrigem = { id: $scope.matricula.unidadeEnsino.id };
                        $scope.transferencia.unidadeEnsinoDestino.id = null;
                        $timeout(function () {
                            Servidor.verificaLabels();
                            $scope.calendario();
                            $scope.desligamento.motivo = null;
                            $('#motivo, #unidade').material_select('');
                            $(".date").mask('00/00/0009');
                        }, 200);
                        if (!opcao) {
                            opcao = 'Transferência';
                        }
                        switch (opcao) {
                            case 'Reclassificação':
                                $scope.aceitandoTransferencia = false;
                                var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'emAndamento': 1});
                                promise.then(function (response) {
                                    if (!response.data.length) {
                                        Materialize.toast("Este aluno não possui nenhuma enturmação.", 2500);
                                        $scope.fecharFormulario();
                                        $scope.opcaoEnvio = '';
                                        $scope.editando = false;
                                    } else {
                                        $scope.enturmacao = response.data[0];
                                        $scope.opcaoEnvio = 'Reclassificação';
                                        $timeout(function(){
                                            $('#turma').material_select('destroy');
                                            $('#turma').material_select();
                                            $scope.editando = true;
                                        }, 100);
                                        if ($scope.enturmacao !== undefined) {
                                            $scope.buscarTurmas($scope.enturmacao.turma.etapa.id);
                                            $scope.buscarDisciplinasCursadas(matricula, $scope.enturmacao);
                                            $scope.opcaoEnvio = 'Reclassificação';
                                        }
                                    }
                                });
                            break
                            case 'Etapa':
                                $scope.aceitandoTransferencia = false;
                                $scope.matriculaEmUso = matricula;
                                var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'emAndamento': 1 });
                                promise.then(function(response){ 
                                    $scope.etapas = []; $scope.turmasEnturmacao = []; $scope.etapaTurma = {id:null}; $scope.turmaNova = {id:null};
                                    if (response.data.length === 1) { 
                                        $scope.alterarEtapa = true; $scope.temEnturmacao = true;
                                        $scope.prepararRemoverEnturmacao(response.data[0].id); 
                                    } else if (response.data.length > 1) { 
                                        Servidor.customToast("Há mais de uma enturmação para a mesma matrícula na mesma unidade, favor verificar.");
                                    } else {
                                        $scope.alterarEtapa = true; $scope.temEnturmacao = false;
                                        $scope.buscarEtapasTurma(matricula.curso.id);
                                    }
                                });
                            break;
                            case 'Movimentação':
                                $scope.aceitandoTransferencia = false;
                                var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'emAndamento': 1});
                                promise.then(function (response) {
                                    /*if (!response.data.length) {
                                        Materialize.toast("Este aluno não possui nenhuma enturmação.", 2500);
                                        $scope.fecharFormulario();
                                        $scope.opcaoEnvio = '';
                                        $scope.editando = false;
                                    } else {*/
                                        if (response.data.length > 0) { $scope.enturmacao = response.data[0]; }
                                        $scope.opcaoEnvio = 'Movimentação';
                                        $timeout(function(){
                                            $('#turma').material_select('destroy');
                                            $('#turma').material_select();
                                            $scope.editando = true;
                                            if ($scope.enturmacao !== undefined) {
                                                $scope.buscarTurmas($scope.enturmacao.turma.etapa.id);
                                                //$scope.buscarDisciplinasCursadas(matricula, $scope.enturmacao);
                                                $scope.opcaoEnvio = 'Movimentação';
                                            }
                                        }, 100);       
                                    //}
                                });
                            break
                            case 'Transferência':
                                $scope.opcaoEnvio = 'Transferência';
                                $scope.transferencia.justificativa = '';
                                $scope.transferencia.dataAgendamento = '';
                                
                                $timeout(function() {
                                    $('#unidadeDestinoAutoComplete').dropdown({
                                        inDuration: 300,
                                        outDuration: 225,
                                        constrain_width: true,
                                        hover: false,
                                        gutter: 45,
                                        belowOrigin: true,
                                        alignment: 'left'
                                    });
                                    $scope.editando = true;
                                }, 250);
                            break
                            case 'Desligamento':
                                $scope.opcaoEnvio = 'Desligamento';
                                $scope.matricula = matricula;
                                $scope.editando = true;
                            break
                            case 'Retorno':
                                $scope.opcaoEnvio = 'Retorno';
                                $scope.editando = true;
                                $scope.retornoMatricula(matricula,indice);
                            break;
                        }
                    });
                });
            }
        };

        $scope.reparaSelect = function (id) {
            $timeout(function () {
                $('#' + id).material_select('destroy');
                $('#' + id).material_select();
            }, 1);
        };

        $scope.escondeUnidade = function () {
            for (var i = 0; i < $scope.unidadesDestino.length; i++) {
                if ($scope.unidade.id === $scope.unidadesDestino[i].id) {
                    $scope.unidadesDestino.splice(i, 1);
                }
            }
            $timeout(function () {
                $('#turma').material_select('destroy');
                $('#turma').material_select();
            }, 150);
        };

        var verificaDisciplinasCompativeis = function(ofertadas, cursadas) {
            var compativeis = 0;
            cursadas.forEach(function(c) {
                ofertadas.forEach(function(o) {
                    if (o.disciplina.id === c.disciplina.id) {
                        compativeis++;
                    }
                });
            });
            return compativeis === cursadas.length;
        };

        $scope.turmasCompativeis = function (turmas) {
            $scope.requisicoes = 0;
            $scope.turmas = [];
            $scope.turma.id = null;
            turmas.forEach(function(turma, idx, turmasArr) {
                $scope.requisicoes++;
                var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                promise.then(function(response) {
                    if (verificaDisciplinasCompativeis(response.data, $scope.cursadas)) {
                        $scope.turmas.push(turma);
                    }
                    if (--$scope.requisicoes === 0) {
                        if ($scope.turmas.length) {
                            $timeout(function(){
                                $scope.turma.id = $scope.turmas[0].id;
                                if ($scope.aceitandoTransferencia) {
                                    $('#selectTurmaTransferencia').material_select();
                                } else {
                                    $scope.permissao = true;
                                    $('#turma').material_select();
                                }
                            }, 200);
                        } else {
                            $timeout(function() {
                                $('#selectTurmaTransferencia').material_select();
                            }, 200);
                            Materialize.toast('Não há turmas compatíveis para realizar a movimentação de ' + $scope.enturmacao.matricula.aluno.nome.split(' ')[0] + '.', 5000);
                        }
                    }
                });
            });
        };

        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.buscarTurmas = function (etapaId) {
            var unidade = null;
            if ($scope.opcaoEnvio === 'Movimentação') {
                if ($scope.enturmacao === undefined) { return; }
                unidade = $scope.enturmacao.matricula.unidadeEnsino.id;
            } else if ($scope.opcaoEnvio === 'Reclassificação') {
                unidade = $scope.enturmacao.matricula.unidadeEnsino.id;
            } else {
                unidade = $scope.transferencia.unidadeEnsinoDestino.id;
            }
            if ($scope.opcaoEnvio === 'Reclassificação') {
                $scope.mostraProgresso();
                var promise = null; var promiseEtapa = Servidor.buscarUm('etapas',etapaId);
                promiseEtapa.then(function(response){
                    var ordem = parseInt(response.data.ordem);
                    promise = Servidor.buscar('turmas', {'etapa_ordem': ordem+1, 'curso': $scope.curso.id, 'unidadeEnsino': unidade});
                    promise.then(function (response) {
                        if (response.data.length > 0) {
                            $scope.turmas = []; $scope.turmas = response.data; $scope.turma.id = null;
                            var promise2 = Servidor.buscar('turmas', {'etapa_ordem': ordem+2, 'curso': $scope.curso.id, 'unidadeEnsino': unidade});
                            promise2.then(function(response){
                                if (response.data.length > 0) {
                                    $scope.turmas = $scope.turmas.concat(response.data);
                                    $timeout(function() { $('#selectTurmaTransferencia').material_select(); $('#turma').material_select(); }, 200);
                                } else {
                                    $timeout(function() { $('#selectTurmaTransferencia').material_select(); $('#turma').material_select(); }, 200);
                                }
                                
                            });
                        } else {
                            Materialize.toast('Nenhuma turma foi encontrada.', 2000);
                        }
                        $scope.fechaProgresso();
                    });
                });
            } else {
                var promise = Servidor.buscar('turmas', {'etapa': etapaId, 'curso': $scope.curso.id, 'unidadeEnsino': unidade});
                promise.then(function (response) {
                    if (response.data.length === 0) {
                        if ($scope.aceitandoTransferencia) {
                            $scope.turmas = [];
                            $scope.turma.id = null;
                            $timeout(function() {
                                $('#selectTurmaTransferencia').material_select();
                            }, 200);
                        }
                        Materialize.toast('Nenhuma turma foi encontrada.', 2000);
                    } else {
                        $scope.turmas = response.data;
                        $timeout(function(){ $('#turma').material_select('destroy'); $('#turma').material_select(); },500);
                        //$scope.turmasCompativeis(response.data);
                    }
                });
            }
        };

        $scope.$watch('turma.id', function(query) {
            if ($scope.turma.id !== null) { $scope.carregarDisciplinas(); }
        });

        $scope.carregarDisciplinas = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('disciplinas-ofertadas',{turma: $scope.turma.id});
            promise.then(function(response){
                $scope.disciplinasNotas = response.data; $scope.notas = [];
                $timeout(function(){
                    for (var i=0; i<$scope.disciplinasNotas.length; i++) { $scope.notas.push({disciplina: {id: $scope.disciplinasNotas[i].disciplina.id}, valor: null}); }
                    var id = null; $('.edit').click(function(){ id = $(this).attr('data-index'); $(this).hide(); $('.input'+id).show(); });
                    $('.save').click(function(){ id = $(this).attr('data-index'); $('.input'+id).hide(); $('.e'+id).show(); }); $scope.fechaProgresso();
                },500);
            });
        };
        
        $scope.reclassificar = function () {
            var notas = []; $scope.mostraProgresso();
            for (var i=0; i<$scope.notas.length; i++) {
                if ($scope.notas[i].valor !== null && $scope.notas[i].valor !== undefined && $scope.notas[i].valor !== "" && $scope.notas[i].valor !== "0" && $scope.notas[i].valor.indexOf("-") === -1) { 
                    $scope.notas[i].valor = $scope.notas[i].valor.replace(",","."); $scope.notas[i].valor = parseFloat($scope.notas[i].valor); notas.push($scope.notas[i]);
                }
                if (i === $scope.notas.length-1 && notas.length > 0) {
                    var reclassificacao = {enturmacaoOrigem: $scope.enturmacao.plain(), turmaDestino: {id: parseInt($scope.turma.id)}, notas: notas, matricula: $scope.matricula.plain()};
                    var retorno = Servidor.finalizar(reclassificacao, 'reclassificacoes', 'Reclassificação');
                    retorno.then(function(){ $scope.fecharFormulario(); $scope.disciplinasNotas = []; $scope.notas = []; $scope.fechaProgresso(); $scope.buscarMatriculas($scope.matriculaMovimentacoes, '', 'botao'); });
                } else if (i === $scope.notas.length-1) {
                    Servidor.customToast('Não é permitido reclassificar sem nenhuma nota informada.'); $scope.fechaProgresso();
                }
            }
        };

        $scope.buscarDisciplinasOfertadas = function (id) {
            var cont = 0;
            var promise = Servidor.buscarUm('turmas', id);
            promise.then(function (response) {
                $scope.turma = response.data;
                $scope.turmaDestino.id = parseInt(id);
                var promise = Servidor.buscar('turmas/' + id + '/disciplinas-ofertadas');
                promise.then(function (response) {
                    $scope.ofertadas = response.data;
                    if ($scope.ofertadas.length > 0) {
                        $timeout(function () {
                            for (var i = 0; i < $scope.ofertadas.length; i++) {
                                for (var j = 0; j < $scope.cursadas.length; j++) {
                                    if ($scope.ofertadas[i].disciplina.id === $scope.cursadas[j].disciplina.id) {
                                        cont++;
                                        $('#ofer' + $scope.ofertadas[i].id).prop('checked', false);
                                        j = $scope.cursadas.length;
                                    } else {
                                        $('#ofer' + $scope.ofertadas[i].id).prop('checked', true);
                                    }
                                }
                            }
                            if (cont === $scope.cursadas.length) {
                                $scope.permissao = true;
                                $scope.erro = '';
                            } else {
                                $scope.erro = 'Turma selecionada não possui o mínimo de disciplinas necessárias.';
                            }
                        }, 250);
                    } else {
                        Materialize.toast('Esta turma não possui disciplinas!', 2500);
                        $scope.permissao = false;
                    }
                });
            });
        };

        $scope.habilitada = function (id) {
            if ($('#ofer' + id)[0].checked === true) {
                $('#ofer' + id)[0].checked = false;
            } else {
                $('#ofer' + id)[0].checked = true;
            }
        };

        $scope.buscarDisciplinasCursadas = function (matricula, enturmacao) {
            if(!enturmacao){
                enturmacao = {turma:{id: null} };
            };
            var promise = Servidor.buscar('disciplinas-cursadas', {'matricula': matricula.id, 'turma': enturmacao.turma.id, status: 'CURSANDO'});
            promise.then(function (response) {
                $scope.cursadas = response.data;
                Servidor.verificaLabels();
            });
        };

        $scope.buscarDisciplinasOfertadasDestino = function(turmaId) {
            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: turmaId});
            promise.then(function(response) {
                $scope.cursadas.forEach(function(c) {
                    response.data.forEach(function(o) {
                        if (c.disciplina.id === o.disciplina.id) {
                            c.disciplinaOfertada = o;
                        }
                    });
                });
            });
        };

        $scope.transferirMovimentacao = function (id) {
            var idDestino = parseInt(id);
            $scope.erro = '';
            var promise = Servidor.buscar('transferencias', {status:'PENDENTE', matricula:$scope.matricula.id});
            promise.then(function(response){
               if(response.data.length){
                   $scope.erro = 'Aluno ja possui uma transferencia pendente';
               }
               if ($scope.transferencia.id) {
                    if (!$scope.aceitar) {
                        if (!$scope.transferencia.resposta) {
                            $scope.erro = 'Preencha os campos obrigatórios.';
                        } else {
                            $scope.transferencia.status = 'RECUSADO';
                            $scope.erro = '';
                        }
                    } else {
                        $scope.transferencia.status = 'ACEITO';
                    }
                } else {
                    $scope.transferencia.status = 'PENDENTE';
                    if ($scope.transferencia.dataAgendamento !== undefined && $scope.transferencia.dataAgendamento) {
                        if (!dateTime.validarDataAgendamento($scope.transferencia.dataAgendamento)) {
                            $scope.erro = 'Data de agendamento inválida.';
                        }
                    }
                    if(!$scope.transferencia.justificativa){
                        $scope.erro = 'Justificativa invalida';
                    }
                    $scope.transferencia.matricula = $scope.matricula;
                }
                if (!$scope.erro) {
                    var transferencia = $scope.transferencia;
                    if(transferencia.dataAgendamento === undefined || !transferencia.dataAgendamento) {
                        transferencia.dataAgendamento = new Date().toJSON().split('T')[0];
                    } else {
                        transferencia.dataAgendamento = dateTime.converterDataServidor($scope.transferencia.dataAgendamento);
                    }
                    transferencia.matricula = { 'id': $scope.transferencia.matricula.id };
                    transferencia.unidadeEnsinoDestino = { 'id': $scope.transferencia.unidadeEnsinoDestino.id };
                    transferencia.unidadeEnsinoOrigem = { 'id': $scope.transferencia.unidadeEnsinoOrigem.id };
                    if($scope.matricula.unidadeEnsino.id === idDestino){
                        Servidor.customToast("Você não pode transferir um aluno para a mesma escola!");
                        $scope.transferencia.dataAgendamento = null;
                        $('#diaAtual').prop('checked', false);
                    } else{
                        var promise = Servidor.finalizar(transferencia, 'transferencias', 'Transferência');
                        promise.then(function (response) {
                            if ($scope.transferencia.status === 'ACEITO') {

                            } else {
                                $scope.fecharFormularioMovimentacoes();
                            }
                        });
                    }
                } else {
                    $scope.transferencia.dataAgendamento = '';
                    Materialize.toast($scope.erro, 3000);
                }
            });
        };

        $scope.desligar = function () {
            if ($scope.desligamento.justificativa === null || $scope.desligamento.justificativa === undefined || $scope.desligamento.justificativa === '') {
                Servidor.customToast('Justificativa é um campo obrigatório.');
            } else {
                $scope.progresso = true;
                $scope.desligamento.matricula = {id: $scope.matricula.id};
                var promise = Servidor.finalizar($scope.desligamento, 'desligamentos', 'Desligamento');
                promise.then(function (response) {
                    if(response.status !== 400) {
                        $scope.liberarVaga($scope.matricula.aluno.id);
                    }
                });
                $scope.fecharFormularioMovimentacoes();
            }
        };

        $scope.liberarVaga = function(pessoaId) {
            /*var promise = Servidor.buscar('solicitacao-vagas', {pessoa: pessoaId});
            promise.then(function(response) {
                var solicitacoes = response.data;                
                $scope.requisicoes = 0;
                if(!solicitacoes.length) {
                    $scope.fecharFormulario();
                }
                solicitacoes.forEach(function(s) {
                    $scope.requisicoes++;
                    if (s.status === "ATIVO") {                        
                        s.status = "ENCERRADO";
                        Servidor.finalizar(s, 'solicitacao-vagas', null);
                        var promise = Servidor.buscar('vagas', {solicitacaoVaga: s.id});
                        promise.then(function(response) {
                            var vagas = response.data;
                            vagas.forEach(function(v) {
                                v.solicitacaoVaga = "";
                                Servidor.finalizar(v, 'vagas', null);
                            });                            
                            if(--$scope.requisicoes === 0) {
                                $scope.fecharFormulario();
                            }
                        });
                    } else {
                        $scope.requisicoes--;
                    }
                    if($scope.requisicoes === 0) {                        
                        $scope.fecharFormulario();
                    }
                });
            });*/
        };

        $scope.movimentar = function () {
            //if ($scope.permissao) {
                $scope.progresso = true;
                $scope.movimentacao.matricula = $scope.enturmacao.matricula;
                $scope.movimentacao.enturmacaoOrigem = $scope.enturmacao;
                $scope.movimentacao.turmaDestino.id = parseInt($scope.turma.id);
                var promise = Servidor.finalizar($scope.movimentacao, 'movimentacoes-turma', 'Movimentação');
                promise.then(function(response) {
                    $scope.fecharFormulario();
//                    $scope.liberarVaga($scope.movimentacao.matricula.aluno.id);                    
                },function(){
                    $scope.fecharFormulario();
                });
            //}
        };

        $scope.fecharFormulario = function () {
            $scope.progresso = false;
            $scope.limparTransferencia();
            $scope.limparMovimentacao();
            $scope.reparaSelect('turmaEnturmacao');
            $scope.reparaSelect('cursoEnturmacao');
            $scope.aceitar = true;
            $scope.editando = false;
            $scope.opcaoEnvio = '';
            $scope.detalhesMovimentacoes =false;
            $scope.detalhesTransferencia = false;
        };

        $scope.formatarData = function () {
            var data = $scope.transferencia.dataAgendamento.split('/');
            data = data[2] + '-' + data[1] + '-' + data[0];
            data = new Date(data).toJSON().split('.')[0];
            return data;
        };

        $scope.matriculaMovimentacoes = {
            'unidadeEnsino': null,
            'aluno_nome': null,
            'curso': null,
            'codigo': null,
            'status': 'CURSANDO'
        };

        // Limpar Buscar na lista Principal
        $scope.reiniciarBusca = function(){
            $timeout(function () {
                $('select').material_select('destroy');
                $('select').material_select();
            }, 100);
            var id = $scope.matriculaMovimentacoes.unidadeEnsino;
            var nome = $scope.nomeUnidadeBusca;
            $scope.matriculaMovimentacoes = {
                'unidadeEnsino': null,
                'curso': null,
                'aluno_nome': null,
                'codigo': null
            };
            $scope.nomeUnidadeBusca = '';
            if(!$scope.isAdmin) {
                $scope.matriculaMovimentacoes.unidadeEnsino = id;
                $scope.nomeUnidadeBusca = nome;
                $timeout(function(){ Servidor.verificaLabels(); }, 50);
            }
        };
        
        $scope.buscarMatriculas = function(matricula, pagina, origem) {            
            if(pagina === $scope.paginaAtual) { return; }
            $scope.mostraListaMovimentacoes = true;
            //var unidade = ($scope.isAdmin) ? $scope.matriculaMovimentacoes.unidadeEnsino : $scope.unidade.id;
            $scope.progresso = true;
            /*if(!$scope.isAdmin && (matricula.aluno_nome !== undefined && matricula.aluno_nome !== null) || (matricula.codigo !== undefined && matricula.codigo !== null)) {
                unidade = null;
            }*/
            if (matricula.aluno_nome === '') { matricula.aluno_nome = null; }
            var promise = Servidor.buscar('matriculas', {
                unidadeEnsino: $scope.matriculaMovimentacoes.unidadeEnsino,
                aluno_nome: matricula.aluno_nome,
                curso: matricula.curso,
                codigo: matricula.codigo,
                status: matricula.status,
                etapa: $scope.etapaBusca
            });
            promise.then(function(response) {
                var matriculas = response.data;
                if(matriculas.length) {
                    var promise = Servidor.buscar('transferencias', {status: 'PENDENTE', unidadeEnsinoOrigem: $scope.matriculaMovimentacoes.unidadeEnsino});
                    promise.then(function(response) {
                        var transferencias = response.data;
                        matriculas.forEach(function(matricula, i) {
                            transferencias.forEach(function(transferencia, j) {
                                if(matricula.id === transferencia.matricula.id) {
                                    matriculas.splice(i, 1);
                                    transferencias.splice(j, 1);
                                }
                            });
                        });
                        $scope.matriculas = matriculas;
                        if(origem === 'botao') { $scope.quantidadePaginas = Math.ceil(response.data.length / 50); }
                        $timeout(function() { $('.tooltipped').tooltip({delay: 50}); $scope.progresso = false; }, 150);
                    });
                } else {
                    $scope.matriculas = [];
                    $scope.progresso = false;
                    Servidor.customToast('Nenhuma matrícula encontrada.'); 
                }
            });
        };

        $scope.atualizarPagina = function(pagina, subs) {
            if (subs) {
                $scope.paginaAtual = pagina;
            } else {
                if ($scope.paginaAtual + pagina > 0 && $scope.paginaAtual + pagina <= $scope.quantidadePaginas) {
                    $scope.paginaAtual += pagina;
                }
            }
        };

        /* Carrega o calendario */
        $scope.calendario = function () {
            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 15,
                max: 1,
                labelMonthNext: 'PRÓXIMO MÊS',
                labelMonthPrev: 'MÊS ANTERIOR',
                labelMonthSelect: 'SELECIONE UM MÊS',
                labelYearSelect: 'SELECIONE UM ANO',
                monthsFull: ['JANEIRO', 'FEVERIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'],
                monthsShort: ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'],
                weekdaysFull: ['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO'],
                weekdaysShort: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
                weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                today: 'HOJE',
                clear: 'LIMPAR',
                close: 'FECHAR',
                format: 'dd/mm/yyyy'
            });
        };

        // Inicializar todo o controlador
        $scope.inicializar = function () {
            $('.tooltipped').tooltip('remove');            
            $timeout(function () {
                $('.counter').each(function () {$(this).characterCounter();});
                $('.tooltipped').tooltip({delay: 50});
                // Dropdown da instituicao
                $('#unidadeOrigem').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true, // Does not change width of dropdown to that of the activator
                        hover: false, // Activate on hover
                        gutter: 45, // Spacing from edge
                        belowOrigin: true, // Displays dropdown below the button
                        alignment: 'left' // Displays dropdown with edge aligned to the left of button
                    }
                );

                /* Inicializando controles via Jquery Mobile */
                if ($(window).width() < 993) {
                    $(".swipeable").on("swiperight", function () {
                        $('.swipeable').removeClass('move-right');
                        $(this).addClass('move-right');
                    });

                    $(".swipeable").on("swipeleft", function () {
                        $('.swipeable').removeClass('move-right');
                    });
                }
                $('.collapsible').collapsible({ accordion : false });
                Servidor.entradaPagina();
                $('ul.tabs').tabs();
                $timeout(function() {$('ul.tabs').tabs('select_tab', 'movimentacoesBusca');}, 50);                
                $('#movimentacoesBusca').ready(function() {
                    $('#buscaUnidadeMovimentar, #curso, #unidade, #motivo, #statusTransferencia, #unidadeDestino, #tipoFiltroHistorico, #statusMatriculaMovimentacoes').material_select('destroy');
                    $('#buscaUnidadeMovimentar, #curso ,#unidade, #motivo, #statusTransferencia, #unidadeDestino, #tipoFiltroHistorico, #statusMatriculaMovimentacoes').material_select();
                    $('#etapas-ofertadas').material_select('destroy'); $('#etapas-ofertadas').material_select();
                });
            }, 500);
            $timeout(function(){
                $('#statusMatriculaMovimentacoes').material_select('destroy'); $('#statusMatriculaMovimentacoes').material_select();
                $('#unidadeBuscaAutoComplete').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: true,
                    hover: false,
                    gutter: 45,
                    belowOrigin: true,
                    alignment: 'left'
                });
            },800);
        };
        
        $scope.telaHistoricoCompleto = false;
        $scope.carregarHistoricoCompleto = function(matricula) {
            $scope.matricula = matricula;
            var promise = Servidor.buscarUm('matriculas/' + matricula.id + '/movimentacoes');
            promise.then(function(response){
                var movimentacoes = response.data;
                var tiposMovimentacoes = [];
                var tiposDesligamentos = [];
                tiposMovimentacoes['transferencias'] = 'Transferência';
                tiposMovimentacoes['movimentacoes-turma'] = 'Movimentação entre turmas';
                tiposMovimentacoes['desligamentos'] = 'Desligamento';
                tiposMovimentacoes['reclassificacoes'] = 'Reclassificação';
                tiposMovimentacoes['retornos'] = 'Retorno';
                tiposDesligamentos['ABANDONO'] = 'Abandono';
                tiposDesligamentos['TRANSFERENCIA_EXTERNA'] = 'Transferência externa';
                tiposDesligamentos['MUDANCA_DE_CURSO'] = 'Mudança de curso';
                tiposDesligamentos['FALECIMENTO'] = 'Falecimento';
                tiposDesligamentos['CANCELAMENTO'] = "Cancelamento";
                var movimentacoesOrdenadas = [];
                
                Object.keys(tiposMovimentacoes).forEach(function(element,index,array){
                    movimentacoes[element].forEach(function(e,i,a){
                       
                       e.novaOrigem = (e.unidadeEnsinoOrigem !== undefined ? e.unidadeEnsinoOrigem.nomeCompleto : null) 
                               || e.origem 
                               || (e.enturmacaoOrigem !== undefined ? e.enturmacaoOrigem.turma.nomeCompleto : null)
                               || (e.unidadeEnsinoOrigem !== undefined ? e.unidadeEnsinoOrigem.nomeCompleto : null)
                               || '-';
                       e.novoDestino =  (e.unidadeEnsinoDestino !== undefined ? e.unidadeEnsinoDestino.nomeCompleto : null)  
                               || e.destino 
                               || (e.enturmacaoDestino !== undefined ? e.enturmacaoDestino.turma.nomeCompleto : null)
                               || (e.unidadeEnsinoDestino !== undefined ? e.unidadeEnsinoDestino.nomeCompleto : null)
                               || '-';
                       e.dataMovimentacao = moment(e.dataConcretizacao).format('DD/MM/YYYY');
                       e.unix = moment(e.dataConcretizacao,'YYYY-MM-DDTHH:mm:ssZ').unix();
                       e.tipoMovimentacao = (e.motivo !== undefined ? tiposDesligamentos[e.motivo] : null) || tiposMovimentacoes[element];
                       movimentacoesOrdenadas.push(e);
                    });
                });
                $scope.todasMovimentacoes = movimentacoesOrdenadas.sort(function(a,b){
                    return a.unix - b.unix;
                });
                $scope.telaHistoricoCompleto = true;
            });
        };
        
        $scope.sairTelaHistoricoCompleto = function() {
            $scope.todasMovimentacoes = [];
            $scope.telaHistoricoCompleto = false;
        };
        
        $scope.buscarUnidades();
        $scope.inicializar();
    }]);
})();
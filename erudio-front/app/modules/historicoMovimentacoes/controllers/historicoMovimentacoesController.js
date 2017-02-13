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
    var historicoMovimentacoesModule = angular.module('historicoMovimentacoesModule', ['servidorModule', 'historicoMovimentacoesDirectives']);

    historicoMovimentacoesModule.controller('historicoMovimentacoesController', ['$scope', 'Servidor', 'Restangular', '$timeout', 'dateTime', '$templateCache',function ($scope, Servidor, Restangular, $timeout, dateTime, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('MOVIMENTACAO');
        $scope.isAdmin = Servidor.verificaAdmin();

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
            'justificativa': '',
            'resposta': '',
            'dataAgendamento': null,
            'matricula': {'id': null},
            'unidadeEnsinoDestino': {'id': ''},
            'unidadeEnsinoOrigem': {'id': ''}
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
        $scope.nomeUnidade = '';

        $scope.matricula = {
            'id': null,
            'unidadeEnsino': {'id': null},
            'aluno': {},
            'codigo': null,
            'status': ''
        };
        $scope.transferencia = {
            'justificativa': '',
            'resposta': '',
            'dataAgendamento': null,
            'matricula': {'id': null},
            'unidadeEnsinoDestino': {'id': null}
        };
        $scope.desligamento = {
            'matricula': {},
            'justificativa': '',
            'destino': '',
            'motivo': ''
        };
        $scope.movimentacao = {
            'matricula': {},
            'enturmacaoOrigem': {},
            'justificativa': '',
            'turmaDestino': {}
        };

        // Controle de Página
        $scope.editando = false;
        $scope.limparTransferencia = function () {
            $scope.transferencia = {
                'justificativa': '',
                'resposta': '',
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
                'motivo': ''
            };
        };

        $scope.limparMatricula = function () {
            $scope.matricula = {
                'id': null,
                'unidadeEnsino': {'id': null},
                'aluno': {},
                'codigo': null,
                'status': ''
            };
        };

        $scope.permissao = false;
        $scope.editando = false;
        $scope.mostraEnturmacao = false;
        $scope.aceitar = true;
        $scope.enturmar = false;

        $scope.buscarUnidades = function () {
            var promise = Servidor.buscar('unidades-ensino', null);
            promise.then(function (response) {
                $scope.unidadesOrigem = response.data;
                $scope.unidadesDestino = response.data;
                $scope.escondeUnidade();
                $scope.unidades = Servidor.isolarElemento(response.data, sessionStorage.getItem('unidade'));
                $timeout(function() {
                    if(sessionStorage.getItem('unidade')){
                        $scope.transferencia.unidadeEnsinoDestino.id = sessionStorage.getItem('unidade');
                        $scope.buscarTransferencias();
                        $scope.matriculaMovimentacoes.unidadeEnsino.id = sessionStorage.getItem('unidade');
                    }
                    $timeout(function(){
                        $('#unidadeDestino').material_select('destroy');
                        $('#unidadeDestino').material_select();
                        $('.unidade-destino').find('.disabled').hide();
                    }, 50);
                }, 250);
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
                $scope.transferencia.unidadeEnsinoDestino = angular.copy(unidade);
            } else {
                $scope.transferencia.unidadeEnsinoOrigem = unidade;
                $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome;
            }
            $timeout(function(){
              Servidor.verificaLabels();
            },50);
        };

        $scope.buscarDisciplinasCursadas = function (matricula) {
            var promise = Servidor.buscar('disciplinas-cursadas', {'matricula': matricula.id});
            promise.then(function (response) {
                $scope.cursadas = response.data;
            });
        };

        $scope.enturmarAluno = function () {
            var enturmacao = {
                'matricula':{ 'id': $scope.transferencia.matricula.id },
                'turma': {'id': parseInt($scope.turma.id) }
            };
            enturmacao.route = 'enturmacoes';
            var promiseDM = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
            promiseDM.then(function(responseDM){
                var result = Servidor.buscar('vagas', {turma: $scope.enturmacao.turma.id});
                result.then(function (response) {
                    var vagas = response.data;
                    var estaEnturmado = false;
                    var vagaNula = null;
                    for (var i=0; i<vagas.length; i++) {
                        if (vagas[i].enturmacao !== undefined) {
                            if (vagas[i].enturmacao === enturmacao.id) { estaEnturmado = true; }
                        } else {
                            vagaNula = vagas[i];
                        }
                    }
                    if (!estaEnturmado) {
                        vagaNula.enturmacao = enturmacao.id;
                        var merge = Servidor.finalizar(vagaNula, 'vagas', '');
                    }
                });

                var idEnturmacao = responseDM.data.id;
                var promise = Servidor.buscar('matriculas/' + $scope.transferencia.matricula.id + '/disciplinas-cursadas', {'etapa': $scope.etapa.id});
                promise.then(function (response){
                    $scope.disciplinasCursadasAluno = response.data;
                    var cont = 0;
                    $scope.disciplinasCursadasAluno.forEach(function(d){
                        var promiseBusca = Servidor.buscarUm('disciplinas-cursadas', d.id);
                        promiseBusca.then(function(responseBusca){
                            d = responseBusca.data;
                            d.enturmacao.id = idEnturmacao;
                            var promiseD = Servidor.finalizar(d, 'disciplinas-cursadas');
                            promiseD.then(function(responseD){
                                if(cont === $scope.disciplinasCursadasAluno.length){
                                    $scope.fecharFormulario();
                                }
                            });
                        });
                        cont++;
                    });
                });
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

        $scope.transferir = function (transferencia, aceito) {
                var resposta = $scope.transferencia.resposta;
                var promise = Servidor.buscarUm('transferencias', transferencia.id);
                promise.then(function (response) {
                    $scope.transferencia = response.data;
                    if (aceito) {
                        $scope.transferencia.status = 'ACEITO';
                        if (!$scope.transferencia.unidadeEnsinoOrigem.id) {
                            $scope.transferencia.unidadeEnsinoOrigem = {id: $scope.matricula.unidadeEnsino.id};
                        }
                        $scope.transferencia.matricula = {id: $scope.transferencia.matricula.id};
                        $scope.transferencia.unidadeEnsinoDestino = {id: $scope.transferencia.unidadeEnsinoDestino.id};
                        $scope.transferencia.unidadeEnsinoOrigem = {id: $scope.transferencia.unidadeEnsinoOrigem.id};
                        var promise = Servidor.finalizar($scope.transferencia, 'transferencias', 'Transferencia');
                        promise.then(function (response) {

                            if ($scope.enturmar) {
                                $scope.enturmarAluno();
                            } else {
                                $scope.fecharFormulario();
                            }
                            $scope.transferencias.forEach(function (t) {
                                if (t.id === response.data.id) {
                                    t.status = response.data.status;
                                }
                            });
                        });
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
                                if ($scope.enturmar) {
                                    $scope.enturmarAluno();
                                } else {
                                    $scope.fecharFormulario();
                                }
                                $scope.transferencias.forEach(function (t) {
                                    if (t.id === response.data.id) {
                                        t.status = response.data.status;
                                    }
                                });
                                $('#recusar-movimentacao').closeModal();
                            });
                        }
                    }
                });
            };

        $scope.buscarCursos = function () {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response) {
                $scope.cursos = response.data;
            });
        };

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
            if($('#enturmar')[0].checked){
                $scope.enturmar = true;
                var promise = Servidor.buscar('etapas', { 'curso': $scope.transferencia.matricula.curso.id});
                promise.then(function (response) {
                    if (!response.data.length) {
                        Materialize.toast('Nenhuma etapa foi encontrada.', 2000);
                        $scope.etapas = [];
                    } else {
                        $scope.etapas = response.data;
                    }
                    $timeout(function () {
                        $('#etapaEnturmacao').material_select('destroy');
                        $('#etapaEnturmacao').material_select();
                    }, 300);
                });
            }else{
                $scope.enturmar = false;
                $scope.etapas = [];
                $scope.etapa.id = null;
                $scope.turma.id = null;
                $timeout(function () {
                    $('#etapaEnturmacao').material_select('destroy');
                    $('#etapaEnturmacao').material_select();
                }, 100);
            }
        };

        $scope.buscarTurmas = function () {
            $scope.turmas = [];
            var promise = Servidor.buscar('turmas', {'unidadeEnsino': $scope.transferencia.unidadeEnsinoDestino.id, 'etapa': $scope.etapa.id, 'curso': $scope.transferencia.matricula.curso.id});
            promise.then(function (response) {
                if (!response.data.length) {
                    Materialize.toast('Nenhuma turma foi encontrada.', 2000);
                } else {
                    $scope.turmasCompativeis(response.data);
                }
            });
        };

        $scope.turmasCompativeis = function(turmas) {
            var cont = 0;
            turmas.forEach(function(turma, i) {
                var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                promise.then(function(response) {
                    var ofertadas = response.data;
                    var compativeis = 0;
                    ofertadas.forEach(function(ofertada) {
                        $scope.cursadas.forEach(function(cursada) {
                            if (cursada.disciplina.id === ofertada.disciplina.id) { compativeis++; }
                            if (compativeis === $scope.cursadas.length) {
                                compativeis = 0;
                                var promiseE = Servidor.buscar('enturmacoes', {'turma': turma.id, 'encerrado': 0});
                                promiseE.then(function(responseE){
                                    var jaEnturmado = false;
                                    if(!responseE.data.length){
                                        $scope.turmas.push(turma);
                                    }else{
                                        responseE.data.forEach(function(e, indexE){
                                            if(e.matricula.id === $scope.transferencia.matricula.id){
                                                jaEnturmado = true;
                                            }
                                            if(indexE === responseE.data.length-1 && !jaEnturmado){
                                                $scope.turmas.push(turma);
                                            }
                                        });
                                    }
                                    if (cont === turmas.length ) {
                                        if ($scope.turmas.length) {
                                            $timeout(function() {
                                                $('#turmaEnturmacao').material_select('destroy');
                                                $('#turmaEnturmacao').material_select();
                                            }, 150);
                                        } else {
                                            $('#turmaEnturmacao').prop('disabled', true);
                                            Materialize.toast('Não há turmas compatíveis para ' + $scope.transferencia.matricula.aluno.nome.split(' ')[0] + ', nesta etapa.', 3500);
                                        }
                                    }
                                });
                            }
                        });
                    });
                    cont++;
                });
            });
        };

        $scope.preparaRecusar = function(transferencia){
          $scope.transferencia = transferencia;
          $('#recusar-movimentacao').openModal();
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
            $scope.transferencia = {
                'justificativa': '',
                'resposta': '',
                'dataAgendamento': null,
                'matricula': {'id': null},
                'unidadeEnsinoDestino': {'id': ''},
                'unidadeEnsinoOrigem': {'id': ''}
            };
            $scope.nomeUnidade = '';
            $scope.buscaAluno = '';
            $scope.reparaSelect('statusTransferencia');
            $scope.reparaSelect('unidadeDestino');
        };

        $scope.validarBusca = function () {
            if($scope.transferencia.unidadeEnsinoDestino.id || $scope.transferencia.status || $scope.buscaAluno){
                console.log($scope.transferencia.unidadeEnsinoDestino.id);
                $scope.buscarTransferencias();
            }else{
                Servidor.customToast('Escolha pelo menos uma das opções de busca.');
            }
        };

        $scope.buscarTransferencias = function () {
            var params = {
                'nome': $scope.buscaAluno,
                'status': $scope.transferencia.status
            };
            if($scope.buscaAluno !== undefined && $scope.buscaAluno.length > 0) {
                var endereco = 'transferencias-pessoas';
            } else {
                endereco = 'transferencias';
            }
            if($scope.transferencia.unidadeEnsinoOrigem !== undefined){
                params.unidadeEnsinoOrigem = $scope.transferencia.unidadeEnsinoOrigem.id;
            }else{
                params.unidadeEnsinoOrigem = '';
            }
            if($scope.transferencia.unidadeEnsinoDestino.id){
                params.unidadeEnsinoDestino = $scope.transferencia.unidadeEnsinoDestino.id;
            }else{
                params.unidadeEnsinoDestino = '';
            }
            var promise = Servidor.buscar('transferencias', params);
            promise.then(function (response) {
                $scope.transferencias = response.data;
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
                if ($scope.transferencias.length === 0) {
                    Materialize.toast('Não há transferencias.', 2000);
                    $scope.reiniciarBusca();
                }
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
                var promiseM = Servidor.buscarUm('matriculas', $scope.transferencia.matricula.id);
                promiseM.then(function(responseM){
                    $scope.transferencia.matricula = responseM.data;
                });
                if (transferencia.status === 'PENDENTE') {
                    $('#switchTransferencia').show();
                } else {
                    $('#switchTransferencia').hide();
                }
                $scope.buscarDisciplinasCursadas($scope.transferencia.matricula);
                $scope.reparaSelect('turmaEnturmacao');
                $scope.reparaSelect('cursoEnturmacao');
                $scope.reparaSelect('etapaEnturmacao');
            });
        };

        $scope.trocarTab = function (tab) {
            //$scope.transferencias = [];
//            $scope.matriculas = [];
//            $scope.buscaAluno = [];
            //$scope.limparTransferencia();
//            $scope.reiniciarBusca();
            if (tab === 'historico') {
                $scope.mostraListaMovimentacoes = false;
                $scope.transferencia.status = 'PENDENTE';
                if (!$scope.isAdmin) {
                    $scope.transferencia.unidadeEnsinoDestino.id = parseInt(sessionStorage.getItem('unidade'));
                }
                $scope.validarBusca();
            } else {
                $scope.mostraListaMovimentacoes = true;
            }
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
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response) {
                $scope.cursos = response.data;
            });
        };

        $scope.carregar = function (matricula, opcao) {
            $scope.detalhesMovimentacoes = true;
            $scope.mostraListaMovimentacoes =  true;
            if (matricula) {
                var promise = Servidor.buscarUm('matriculas', matricula.id);
                promise.then(function(response) {
                    $scope.matricula = response.data;
                    promise = Servidor.buscarUm('pessoas', response.data.aluno.id);
                    promise.then(function(response) {
                        $scope.matricula.aluno = response.data;
                        $scope.transferencia.unidadeEnsinoDestino.id = null;
                        $timeout(function () {
                            Servidor.verificaLabels();
                            $scope.calendario();
                            $scope.desligamento.motivo = null;
                            $('#motivo, #unidade').material_select('');
                            $(".date").mask('00/00/0009');
                            $scope.editando = true;
                        }, 200);
                        if (!opcao) {
                            opcao = 'Transferência';
                        }
                        switch (opcao) {
                            case 'Movimentação':
                                var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'encerrado': false});
                                promise.then(function (response) {
                                    if (!response.data.length) {
                                        Materialize.toast("Este aluno não possui nenhuma enturmação.", 2500);
                                        $scope.opcaoEnvio = 'Transferência';
                                    } else {
                                        $timeout(function(){
                                            $('#turma').material_select('destroy');
                                            $('#turma').material_select();
                                        }, 100);
                                        $scope.opcaoEnvio = 'Movimentação';
                                        $scope.enturmacao = response.data[0];
                                        $scope.buscarTurmas($scope.enturmacao.turma.etapa.id);
                                        $scope.buscarDisciplinasCursadas(matricula, $scope.enturmacao);
                                    }
                                });
                            break
                            case 'Transferência':
                                $scope.opcaoEnvio = 'Transferência';
                                $timeout(function() {
                                    $('#unidadeDestinoAutoComplete').dropdown({
                                            inDuration: 300,
                                            outDuration: 225,
                                            constrain_width: true,
                                            hover: false,
                                            gutter: 45,
                                            belowOrigin: true,
                                            alignment: 'left'
                                        }
                                    );
                                }, 250);
                            break
                            case 'Desligamento':
                                $scope.opcaoEnvio = 'Desligamento';
                                $scope.matricula = matricula;
                            break
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
                if (sessionStorage.getItem('unidade') === $scope.unidadesDestino[i].id) {
                    $scope.unidadesDestino.splice(i, 1);
                }
            }
            $timeout(function () {
                $('#turma').material_select('destroy');
                $('#turma').material_select();
            }, 150);
        };

        $scope.turmasCompativeis = function (turmas) {
            var posTurma = 0;
            var push;
            turmas.forEach(function (turma) {
                if (turma.id !== $scope.enturmacao.turma.id) {
                    var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                    promise.then(function (response) {
                        posTurma++;
                        var ofertadas = response.data;
                        var compativeis = 0;
                        ofertadas.forEach(function (ofertada) {
                            $scope.cursadas.forEach(function (cursada) {
                                if (cursada.disciplina.id === ofertada.disciplina.id) {
                                    compativeis++;
                                }
                            });
                            if (compativeis === $scope.cursadas.length) {
                                push = true;
                                if ($scope.turmas.length) {
                                    $scope.turmas.forEach(function(t, i) {
                                        if (turma.id === t.id) { push = false; }
                                        if ($scope.turmas.length-1 === i && push) {
                                            $scope.turmas.push(turma);
                                        }
                                    });
                                } else {
                                    $scope.turmas.push(turma);
                                }
                            }
                        });
                        if (turmas.length - 1 === posTurma) {
                            if ($scope.turmas.length) {
                                $scope.opcaoEnvio = 'Movimentação';
                                $timeout(function () {
                                    $scope.permissao = true;
                                    Servidor.verificaLabels();
                                    $('#turma').material_select('destroy');
                                    $('#turma').material_select();
                                }, 300);
                            } else {
                                $('#turma').prop('disabled', true);
                                Materialize.toast('Não há turmas compatíveis para realizar a movimentação de ' + $scope.enturmacao.matricula.aluno.nome.split(' ')[0] + '.', 5000);
                            }
                        }
                    });
                }
            });
        };

        $scope.buscarTurmas = function (etapaId) {
            var promise = Servidor.buscar('turmas', {'etapa': etapaId, 'curso': $scope.curso.id});
            promise.then(function (response) {
                if (response.data.length === 0) {
                    Materialize.toast('Nenhuma turma foi encontrada.', 2000);
                } else {
                    $scope.turmasCompativeis(response.data);
                }
            });
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
            var promise = Servidor.buscar('disciplinas-cursadas', {'matricula': matricula.id, 'turma': enturmacao.turma.id});
            promise.then(function (response) {
                $scope.cursadas = response.data;
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
                if ($scope.transferencia.dataAgendamento && $scope.transferencia.dataAgendamento !== undefined) {
                    if (!dateTime.validarDataAgendamento($scope.transferencia.dataAgendamento)) {
                        $scope.erro = 'Data de agendamento inválida.';
                    }
                } else {
                    $scope.transferencia.dataAgendamento = null;
                }
                if(!$scope.transferencia.justificativa){
                    $scope.erro = 'Justificativa invalida';
                }


                $scope.transferencia.matricula = $scope.matricula;

            }
            if (!$scope.erro) {
                var transferencia = $scope.transferencia;
                if(transferencia.dataAgendamento !== null && transferencia.dataAgendamento !== undefined){
                    transferencia.dataAgendamento = dateTime.converterDataServidor($scope.transferencia.dataAgendamento);
                }
                transferencia.matricula = { 'id': $scope.transferencia.matricula.id };
                transferencia.unidadeEnsinoDestino = { 'id': $scope.transferencia.unidadeEnsinoDestino.id };
                if($scope.matricula.unidadeEnsino.id === idDestino){
                    Servidor.customToast("Você não pode transferir um aluno para a mesma escola!");
                    $scope.transferencia.dataAgendamento = null;
                    $('#diaAtual').prop('checked', false);
                } else{
                    var promise = Servidor.finalizar(transferencia, 'transferencias', 'Transferência');
                    promise.then(function () {
                        $scope.fecharFormularioMovimentacoes();
                    });
                }
            } else {
                Materialize.toast($scope.erro, 3000);
            }
            });

        };

        $scope.desligar = function () {
            $scope.desligamento.matricula.id = $scope.matricula.id;
            var promise = Servidor.finalizar($scope.desligamento, 'desligamentos', 'Desligamento');
            promise.then(function () {

                $scope.fecharFormularioMovimentacoes();
            });
        };

        $scope.movimentar = function () {
            if ($scope.permissao) {
                $scope.movimentacao.matricula = $scope.enturmacao.matricula;
                $scope.movimentacao.enturmacaoOrigem = $scope.enturmacao;
                $scope.movimentacao.turmaDestino.id = parseInt($scope.turma.id);
                var promise = Servidor.finalizar($scope.movimentacao, 'movimentacoes-turma', 'Movimentação');
                promise.then(function(response) {
                    $scope.fecharFormulario();
                });
            }
        };

        $scope.fecharFormulario = function () {
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
            'unidadeEnsino': '',
            'aluno_nome': '',
            'curso': '',
            'codigo': '',
            'status': 'CURSANDO'
        };

        // Limpar Buscar na lista Principal
        $scope.reiniciarBusca = function(){
            $timeout(function () {
                $('select').material_select('destroy');
                $('select').material_select();
            }, 100);
            $scope.matriculaMovimentacoes = {
                'unidadeEnsino': null,
                'curso': null,
                'aluno_nome': '',
                'codigo': ''
            };
        };

        // Realiza a busca de aluno matriculados em uma unidade
        $scope.buscarMatriculas = function (matricula, pagina, origem) {
            $scope.mostraListaMovimentacoes = true;
            if (pagina !== $scope.paginaAtual) {
                if (origem === 'botao') {
                    $scope.matriculas = [];
                }
                if (!pagina) {
                    $scope.paginaAtual = 0;
                    $(".paginasLista0").addClass('active');
                } else {
                    $scope.paginaAtual = pagina;
                }
                if (origem === 'botao' && $scope.qtdPaginas) {
                    for (var i = 1; i <= $scope.qtdPaginas; i++) {
                        $(".paginasLista" + parseInt(i)).remove();
                    }
                }
                if (Servidor.verificaAdmin()) {
                    var unidade = $scope.matriculaMovimentacoes.unidadeEnsino;
                } else {
                    unidade = sessionStorage.getItem('unidade');
                }
                var promise = Servidor.buscar('matriculas', {
                    'page': pagina,
                    'unidadeEnsino': unidade,
                    'aluno_nome': matricula.aluno_nome,
                    'curso': matricula.curso,
                    'codigo': matricula.codigo,
                    'status': 'CURSANDO'
                });
                promise.then(function (response) {
                    $('.tooltipped').tooltip('remove');
                    $timeout(function () {
                        $('.modal-trigger').leanModal();
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
                            Servidor.entradaSequencialIn('.card-result', $scope.matriculas.length);
                        }, 150);
                    }, 500);
                    if (response.data.length === 0) {
                        Materialize.toast('Não há matrículas!', 1000);
                    } else {
                        $scope.matriculas = response.data;
                        if (origem === 'botao') {
                            $scope.qtdPaginas = Math.ceil(response.data.length / 50);
                            for (var i = 1; i < $scope.qtdPaginas; i++) {
                                var a = '<li class="waves-effect paginasLista' + i + '" data-ng-click="alterarPagina(' + parseInt(i) + '); buscarMatriculas(matriculaMovimentacoes, ' + parseInt(i) + ');"><a href="#!">' + parseInt(i + 1) + '</a></li>';
                                $(".paginasLista" + parseInt(i - 1)).after($compile(a)($scope));
                            }
                        }
                        var promise = Servidor.buscar('transferencias', {'status': 'PENDENTE'});
                        promise.then(function (response) {
                            var transferencias = response.data;
                            var matriculas = $scope.matriculas;
                            for (var i = 0; i < matriculas.length; i++) {
                                for (var j = 0; j < transferencias.length; j++) {
                                    if (matriculas[i].id === transferencias[j].matricula.id) {
                                        $('#' + matriculas[i].id).find('#btn-transferir').hide();
                                    }
                                }
                            }
                        });
                    }
                });
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
                $('#buscaUnidade, #curso, #unidade, #motivo, #statusTransferencia, #unidadeDestino').material_select('destroy');
                $('#buscaUnidade, #curso ,#unidade, #motivo, #statusTransferencia, #unidadeDestino').material_select();
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
                $(document).ready(function(){$('ul.tabs').tabs();});
                $(document).ready(function(){$('select').material_select();});
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
            }, 500);
        };
        $scope.buscarCursos();
        $scope.buscarUnidades();
        $scope.inicializar();
    }]);
})();

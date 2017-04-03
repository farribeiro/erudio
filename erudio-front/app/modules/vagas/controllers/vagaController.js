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
    var vagaModule = angular.module('vagaModule', ['vagaDirectives', 'servidorModule']);

    vagaModule.controller('VagaController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', '$compile', 'dateTime', 'makePdf', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, $compile, dateTime, makePdf) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('SOLICITAR_VAGA');
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.role = 'SOLICITAR_VAGA';
        $scope.permissao = true;
        $scope.vaga = { turma:null, enturmacao:null };
        $scope.solicitacaoVaga = { pessoa:{ id: null }, descricao: '', dataSolicitacao: null, status: 'ATIVO' };
        $scope.unidades = [];
        $scope.unidade = {id: null};
        $scope.cursos = [];
        $scope.curso = null;
        $scope.hasCurso = false;
        $scope.etapas = [];
        $scope.etapa = null;
        $scope.turmas = [];
        $scope.turma = null;
        $scope.pessoas = [];
        $scope.pessoa = null;
        $scope.editando = false;
        $scope.nomeAluno = '';
        $scope.criaPessoa = true;
        $scope.statusSolicitacao = 'ATIVO';
        $scope.mostrarCortina = function() { $scope.cortina = true; $scope.progresso = true; };
        $scope.fecharCortina = function() { $scope.cortina = false; $scope.progresso = false; };

        $scope.inicializar = function () {
            $scope.buscarUnidades();
            $timeout(function () {
                $('ul.tabs').tabs();
                $('select').material_select();
                $(".date").mask('00/00/0009');
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                Servidor.entradaPagina();
            }, 500);
        };

        $scope.buscarUnidades = function () {
            if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                if ($scope.nomeUnidade.length > 4) { $scope.verificaAlocacao($scope.nomeUnidade); } else { $scope.unidades = []; }
            } else {
                $scope.verificaAlocacao(null);
            }
        };

        $scope.verificaAlocacao = function (nomeUnidade) {
            var alocacao = sessionStorage.getItem('alocacao');
            if (!alocacao || $scope.isAdmin) {
                if (Servidor.verificarPermissoes($scope.role)) {
                    $scope.permissao = true;
                    if (Servidor.verificaAdmin()) {
                        var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                        promise.then(function (response) {
                            $scope.unidades = response.data;
                            $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); }, 500);
                        });
                    } else { $scope.alocacao = null; }
                } else { $scope.permissao = false; }
            } else {
                if (Servidor.verificarPermissoes($scope.role)) {
                    $scope.permissao = true;
                    var promise = Servidor.buscarUm('alocacoes', alocacao);
                    promise.then(function (response) {
                        $scope.alocacao = response.data; $scope.unidades = [$scope.alocacao.instituicao];
                        $scope.unidade = $scope.alocacao.instituicao;
                        $scope.selecionaUnidade();
                        $timeout(function () { $('#unidade, #unidadeSolicitacaoFiltro').material_select('destroy'); $('#unidade, #unidadeSolicitacaoFiltro').material_select(); }, 500);
                    });
                } else { $scope.permissao = false; }
            }
        };

        $scope.selecionaUnidade = function (unidade) {
            if (unidade) { $scope.nomeUnidade = unidade.nomeCompleto; $scope.unidade = unidade; }
            var unidade = Servidor.buscarUm('unidades-ensino', $scope.unidade.id);
            unidade.then(function(response){
                $scope.cursos = response.data.cursos;
//                if ($scope.cursos.length > 0) {
//                    $('.curso-select').removeClass('hide');
                    $timeout(function () { $('#curso, #cursoSolicitacaoFiltro').material_select('destroy'); $('#curso, #cursoSolicitacaoFiltro').material_select(); }, 500);
//                } else { $('.curso-select').addClass('hide'); }
//                $('.etapa-select').addClass('hide'); $('.finaliza-vaga').addClass('hide'); $('.turma-select').addClass('hide');
            });
        };

        $scope.selecionaCurso = function () {
            var etapas = Servidor.buscar('etapas',{ curso: $scope.curso.id });
            etapas.then(function(response){
                $scope.etapas = response.data;
//                if ($scope.etapas.length > 0) {
//                    $('.etapa-select').removeClass('hide');
                    $timeout(function () { $('#etapa, #etapaSolicitacaoFiltro').material_select('destroy'); $('#etapa, #etapaSolicitacaoFiltro').material_select(); }, 500);
//                } else { $('.etapa-select').addClass('hide'); }
//                $('.finaliza-vaga').addClass('hide'); $('.turma-select').addClass('hide');
            });
        };

        $scope.selecionaEtapa = function () {
            var turmas = Servidor.buscar('turmas',{ unidadeEnsino: $scope.unidade.id, curso: $scope.curso.id, etapa: $scope.etapa.id });
            turmas.then(function(response){
                $scope.turmas = response.data; $('.turma-select').removeClass('hide');
                $timeout(function () { $('#turma').material_select('destroy'); $('#turma').material_select(); }, 500);
            });
        };

        $scope.selecionaTurma = function () {
            $('.aluno-nome').removeClass('hide');
            $('.finaliza-vaga').removeClass('hide');
//            $scope.nomeAluno = '';
        };

        $scope.$watch("nomeAluno", function(query){ if (query !== undefined) { if (query.length > 2) { $scope.buscaAluno(query); } } });

        $scope.verificaTexto = function () {
            if ($scope.nomeAluno === undefined) { $('.finaliza-vaga').addClass('hide');$('.select-unidade').removeClass('hide');
            } else if ($scope.nomeAluno.length === 0) { $('.finaliza-vaga').addClass('hide'); }
        };

        $scope.buscarSolicitacoes = function() {
            $('select').material_select();
            $scope.mostrarCortina();
            var promise = Servidor.buscar('solicitacao-vagas', {status: $scope.statusSolicitacao});
            promise.then(function(response) {
                if (response.data.length) {
                    $scope.solicitacoes = [];
                    var solicitacoes = response.data;
                    $scope.requisicoes = 0;
                    solicitacoes.forEach(function(solicitacao) {
                        $scope.requisicoes++;
                        var promise = Servidor.buscar('vagas', {solicitacaoVaga: solicitacao.id});
                        promise.then(function(response) {
                            $scope.requisicoes--;
                            if (response.data.length) {
                                solicitacao.vaga = response.data[0];
                                $scope.requisioes++;
                                var promise = Servidor.buscarUm('turmas', response.data[0].turma);
                                promise.then(function(response) {
                                    $scope.requisicoes--;
                                    solicitacao.turma = response.data;
                                    if ($scope.unidade.id === null) {
                                        var unidade = sessionStorage.getItem('unidade');
                                    } else {
                                        unidade = $scope.unidade.id;
                                    }
                                    if (solicitacao.turma.unidadeEnsino.id === parseInt(unidade)) {
                                        $scope.solicitacoes.push(solicitacao);
                                    }
                                    if ($scope.requisicoes === 0) {
                                        $('.tooltipped').tooltip('remove');
                                        $timeout(function() {
                                            $('.tooltipped').tooltip({delay:50});
                                            $scope.fecharCortina();
                                        }, 500);
                                    }
                                });
                            }
                            if ($scope.requisicoes === 0) {
                                $('.tooltipped').tooltip('remove');
                                $timeout(function() {
                                    $('.tooltipped').tooltip({delay:50});
                                    $scope.fecharCortina();
                                }, 500);
                            }
                        });
                    });
                } else {
                    Servidor.customToast('Não há solicitações de vagas para sua unidade.');
                    $scope.fecharCortina();
                }
            });
        };

        $scope.recusarSolicitacao = function(solicitacao) {
            $scope.mostrarCortina();
            solicitacao.vaga.solicitacaoVaga = null;
            var promise = Servidor.finalizar(solicitacao.vaga, 'vagas', '');
            promise.then(function() {
                solicitacao.status = 'NEGADO';
                var promise = Servidor.finalizar(solicitacao, 'solicitacao-vagas', '');
                promise.then(function(response) {
                    solicitacao.status = response.data.status;
                    $timeout(function () {
                        $scope.fecharCortina();
                        Servidor.customToast('Solicitaçao recusada com sucesso!');
                    }, 500);
                });
            });
        };

        $scope.aceitarSolicitacao = function() {
            $scope.mostrarCortina();
            var matricula = {
                curso: {id: $scope.solicitacao.turma.etapa.curso.id},
                aluno: {id: $scope.solicitacao.pessoa.id},
                unidadeEnsino: {id: $scope.solicitacao.turma.unidadeEnsino.id}
            };
            var promise = Servidor.finalizar(matricula, 'matriculas', '');
            promise.then(function(response) {
                matricula = response.data;
                $scope.disciplinas.forEach(function(d) {
                    if (!d.opcional || $('#dis'+d.id).prop('checked')) {
                        var disciplina = {
                            matricula: {id: matricula.id},
                            disciplina: {id: d.id}
                        };
                        $scope.requisicoes++;
                        var promise = Servidor.finalizar(disciplina, 'disciplinas-cursadas', '');
                        promise.then(function(response) {
                            if (--$scope.requisicoes === 0) {
                                var enturmacao = {
                                    matricula: {id: matricula.id},
                                    turma: {id: $scope.solicitacao.turma.id}
                                };
                                var promise = Servidor.finalizar(enturmacao, 'enturmacoes', '');
                                promise.then(function(response) {
                                    var enturmacao = response.data;
                                    $scope.solicitacao.status = 'ACEITO';
                                    var promise = Servidor.finalizar($scope.solicitacao, 'solicitacoes-vaga', 'Solicitações');
                                    promise.then(function() {
                                        $scope.solicitacao.vaga.enturmacao = enturmacao.id;
                                        var promise = Servidor.finalizar($scope.solicitacao.vaga, 'vagas', '');
                                        promise.then(function() {
                                            $scope.fecharCortina();
                                        });                                        
                                    });
                                });
                            }
                        });
                    }
                });
            });
        };

        $scope.prepararMatricula = function(solicitacao) {
            $scope.mostrarCortina();
            $scope.solicitacao = solicitacao;
            var promise = Servidor.buscar('disciplinas', {etapa: solicitacao.turma.etapa.id});
            promise.then(function(response) {
                $scope.disciplinas = response.data;
                $scope.disciplinas.forEach(function(disciplina, i) {
                    var promise = Servidor.buscarUm('disciplinas', disciplina.id);
                    promise.then(function(response) {
                        disciplina = response.data;
                        if (response.data.opcional) { $scope.temOpcional = true; }
                        if (i === $scope.disciplinas.length-1) {
                            $timeout(function() { $('#modal-confirmar-solicitacao').modal(); $scope.fecharCortina(); }, 500);
                        }
                    });
                });
            });
        };

        $scope.matricular = function() {
            $scope.mostrarCortina();
            var matricula = {
                curso: $scope.solicitacao.turma.etapa.curso,
                unidade: $scope.solicitacao.turma.unidadeEnsino,
                aluno: $scope.solicitacao.pessoa
            };
            var promise = Servidor.finalizar(matricula, 'matriculas', 'Matrícula');
            promise.then(function(response) {
                $scope.salvarDisciplinasCursadas(response.data);
            });
        };

        $scope.salvarDisciplinasCursadas = function(matricula) {
            $scope.disciplinas.forEach(function(disciplina) {
                if (!disciplina.opcional || $('#dis'+disciplina.id)[0].checked) {
                    Servidor.finalizar({
                        disciplina: disciplina,
                        matricula: matricula
                    },
                    'disciplinas-cursadas', '');
                }
            });
            $scope.enturmar(matricula);
        };

        $scope.enturmar = function(matricula) {
            var enturmacao = {
                matricula: matricula,
                turma: $scope.solicitacao.turma
            };
            var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
            promise.then(function() {
                $timeout(function() { $scope.fecharCortina(); $('#modal-confirmar-solicitacao').closeModal(); }, 500);
            });
        };

        $scope.buscaAluno = function (nome) {
            var params = {'nome': nome};
            $timeout.cancel($scope.busca);
            $scope.busca = $timeout(function(){
                var promise = Servidor.buscar('pessoas', params);
                promise.then(function(response) {
                    $scope.pessoas = response.data;
                    if ($scope.pessoas.length === 0) {
                        Materialize.toast('Nenhum aluno encontrado com este nome, clique no botão verde para adicionar.');
                        $scope.criaPessoa = false;
                    } else { $scope.criaPessoa = true; };
                });
            }, 1000);
        };

        $scope.selecionaPessoa = function (pessoa) {
            $scope.pessoa = pessoa;
            $scope.nomeAluno = $scope.pessoa.nome;
            $('.unidade-select').removeClass('hide');
        };

        $scope.filtrarSolicitacaoPeriodo = function(dataSolicitacao) {
            if ($scope.dataInicio.length === 10) {
                var inicio = dateTime.converterDataServidor($scope.dataInicio);
                if ($scope.dataTermino.length < 10) {
                    return dateTime.dateLessOrEqual(inicio, dataSolicitacao);
                }
            }
            if ($scope.dataTermino.length === 10) {
                var termino = dateTime.converterDataServidor($scope.dataTermino);
                if ($scope.dataInicio.length < 10) {
                    return dateTime.dateGreaterOrEqual(termino, dataSolicitacao);
                }
            }
            if ($scope.dataInicio.length === 10 && $scope.dataTermino.length === 10) {
                return dateTime.dateBetween(dataSolicitacao, inicio, termino);
            }
            return true;
        };

        $scope.verificarDataExpedicao = function(calendario, data, efetivos) {
            var promise = Servidor.buscar('calendarios/'+calendario.id+'/dias', {data:data});
            promise.then(function(response) {
                if (response.data.length > 0 && response.data[0].efetivo) {
                    efetivos++;
                }
                if (efetivos < 2) {
                    data = data.split('-');
                    data = new Date(data[0], parseInt(data[1])-1, parseInt(data[2])+1).toJSON().split('T')[0];
                    $scope.verificarDataExpedicao(calendario, data, efetivos);
                } else {
                    $scope.solicitacaoVaga.dataExpiracao = data;
                    $scope.pessoa.dataExpiracaoVaga = data;
                    var result = Servidor.finalizar($scope.solicitacaoVaga,'solicitacao-vagas','Solicitação criada com sucesso!');
                    result.then(function(response){
                        var solicitacao = response.data;
                        var promise = Servidor.buscar('vagas',{ turma: $scope.turma.id });
                        promise.then(function(response){
                            var vagas = response.data; var vagaTurma = null;
                            for (var j=0; j<vagas.length; j++) { if (vagas[j].enturmacao === undefined && vagas[j].solicitacaoVaga === undefined) { vagaTurma = vagas[j]; } }
                            if (vagaTurma !== null) {
                                //2º verificação se há vagas
                                vagaTurma.solicitacaoVaga = solicitacao.id;
                                Servidor.finalizar(vagaTurma,'vagas','');
                                $scope.gerarPdf($scope.pessoa,$scope.turma,true);
                            } else {
                                //Não tem vaga
                                $scope.gerarPdf($scope.pessoa,$scope.turma,false);
                            }
                        });
                    });
                }
            });
        };

        $scope.finalizar = function () {
            if ($scope.unidade.id !== null && $scope.curso.id !== null && $scope.etapa.id !== null && $scope.turma.id !== null && $scope.nomeAluno !== undefined) {
                var turma = Servidor.buscarUm('turmas',$scope.turma.id);
                turma.then(function(response){
                    var classe = response.data;
                    $scope.turma = response.data;
                    var promise = Servidor.buscar('vagas', {turma: classe.id});
                    var solicitacoes = 0;
                    promise.then(function(response){
                        var vagas = response.data;
                        for (var j=0; j<vagas.length; j++) { if (vagas[j].solicitacaoVaga !== undefined) { solicitacoes++; } }
                        classe.quantidadeAlunos = classe.quantidadeAlunos + solicitacoes;
                        var total = classe.limiteAlunos - classe.quantidadeAlunos;
                        if (total > 0) {
                            //1º verificação se há vagas
                            var promise = Servidor.buscar('pessoas',{ nome: $scope.nomeAluno });
                            promise.then(function(response){
                                var pessoas = response.data;
                                if (pessoas.length > 0) {
                                    var pessoa = pessoas[0]; $scope.solicitacaoVaga.pessoa.id = pessoa.id;
                                    $scope.pessoa = pessoa;
                                    $scope.solicitacaoVaga.dataSolicitacao = moment().format("YYYY-MM-DD");
                                    var calendario = $scope.turma.calendario;
                                    var data = new Date().toJSON().split('T')[0];
                                    if(dateTime.dateGreaterOrEqual(data, calendario.dataTermino)) {
                                        $scope.gerarPdf(pessoa, classe, false);
                                    } else {
                                        $scope.verificarDataExpedicao(calendario, data, 0);
                                    }                                    
                                    
                                } else { Servidor.customToast('Nenhuma pessoa ativa com este nome.'); }
                            });
                        } else {
                            //Não tem vaga
                            var promise = Servidor.buscar('pessoas',{ nome: $scope.nomeAluno });
                            promise.then(function(response){
                                var pessoas = response.data; if (pessoas.length > 0) { var pessoa = pessoas[0]; $scope.gerarPdf(pessoa,classe,false); }
                            });
                        }
                    });
                });
            } else { Materialize.toast('Há campos obrigatórios não preenchidos.'); }
        };

        $scope.gerarPdf = function(pessoa,turma,hasVaga){
            if (!pessoa || !turma) {
                Materialize.toast('O PDF não pode ser gerado. Caso o problema presista, contate o administrador do sistema.');
            } else {
                var matricula = {'unidadeEnsino': {'id': null} };
                var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
                promise.then(function (response) {
                    if (response.data) {
                        matricula.vinculo = response.data;
                        var promise = Servidor.buscarUm('alocacoes', sessionStorage.getItem('alocacao'));
                        promise.then(function(response){
                            if (response.data) {
                                var unidade = Servidor.buscarUm('unidades-ensino', response.data.instituicao.id);
                                unidade.then(function(response){
                                    if (response.data) {
                                        matricula.unidadeEnsino = response.data; makePdf.atestadoVaga(pessoa,turma,hasVaga,matricula);
                                    } else { Materialize.toast('Nenhuma Unidade de Ensino encontrada nesta alocação.'); }
                                });
                            } else { Materialize.toast('Nenhuma alocação encontrada ou selecionada para gerar o PDF.'); }
                        });
                    } else { Materialize.toast('Nenhum vínculo encontrado ou selecionado para gerar o PDF.'); }
                });
            }
        };

        $scope.inicializar();
    }]);
})();
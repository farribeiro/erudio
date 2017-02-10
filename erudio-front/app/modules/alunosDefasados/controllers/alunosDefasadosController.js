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
(function(){
    var alunosDefasadosModule = angular.module('alunosDefasadosModule', ['servidorModule', 'alunosDefasadosDirectives']);
    alunosDefasadosModule.controller('alunosDefasadosController', ['$scope', '$timeout', 'dateTime', 'makePdf', 'Servidor', function($scope, $timeout, dateTime, makePdf, Servidor) {

        $scope.cortina = false;    
        $scope.mostrarCortina = function() { $scope.cortina = true; };
        $scope.fecharCortina = function() { $scope.cortina = false; };    

        $scope.etapasSelecionadas = [];
        $scope.etapa = { id: null, turmas: [] };
        $scope.busca = {curso: {id: null} };

        var anoAtual = new Date;
        $scope.anoAtual = anoAtual.getFullYear();

        $scope.visualizarAlunos = false;

        $scope.montarSelect = function(seletor) {
            $(seletor).material_select('destroy');
            $timeout(function() { $(seletor).material_select(); }, 1);
        };

        $scope.montarTooltip = function() {
            $('.tooltipped').tooltip('remove');
            $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 500);
        };

        $scope.buscarCursos = function() {
            $scope.mostrarCortina();        
            var promise = Servidor.buscarUm('unidades-ensino', sessionStorage.getItem('unidade'));
            promise.then(function(response) {
                $scope.cursos = response.data.cursos;
                if ($scope.cursos.length === 1) {
                    $scope.busca.curso = $scope.cursos[0];
                    $scope.buscarEtapas($scope.busca.curso.id);
                }
                $scope.unidade = response.data;
                $scope.etapas = [];
                $scope.etapa.id = null;
                $scope.montarSelect('#cursoAlunosDefasados');
                $scope.montarSelect('#etapaAlunosDefasados');
                Servidor.entradaPagina();
                $scope.fecharCortina();            
            });
        };

        $scope.buscarEtapas = function(cursoId) {
            $scope.mostrarCortina();
            var promise = Servidor.buscar('etapas', {curso: cursoId});
            promise.then(function(response) {
                $scope.etapas = response.data;
                $scope.montarSelect('#etapaAlunosDefasados');            
                $scope.fecharCortina();
                $('.tooltipped').tooltip('remove');
                $timeout(function(){
                    $('.tooltipped').tooltip({delay: 50});
                }, 50);
            });
        };

        $scope.buscarTurmas = function(etapaId) {
            $scope.mostrarCortina();
            var promise = Servidor.buscarUm('etapas', etapaId);
            promise.then(function(response) {
                $scope.etapa = response.data;
                var promise = Servidor.buscar('turmas', {etapa: response.data.id, 'unidadeEnsino': sessionStorage.getItem('unidade')});
                promise.then(function(response) {
                    $scope.turmas = response.data;
                    $scope.etapa.turmas = [];
                    $('#checkboxTurmas').prop('checked', false);
                    $scope.montarTooltip();
                    $scope.fecharCortina();
                });
            });            
        };

        $scope.selecionarTurma = function(turma) {
            for(var i = 0; i < $scope.etapa.turmas.length; i++) {
                if (parseInt($scope.etapa.turmas[i].id) === parseInt(turma.id)) {
                    if ($scope.etapa.turmas.length === $scope.turmas.length) {
                        $('#checkboxTurmas').prop('checked', false);
                    }
                    $scope.etapa.turmas.splice(i, 1);
                    return 0;
                }
            };
            $scope.etapa.turmas.push(angular.copy(turma));
            $('#tur'+turma.id)[0].checked = true;
            if ($scope.etapa.turmas.length === $scope.turmas.length) {
                $('#checkboxTurmas').prop('checked', true);
            }
        };

        $scope.selecionarTodasTurmas = function() {
            if ($scope.etapa.turmas.length === $scope.turmas.length) {
                if (!$('#checkboxTurmas')[0].checked) {
                    $scope.etapa.turmas = [];
                    $('.checkbox-turma').prop('checked', false);
                }
            } else {
                if ($('#checkboxTurmas')[0].checked) {
                    $scope.etapa.turmas = angular.copy($scope.turmas);
                    $('.checkbox-turma').prop('checked', true);
                }
            }
        };

        $scope.selecionarEtapa = function(etapa) {
            var encontrou = false;
            $scope.etapasSelecionadas.forEach(function(etapaS, i) {
                if (parseInt(etapaS.id) === parseInt(etapa.id)) {
                    if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                        $('#checkboxEtapas').prop('checked', false);
                    }
                    $scope.etapasSelecionadas.splice(i, 1);
                    encontrou = true;
                }
            });
            if (!encontrou) {
                $scope.etapasSelecionadas.push(angular.copy(etapa));
                $('#eta'+etapa.id)[0].checked = true;
                if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                    $('#checkboxEtapas').prop('checked', true);
                }
            }
        };

        $scope.selecionarTodasEtapas = function() {
            if ($scope.etapasSelecionadas.length === $scope.etapas.length) {
                if (!$('#checkboxEtapas')[0].checked) {
                    $scope.etapasSelecionadas = [];
                    $('.checkbox-etapa').prop('checked', false);
                }
            } else {
                if ($('#checkboxEtapas')[0].checked) {
                    $scope.etapasSelecionadas = angular.copy($scope.etapas);
                    $('.checkbox-etapa').prop('checked', true);
                }
            }
        };

        $scope.prepararDocumento = function() {
            $scope.mostrarCortina();
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response) {
                $scope.vinculo = response.data;            
                if ($scope.etapa.turmas.length) {                
                    $scope.buscarDadosPdf([$scope.etapa]);
                } else {
                    var ultimaEtapa = $scope.etapasSelecionadas.length-1;
                    var etapas = $scope.etapasSelecionadas.sort(compararEtapas);
                    etapas.forEach(function(etapa, i) {
                        var promise = Servidor.buscarUm('etapas', etapa.id);
                        promise.then(function(response) {
                            etapa.curso = response.data.curso;
                            var promise = Servidor.buscar('turmas', {etapa: etapa.id});
                            promise.then(function(response) {
                                etapa.turmas = response.data;
                                if (ultimaEtapa ===  i) {
                                    $timeout(function(){ $scope.buscarDadosPdf(etapas); }, 500);
                                }
                            });
                        });
                    });
                }
            });            
        };

        $scope.buscarVinculo = function(){
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response){
                $scope.vinculo = response.data;
                var promiseB = Servidor.buscarUm('alocacoes',  sessionStorage.getItem('alocacao'));
                promiseB.then(function(response){
                    $scope.alocacao = response.data;
                    var promiseC = Servidor.buscarUm('instituicoes', $scope.alocacao.instituicao.id);
                    promiseC.then(function(response){
                        $scope.alocacao.instituicao = response.data;
                    });
                });
            });
        };

        $scope.prepararVisualizacao = function(etapa){
            $scope.buscarVinculo();
            $scope.mostrarCortina();
            $scope.visualizarAlunos = true;
            var promise = Servidor.buscar('turmas', {'etapa':etapa.id});
            promise.then(function(response){
                var turmas = response.data;
                turmas.forEach(function(turma){
                    var promiseB = Servidor.buscar('enturmacoes', {'turma': turma.id});
                    promiseB.then(function (response) {
                        var enturmacoes = response.data;
                        enturmacoes.forEach(function (enturmacao) {
                            enturmacao.anosDiferenca = $scope.selecionarAlunosDefasados(enturmacao, 1);
                            if (enturmacao.anosDiferenca === 0) {

                            } else {

                            }
                        });
                    });
                });
                $scope.fecharCortina();
            });
        };

        function compararEtapas(a, b) {
            if (a.nomeExibicao > b.nomeExibicao) {
                return 1;
            } else {
                return -1;
            }
            return 0;
        }

        $scope.buscarDadosPdf = function(etapas, destino) {
            $scope.requisicoes = 0;
            etapas.forEach(function(etapa) {
                etapa.turmas.forEach(function(turma) {                    
                    var promise = Servidor.buscar('enturmacoes', {turma: turma.id});
                    promise.then(function(response) {
                        turma.enturmacoes = response.data;
                        turma.enturmacoes.forEach(function(enturmacao) {
                            enturmacao.diferenca = $scope.selecionarAlunosDefasados(enturmacao, 11);
                            if (enturmacao.diferenca) {
                                $scope.requisicoes++;
                                var promise = Servidor.buscarUm('pessoas', enturmacao.matricula.aluno.id);
                                promise.then(function(response) {                                    
                                    enturmacao.matricula.aluno = response.data;
                                    if (--$scope.requisicoes === 0) {
                                        $timeout(function() { 
                                            if (destino === 'visualizar') {

                                            } else {
                                                makePdf.gerarPdfAlunosDefasados(etapas, $scope.vinculo, $scope.unidade);
                                                $scope.fecharCortina();     
                                            }
                                        }, 500);
                                    }
                                });
                            } else {
                                $timeout(function() {
                                    if (--$scope.requisicoes === 0) {
                                        if (destino === 'visualizar') {

                                        } else {
                                            makePdf.gerarPdfAlunosDefasados(etapas, $scope.vinculo, $scope.unidade);
                                            $scope.fecharCortina();
                                        }
                                    }
                                }, 500);                                    
                            }
                        });
                    });
                });
            });
        };

        $scope.selecionarAlunosDefasados = function(enturmacao, idadeBase) {
            var ano = new Date().getFullYear();
            var dataComparacao = ano + '-03-31';
            var data = enturmacao.matricula.aluno.dataNascimento.split('-');
            var diferenca = ano - parseInt(data[0]) - idadeBase;
            if (diferenca > 2) {
                return diferenca;
            } else if (diferenca === 2){
                var dataAluno = ano + '-' + data[1] + '-' + data[2];
                if (dateTime.dateLessThan(dataComparacao, dataAluno)) {
                    return diferenca;                
                }
            }
            return 0;
        };

        $scope.voltarInicio = function () {
            $scope.visualizarAlunos = false;
            $scope.turmas = [];
            $scope.etapa = {id: null, turmas: []};
        };

        $scope.inicializar = function() {
            $scope.buscarCursos();
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 50});
            },100);
            $('.tooltipped').tooltip({delay: 50});
        };

        $scope.inicializar();
    }]);
}());
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

    var diarioFrequenciasModule = angular.module('diarioFrequenciasModule', ['servidorModule', 'diarioFrequenciasDirectives', 'dateTimeModule']);

    diarioFrequenciasModule.controller('diarioFrequenciasController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'makePdf', 'dateTime', function ($scope, Servidor, Restangular, $timeout, $templateCache, makePdf, dateTime) {
        $templateCache.removeAll();

        $scope.mostrarCortina = function() { $scope.cortina = true; };
        $scope.fecharCortina = function() { $scope.cortina = false; };
        $scope.editando = false;
        $scope.disciplinasSelecionadas = [];
        $scope.requisicoes = 0;
        $scope.unidade = {id: parseInt(sessionStorage.getItem('unidade'))};
        $scope.isAdmin = Servidor.verificaAdmin();

        var montarSelect = function(seletor, tempo) {
            if (!tempo) { tempo = 250; }
            $(seletor).material_select('destroy');
            setTimeout(function() { $(seletor).material_select(); }, tempo);
        };

        $scope.limparBusca = function() {
            $scope.busca = {
                curso: { id: null },
                etapa: { id: null },
                turma: { id: null },
                mes: {numero: null, nome: null}
            };
            $scope.etapas = [];
            $scope.turmas = [];
            montarSelect('#cursoDiarioFrequencia, #etapaDiarioFrequencia, #turmaDiarioFrequencia, #mesDiarioFrequencia');
        };

        $scope.buscarUnidades = function(nome) {
            if(nome !== undefined && nome.length > 4) {
                var promise = Servidor.buscar('unidades-ensino', {nome: nome});
                promise.then(function(response) {
                    $scope.unidades = response.data;
                });
            }
        };

        $scope.selecionarUnidade = function(unidade) {
            $scope.nomeUnidade = unidade.nomeCompleto;
            $scope.unidade = unidade;
        };

        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response) {
                $scope.cursos = response.data;
                montarSelect('#cursoDiarioFrequencia');
            });
        };

        $scope.buscarEtapas = function(curso) {
            var promise = Servidor.buscar('etapas', {curso: curso});
            promise.then(function(response) {
                $scope.etapas = response.data;
                montarSelect('#etapaDiarioFrequencia');
            });
        };

        $scope.buscarTurmas = function(etapa) {
            var promise = Servidor.buscar('turmas', {etapa: etapa, unidadeEnsino: $scope.unidade.id});
            promise.then(function(response) {
                $scope.turmas = response.data;
                montarSelect('#turmaDiarioFrequencia');
            });
        };

        $scope.buscarVinculo = function() {
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response) {
                $scope.vinculo = response.data;
            });
        };

        $scope.buscarDisciplinasOfertadas = function(id) {
            if (!id) { return Servidor.customToast("Selecione uma turma para realizar a busca."); };
            if ($scope.busca.mes === undefined || !$scope.busca.mes.numero) { return Servidor.customToast("Selecione um mês para realizar a busca.");}
            var promise = Servidor.buscarUm('turmas', id);
            promise.then(function(response) {
                $scope.checkAll = false;
                var turma = response.data;
                var promise = Servidor.buscar('disciplinas-ofertadas', {turma: turma.id});
                promise.then(function(response) {
                    response.data.forEach(function(ofertada) {
                        ofertada.turma = turma;
                    });
                    $scope.disciplinasOfertadas = response.data;
                    $('.tooltipped').tooltip('remove');
                    $timeout(function() { $('.tooltipped').tooltip(); }, 50);
                    $scope.disciplinasSelecionadas = [];
                });
            });
        };

        $scope.selecionarTudo = function () {
            if ($scope.disciplinasSelecionadas.length === $scope.disciplinasOfertadas.length) {
                if (!$('#disciplinas')[0].checked) {
                    $scope.disciplinasSelecionadas = [];
                    $('.checkbox-turma').prop('checked', false);
                }
            } else {
                if ($('#disciplinas')[0].checked) {
                    $scope.disciplinasSelecionadas = angular.copy($scope.disciplinasOfertadas);
                    $('.checkbox-turma').prop('checked', true);
                }
            }
        };

        $scope.selecionarDisciplina = function(disciplinaOfertada){
            var achou = false;
            var vazio = false;
            if(!$scope.disciplinasSelecionadas.length){
                $scope.disciplinasSelecionadas.push(disciplinaOfertada);
                vazio = true;
                achou = true;
            }
            for (var i = 0; i < $scope.disciplinasSelecionadas.length && vazio === false; i++) {
                if(disciplinaOfertada.id === $scope.disciplinasSelecionadas[i].id){
                    $scope.disciplinasSelecionadas.splice(i, 1);
                    achou = true;
                }
            }
            if(!achou) {
                $scope.disciplinasSelecionadas.push(disciplinaOfertada);
            }
        };

        $scope.carregarFrequencia = function (disciplina, mes){
            $scope.mes = dateTime.converterMes(mes);
            $scope.disciplina = disciplina;
            $scope.editando = true;
            $timeout(function(){
                $scope.fecharCortina();
            }, 250);
        };

        $scope.prepararVisualizacao = function(disciplina, mes) {
            $scope.mostrarCortina();
            $scope.disciplinasSelecionadas = [];
            $scope.disciplinasSelecionadas.push(disciplina);
            $scope.busca.mes.numero = mes;
            if (parseInt(mes) < 10) { mes = '0' + mes; }
            var promise = Servidor.buscarUm('turmas', disciplina.turma.id);
            promise.then(function(response) {
                disciplina.turma = response.data;
            });
            disciplina.temObservacoes = false;
            var promise = Servidor.buscar('enturmacoes', {turma: disciplina.turma.id, encerrado: 0});
            promise.then(function(response) {
                disciplina.enturmacoes = response.data;
                var promise = Servidor.buscar('turmas/'+disciplina.turma.id+'/aulas', {mes: mes, disciplina: disciplina.id});
                promise.then(function(response) {
                    if(!response.data.length) { return Servidor.customToast('Esta turma não possui aulas.'); }
                    disciplina.aulas = response.data;
                    disciplina.aulas.forEach(function(aula, j) {
                        var promise = Servidor.buscar('aula-observacoes', {aula: aula.id});
                        promise.then(function(response) {
                            aula.observacoes = response.data;
                            if (response.data.length) { disciplina.temObservacoes = true; }
                            if (j === disciplina.aulas.length-1) {
                                disciplina.enturmacoes.forEach(function(enturmacao, indice) {
                                    enturmacao.frequencias = [];
                                    $scope.requisicoes++;
                                    var promise = Servidor.buscar('frequencias', {matricula: enturmacao.matricula.id, mes: mes});
                                    promise.then(function (response) {
                                        var frequencias = response.data;
                                        var freq;
                                        enturmacao.faltas = 0;
                                        enturmacao.ativa = 0;
                                        disciplina.aulas.forEach(function (aula) {
                                            freq = {status: ' '};
                                            frequencias.forEach(function (frequencia) {
                                                if (frequencia.aula.id === aula.id) {
                                                    switch (frequencia.status) {
                                                        case 'PRESENCA':
                                                            frequencia.status = 'C';
                                                            enturmacao.ativa++;
                                                            break;
                                                        case 'FALTA':
                                                            frequencia.status = 'F';
                                                            enturmacao.ativa++;
                                                            enturmacao.faltas++;
                                                            break;
                                                        case 'FALTA_JUSTIFICADA':
                                                            frequencia.status = 'FJ';
                                                            enturmacao.ativa++;
                                                            break;
                                                        case 'DISPENSA':
                                                            frequencia.status = 'D';
                                                            enturmacao.ativa++;
                                                            break;
                                                    }
                                                    freq = frequencia;
                                                }
                                            });
                                            enturmacao.frequencias.push(freq);
                                        });
                                        if (enturmacao.matricula.status !== 'CURSANDO' && !enturmacao.ativa) {
                                            disciplina.enturmacoes.splice(indice, 1);
                                        }
                                        if (--$scope.requisicoes === 0) {
                                            $scope.carregarFrequencia(disciplina, mes);
                                        }
                                    });
                                });
                            }
                        });
                    });
                });
            });
        };

        $scope.gerarPdfDisciplina = function(disciplina) {
            $scope.disciplinasSelecionadas.push(disciplina);
            $('#dis'+disciplina.id).prop('checked',true);
            setTimeout(function(){$scope.gerarPdf();},50);
        };

        $scope.gerarPdf = function(){
            $scope.mostrarCortina();
            var mes = parseInt($scope.busca.mes.numero);
            if (mes < 10) { $scope.busca.mes.numero = '0' + mes; }
            console.log(mes, $scope.busca.mes.numero);
            var requisicoes = 0;
            var promise = Servidor.buscar('enturmacoes', {turma: $scope.disciplinasSelecionadas[0].turma.id, encerrado: 0});
            promise.then(function(response) {
                var enturmacoes = response.data;                
                enturmacoes.forEach(function(enturmacao, i) {
                    requisicoes++;
                    var promise = Servidor.buscar('frequencias', {matricula:enturmacao.matricula.id, mes: $scope.busca.mes.numero});
                    promise.then(function(response) {
                        requisicoes--;
                        enturmacao.matricula.frequencias = response.data;
                        if (i === enturmacoes.length-1) {
                            $scope.disciplinasSelecionadas.forEach(function(ofertada) {
                                ofertada.temObservacoes = false;
                                requisicoes++;
                                var promise = Servidor.buscar('turmas/'+ofertada.turma.id+'/aulas', {disciplina: ofertada.id, mes: $scope.busca.mes.numero});
                                promise.then(function(response) {
                                    requisicoes--;
                                    ofertada.aulas = response.data;
                                    if(!ofertada.aulas.length && $scope.disciplinasSelecionadas.length === 1) {
                                        Servidor.customToast(ofertada.nomeExibicao + " não possui aulas.");
                                        $scope.fechaCortina();
                                    }
                                    ofertada.aulas.forEach(function(aula) {
                                        requisicoes++;
                                        var promise = Servidor.buscar('aula-observacoes', {aula: aula.id});
                                        promise.then(function(response) {
                                            if (response.data.length) {
                                                ofertada.temObservacoes = true; aula.observacoes = response.data;
                                            }
                                            if (--requisicoes === 0) {
                                                makePdf.gerarDiarioPresenca(enturmacoes, $scope.disciplinasSelecionadas, $scope.vinculo, $scope.busca.mes.numero);
                                                $timeout(function() {
                                                    $scope.fecharCortina();
                                                }, 500);
                                            }
                                        });
                                    });
                                });
                            });
                        }
                    });
                });
            });
        };

        $scope.fecharFormulario = function(){
            $scope.editando = false;
        };

        $scope.inicializar = function(){
            $scope.buscarCursos();
            $scope.buscarVinculo();
            $timeout(function(){
                $('select').material_select();
                Servidor.entradaPagina();
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
            }, 500);
        };

        $scope.inicializar();

    }]);
})();

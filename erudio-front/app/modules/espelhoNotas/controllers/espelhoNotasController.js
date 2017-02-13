/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *    DITEC - Diretoria de Tecnologias educacionais                        *
 *    ditec@itajai.sc.gov.br                                               *
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
    var espelhoNotasModule = angular.module('espelhoNotasModule', ['servidorModule', 'espelhoNotasDirectives']);

    espelhoNotasModule.controller('espelhoNotasController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'makePdf', 'dateTime', function ($scope, Servidor, Restangular, $timeout, $templateCache, makePdf, dateTime) {
        $templateCache.removeAll();
        
        //Variáveis do controller
        $scope.cursos = [];
        $scope.curso = null;
        $scope.etapas = [];
        $scope.etapa = null;
        $scope.turma = null;
        $scope.turmas = [];
        $scope.media = null;
        $scope.editandoPeriodo = false;
        $scope.editandoAnual = false;
        $scope.turmasSelecionadas = [];
        $scope.disciplina = null;
        $scope.disciplinas = [];
        $scope.disciplinasOfertadas = [];
        $scope.disciplinasSelecionadas = [];
        $scope.editandoInicial = true;
        $scope.faltas = [];
        $scope.cortina = false;
        $scope.requisicoes = 0;

        $scope.$watch("requisicoes", function() {
            if ($scope.requisicoes) {
                $scope.cortina = true;
            } else {
                $scope.cortina = false;
            }
        });

        $scope.dataAtual =  function (){
            var data = new Date();
            var dia = data.getDate();
            var mes = data.getMonth() + 1;
            var ano = data.getFullYear();
            if (mes < 10){mes = '0' + (data.getMonth() + 1);}
            if (dia < 10){dia = '0' + data.getDate();}
            $scope.dataPadrao = 'Itajaí, '+dia+' de '+ dateTime.converterMes(mes) +' de '+ano;
        };

        $scope.buscarVinculo = function() {
            var promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
            promise.then(function(response) {
                $scope.vinculo = response.data;
            });
        };

        $scope.buscarAlocacao = function() {
            var promise = Servidor.buscarUm('alocacoes', sessionStorage.getItem('alocacao'));
            promise.then(function(response) {
                $scope.alocacao = response.data;
                var promise = Servidor.buscarUm('instituicoes', $scope.alocacao.instituicao.id);
                promise.then(function(response) {
                    $scope.alocacao.instituicao = response.data;
                });
            });
        };

        $scope.buscarCursos = function(){
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response){
                if(response.data.length > 0){
                    $scope.cursos = response.data;
                    $timeout(function () {
                        $('select').material_select('destroy');
                        $('select').material_select();
                    });
                } else { Servidor.customToast('Nenhum curso cadastrado!');}
            });
        };

        $scope.buscarEtapas = function(id){
            if(id){
                var promise = Servidor.buscar('etapas', {'curso':id});
                promise.then(function(response){
                    if(response.data.length > 0){
                        $scope.etapas = response.data;
                        $timeout(function () {
                            $('select').material_select('destroy');
                            $('select').material_select();
                        }, 100);
                    } else { Servidor.customToast('Nenhuma etapa cadastrada!');}
                });
            }
        };

        $scope.buscarTurmas =  function(id, opcao) {
            if(id) {
                var promise = Servidor.buscar('turmas', {'etapa': id, 'unidadeEnsino': sessionStorage.getItem('unidade')});
                promise.then(function(response) {
                    if(response.data.length > 0) {
                        $scope.turmas =  response.data;
                        $('.tooltipped').tooltip('remove');
                        $timeout(function () {
                            $('select').material_select('destroy');
                            $('select').material_select();
                            $('.tooltipped').tooltip({delay: 50});
                        }, 100);
                    } else { Servidor.customToast('Nenhuma turma cadastrada!'); }
                });
            } if(opcao) {
                $scope.editandoPeriodo = true;
            } else {
                $scope.editandoPeriodo = false;
            }
        };

        $scope.buscarMedias = function(turma){
            var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma});
            promise.then(function(response){
                var enturmacoes  = response.data;
                enturmacoes.forEach(function(enturmacao){
                    var promiseB = Servidor.buscar('disciplinas-cursadas', {'matricula': enturmacao.matricula.id});
                    promiseB.then(function(response){
                        enturmacao.disciplinasCursadas =  response.data;
                        enturmacao.disciplinasCursadas.forEach(function(disciplinaCursada){
                            var promiseC = Servidor.buscar('medias', {'disciplinas-cursadas': disciplinaCursada.id});
                            promiseC.then(function(response){
                                var medias = response.data;
                                medias.forEach(function(media){
                                    if(media.numero === parseInt($scope.media)){
                                        disciplinaCursada.media = media;
                                    }
                                });
                            });
                        });
                    });
                });
            });
        };

        $scope.buscarDisciplinas = function(id){
            var promise = Servidor.buscar('disciplinas', {'etapa': id});
            promise.then(function(response){
               $scope.disciplinas = response.data;
               if($scope.disciplinas.length > 0){
                   $scope.editandoAnual = true;
               } else{
                   Servidor.customToast('Nao há disciplinas nesta turma!');
                   $scope.editandoAnual = false;
               }
            });
        };

        $scope.buscarDisciplinasOfertadas = function(id) {
            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: id});
            promise.then(function(response) {
                $scope.disciplinasOfertadas = response.data;
                $('.tooltipped').tooltip('remove');
                $timeout(function(){
                    $('.tooltipped').tooltip({delay: 50});
                }, 100);
                if (response.data.length) {
                    $scope.editandoAnual = true;
                } else {
                    $scope.editandoAnual = false;
                    Servidor.customToast('Nao ha disciplinas nesta turma!');
                }
            });
        };

        $scope.buscarInfoPeriodo = function() {
            $scope.requisicoes = $scope.turmasSelecionadas.length;
            $scope.turmasSelecionadas.forEach(function(t) {
                $scope.requisicoes--;
                var promise = Servidor.buscarUm('turmas', t.id);
                promise.then(function(response) {
                    var promise = Servidor.buscar('periodos', {calendario: response.data.calendario.id});
                    promise.then(function(response) {
                        var periodo;
                        response.data.forEach(function(p) {
                            if (parseInt(p.media) === $scope.media) {
                                periodo = p;
                            }
                        });
                        var promise = Servidor.buscar('enturmacoes', {turma: t.id, encerrado: 0});
                        promise.then(function(response) {
                            t.enturmacoes = response.data;
                            t.enturmacoes.forEach(function(e) {
                                e.faltas = 0;
                                var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: e.id});
                                promise.then(function(response) {
                                    e.disciplinasCursadas = response.data;
                                    $scope.requisicoes += e.disciplinasCursadas.length;
                                    e.disciplinasCursadas.forEach(function(d) {
                                        var promise = Servidor.buscar('medias', {disciplinaCursada: d.id});
                                        promise.then(function(response) {
                                            var medias = response.data;
                                            d.media = {valor: 'ND'};
                                            medias.forEach(function(m) {                                                
                                                if (parseInt(m.numero) === $scope.media) {                                                    
                                                    if (m.valor !== undefined && m.valor) {
                                                        d.media.valor = m.valor;
                                                    }
                                                }
                                            });
                                            var promise = Servidor.buscar('frequencias', {disciplina: d.id});
                                                promise.then(function(response) {
                                                var frequencias = response.data;
                                                frequencias.forEach(function(f) {
                                                    if (f.status === 'FALTA' && dateTime.dateBetween(f.aula.dia.data, periodo.dataInicio, periodo.dataTermino)) {
                                                        e.faltas++;
                                                    }
                                                });
                                                if (--$scope.requisicoes === 0) {                                                    
                                                    makePdf.gerarPdfEspelhoNotasPeriodo($scope.disciplinas, $scope.turmasSelecionadas, $scope.alocacao, $scope.vinculo);
                                                }
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        };

        $scope.buscarFrequenciasTurma = function() {
            $scope.mostrarCortina();
            var turma = $scope.disciplinasSelecionadas[0].turma;
            var promise = Servidor.buscarUm('turmas', turma.id);
            promise.then(function(response) {
                $scope.turma = response.data;
                var promise = Servidor.buscar('enturmacoes', {turma: turma.id, encerrado: 0});
                promise.then(function(response) {
                    var enturmacoes = response.data;
                    var promise = Servidor.buscar('disciplinas-cursadas', {turma: turma.id});
                    promise.then(function(response) {
                        var disciplinasCursadas = [];
                        $scope.disciplinasSelecionadas.forEach(function(ofertada) {
                            response.data.forEach(function(cursada) {
                                if (cursada.disciplina.id === ofertada.disciplina.id) {
                                    disciplinasCursadas.push(cursada);
                                }
                            });
                        });
                        enturmacoes.forEach(function(enturmacao, indice){
                            enturmacao.disciplinasCursadas = [];
                            var promise = Servidor.buscar('frequencias', {matricula: enturmacao.matricula.id});
                            promise.then(function(response) {
                                enturmacao.frequencias = response.data;
                                disciplinasCursadas.forEach(function(cursada) {
                                    if (cursada.matricula.id === enturmacao.matricula.id) {
                                        cursada.faltas = 0;
                                        enturmacao.frequencias.forEach(function(frequencia) {
                                            if (frequencia.disciplinaCursada.id === cursada.id && frequencia.status === 'FALTA') {
                                                cursada.faltas++;
                                            }
                                        });
                                        enturmacao.disciplinasCursadas.push(cursada);
                                    }
                                });
                                if (indice === enturmacoes.length-1) {
                                    $timeout(function() {
                                        $scope.buscarMediasTurma(enturmacoes);
                                    }, 1000);
                                }
                            });
                        });
                    });
                });
            });
        };

        $scope.buscarMediasTurma = function(enturmacoes) {
            $scope.disciplinasSelecionadas.forEach(function(ofertada, i) {
                enturmacoes.forEach(function(enturmacao, j) {
                    enturmacao.disciplinasCursadas.forEach(function(cursada) {
                        if (cursada.disciplina.id === ofertada.disciplina.id) {
                            var promise = Servidor.buscar('medias', {disciplinaCursada: cursada.id});
                            promise.then(function(response) {
                                cursada.medias = response.data;
                                if (i === $scope.disciplinasSelecionadas.length-1 && j === enturmacoes.length-1) {
                                    $timeout(function() {
                                        $scope.fecharCortina();
                                        makePdf.gerarPdfEspelhoNotasAnual($scope.disciplinasSelecionadas, enturmacoes, $scope.turma, $scope.alocacao, $scope.vinculo);
                                    }, 1000);
                                }
                            });
                        }
                    });
                });
            });
        };

        $scope.buscarDisciplinas = function(etapa) {
            var promise = Servidor.buscar('disciplinas', {etapa: etapa});
            promise.then(function(response) {
                $scope.disciplinas = response.data;
            });
        }

        $scope.selecionarTurma = function(turma){
            var achou = false;
            var vazio = false;
            if(!$scope.turmasSelecionadas.length){
                $scope.turmasSelecionadas.push(turma);
                achou = true;
                vazio = true;
            }
            for (var i = 0; i < $scope.turmasSelecionadas.length && vazio === false; i++) {
                if(turma.id === $scope.turmasSelecionadas[i].id){
                    $scope.turmasSelecionadas.splice(i, 1);
                    achou = true;
                }
            }
            if(!achou){
                $scope.turmasSelecionadas.push(turma);
            }            
            $('#turma').prop('checked', $scope.turmasSelecionadas.length === $scope.turmas.length);
        };

        $scope.selecionarTudo = function () {
            if ($scope.turmasSelecionadas.length === $scope.turmas.length) {
                if (!$('#turma')[0].checked) {
                    $scope.turmasSelecionadas = [];
                    $('.checkbox-turma').prop('checked', false);
                }
            } else {
                if ($('#turma')[0].checked) {
                    $scope.turmasSelecionadas = angular.copy($scope.turmas);
                    $('.checkbox-turma').prop('checked', true);
                }
            }
        };

        $scope.selecionarDisciplina = function (disciplina) {
            var achou = false;
            var vazio = false;
            if (!$scope.disciplinasSelecionadas.length) {
                $scope.disciplinasSelecionadas.push(disciplina);
                vazio = true;
                achou = true;
            }
            for (var i = 0; i < $scope.disciplinasSelecionadas.length && vazio === false; i++) {
                if (disciplina.id === $scope.disciplinasSelecionadas[i].id) {
                    $scope.disciplinasSelecionadas.splice(i, 1);
                    achou = true;
                }
            }
            if (!achou) {
                $scope.disciplinasSelecionadas.push(disciplina);
            }
        };

        $scope.selecionarTudoDisciplina = function () {
            if ($scope.disciplinasSelecionadas.length === $scope.disciplinasOfertadas.length) {
                if (!$('#disciplina')[0].checked) {
                    $scope.disciplinasSelecionadas = [];
                    $('.checkbox-turma').prop('checked', false);
                }
            } else {
                if ($('#disciplina')[0].checked) {
                    $scope.disciplinasSelecionadas = angular.copy($scope.disciplinasOfertadas);
                    $('.checkbox-turma').prop('checked', true);
                }
            }
        };

        $scope.trocarTab = function(lista){
            if(lista === 'notasAnual'){
                $scope.editandoPeriodo = false;
                $scope.limparDisciplinas();
            } else if(lista === 'notasPeriodo') {
                $scope.editandoAnual = false;
                $scope.limparTurmas();
            }
        };

        $scope.limparTurmas = function(){
            $scope.etapa.id = null;
            $scope.curso.id = null;
            $scope.etapas = [];
            $timeout(function () {
                $('#curso, #etapa').material_select('destroy');
                $('#curso, #etapa').material_select();
            }, 100);
           $scope.editandoPeriodo = false;
        };

        $scope.limparDisciplinas = function(){
            $scope.etapas = [];
            $scope.turmas = [];
            $scope.curso.id = null;
            $scope.etapa.id = null;
            $scope.turma.id = null;
            $timeout(function () {
                $('#cursoDisciplina, #etapaDisciplina, #turmaDisciplina').material_select('destroy');
                $('#cursoDisciplina, #etapaDisciplina, #turmaDisciplina').material_select();
            }, 100);
            $scope.editandoAnual = false;
        };

        $scope.carregarAnual =  function(turma, disciplina, id){
            $scope.dataAtual();
            $scope.mostrarCortina();
            $scope.disciplinaSAnual = disciplina;
            var promise = Servidor.buscar('enturmacoes', {'turma':turma});
            promise.then(function(response){
               $scope.enturmacoes =  response.data;
               var promiseB = Servidor.buscarUm('unidades-ensino', $scope.enturmacoes[0].turma.unidadeEnsino.id);
               promiseB.then(function(response){
                    $scope.enturmacoes[0].turma.unidadeEnsino = response.data;
               });
               $scope.enturmacoes.forEach(function(enturmacao, i){
                    var promiseC = Servidor.buscar('disciplinas-cursadas', {'matricula': enturmacao.matricula.id, 'disciplina':id});
                    promiseC.then(function(response){
                        var disciplinaCursada = response.data[0];
                        var promiseD = Servidor.buscar('frequencias', {'disciplina': disciplinaCursada.id});
                        $scope.total = 0;
                        promiseD.then(function (response) {
                            var frequencias = response.data;
                            enturmacao.faltas = 0;
                            frequencias.forEach(function(frequencia){
                                if(frequencia.status === 'FALTA'){
                                    enturmacao.faltas++;
                                    $scope.total += 1;
                                }
                            });
                        });
                        var promiseE = Servidor.buscar('medias', {'disciplinaCursada': disciplinaCursada.id});
                        promiseE.then(function(response){
                            enturmacao.medias = response.data;
                            $scope.cabecalhoAlunos = response.data;
                            if($scope.enturmacoes.length-1 === i) {
                                $timeout(function(){
                                    $('.bimestres').width(enturmacao.medias.length / 1 + 'rem');
                                    $scope.editandoEspelhoAnual = true;
                                    $scope.editandoInicial = false;
                                    $scope.editandoEspelhoPeriodo = false;
                                    $scope.fecharCortina();
                                }, 1000);
                            }
                        });
                    });
                });
            });
        };

        $scope.carregarPeriodo =  function(turma){
            $scope.turma = turma;
            $scope.editandoInicial = false;
            $scope.mostrarCortina();
            $scope.editandoEspelhoAnual = false;
            $scope.dataAtual();
            var promise = Servidor.buscar('enturmacoes', {'turma': turma.id});
            promise.then(function(response){
               $scope.enturmacoes = response.data;
               var promiseB = Servidor.buscarUm('unidades-ensino', $scope.enturmacoes[0].turma.unidadeEnsino.id);
               promiseB.then(function(response){
                    $scope.enturmacoes[0].turma.unidadeEnsino = response.data;
               });
                $scope.enturmacoes.forEach(function (enturmacao, i) {
                    var promiseC = Servidor.buscar('disciplinas-cursadas', {'matricula': enturmacao.matricula.id});
                    promiseC.then(function (response) {
                        $scope.cabecalhoDisciplinas = response.data;
                        var promiseD = Servidor.buscar('frequencias', {'matricula': enturmacao.matricula.id});
                        promiseD.then(function (response) {
                            $scope.totalFalta = 0;
                            enturmacao.faltas = 0;
                            var frequencias = response.data;
                            frequencias.forEach(function (frequencia) {
                                if (frequencia.status === 'FALTA') {
                                    $scope.cabecalhoDisciplinas.forEach(function (disciplina) {
                                        if (disciplina.id === frequencia.disciplinaCursada.id) {
                                            enturmacao.faltas++;
                                            $scope.totalFalta += 1;
                                        }
                                    });
                                }
                            });
                        });
                        $scope.fecharCortina();
                        $scope.editandoEspelhoPeriodo = true;
                    });
                });
            });
        };

        $scope.fecharVisualizadorAnual = function(){
            $scope.editandoEspelhoAnual = false;
            $scope.editandoInicial = true;
        };

        $scope.fecharVisualizadorPeriodo = function(){
            $scope.editandoEspelhoPeriodo = false;
            $scope.editandoInicial = true;
        };

        $scope.fecharCortina = function(){
            $scope.cortina = false;
        };

        $scope.mostrarCortina = function () {
            $scope.cortina = true;
        };

        $scope.inicializar = function(){
            $('.tooltipped').tooltip('remove');
            $timeout(function(){
                $('ul.tabs').tabs();
                $('.tooltipped').tooltip({delay: 50});
                $('select').material_select('destroy');
                $('select').material_select();
            }, 500);
            $('.tooltipped').tooltip({delay: 50});
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 50});
            }, 100);
            $scope.buscarCursos();
            $scope.buscarVinculo();
            $scope.buscarAlocacao();
        };

        $scope.inicializar();

    }]);
})();

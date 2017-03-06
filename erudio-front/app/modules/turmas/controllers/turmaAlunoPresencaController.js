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
    var turmaAlunoPresencaModule = angular.module('turmaAlunoPresencaModule', ['servidorModule', 'turmaDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaAlunoPresencaModule.controller('TurmaAlunoPresencaController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin();
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','frequencias-aluno');
        //ATRIBUTOS
        $scope.opcaoPesquisa = ''; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Presenças do Aluno"; $scope.mostraAlunos = true;
        $scope.buscaFrequenciaAluno = { 'matricula': null, 'disciplina': {'id': null}, 'data': null, 'diaAula': null, 'mes': '', 'aula': null };
        $scope.disciplinasCursadas = []; $scope.index = '';
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = '/#/turmas/' + $routeParams.id + '/alunos'; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //FECHAR MODAL JUSTIFICATIVA
        $scope.fecharModal = function () { $('.lean-overlay').hide(); $('.modal').closeModal(); $('#FALTA')[0].checked = false; };
        
        //SELECAO DE ETAPA
        /*$scope.selecionarEtapa = function(etapaId) {
            var promise = Servidor.buscar('disciplinas', {etapa: etapaId});
            promise.then(function(response) {
                var disciplinas = response.data; var opcional = true; $scope.requisicoes = 0;
                disciplinas.forEach(function(d) {
                    $scope.requisicoes++; var promise = Servidor.buscarUm('disciplinas', d.id);
                    promise.then(function(response) {
                        if (!response.data.opcional) { opcional = false; }
                        //if (--$scope.requisicoes === 0 && $scope.etapa.disciplinasOpcionais !== undefined) { $scope.etapa.disciplinasOpcionais = opcional; }
                    });
                });
            });
        };*/
            
        //BUSCA DIA DA FREQUENCIA
        $scope.buscarDiaFrequencia = function () {
            $scope.frequenciasDoAluno = []; $scope.disciplinasFrequencia = [];
            $scope.index = '';
            if ($scope.buscaFrequenciaAluno.data.length === 10) {
                var arrayData = $scope.buscaFrequenciaAluno.data.split('/'); var data = arrayData[2] + "-" + (arrayData[1]) + "-" + arrayData[0];
                var promise = Servidor.buscarUm('turmas', $scope.turma.id);
                promise.then(function (response) {
                    $scope.turma = response.data;
                    var promise = Servidor.buscar('calendarios/' + $scope.turma.calendario.id + '/dias', {'data': data});
                    promise.then(function (response) {
                        if (response.data.length) {
                            if (response.data[0].letivo === false) {
                                Servidor.customToast('Dia não letivo');
                            } else {
                                $scope.idDiaFrequenciaAluno = response.data[0].id; $scope.buscaFrequenciaAluno.diaAula = $scope.idDiaFrequenciaAluno;
                                $scope.buscarDisciplinasFrequencia();
                            }
                        } else { Servidor.customToast('Dia não cadastrado'); }
                    });
                });
            }
        };
        
        //ABRE MODAL DE JUSTIFICATIVA
        $scope.justificarFalta = function (frequencia) {
            $scope.frequencia = frequencia;
            $timeout(function () {
                $scope.desabilitarBotao = true;
                $('#modal-justificativa').openModal();
            }, 100);
        };
        
        //ALTERA FREQUENCIA
        $scope.alteraStatusDaFrequencia = function (status, frequencia) {
            if (frequencia) { $scope.frequencia = frequencia; } $scope.frequencia.status = status;
            if (status === 'PRESENCA' && $scope.frequencia.justificativa === undefined) { $scope.frequencia.justificativa = null; }
            if ($scope.frequencia.justificativa && status !== 'PRESENCA') { $scope.frequencia.status += '_JUSTIFICADA'; }
            var promise = Servidor.finalizar($scope.frequencia, 'frequencias', 'Status');
            promise.then(function (response) {
                $scope.frequenciasDoAluno.forEach(function(freq) { if (freq === response.data.id) { freq = response.data; } }); $scope.frequencia = {};
                $('#modal-justificativa-diarios').closeModal();
            });
        };
            
        //LIMPA OPCAO DO FILTRO
        $scope.limpaOpcao = function () {
            $scope.index = null;
            if ($scope.opcaoPesquisa === 'mes') {
                $scope.buscaFrequenciaAluno.data = null; $scope.frequenciasDoAluno = []; $scope.disciplinasCursadasFrequencia = [];
                $scope.buscarDisciplinasCursadas($scope.matricula.id, $scope.turma.etapa.id);
            } else if ($scope.opcaoPesquisa === 'dia') {
                $scope.buscaFrequenciaAluno.mes = ''; $scope.frequenciasMes = []; $('#aulaData').focus();
                $timeout(function () { $('#disciplinasDoDia').material_select('destroy'); $('#disciplinasDoDia').material_select(); }, 100);
            }
        };
            
        //INICIA MAPA
        $scope.initMap = function (comJanela, idMap) {
            if ($scope.aluno.endereco !== null) {
                var map; var latLng = new google.maps.LatLng($scope.aluno.endereco.latitude, $scope.aluno.endereco.longitude);
                var options = {zoom: 17, center: latLng};
                map = new google.maps.Map(document.getElementById(idMap), options);
                $scope.marker = new google.maps.Marker({position: latLng, title: $scope.aluno.nome, map: map});
                var infowindow = new google.maps.InfoWindow(), marker;
                google.maps.event.addListener(map, 'click', (function (event) {
                    $scope.marker.setMap(null); var newLatLng = event.latLng;
                    $scope.aluno.endereco.latitude = newLatLng.lat(); $scope.aluno.endereco.longitude = newLatLng.lng();
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    $scope.marker = new google.maps.Marker({position: newLatLng, map: map}); infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function (e) { infowindow.open(null, null); }); $('.gm-style').on('click', '.btn-address-close', function (e) { infowindow.open(null, null);});
                    return true;
                }));
                if (comJanela) {
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function (e) { infowindow.open(null, null); }); $('.gm-style').on('click', '.btn-address-close', function (e) { infowindow.open(null, null); });
                }
            }
        };
        
        //MUDA STATUS DE ALUNO PARA FALTA
        $scope.darFalta = function() {
            $scope.aulasDia.forEach(function(aula) {
                if ($scope.frequencia.aula.id === aula.id) {
                    var promise = Servidor.buscarUm('disciplinas-ofertadas', aula.disciplinaOfertada.id);
                    promise.then(function(response) {
                        var disciplina = response.data.disciplina;
                        var params = { matricula: $scope.frequencia.matricula.id, disciplina: disciplina.id };
                        promise = Servidor.buscar('disciplinas-cursadas', params);
                        promise.then(function(response) {
                            var disciplinaCursada = response.data[0];
                            $scope.frequenciasAlunosTurma.forEach(function(freq) {
                                if (disciplinaCursada.id === freq.disciplinaCursada.id) {
                                    if ($scope.frequencia.justificativa === undefined ) { $scope.frequencia.justificativa = null; }
                                    if ($scope.frequencia.justificativa) { freq.justificativa = $scope.frequencia.justificativa; freq.status = 'FALTA_JUSTIFICADA';
                                    } else { freq.status = 'FALTA'; }
                                    var enturmacao = $scope.frequencia.enturmacao;
                                    $('#PRESENCA'+enturmacao.id+enturmacao.matricula.id+$scope.frequencia.aula.id)[0].checked = false;
                                }
                            });
                        });
                    });
                }
            });
        };
        
        //MOSTRA INFORMACAO DO ALUNO
        $scope.infoAluno = function (enturmacao) {
            $scope.mostraProgresso(); $scope.matricula = enturmacao.matricula;
            var promise = Servidor.buscarUm('pessoas', enturmacao.matricula.aluno.id);
            promise.then(function (response) {
                $scope.aluno = response.data; $('#info-aluno').openModal();
                var promise = Servidor.buscar('telefones', {'pessoa': $scope.aluno.id});
                promise.then(function (response) { $scope.telefones = response.data; });
            });
            $timeout(function () { $scope.fechaProgresso();
                if ($scope.aluno.endereco.latitude !== undefined) { $scope.initMap(false, "info-map"); }
            }, 300);
        };
            
        //CARREGAR HORARIO DISCIPLINA
        $scope.carregarHorarioDisciplinas = function (index) {
            var id = $scope.matriculaPresenca.id;
            var promise = Servidor.buscar('frequencias', {'matricula': id, 'aula': $scope.disciplinasFrequencia[index].id});
            promise.then(function (response) {
                $scope.frequenciasDoAluno = response.data;
                if ($scope.frequenciasDoAluno.length === 0) { Servidor.customToast('Nenhuma frequência registrada');
                } else { $scope.textoJustificativa = $scope.frequenciasDoAluno[0].justificativa; }
                $timeout(function () { $('#statusFrequencia').material_select('destroy'); $('#statusFrequencia').material_select(); }, 100);
            });
        };
        
        //BUSCAR DISCIPLINAS DO DIA
        $scope.buscarDisciplinasFrequencia = function () {
            $scope.disciplinasFrequencia = []; $scope.arrayHorarios = [];
            var promisse = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', {'dia': $scope.buscaFrequenciaAluno.diaAula});
            promisse.then(function (response) {
                var cont = 0;
                response.data.forEach(function (a, $index) {
                    var array = a.horario.inicio.split(':'); $scope.arrayHorarios.push(array[0] + " " + $index); cont++;
                    if (cont === response.data.length) {
                        $scope.arrayHorarios.sort();
                        $scope.arrayHorarios.forEach(function (h, $indexH) {
                            var arrayHorario = h.split(' '); var posicao = arrayHorario[1];
                            $scope.disciplinasFrequencia[$indexH] = response.data[posicao];
                        });
                    }
                });
                $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 200);
            });
        };
        
        //BUSCAR FREQUANCIAS MES
        $scope.buscarFrequenciasMes = function (index) {
            $scope.frequenciasMes = []; var id = $scope.matriculaPresenca.id;
            if (index === 'todas') { var disciplina = {'id': null}; } else { disciplina = $scope.disciplinasCursadas[$scope.index]; }
            var promise = Servidor.buscar('frequencias', {'matricula': id, 'mes': $scope.buscaFrequenciaAluno.mes, 'disciplina': disciplina.id});
            promise.then(function (response) {
                if (response.data.length > 0) { $scope.frequenciasMes = response.data; } else { Servidor.customToast('Nenhuma Frequência registrada'); }
            });
        };
        
        //ABRIR FREQUENCIAS DO ALUNO
        $scope.presencaMatricula = function (matricula) {
            $scope.mostraProgresso();
            $scope.nenhumaEnturmacao = false;
            $scope.adicionarAlunos = false;
            $scope.fazerChamada = false;
            
            $scope.buscaFrequenciaAluno.matricula = matricula.id;
            $scope.calendario();
            $scope.mostraEnturmacoes = false;
            $scope.alunoPresenca = true;
            $scope.matriculaPresenca = matricula;
            $timeout(function () {
                $scope.fechaLoader();
                $('#info-aluno').closeModal();
                for (var i = 0; i < 2; i++) {
                    $('select').material_select('destroy');
                    $('select').material_select();
                }
                callDatepicker();
            }, 500);
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id); $('.title-module').html($scope.titulo);
            promise.then(function(response){
                $scope.turma = response.data;
                var promise = Servidor.buscarUm('matriculas',$routeParams.idAluno);
                promise.then(function(response){ $scope.matriculaPresenca = response.data; $('select').material_select('destroy'); $('select').material_select(); });
                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina();}, 1000);
            });
        };
        
        $scope.inicializar(); 
    }]);
})();

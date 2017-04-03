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
    var turmaPresencasModule = angular.module('turmaPresencasModule', ['servidorModule', 'dateTimeModule', 'turmaDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaPresencasModule.controller('TurmaPresencasController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', 'dateTime', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams, dateTime) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','frequencia');
        //ATRIBUTOS
        $scope.enturmacoes = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Lista de Chamada"; $scope.mostraAlunos = true; $scope.index = 0; $scope.aulasDia = [];
        $scope.horarioAula = false; $scope.aulaDisciplina = {}; $scope.alunoNotas = false; $scope.mostraMedias = false; $scope.presencaBtn = true;
        $scope.frequencia = { 'turma': {id: null}, 'disciplina': {id: null}, 'aula': {id: null} }; 
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/turmas/' + $routeParams.id; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        //DISPENSAR TURMA
        $scope.dispensarTurma = function() { $scope.frequenciasAlunosTurma.forEach(function(freq) { freq.status = 'DISPENSA'; freq.justificativa = ''; }); $scope.finalizarChamada(); };
        
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
        
        //SELECIONA O DIA DA CHAMADA
        $scope.escolheDiaChamada = function (data) {
            if (!data) { data = $scope.diaChamada; } $scope.presencas = false; $scope.horarioAula = false; $scope.idDia = null;
            $scope.aulaDisciplina = {}; $scope.aulasDia = [];
            if (data !== undefined && data.length === 10) {
                var dataServidor = Servidor.getDate();
                dataServidor.then(function(response) {
                    var dataAtual = response.data; $scope.aulasDia = []; var chamada = data.split('/').join("");
                    chamada =  chamada.slice(4,8) + '-' + chamada.slice(2,4) + '-' + chamada.slice(0,2);
                    if (dateTime.dateLessOrEqual(chamada, dataAtual)) {
                        var promise = Servidor.buscar('calendarios/' + $scope.turma.calendario.id + '/dias', {'data': chamada});
                        promise.then(function (response) {
                            if (response.data.length) {
                                $scope.idDia = response.data[0].id; $scope.enturmacoesDisciplinas = $scope.enturmacoes; $scope.buscarAulasDia();
                            } else { Servidor.customToast('Este dia não consta no calendário escolar desta turma.'); }
                        });
                    } else { Servidor.customToast('Esta aula ainda não ocorreu.'); }
                });                        
            }
        };
        
        //BUSCA AULAS E DISCIPLINA DO DIA
        $scope.buscarAulasDia = function () {
            $scope.mostraProgresso(); $scope.aulasDia = []; $scope.index = null; $scope.frequencia.aula.id = null; $scope.horarioAula = false; $scope.arrayHorarios = [];
            var promise = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', {'dia': $scope.idDia});
            promise.then(function (response) {
                if(!response.data.length) { return Servidor.customToast('Não há aulas neste dia.'); }
                response.data.forEach(function (d, $index) {
                    var array = d.horario.inicio.split(':'); $scope.arrayHorarios.push(array[0] + " " + $index);
                    if ($index === response.data.length - 1) {
                        $scope.arrayHorarios.sort();
                        $scope.arrayHorarios.forEach(function (h, $indexH) { var arrayHorario = h.split(' '); var posicao = arrayHorario[1]; $scope.aulasDia[$indexH] = response.data[posicao]; });
                    }
                });
                $timeout(function () { $scope.fechaProgresso(); $('select').material_select('destroy'); $('select').material_select(); }, 150);
            });
        };
        
        //CARREGA AULAS
        $scope.carregarAulas = function (index) {
            $scope.frequenciasAlunosTurma = []; $scope.presencas = false; $scope.horarioAula = false; $scope.mostraProgresso();
            $('#todasEnturmacoes')[0].checked = true;
            if (index === 'todas') { $scope.setaPresenca();
            } else {
                var promise = Servidor.buscarUm('aulas', $scope.aulasDia[index].id);
                promise.then(function (response) {
                    $scope.aulaDisciplina = response.data;
                    var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.aulaDisciplina.disciplinaOfertada.id);
                    promise.then(function (responseD) { $scope.aulaDisciplina.disciplinaOfertada = responseD.data; $scope.marcarFrequenciaDisciplina(); });
                });
            }
        };
        
        //SETA PRESENCA PARA TODOS
        $scope.setaPresenca = function () {
            $scope.aulaDisciplina = {}; $scope.horarioAula = false; $scope.frequenciasTurma = []; $scope.frequenciasAlunosTurma = []; var cont = 0;
            $scope.enturmacoesDisciplinas.forEach(function (e, $index) {
                $scope.aulasDia.forEach(function (a, i) {
                    var promise = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'aula': a.id});
                    promise.then(function (response) {
                        cont++; $scope.frequenciasTurma = response.data;
                        if ($scope.frequenciasTurma.length) {
                            $scope.desabilitarBotao = false;
                            if (response.data[0].status === 'PRESENCA') { $('#' + response.data[0].status + e.id + e.matricula.id + a.id)[0].checked = true; }
                            $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = true;
                        } else {
                            $scope.desabilitarBotao = true; $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = false; $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].checked = true; $scope.justificativaDeFalta = null;
                            var frequencia = { 'status': 'PRESENCA', 'disciplinaCursada': {'id': null}, 'aula': {'id': a.id} };
                            var promise = Servidor.buscarUm('disciplinas-ofertadas', a.disciplinaOfertada.id);
                            promise.then(function (response) {
                                var id = response.data.disciplina.id; var promise = Servidor.buscar('matriculas/' + e.matricula.id + '/disciplinas-cursadas', {'disciplina': id});
                                promise.then(function (response) {
                                    if (response.data.length) {
                                        frequencia.disciplinaCursada.id = response.data[0].id; e.matricula.disciplinaCursada = response.data; $scope.frequenciasAlunosTurma.push(frequencia);
                                    } else {
                                        $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = true; $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].checked = false;
                                    }
                                });
                            });
                        }
                        if ($index === $scope.enturmacoesDisciplinas.length-1 && $scope.aulasDia.length-1 === i) {
                            $scope.presencas = true; $timeout(function() { $scope.fechaProgresso(); }, 500);
                        }
                    });
                });
            });
        };
        
        //MARCA FREQUENCIA DE UMA AULA
        $scope.marcarFrequenciaDisciplina = function () {
            var aulaId = $scope.aulaDisciplina.id; $scope.frequenciasTurma = []; $scope.frequenciasAlunosTurma = []; $scope.mostraProgresso();
            $scope.enturmacoesDisciplinas.forEach(function (e, $index) {
                var promise = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'aula': aulaId});
                promise.then(function (response) {
                    $scope.frequenciasTurma = response.data;
                    if ($scope.frequenciasTurma.length) {
                        $scope.desabilitarBotao = false;
                        if (response.data[0].status === 'PRESENCA') { $('#' + response.data[0].status + e.id + e.matricula.id + aulaId)[0].checked = true; }
                        $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].disabled = true;
                    } else {
                        $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].disabled = false; $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].checked = true;
                        $scope.justificativaDeFalta = null;
                        var frequencia = { 'status': 'PRESENCA', 'disciplinaCursada': {'id': null}, 'aula': {'id': aulaId} };
                        var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.aulaDisciplina.disciplinaOfertada.id);
                        promise.then(function (response) {
                            var id = response.data.disciplina.id;
                            var promise = Servidor.buscar('matriculas/' + e.matricula.id + '/disciplinas-cursadas', {'disciplina': id});
                            promise.then(function (response) {
                                e.matricula.disciplinaCursada = response.data; frequencia.disciplinaCursada.id = response.data[0].id; $scope.frequenciasAlunosTurma.push(frequencia);
                            });
                        });
                    }
                    if ($index === $scope.enturmacoesDisciplinas.length -1) {
                        $timeout(function() { $scope.horarioAula = true; $scope.fechaProgresso(); }, 500);
                    }
                });
            });
        };
        
        //SELECIONA TODAS ENTURMACOES
        $scope.selecionarTodasEnturmacoes = function (idAula) {
            $scope.mostraProgresso();
            if($('#todasEnturmacoes')[0].checked){
                var enturmacoesAtivas = 0;
                $scope.enturmacoesDisciplinas.forEach(function(e, index){
                    if($('#PRESENCA'+e.id+e.matricula.id+idAula)[0].checked){ enturmacoesAtivas++; }
                    if(index === $scope.enturmacoesDisciplinas.length-1){
                        if(enturmacoesAtivas === $scope.enturmacoesDisciplinas.length){
                            $('#todasEnturmacoes')[0].checked = false; $scope.fechaProgresso(); Servidor.customToast('Todas as frequências já estão marcadas');
                        }else{ $scope.marcarFrequenciaDisciplina(); }
                    }
                });
            }else{
                $scope.enturmacoesDisciplinas.forEach(function(e, indexE){
                    $('#PRESENCA'+e.id+e.matricula.id+idAula)[0].checked = false;
                    if(indexE === $scope.enturmacoesDisciplinas.length-1){
                        $scope.frequenciasAlunosTurma.forEach(function (f,indexF) {
                            f.status = 'FALTA';
                            if(indexF === $scope.frequenciasAlunosTurma.length-1){ $timeout(function(){ $scope.fechaProgresso(); },200); }
                        });
                    }
                });
            }
        };
        
        //SELECIONAR TODAS AS PRESENCAS POR DISCIPLINA
        $scope.selecionarTodasPrecencasDisciplinas = function(aula) {
            var bool = $('#desmarcar'+aula.id).prop('checked');
            var promise = Servidor.buscarUm('disciplinas-ofertadas', aula.disciplinaOfertada.id);
            promise.then(function(response) {
                var disciplina = response.data.disciplina;
                $scope.enturmacoesDisciplinas.forEach(function(e) {
                    Servidor.buscar('disciplinas-cursadas', {disciplina: disciplina.id, matricula: e.matricula.id, status: 'CURSANDO'})
                    .then(function(response) {
                        var cursada = response.data[0];
                        $('#PRESENCA'+e.id+e.matricula.id+aula.id).prop('checked', bool);
                        $scope.frequenciasAlunosTurma.forEach(function(f) { if (f.disciplinaCursada.id === cursada.id) { f.status = (bool) ? 'PRESENCA' : 'FALTA'; } });
                    });
                });
            });
        };
        
        //VERIFICA PRESENCA OU FALTA
        $scope.verificaFaltaPresenca = function(enturmacao, aula) {
            var presenca = $('#PRESENCA'+enturmacao.id+enturmacao.matricula.id+aula.id)[0];
            if (presenca.checked) { $scope.setaFrequencia(enturmacao.matricula.id, aula.id, enturmacao.id);
            } else {
                presenca.checked = true;
                $scope.frequencia = { matricula: enturmacao.matricula, enturmacao: enturmacao, status: 'FALTA', aula: aula };
                $('#modal-justificativa').modal('open');
            }
        };
        
        //VERIFICA TURMA DISPENSADA
        $scope.verificaTurmaDispensada = function() {
            if ($scope.index === 'todas') { $scope.finalizarChamada();
            } else {
                var cont = 0;
                $scope.frequenciasAlunosTurma.forEach(function(freq) { if (freq.status === 'FALTA') { cont++; } });
                if (cont === $scope.frequenciasAlunosTurma.length) { $('#modal-falta-dispensa').modal(); } else { $scope.finalizarChamada(); }
            }
        };
        
        //FINALIZRA CHAMADA
        $scope.finalizarChamada = function () {
            $scope.mostraProgresso();
            if ($scope.frequenciasAlunosTurma.length) {
                var objetoAdd = {'frequencias': []}; objetoAdd.frequencias = $scope.frequenciasAlunosTurma;
                var result = Servidor.finalizar(objetoAdd, 'frequencias/*', 'Chamada');
                result.then(function (response) {
                    $timeout(function () {
                        $scope.frequenciasAlunosTurma = []; $scope.frequenciasTurma = [];
                        if ($scope.index === 'todas') { $scope.setaPresenca(); } else { $scope.marcarFrequenciaDisciplina(); }
                    }, 350);
                    var frequencia = { 'status': null, 'disciplinaCursada': {'id': null}, 'aula': {'id': null}, 'justificativa': null };
                    $scope.fechaProgresso();
                });
            }
        };
        
        //SETA FREQUENCIA
        $scope.setaFrequencia = function (matricula, aula, enturmacao) {
            $scope.aulasDia.forEach(function (a) {
                if (a.id === aula) {
                    var promise = Servidor.buscarUm('disciplinas-ofertadas', a.disciplinaOfertada.id);
                    promise.then(function (response) {
                        var id = response.data.disciplina.id;
                        var promise = Servidor.buscar('matriculas/' + matricula + '/disciplinas-cursadas', {'disciplina': id});
                        promise.then(function (response) {
                            $scope.frequenciasAlunosTurma.forEach(function (f) {
                                if (response.data[0].id === f.disciplinaCursada.id && f.aula.id === aula) {
                                    if ($('#PRESENCA' + enturmacao + matricula + aula)[0].checked) { f.status = 'PRESENCA'; f.justificativa = '';
                                    } else {
                                        f.status = 'FALTA'; if($('#todasEnturmacoes')[0].checked){ $('#todasEnturmacoes')[0].checked = false; }
                                    }
                                }
                            });
                        });
                    });
                }
            });
        };
        
        //NOTAS DA MATRICULA
        $scope.notasMatricula = function (matricula) {
            $scope.mostraProgresso(); $scope.matricula = matricula;
            if(!$scope.ativaTab){ $('#notasMatriculaID').addClass('card-panel'); }
            $scope.buscarDisciplinasOfertadas(); $('#info-aluno').closeModal();
            if ($scope.turma.etapa.sistemaAvaliacao.tipo === 'QUANTITATIVO') { $scope.sistemaAvaliacao = 'quantitativas'; } else { $scope.sistemaAvaliacao = 'qualitativas'; }
            var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'turma': $scope.turma.id, 'encerrado': 0});
            promise.then(function (response) {
                $scope.etapaEnturmacao = response.data[0].turma.etapa.id;
                $scope.buscarDisciplinasCursadas(matricula.id, $scope.etapaEnturmacao);
            });
        };
        
        //BUSCAR DISCIPLINAS CURSADAS
        $scope.buscarDisciplinasCursadas = function (id, etapa) {
            var promise = Servidor.buscar('matriculas/' + id + '/disciplinas-cursadas', {'etapa': etapa});
            promise.then(function (response) {
                if (response.data.length) {
                    $scope.buscarMedias(); $timeout(function () { $scope.fechaProgresso(); $('select').material_select('destroy'); $('select').material_select(); }, 100);
                } else { $scope.fechaProgresso(); }
            });
        };
        
        //REALIZAR CHAMADA
        $scope.realizarChamada = function (turma) {
            //callDatepicker();
            $scope.mostraProgresso();
            var promise = Servidor.buscarUm('turmas', turma.id);
            promise.then(function (response) {
                $scope.turma = response.data; $scope.enturmacoes = [];
                var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': false});
                promise.then(function (response) {
                    if (response.data.length > 0) {
                        var enturmacoes = response.data;
                        enturmacoes.forEach(function(enturmacao) {
                            var promise = Servidor.buscarUm('enturmacoes', enturmacao.id);
                            promise.then(function(response) {
                                $scope.enturmacoes.push(response.data); if ($scope.enturmacoes.length === enturmacoes.length) { $scope.fechaProgresso(); }
                            });
                        });
                    } else { $scope.mensagem = ''; $scope.semEnturmacoes = true; $scope.fechaProgresso(); }
                    $timeout(function () { $scope.editando = true; Servidor.verificaLabels(); }, 300);
                });
            });
            $scope.nenhumaEnturmacao = false; $scope.adicionarAlunos = false; $scope.voltarAlunos = false;
            var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
            promise.then(function (response) { $scope.disciplinasOfertadas = response.data; });
            $scope.frequencia.turma.id = turma.id; $scope.fazerChamada = true;
        };
        
        //CHAMA DATEPICKER
        var callDatepicker = function() {
            $('.data').pickadate({
                selectMonths: true, selectYears: false, max: 0.5,
                labelMonthNext: 'PRÓXIMO MÊS', labelMonthPrev: 'MÊS ANTERIOR', labelMonthSelect: 'SELECIONE UM MÊS', labelYearSelect: 'SELECIONE UM ANO',
                monthsFull: ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'],
                monthsShort: ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'],
                weekdaysFull: ['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO'],
                weekdayShort: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
                weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                today: 'HOJE', clear: 'LIMPAR', close: 'FECHAR', format: 'dd/mm/yyyy'
            });
            $('.data').click(function() { $('.picker__year').css('margin-left', '7rem'); });
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id); $('.title-module').html($scope.titulo);
            promise.then(function(response){
                $scope.turma = response.data; $scope.realizarChamada($scope.turma);
                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina();}, 1000);
            });
        };        
        
        $scope.inicializar(); 
    }]);
})();
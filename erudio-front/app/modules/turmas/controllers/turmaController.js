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
    // DEFINIÇÃO DO MÓDULO
    var turmaModule = angular.module('turmaModule', ['servidorModule', 'turmaDirectives','erudioConfig']);
    
    turmaModule.service('TurmaService', [function () {
        this.abrirFormulario = false;
        this.abreForm = function () { this.abrirFormulario = true; };
        this.fechaForm = function() { this.abrirFormulario = false; this.setEnturmacao({id:null}); };
        this.enturmacao;
        this.setEnturmacao = function(enturmacao) { this.enturmacao = enturmacao; };
        this.turma;
        this.setTurma = function(turma) { this.turma = turma; };
    }]);

    //DEFINIÇÃO DO CONTROLADOR
    turmaModule.controller('TurmaController', ['$scope', 'Servidor', 'dateTime', 'Restangular', '$timeout', '$compile', 'dateTime', 'TurmaService', 'MatriculaService', '$templateCache', 'ErudioConfig', function ($scope, Servidor, dateTime, Restangular, $timeout, $compile, dateTime, TurmaService, MatriculaService, $templateCache, ErudioConfig) {
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        
            /* ATRIBUTOS GERAIS */
            $scope.editando = false; // VARIAVEL AUXILIAR PARA VERIFICAR SE PÁGINA É DE LISTA OU DE EDIÇÃO.
            $scope.opcoesTurma = false; // VARIAVEL DE CONTROLE PARA TELA DE PROFESSORES
            $scope.abrirResultadosBusca = false; // VARIAVEL PARA ABRIR RESULTADOS DE BUSCA
            $scope.loader = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO CIRCULAR
            $scope.progresso = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO EM LINHAal
            $scope.cortina = false; // VARIAVEL QUE MOSTRA OU ESCONDE A DIV ESCURA TRANSLUCIDA PARA EVITAR ITERAÇÃO COM O USUARIO DURANTE AS CHAMADAS.
            $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; }; // CONTROLE DA BARRA DE PROGRESSO
            $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; }; // CONTROLE DA BARRA DE PROGRESSO
            $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } }; // CONTROLE DO PROGRESSO CIRCULAR
            $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; }; // CONTROLE DO PROGRESSO CIRCULAR
            $scope.ativaTab = false; // CONTROLE DAS ABAS DE CONTEUDO DE TURMA
            $scope.excluirDisciplina = false; // VARIAVEL DE CONTROLE DE EXCLUSAO DE DISCIPLINA DA TURMA
            $scope.role = 'TURMA'; // NOME DA PERMISSAO
            $scope.permissao = true; // CONTROLE DE EXIBICAO DO MÓDULO
            $scope.isAdmin = Servidor.verificaAdmin(); //|| !sessionStorage.getItem('unidade');
            $scope.instituicao = parseInt(sessionStorage.getItem('instituicao'));
            $scope.dataInicioQuadroHorario = '';
            $scope.aulaGerada = false;
            $scope.escrita = Servidor.verificaEscrita('TURMA');
            $scope.disciplinas = []; $scope.disciplina = {id:null}; $scope.disciplinasSalvas = [];
            $scope.disciplinasOfertadas = []; $scope.recemRemovidas = []; $scope.recemAdicionadas = []; $scope.integral = true;

            /* ATRIBUTOS DE TURMA */
            $scope.unidades = []; // ARRAY DE UNIDADES DE ENSINO
            $scope.calendarios = []; // ARRAY DE CALENDARIOS ACADEMICOS
            $scope.cursos = []; // ARRAY DE CURSOS
            $scope.etapas = []; // ARRAY DE ETAPAS
            $scope.turnos = []; // ARRAY DE TURNOS
            $scope.turmas = []; // ARRAY DE TURMAS
            $scope.unidade = {'id':null}; // ESTRUTURA DE UNIDADE DE ENSINO
            $scope.curso = { 'id': null }; // ESTRUTURA DE CURSO
            $scope.etapa = { 'id': null }; // ESTRUTURA DE ETAPA
            $scope.mostraProfessores = false; // CONTROLE DE EXIBIÇÃO DE PROFESSORES
            $scope.disciplinasOfertadasTurma = []; // ARRAY DE DISCIPLINAS OFERTADAS POR TURMA
            $scope.nomeProfessor = null; // LABEL COM O NOME DO PROFESSOR
            $scope.formTurma = false; // VARIAVEL DE CONTROLE DE EXIBICAO DO FORMULÁRIO DA TURMA
            $scope.opcaoForm = ''; // VARIAVEL DE CONTROLE DO FORM
            $scope.disciplinaProfessor = { 'id': null, 'professores': [{'id': null}] }; // ESTRUTURA DE DISCIPLINA DA TURMA
            $scope.turma = { 'nome': '', 'apelido': '', 'calendario': {id: null}, 'limiteAlunos': null, 'turno': {id: null}, 'etapa': {id: null}, 'unidadeEnsino': {id: null}, 'quadroHorario': {id: null}, 'periodo': {id: null} }; // ESTRUTURA DE TURMA
            $scope.cursoTurma = {'id': null}; // ESTRUTURA DO CURSO DA TURMA
            $scope.TurmaService = TurmaService;
            $scope.MatriculaService = MatriculaService;
            $scope.turmaBusca = {curso: {id:null}, etapa:{id:null}};
            $scope.$watch('MatriculaService.abrirFormulario', function(abreForm) {                
                if(abreForm) {
                    $scope.carregarTurma(TurmaService.turma, 'alunos');
                    MatriculaService.fechaForm();
                }
            });
            //MOSTRA DISCIPLINAS OFERTADAS
            //$scope.$watch("turma.etapa.id", function(query){ $scope.buscaDisciplinaOfertada(); });

            /* INICIALIZAÇÃO */
            $scope.inicializar = function () {
                $('.tooltipped').tooltip('remove');
                $timeout(function () {
                    $('.tooltipped').tooltip({delay: 50}); $('ul.tabs').tabs();
                    $('.counter').each(function () { $(this).characterCounter(); });
                    $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                    $('select').material_select('destroy'); $('select').material_select();
                    $scope.calendario();
                    Servidor.entradaPagina();
                }, 1000);
            };
            
            //FECHAR TURMA
            $scope.fecharTurma = function (){
                $scope.mostraProgresso();
                var promise = Servidor.finalizar({nome:null},'disciplinas-ofertadas/'+$scope.disciplinaTurma.id+'/media-final', null);
                promise.then(function(response){
                    Servidor.customToast("Média calculada com sucesso.");
                    $scope.buscarAlunos($scope.disciplinaTurma.id);
                }, function (error) {
                    Servidor.customToast(error.message);
                });
            };
            
            $scope.agrupamentoSelecionado = {id: null};
            //BUSCA DISCIPLINA OFERTADA
            $scope.buscaDisciplinaOfertada = function (etapa) {
                var promise = Servidor.buscarUm('etapas',etapa);
                promise.then(function(response){
                    var etapa = response.data;
                    if (!etapa.integral) {
                        $scope.mostraPeriodo = true; $scope.integral = false;
                        var promise = Servidor.buscar('agrupamentos-disciplinas',{etapa:etapa});
                        promise.then(function (response){
                            $scope.agrupamentos = response.data;
                            $timeout(function(){ $("#disciplinasTurmaFormulario").material_select('destroy'); $("#disciplinasTurmaFormulario").material_select(); },500);
                        });
                    } else {
                        $scope.mostraPeriodo = false; $scope.integral = true;
                    }
                });
            };

            //BUSCA DISCIPLINAS
            $scope.buscaDisciplinas = function (id) {
                $scope.agrupamentoSelecionado.id = id; $scope.disciplinasOfertadas = [];
                var promise = Servidor.buscarUm('agrupamentos-disciplinas',id);
                promise.then(function (response){ 
                    response.data.disciplinas.forEach(function(d){ $scope.disciplinasOfertadas.push({ disciplina: {id: d.id} }); });
                });
            };

            //SELECIONA DISCIPLINA OFERTADA
            $scope.situacaoDisciplina = function (disciplina) {
                if ($scope.turma.id === undefined) {
                    if ($scope.disciplinasOfertadas.length === 0) {
                        $('.d'+disciplina.id).addClass('light-blue accent-2'); $scope.disciplinasOfertadas.push(disciplina.id);
                    } else {
                        var i = $scope.disciplinasOfertadas.indexOf(disciplina.id);
                        if (i >= 0) { $('.d'+disciplina.id).removeClass('light-blue accent-2'); $scope.disciplinasOfertadas.splice(i,1);
                        } else { $('.d'+disciplina.id).addClass('light-blue accent-2'); $scope.disciplinasOfertadas.push(disciplina.id); }
                    }
                }
            };

            //SELECIONA DISCIPLINA OFERTADA
            $scope.situacaoDisciplinaEdit = function (id) {
                id = parseInt(id);
                if ($scope.disciplinasOfertadas.length === 0) {
                    $('.d'+id).addClass('light-blue accent-2');
                    var promise = Servidor.finalizar({disciplina: {id:id}, turma: {id:$scope.turma.id}},'disciplinas-ofertadas','Disciplina');
                    promise.then(function(response){ $scope.buscaDisciplinaOfertada(); });
                } else {
                    $timeout(function(){
                        var indexes = [];
                        for (var i=0; i<$scope.disciplinasOfertadas.length; i++) { indexes.push($scope.disciplinasOfertadas[i].disciplina.id); }
                        $timeout(function(){
                            var ind = indexes.indexOf(id);
                            if (ind >= 0) {
                                $scope.mostraProgresso();
                                var removePromise = Servidor.buscarUm('disciplinas-ofertadas',$scope.disciplinasOfertadas[ind].id);
                                removePromise.then(function(response){ $('.d'+id).removeClass('light-blue accent-2'); Servidor.remover(response.data,'Disciplina'); $scope.fechaProgresso(); });
                                $timeout(function(){ $scope.buscaDisciplinaOfertada(); },500);
                            } else {
                                $('.d'+id).addClass('light-blue accent-2');
                                var promise = Servidor.finalizar({disciplina: {id:id}, turma: {id:$scope.turma.id}},'disciplinas-ofertadas','Disciplina');
                                promise.then(function(response){ $scope.buscaDisciplinaOfertada(); });
                            }
                        },500);
                    },500);
                }
            };
            
            $scope.alfabetizar = function(enturmacao) {
        	$scope.mostraProgresso();
		var promise = Servidor.buscarUm('matriculas', enturmacao.matricula.id);
		promise.then(function(response) {
			var matricula = response.data; //matricula.alfabetizado = (enturmacao.matricula.alfabetizado) ? "" : new Date().getFullYear();
			var promise = Servidor.buscarUm("pessoas",matricula.aluno.id);
			promise.then(function(response){
			    var pessoa = response.data; pessoa.alfabetizado = true;
			    var promise = Servidor.finalizar(pessoa, 'pessoas', 'Aluno');
			    promise.then(function() { $scope.fechaProgresso(); enturmacao.matricula.aluno.alfabetizado = true; });
			});
		});
            };

            $scope.selecionarOpcaoLista = function(turma, opcao) {
                var promise = Servidor.buscarUm('turmas', turma.id);
                promise.then(function(response) {
                    $scope.turma = response.data;
                    $scope.trocarTab(opcao);
                });
            };

            $scope.carregarQuadroHorariosCompativeis = function(turnoId) {
                $scope.quadroHorariosCompativeis = [];
                $scope.turma.quadroHorario = {id: null};
                $('#turnoTurmaForm').material_select('destroy');
                $('#turnoTurmaForm').material_select();
                turnoId = parseInt(turnoId);
                var inicio; var termino;
                $scope.turnos.forEach(function(t) {
                    if(turnoId === t.id) { 
                        var turno = t;
                        inicio = turno.inicio.replace(':','');
                        inicio = parseInt(inicio);
                        termino = turno.termino.replace(':','');
                        termino = parseInt(termino);
                    } 
                });
                $scope.quadroHorarios.forEach(function(qh) {
                    var qhInicio = qh.inicio.replace(':',''); qhInicio = parseInt(qhInicio);
                    var qhTermino = qh.termino.replace(':',''); qhTermino = parseInt(qhTermino);
                    if(qhInicio >= inicio && qhTermino <= termino) {
                        $scope.quadroHorariosCompativeis.push(angular.copy(qh));
                    }
                });
                setTimeout(function() {
                    $('#quadroHorarioTurmaFormulario').material_select('destroy');
                    $('#quadroHorarioTurmaFormulario').material_select();
                    if(!$scope.quadroHorariosCompativeis.length) {
                        Servidor.customToast('Não há nenhum quadro de horarios compatível com este turno.');
                    }
                }, 50);              
            };

            /* CONTROLE DAS TABS */
            $scope.trocarTab = function (tab) {
                $scope.ativaTab = true;
                $scope.oferecendoDisciplina = false;
                switch (tab) {
                    case 'pdf':
                            $scope.mostraPdf = true; $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                            $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                        break;
                    case 'form':
                        if ($scope.turma.id) {
                            $scope.mostraForm = true; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                            $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                        } break;
                    case 'alunos':
                        if ($scope.turma.id) {
                            $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                            $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                            if ($scope.turma.quantidadeAlunos) {
                                $scope.buscarEnturmacoes($scope.turma, true);
                            } else {
                                if(sessionStorage.getItem('unidade')) {
                                    $scope.enturmarAlunos();
                                    Servidor.customToast('Turma não possui nenhum aluno enturmado.');
                                } else {
                                    $scope.trocarTab('form');
                                    Servidor.customToast('Esta turma não possui alunos.');
                                }                                
                            }
                            $timeout(function(){
                                $('#paginaAlunos, #paginaFrequenciaAluno').removeClass('card-panel'); $('#notasMatriculaID').removeClass('card-panel');
                            },100);
                        }else{
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                    case 'oferecer-disciplina':
                        if ($scope.turma.id) {
                            $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                            $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: $scope.turma.id});
                            promise.then(function(response) {
                                $scope.disciplinasOfertadas = response.data;
                                $timeout(function() {
                                    $('#disciplinaOferecida').material_select();
                                }, 500);
                                if ($scope.turma.quantidadeAlunos) {
                                    var promise = Servidor.buscar('enturmacoes', {turma: $scope.turma.id});
                                    promise.then(function(response) {
                                        $scope.enturmacoes = response.data;
                                        $scope.selecionarAlunosAptos();
                                        $scope.oferecendoDisciplina = true;
                                    });
                                } else {
                                    $scope.oferecendoDisciplina = false;
                                    $scope.enturmarAlunos();
                                    Servidor.customToast('Turma não possui nenhum aluno enturmado');
                                }
                            });
                        }else{
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                    case 'quadro':
                        if ($scope.turma.id) {
                            $scope.mostraForm = false; $scope.mostraQuadroHorario = true; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false;
                            $scope.mostraEnturmacoes = false; $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                            $('#paginaQuadro').removeClass('card-panel'); $scope.quadroHorarioTurma($scope.turma);
                        } else {
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                    case 'professores':
                        if ($scope.turma.id) {
                            $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                            $scope.mostraProfessores = true; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                            $scope.fazerChamada = false; $scope.fechaTurma = false;
                            $('#paginaProfessores').removeClass('card-panel'); $scope.professoresDisciplinas($scope.turma);
                            var promise = Servidor.buscarUm('quadro-horarios', $scope.turma.quadroHorario.id);
                            promise.then(function(response) {
                                $scope.quadroHorario = response.data;
                            });
                        } else {
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                    case 'chamada':
                        if ($scope.turma.id) {
                            $scope.mostraForm = false; $scope.mostraQuadroHorario = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false;
                            $scope.mostraEnturmacoes = false; $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.adicionarAlunos = false;
                            $scope.realizarChamada($scope.turma, true); $scope.fechaTurma = false;
                            $scope.editando = true;
                        } else {
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                    case 'fecharTurma':
                        if ($scope.turma.id) {
                            $scope.disciplinaTurma = {id: null}; $scope.endTurma = true;
                            $scope.mostraForm = false; $scope.mostraQuadroHorario = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false;
                            $scope.mostraEnturmacoes = false; $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.adicionarAlunos = false;
                            $scope.fechaTurma = true;
                            var promise = Servidor.buscar('disciplinas-ofertadas', {turma: $scope.turma.id});
                            promise.then(function(response) {
                                $scope.disciplinasTurma = response.data;
                                $timeout(function () { 
                                    $('#disciplinaNota').material_select('destroy'); $('#disciplinaNota').material_select(); $('#disciplina').material_select('destroy'); $('#disciplina').material_select();
                                    //$('#disciplina').change(function(){ $scope.buscarAlunos($(this).val()); });
                                }, 100);
                            });
                            $scope.editando = true;
                        } else {
                            Servidor.customToast('Preencha os dados cadastrais primeiro.');
                        } break;
                };
            };
            
            $scope.encerrarTurma = function (){ 
                $scope.turma.status = "ENCERRADO";
                var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                promise.then(function (response) { $scope.turma = response.data; $scope.fechaProgresso(); $scope.fecharFormulario(); }, function (){ console.log('teste'); $scope.fechaProgresso(); });
            };
            
            //BUSCA ALUNOS
            $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.mediasPorAluno = []; $scope.statusAlunos = []; $scope.botoesEncerrar = true;
            $scope.buscarAlunos = function (disciplina) {
                $scope.disciplinaTurma.id = disciplina; $scope.mdfinal = false; $scope.mdstatus = false; $scope.mdfreq = false; $scope.emAberto = false;
                $scope.enturmacoes = []; $scope.enturmacoesNotas = []; $scope.mostraProgresso(); $scope.nomeAtual = ''; $scope.statusAlunos = []; $scope.botoesEncerrar = true;
                var promise = Servidor.buscar('disciplinas-cursadas', {'disciplinaOfertada': disciplina, 'view': 'medias'});
                promise.then(function(response){
                    $scope.totalMedias = []; for (var j=0; j<$scope.turma.etapa.sistemaAvaliacao.quantidadeMedias; j++) { $scope.totalMedias.push(j); }
                    var frequenciaAprovacao = parseFloat($scope.turma.etapa.sistemaAvaliacao.frequenciaAprovacao);
                    $scope.contadorMediasFaltantes = 0; var exameCounter = 0;
                    for(var i=0; i<response.data.length; i++) {
                        if (response.data[i].emAberto) { $scope.emAberto = true; }
                        if (response.data[i].mediaFinal !== undefined) { $scope.mdfinal = true; }
                        if (response.data[i].status !== undefined) { $scope.mdstatus = true; }
                        if (response.data[i].frequenciaTotal !== undefined) { $scope.mdfreq = true; }
                        if (response.data[i].status === "EM_EXAME" && exameCounter === 0) { $scope.totalMedias.push(4); exameCounter++; }
                        response.data[i].medias.forEach(function(media, j){ if (media.valor === undefined) { media.valor = "N/A"; $scope.contadorMediasFaltantes++; } });
                        if (response.data[i].mediaPreliminar === undefined) { response.data[i].mediaPreliminar = "N/A"; }
                        if (response.data[i].statusPrevisto === "EM_EXAME") { response.data[i].statusPrevisto = "EXAME"; }
                        var frequencia = response.data[i].frequenciaPreliminar;
                        if (response.data[i].statusPrevisto === "REPROVADO" && frequencia < frequenciaAprovacao) { response.data[i].statusPrevisto = "REPROVADO POR FALTA"; }
                    }
                    $scope.enturmacoesNotas = response.data;
                    $timeout(function(){ $scope.botoesEncerrar = false; },500);
                });
            };

            /* INICIALIZAR MAPA */
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
            
            var callDatepicker = function() {
                $('.data').pickadate({
                    selectMonths: true,
                    selectYears: false,
                    max: 0.5,
                    labelMonthNext: 'PRÓXIMO MÊS',
                    labelMonthPrev: 'MÊS ANTERIOR',
                    labelMonthSelect: 'SELECIONE UM MÊS',
                    labelYearSelect: 'SELECIONE UM ANO',
                    monthsFull: ['JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'],
                    monthsShort: ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'],
                    weekdaysFull: ['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO'],
                    weekdayShort: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
                    weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                    today: 'HOJE',
                    clear: 'LIMPAR',
                    close: 'FECHAR',
                    format: 'dd/mm/yyyy'
                });
                $('.data').click(function() { $('.picker__year').css('margin-left', '7rem'); });
            };

            /* -------------------------- MÉTODOS DE TURMA -------------------------- */

            /* REINICIA A BUSCA */
            $scope.reiniciarBusca = function () {
                $scope.etapa.id = null; $scope.nomeUnidade = null;
                $scope.turmaBusca.curso.id = null; $scope.turmaBusca.turmas = [];
                $timeout(function () {
                    $('#unidade, #etapa, #curso').material_select('destroy');
                    $('#unidade, #etapa, #curso').material_select();
                }, 100);
            };

            /* CONTROLE DE SELECTS */
            $scope.verificaUnidadeBusca = function (id) { if (id === $scope.unidade.id) { return true; } };
            $scope.verificaEtapaBusca = function (id) { if (id === $scope.etapa.id) { return true; } };
            $scope.verificaCursoBusca = function (id) { if (id === $scope.curso.id) { return true; } };

            $scope.selecionarEtapa = function(etapaId) {
                var promise = Servidor.buscar('etapas',etapaId);
                promise.then(function(response){ if (response.data.integral) { $scope.integral = true; } });
                var promise = Servidor.buscar('disciplinas', {etapa: etapaId});
                promise.then(function(response) {
                    var disciplinas = response.data;
                    var opcional = true;
                    $scope.requisicoes = 0;
                    disciplinas.forEach(function(d) {
                        $scope.requisicoes++;
                        var promise = Servidor.buscarUm('disciplinas', d.id);
                        promise.then(function(response) {
                            if (!response.data.opcional) {
                                opcional = false;
                            }
                            if (--$scope.requisicoes === 0) {
                                $scope.etapa.disciplinasOpcionais = opcional;
                            }
                        });
                    });
                });
            };

            /* BUSCA DE TURMAS */
            $scope.buscarTurmas = function (origem) {
                $('.tooltipped').tooltip('remove');
                $(document).ready(function(){$('.tooltipped').tooltip({delay: 50});});
                $scope.mostraLoader();
                if (!$scope.unidade.id && origem === 'formBusca') {
                    Servidor.customToast('Selecione uma unidade para realizar a busca de turmas'); $scope.fechaLoader(); $scope.turmas = [];
                } else if ($scope.unidade.id) {
                    $timeout(function () { $('#unidade, #etapa, #curso').material_select('destroy'); $('#unidade, #etapa, #curso').material_select(); }, 100);
                    var url = 'turmas?unidadeEnsino='+$scope.unidade.id;
                    if ($scope.turmaBusca.etapa.id !== null) { url += '&etapa='+$scope.turmaBusca.etapa.id; }
                    if ($scope.turmaBusca.curso.id !== null) { url += '&curso='+$scope.turmaBusca.curso.id; }
                    var promise = Servidor.buscar(url+'&view=contagem_enturmacoes',null);
                    promise.then(function (response) {
                        $scope.turmas = response.data;
                        for (var i =0; i<$scope.turmas.length; i++) {
                            /*var promise = Servidor.buscar('vagas', {'turma': $scope.turmas[i].id});
                            var turma = $scope.turmas[i];
                            promise.then(function(response){
                                var vagas = response.data;
                                for (var j=0; j<vagas.length; j++) {
                                    if (vagas[j].solicitacaoVaga !== undefined) { turma.quantidadeAlunos++; }
                                }
                            });*/
                        }
                        $scope.abrirResultadosBusca = true;
                        if ($scope.turmas.length > 0) {
                            $('.tooltipped').tooltip('remove');
                            $timeout(function () {
                                $('.modal-trigger').leanModal(); $('.tooltipped').tooltip({delay: 50});
                                window.scrollTo(0, 600);
                                $timeout(function () { Servidor.entradaSequencialIn('.card-result', $scope.turmas.length); }, 150);
                            }, 1000);
                        } else {
                            Servidor.customToast('Nenhuma turma encontrada. Verifique os parâmetros de busca e tente novamente.');
                        }
                        $scope.fechaLoader();
                    });
                } else {
                    $scope.reiniciarBusca();
                }
            };

            $scope.selecionaUnidade = function(unidade) {
                if (unidade.tipo === undefined) { unidade.tipo = {sigla:''}; }
                $scope.nomeUnidade = unidade.nomeCompleto;
                if($scope.editando) {
                    if ($scope.turma.unidadeEnsino.id !== unidade.id) {
                        $scope.nomeUnidade = unidade.nomeCompleto;
                        $scope.turma.unidadeEnsino = unidade;
                        $scope.buscarCalendarios(unidade.id);
                        $scope.buscarQuadroHorarios(unidade.id);
                        $scope.buscarCursos();
                    } else if ($scope.unidade.id !== unidade.id) {
                        $scope.nomeUnidade = unidade.nomeCompleto;
                        $scope.turma.unidadeEnsino = unidade;
                        $scope.buscarCalendarios(unidade.id);
                        $scope.buscarQuadroHorarios(unidade.id);
                        $scope.buscarCursos();
                    }
                } else {
                    if ($scope.unidade.id !== unidade.id) {
                        $scope.nomeUnidade = unidade.nomeCompleto;
                        $scope.unidade = unidade;
                        $scope.buscarCursos();
                    }
                }
                $timeout(function(){Servidor.verificaLabels(); },100);
            };

            /* VERIFICA SE HÁ ALOCAÇÃO SELECIONADA */
            $scope.verificaAlocacao = function (nomeUnidade) {
                var alocacao = sessionStorage.getItem('alocacao');
                if ($scope.isAdmin) {
                    if (Servidor.verificarPermissoes($scope.role)) {
                        $scope.permissao = true;
                        if ($scope.isAdmin) {
                            var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                            promise.then(function (response) {
                                $scope.unidades = response.data;
                               $timeout(function () {
                                    $('#unidade').material_select('destroy');
                                    $('#unidade').material_select();
                                    $scope.fechaProgresso(); }, 500);
                            });
                        } else {
                            $scope.alocacao = null;
                        }
                    } else {
                        $scope.permissao = false;
                    }
                } else {
                    if (Servidor.verificarPermissoes($scope.role)) {
                        $scope.permissao = true;
                        /*var promise = Servidor.buscarUm('alocacoes', alocacao);
                        promise.then(function (response) {
                            $scope.alocacao = response.data; $scope.unidades = [$scope.alocacao.instituicao];
                            if ($scope.unidades.length === 1) { $scope.unidade = $scope.alocacao.instituicao; }
                            $timeout(function () {
                                $('#unidade').material_select('destroy');
                                $('#unidade').material_select();
                                $scope.fechaProgresso(); }, 500);
                        });*/
			var promise = Servidor.buscarUm('users',sessionStorage.getItem('pessoaId'));
			promise.then(function(response) {
				var user = response.data;
				$scope.atribuicoes = user.atribuicoes;
				$timeout(function () {
                                    var hasGeral = false;
				    for (var i=0; i<$scope.atribuicoes.length; i++) {
					//if ($scope.atribuicoes[i] !== undefined) {
                                        if ($scope.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push($scope.atribuicoes[i].instituicao); }// else { $scope.isAdmin = true; }
                                        else { $scope.isAdmin = true; console.log($scope.atribuicoes[i]); }
                                        if (i === $scope.atribuicoes.length-1) {
                                            if ($scope.isAdmin) {
                                                /*var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                                                promise.then(function (response) {
                                                    $scope.unidades = response.data;
                                                    $timeout(function () {
                                                        $('#unidade').material_select('destroy');
                                                        $('#unidade').material_select();
                                                        $scope.fechaProgresso(); }, 500);
                                                });*/
                                                $scope.verificaAlocacao();
                                            } else {
                                                if (i === $scope.atribuicoes.length-1) {
                                                    if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; $scope.buscarCursos(); }
                                                    $timeout(function () { $('#unidade').material_select('destroy'); $('#unidade').material_select(); $('#curso').material_select('destroy'); $('#curso').material_select(); $('#cursoE').material_select('destroy'); $('#cursoE').material_select(); $scope.fechaProgresso(); }, 500);
                                                }
                                            }
                                        }
					//} else {
                                            //$scope.fechaProgresso();
					//}
				    }
				},500);
			});
                    } else {
                        $scope.permissao = false;
                    }
                }
            };

            $scope.mostrarLabels = function () {
                $('.toolchip').fadeToggle(250);
            };

            /* VERIFICANDO PERMISSAO DE ESCRITA PARA INPUTS */
            $scope.verificarEscritaInput = function () {
                var result = Servidor.verificaEscrita($scope.role);
                if (result) { return ''; } else { return 'disabled'; }
            };

            /* VERIFICANDO PERMISSAO DE ESCRITA */
            $scope.verificarEscrita = function () {
                var result = Servidor.verificaEscrita($scope.role);
                if (result) { return true; } else { return false; }
            };
            $scope.verificarEscrita = function() {
                return Servidor.verificaEscrita('TURMA');
            };

            /* CARREGA O SELECT DE UNIDADES */
            $scope.buscarUnidades = function () {
                //if (!$scope.editando) {
                    if ($scope.nomeUnidade !== undefined && $scope.nomeUnidade !== null) {
                        if ($scope.nomeUnidade.length > 4) {
                            $scope.mostraProgresso();
                            $scope.verificaAlocacao($scope.nomeUnidade);
                        } else {
                            $scope.unidades = [];
                        }
                    } else {
                        $scope.mostraProgresso();
                        $scope.verificaAlocacao(null);
                    }
                /*} else {
                    if (nome !== undefined && nome !== null) {
                        if (nome.length > 4) {
                            $scope.mostraProgresso();
                            $scope.verificaAlocacao(nome);
                        } else {
                            $scope.unidades = [];
                        }
                    } else {
                        $scope.mostraProgresso();
                        $scope.verificaAlocacao(null);
                    }
                }*/
            };

            /* CARREGA O SELECT DE CURSOS */
            $scope.buscarCursos = function () {
		$scope.mostraProgresso(); var promise = null; $scope.disciplinasOfertadas = []; $scope.disciplinas = []; $scope.integral = true;
		if ($scope.editando) {
			promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.turma.unidadeEnsino.id});
		} else {
			promise = Servidor.buscar('cursos-ofertados', {unidadeEnsino: $scope.unidade.id});
		}
		promise.then(function(response) {
			$scope.cursos = response.data;
			if(response.data.length) {
			    if($scope.cursos.length === 1) { $scope.buscarEtapas($scope.cursos[0].curso.id); }
 	    		    if ($scope.editando) { $scope.mostraProgresso(); $timeout(function () { $('#cursoE').material_select('destroy'); $('#cursoE').material_select(); $scope.fechaProgresso(); }, 1000); } else { $timeout(function () { $('#curso').material_select('destroy'); $('#curso').material_select(); $scope.fechaProgresso(); }, 1000); }
			} else { $scope.fechaProgresso(); Servidor.customToast('Não há cursos nesta unidade.'); }
		});      
            };

            /* CARREGA O SELECT DE ETAPAS */
            $scope.buscarEtapas = function (id) {
                if (id) {
                    $scope.disciplinasOfertadas = []; $scope.disciplinas = []; $scope.integral = true;
                    if($scope.editando) { $scope.turma.etapa.id = null; } else { $scope.turmaBusca.etapa.id = null; }
                    var promise = Servidor.buscar('etapas', {'curso': id});
                    promise.then(function (response) {
                        $scope.etapas = response.data;
                        if ($scope.etapas.length === 0) { Materialize.toast('Nenhuma Etapa cadastrada', 1500); }
                        $timeout(function () {
                            if($scope.editando) {
                                $('#etapaForm').material_select('destroy');
                                $('#etapaForm').material_select();
                            } else {
                                $('#etapa').material_select('destroy');
                                $('#etapa').material_select();
                            }               
                        }, 200);
                    });
                }
            };

            /* CARREGA ARRAY DE TURNOS */
            $scope.buscarTurnos = function () {
                var promise = Servidor.buscar('turnos', null);
                promise.then(function (response) {
                    $scope.turnos = response.data;
                    $timeout(function() {
                        $('#turnoTurmaForm').material_select();
                    }, 500);
                });
            };

            /* CARREGA ARRAY DE CALENDARIOS */
            $scope.buscarCalendarios = function (unidade) {
                /*var uni = JSON.parse(sessionStorage.getItem('unidade'));
                if (!unidade) { unidade = uni.id; }
                unidade = ($scope.isAdmin) ? unidade : uni.id;*/
                if ($scope.turma.unidadeEnsino.id !== null) { 
                    unidade = $scope.turma.unidadeEnsino.id;
                    var promise = Servidor.buscar('calendarios', {instituicao: unidade});
                    promise.then(function (response) {
                        $scope.calendarios = response.data;
                        if ($scope.calendarios.length > 1) {
                            $scope.calendarios.forEach(function(calendario) {
                                if (calendario.instituicao.id === $scope.instituicao) {
                                    $scope.turma.calendario = calendario;
                                }
                            });
                        } else {
                            $scope.turma.calendario = $scope.calendarios[0];
                        }
                        $timeout(function() {
                            $('#calendarioTurmaForm').material_select();
                        }, 500);
                    });
                } else {
                    $timeout(function() {
                        $('#calendarioTurmaForm').material_select();
                    }, 500);
                }
                
            };

            /* CARREGA ARRAY DE QUADRO DE HORARIOS */
            $scope.buscarQuadroHorarios = function (unidade) {
                if(unidade === undefined || !unidade) {
                    var uni = JSON.parse(sessionStorage.getItem('unidade'));
                    unidade = ($scope.isAdmin) ? $scope.turma.unidadeEnsino.id : uni.id;
                }
                var promise = Servidor.buscar('quadro-horarios', {unidadeEnsino: unidade});
                promise.then(function (response) {
                    $scope.quadroHorarios = response.data;                    
                    if ($scope.quadroHorarios.length === 1) {
                        $scope.turma.quadroHorario = $scope.quadroHorarios[0];
                    }
                    $timeout(function() {
                        $('#quadroHorarioTurmaFormulario').material_select();
			//$('#cursoE').material_select('destroy');
                        $('#cursoE').material_select(); $scope.fechaProgresso();
                    }, 500);
                });
            };

            /* VALIDAÇÃO DE FORMULÁRIO */
            $scope.validar = function (id) { if (Servidor.validar(id)) { return true; } };

            $scope.statusTurma = function(){ var retorno = true; if ($scope.turma.status === 'ENCERRADO') { retorno = false; } return retorno; };

            /* SALVAR TURMA */
            $scope.finalizar = function () {
                delete $scope.turma.periodo;
                if ($scope.integral) {
                    if ($scope.turma.nome && $scope.turma.limiteAlunos && $scope.turma.etapa.id && $scope.turma.turno.id && $scope.turma.calendario.id && $scope.turma.quadroHorario.id) {
                        var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                        promise.then(function (response) { $scope.turma = response.data; $scope.fechaProgresso(); $scope.fecharFormulario(); }, function (error) { Servidor.customToast(error.message); $scope.fechaProgresso(); $scope.fecharFormulario(); });
                        $scope.fechaProgresso(); $scope.fecharFormulario();
                    } else {
                        Servidor.customToast("Campos obrigatórios não preenchidos");
                        $scope.fechaProgresso();
                    }
                } else {
                    //if ($scope.disciplinasOfertadas.length > 0) {
                        $scope.turma.periodo.id = parseInt($scope.turma.periodo.id);
                        if ($scope.turma.nome && $scope.turma.limiteAlunos && $scope.turma.etapa.id && $scope.turma.turno.id && $scope.turma.calendario.id && $scope.turma.quadroHorario.id && $scope.turma.periodo.id && $scope.agrupamentoSelecionado.id) {
                            var novo = false;
                            if ($scope.turma.id === undefined) { novo = true; }
                            var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                            promise.then(function (response) { 
                                $scope.turma = response.data; $scope.fechaProgresso();
                                if (novo) { var disciPromise = Servidor.finalizar({disciplinasOfertadas:$scope.disciplinasOfertadas},'turmas/'+$scope.turma.id+'/disciplinas-ofertadas','Disciplinas'); $scope.fecharFormulario(); }
                                $scope.fechaProgresso(); $scope.fecharFormulario();
                            }, function (error) {
                                Servidor.customToast(error.message);
                                $scope.fechaProgresso(); $scope.fecharFormulario();
                            });
                        } else {
                            Servidor.customToast("Campos obrigatórios não preenchidos");
                            $scope.fechaProgresso();
                        }
                    //} else { Servidor.customToast("A etapa desta turma não possui disciplinas, escolha-as."); $scope.fechaProgresso(); }
                }
                /*$scope.mostraLoader();
                if (!etapaId) {
                    etapaId = $scope.etapa.id;
                }
                var promise = Servidor.buscar('disciplinas', {etapa: $sprcope.turma.etapa.id});
                promise.then(function(response) {
                    if(response.data.length) {
                        $scope.turma.unidadeEnsino = {id: $scope.turma.unidadeEnsino.id};
                        $scope.turma.calendario = {id: $scope.turma.calendario.id};
                        $scope.turma.etapa = {id: $scope.turma.etapa.id};
                        $scope.turma.turno = {id: $scope.turma.turno.id};
                        if ($scope.turma.nome && $scope.turma.limiteAlunos && $scope.turma.etapa.id && $scope.turma.turno.id && $scope.turma.calendario.id && $scope.turma.quadroHorario.id) {
                            var promise = Servidor.finalizar($scope.turma, 'turmas', 'Turma');
                            promise.then(function (response) {
                                $scope.unidade.id = response.data.unidadeEnsino.id;
                                $scope.curso.id = response.data.etapa.modulo.curso.id;
                                $scope.etapa.id = response.data.etapa.id;
                                $scope.turma = response.data;
                                $scope.fecharFormulario();
                                $scope.fechaLoader();
                            });
                        } else {
                            Servidor.customToast("Campos obrigatórios não preenchidos");
                            $scope.fechaLoader();
                        }
                    } else {
                        Servidor.customToast("A etapa desta turma não possui disciplinas.");
                        $scope.fechaLoader();
                    }
                });    */                    
            };
            
            $scope.buscarPeriodos = function(calendarioId,etapaId){
                var promise = Servidor.buscarUm('etapas',etapaId);
                promise.then(function(response){
                    if (!response.data.integral) { $scope.integral = false; }
                    $scope.periodoLabel = response.data.sistemaAvaliacao.regime.unidade; $scope.mostraPeriodo = true;
                    var promisePeriodos = Servidor.buscar('periodos',{calendario: calendarioId});
                    promisePeriodos.then(function(response){ 
                        $scope.periodos = response.data;
                        $timeout(function(){ $("#periodoTurmaFormulario").material_select('destroy'); $("#periodoTurmaFormulario").material_select(); },500);
                    });
                }); 
            };

            /* CARREGA TURMA PARA EDIÇÃO */
            $scope.carregarTurma = function (turma, opcao) {
                //window.scrollTo(0, 0);
                $scope.periodos = []; $('#turmaForm').show(); $('#form').addClass('active'); $scope.trocarTab('form'); $scope.endTurma = false;
                $('#nome').focus(); $('div').find('.unidade-banner').removeClass('topo-pagina');
                Servidor.verificaLabels(); $scope.mostraEnturmacoes = false;
                $scope.opcaoForm = 'turma'; $scope.mostraLoader();
                if (turma) { 
                    $scope.disciplinas = []; $scope.integral = true;
                    $('#alunos').removeClass('botao-desabilitado'); $('#professores').removeClass('botao-desabilitado'); $('#grade').removeClass('botao-desabilitado'); $('#alunos').addClass('yellow waves-effect');
                    $('#professores').addClass('green waves-effect'); $('#quadro').addClass('blue white-text waves-effect'); $('.material-icons').removeClass('cor-botao-desabilitado');
                    $scope.acao = "Editar";
                    var promise = Servidor.buscarUm('turmas', turma.id);
                    promise.then(function (result) {
                        $scope.quadroHorariosCompativeis = []; $scope.quadroHorariosCompativeis.push(result.data.quadroHorario);
                        $scope.turma = result.data; $('#tabAlunos, #tabQuadro, #tabProfessores').removeClass('disabled');
                        $timeout(function () {
                            $scope.unidade.id = $scope.turma.unidadeEnsino.id; 
                            var promise = Servidor.buscarUm('etapas',$scope.turma.etapa.id);
                            promise.then(function(response){
                                $scope.cursoTurma.id = response.data.curso.id;
                                $scope.curso.id = response.data.curso.id; $scope.etapa.id = $scope.turma.etapa.id;
                                $scope.buscarEtapas(response.data.curso.id);
                                //if (!response.data.integral) { $scope.buscaDisciplinaOfertada(response.data.id); $scope.buscarPeriodos(result.data.calendario.id, result.data.etapa.id); }
                                if (!response.data.integral) { $scope.buscarPeriodos($scope.turma.calendario.id, response.data.id); $scope.integral = false; }
                                $scope.nomeUnidade = $scope.turma.unidadeEnsino.nomeCompleto;
                                $scope.editando = true; $('#voltar').show(); $scope.mostraForm = true; $scope.formTurma = true;
                                $scope.buscarCalendarios($scope.turma.unidadeEnsino.id);
                                $scope.buscarQuadroHorarios(); $scope.buscarTurnos();
                                if (opcao) { $scope.trocarTab(opcao); $scope.origemBotao = false; } else { $scope.origemBotao = true; }
                                $timeout(function () { $('#nome').focus(); $('select').material_select('destroy'); $('select').material_select(); Servidor.verificaLabels(); $scope.fechaLoader(); }, 800);
                            });
                        }, 500);
                    });
                } else {
                    $scope.acao = "Cadastrar"; $('#alunos').removeClass('yellow waves-effect'); $scope.disciplinas = []; $scope.integral = true;
                    $('#professores').removeClass('green waves-effect'); $('#quadro').removeClass('blue waves-effect');
                    $('#alunos').addClass('botao-desabilitado'); $('#professores').addClass('botao-desabilitado');
                    $('#quadro').addClass('botao-desabilitado'); $('.ico-btn-turma').addClass('cor-botao-desabilitado'); //$("#calendarioTurmaForm").attr('disabled','');
                    $scope.turma = { 'nome': '', 'apelido': '', 'calendario': {'id': null}, 'turno': {'id': null}, 'etapa': {'id': $scope.turmaBusca.etapa.id, curso: {'id': $scope.turmaBusca.curso.id}}, 'unidadeEnsino': {'id': $scope.unidade.id}, 'quadroHorario': {'id': null}, 'periodo': {id: null} };
                    $timeout(function(){
                        $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                    },100);
                    $timeout(function () {
                        Servidor.verificaLabels();$scope.fechaLoader();
                        $scope.editando = true; $('#voltar').show();
                        $scope.mostraForm = true; $scope.formTurma = true;
                        if ($scope.turmaBusca.etapa.id !== undefined && $scope.turmaBusca.etapa.id !== null && $scope.turmaBusca.etapa.id !== "") { $scope.buscaDisciplinaOfertada($scope.turmaBusca.etapa.id); }
                        $scope.buscarCalendarios(); $scope.buscarQuadroHorarios(); $scope.buscarTurnos();
			if ($scope.isAdmin) { $scope.buscarUnidades(); }
                        if (opcao) { $scope.trocarTab(opcao); $scope.origemBotao = false; } else { $scope.origemBotao = true; }
                        $timeout(function () { $('#nome').focus(); $('select').material_select('destroy'); $('select').material_select(); }, 1000);
                    }, 500);
                }                
            };

            $scope.calendarioPreenchido = function() {
                console.log($scope.turma.id);
            };

            /* BUSCA DISCIPLINAS DE ACORDO COM OS VINCULOS */
            $scope.disciplinasOfertadasProfessores = function (turma) {
                $scope.disciplinasOfertadasTurma = [];
                var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                promise.then(function (response) {
                    if (response.data.length) {
                        $scope.disciplinasOfertadasTurma = response.data;
                        response.data.forEach(function (d, indexD) {
                            var promiseD = Servidor.buscarUm('disciplinas-ofertadas', d.id);
                            promiseD.then(function (responseD) {
                                $scope.disciplinasOfertadasTurma[indexD] = responseD.data;
                                if (responseD.data.professores.length > 0) {
                                    responseD.data.professores.forEach(function (a, $index) {
                                        var promise = Servidor.buscarUm('alocacoes', a.id);
                                        promise.then(function (responseA) {
                                            responseD.data.professores[$index] = responseA.data;
                                            if ($index === responseD.data.professores.length - 1) {
                                                $scope.disciplinasOfertadasTurma[indexD] = responseD.data;
                                                $('.tooltipped').tooltip('remove');
                                                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); }, 50);
                                            }
                                        });
                                    });
                                }
                                if (indexD === $scope.disciplinasOfertadasTurma.length - 1) { $scope.diasDisciplinas(); }
                            });
                        });
                    } else {
                        Servidor.customToast('Turma não possui nenhuma disciplina cadastrada'); $scope.fechaLoader();
                    }
                });
            };

            /* BUSCA OS DIAS DA DISCIPLINA */
            $scope.diasDisciplinas = function () {
                var cont = 0;
                $scope.disciplinasOfertadasTurma.forEach(function(d, index){
                    var promiseH = Servidor.buscar('horarios-disciplinas', {'disciplina': d.id});
                    promiseH.then(function(responseH){
                        cont++;
                        if(responseH.data.length){
                            $scope.disciplinasOfertadasTurma[index].dias = [];
                            responseH.data.forEach(function(h, indexH){
                                if($scope.disciplinasOfertadasTurma[index].dias.length > 0){
                                    var cont = 0;
                                    for (var i = 0; i < $scope.disciplinasOfertadasTurma[index].dias.length;i++) {
                                        if(h.horario.diaSemana.diaSemana === $scope.disciplinasOfertadasTurma[index].dias[i]){ cont++; }
                                        if(i === $scope.disciplinasOfertadasTurma[index].dias.length-1 && cont === 0){ $scope.disciplinasOfertadasTurma[index].dias.push(h.horario.diaSemana.diaSemana); }
                                    }
                                }else{
                                    $scope.disciplinasOfertadasTurma[index].dias.push(h.horario.diaSemana.diaSemana);
                                }
                            });
                        }
                        if(cont === $scope.disciplinasOfertadasTurma.length){ $scope.converteDiasSemana(); }
                    });

                });
            };

            $scope.getDiaSemanaNome = function(diaSemana) {
                switch(diaSemana) {
                    case "1": return "Domingo";
                    case "2": return "Segunda";
                    case "3": return "Terça";
                    case "4": return "Quarta";
                    case "5": return "Quinta";
                    case "6": return "Sexta";
                    case "7": return "Sábado";
                    default : return null;
                }
            };

            $scope.abrirModalHorarios = function(disciplina) {
                $scope.quadroHorario.modelo.posicaoIntervalo = parseInt($scope.quadroHorario.modelo.posicaoIntervalo);
                var promise = Servidor.buscar('horarios-disciplinas', {disciplina: disciplina.id});
                promise.then(function(response) {
                    $scope.disciplina = disciplina;
                    $scope.horarios = response.data;                    
                    var aulas = parseInt($scope.quadroHorario.modelo.quantidadeAulas);
                    $scope.horarios.forEach(function(h) {
                        var count = 1;
                        $scope.quadroHorario.horarios.forEach(function(hs) {                            
                            if(h.horario.id === hs.id) {
                                h.naula = count;
                            }
                            if(++count > aulas) { count = 1; }
                        });
                    });                    
                    $('#horarios-disciplina').openModal();
                });
            };

            /* CONVERTE O NUMERO PARA O NOME DO DIA */
            $scope.converteDiasSemana = function () {
                $scope.disciplinasOfertadasTurma.forEach(function(d, index){
                    if(d.dias){
                        d.dias.sort();
                        d.dias.forEach(function(dia, i){
                            switch (dia){
                                case "2":
                                    $scope.disciplinasOfertadasTurma[index].dias[i] = 'Segunda';
                                    break;
                                case "3":
                                    $scope.disciplinasOfertadasTurma[index].dias[i] = 'Terça';
                                    break;
                                case "4":
                                    $scope.disciplinasOfertadasTurma[index].dias[i] = 'Quarta';
                                    break;
                                case "5":
                                    $scope.disciplinasOfertadasTurma[index].dias[i] = 'Quinta';
                                    break;
                                case "6":
                                    $scope.disciplinasOfertadasTurma[index].dias[i] = 'Sexta';
                                    break;
                            };
                        });
                    }
                    if(index === $scope.disciplinasOfertadasTurma.length-1){
                        window.scrollTo(0, 0); $scope.fechaLoader();
                        $scope.editando = true; $scope.mostraProfessores = true;
                    }
                });
            };

            /* BUSCA AS DISCIPLINAS POR PROFESSOR */
            $scope.professoresDisciplinas = function (turma) {
                $scope.mostraLoader();
                var promise = Servidor.buscarUm('turmas', turma.id);
                promise.then(function (response) {
                    $scope.turma = response.data;
                    $scope.disciplinasOfertadasProfessores($scope.turma);
                    $('.tooltipped').tooltip('remove');
                    $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                    $('.opcoesTurma' + turma.id).hide(); $('.btn-voltar').show();
                });
            };

            /* DROPDOWN DE PROFESSORES */
            $scope.adicionarProfessor = function (fromModal) {
                $scope.disciplinaProfessor.id = null; $scope.nomeProfessor = '';
                $timeout(function () {
                    if(!fromModal) {
                        $('#modal-adicionar-professor').openModal();
                        $timeout(function () {
                            $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                        }, 200);
                    }
                    $('#disciplinaProfessor').material_select('destroy'); $('#disciplinaProfessor').material_select();
                }, 200);
            };

            /*BUSCA ALOCACOES DE PROFESSORES */
            $scope.buscarAlocacoes = function () {
                if (!$scope.nomeProfessor) {
                    $scope.professores = [];
                } else {
                    if ($scope.nomeProfessor.length >= 3) {
                        var promise = Servidor.buscar('alocacoes', {'funcionario_nome': $scope.nomeProfessor, 'professor': 1, 'instituicao': $scope.turma.unidadeEnsino.id});
                        promise.then(function (response) {
                            if (response.data.length) {
                                $timeout(function () { $scope.professores = response.data; }, 200);
                            } else {
                                Servidor.customToast('Nenhum professor encontrado');
                            }
                        });
                    }
                }
            };

            /* CARREGA ID DA ALOCAÇÃO */
            $scope.carregaFuncionario = function (professor) {
                $scope.nomeProfessor = professor.vinculo.funcionario.nome; $scope.disciplinaProfessor.professores[0].id = professor.id;
                $timeout(function () { Servidor.verificaLabels(); }, 100);
            };

            /* ADICIONA PROFESSOR NA DISCIPLINA */
            $scope.finalizarProfessores = function () {
                if($scope.disciplinaProfessor.professores[0] === undefined || !$scope.disciplinaProfessor.professores[0].id) {
                    return Servidor.customToast('Selecione um professor.');
                }
                $scope.mostraLoader(); var cont = 0; var adicionaProfessor = false;
                var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.disciplinaProfessor.id);
                promise.then(function (response) {
                    $scope.disciplina = response.data;
                    if ($scope.disciplina.professores.length) {
                        $scope.disciplinaProfessor.professores.forEach(function (f) {
                            $scope.disciplina.professores.forEach(function (a) {
                                if (f.id === a.id) {
                                    Servidor.customToast('Professor já ministra esta disciplina.'); $scope.nomeProfessor = '';
                                    $scope.professores = []; $scope.fechaLoader();
                                    return false;
                                } else {
                                    cont++;
                                }
                                if (cont === $scope.disciplina.professores.length) {
                                    $scope.disciplina.professores.push(f); adicionaProfessor = true;
                                }
                            });
                        });
                    } else {
                        adicionaProfessor = true; $scope.disciplina.professores = $scope.disciplinaProfessor.professores;
                    }
                    if (adicionaProfessor) {
                        var promise = Servidor.finalizar($scope.disciplina, 'disciplinas-ofertadas', null);
                        promise.then(function (response) {
                            Servidor.customToast('Professor adicionado com sucesso.');
                            $scope.disciplinasOfertadasTurma.forEach(function (d, $indexD) {
                                if (d.id === response.data.id) {
                                    var dias = $scope.disciplinasOfertadasTurma[$indexD].dias;
                                    $scope.disciplinasOfertadasTurma[$indexD] = response.data;
                                    $scope.disciplinasOfertadasTurma[$indexD].dias = dias;
                                    $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (a) {
                                        var promise = Servidor.buscarUm('alocacoes', a.id);
                                        promise.then(function (responseA) {
                                            $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (p, $index) {
                                                if (p.id === responseA.data.id) { $scope.disciplinasOfertadasTurma[$indexD].professores[$index] = responseA.data; }
                                            });
                                        });
                                    });
                                }
                            });
                            $timeout(function () { $scope.fechaLoader(); $scope.adicionarProfessor(true); }, 300);
                        });
                    }
                });
            };

            /* CARREGA INFORMAÇÃO DO PROFESSOR NA MODAL */
            $scope.carregaInfoProfessor = function (alocacao) {
                $scope.funcionario = null; $scope.telefones = [];
                $scope.disciplinasMinistradas = []; $scope.alocacao = alocacao;
                $scope.mostraLoader();
                $scope.alocacao.disciplinasMinistradas.forEach(function (d) {
                    var promise = Servidor.buscarUm('disciplinas-ofertadas', d.id);
                    promise.then(function (response) { $scope.disciplinasMinistradas.push(response.data); });
                });
                var promise = Servidor.buscarUm('pessoas', alocacao.vinculo.funcionario.id);
                promise.then(function (response) {
                    $scope.funcionario = response.data;
                    var promise = Servidor.buscar('telefones', {'pessoa': $scope.funcionario.id});
                    promise.then(function (response) { $scope.telefones = response.data; $('#info-professor').openModal(); $scope.fechaLoader(); });
                });
            };

            /* GUARDA ALOCAÇÃO PARA REMOVER */
            $scope.prepararRemoverProfessor = function (alocacao, disciplina) {
                $scope.disciplinaRemover = disciplina; $scope.alocacaoRemover = alocacao; $('#remove-modal-professor').openModal();
            };

            /* REMOVE ALOCAÇÃO */
            $scope.removerProfessor = function () {
                var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.disciplinaRemover.id);
                promise.then(function (response) {
                    $scope.disciplina = response.data;
                    $scope.disciplina.professores.forEach(function (a, $index) {
                        if (a.id === $scope.alocacaoRemover.id) {
                            $scope.disciplina.professores.splice($index, 1);
                            var promise = Servidor.finalizar($scope.disciplina, 'disciplinas-ofertadas', 'Disciplina Ofertada');
                            promise.then(function (response) {
                                $scope.disciplinasOfertadasTurma.forEach(function (d, $indexD) {
                                    if (d.id === response.data.id) {
                                        var dias = $scope.disciplinasOfertadasTurma[$indexD].dias;
                                        $scope.disciplinasOfertadasTurma[$indexD] = response.data;
                                        $scope.disciplinasOfertadasTurma[$indexD].dias = dias;
                                        $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (a) {
                                            var promise = Servidor.buscarUm('alocacoes', a.id);
                                            promise.then(function (responseA) {
                                                $scope.disciplinasOfertadasTurma[$indexD].professores.forEach(function (p, $index) {
                                                    if (p.id === responseA.data.id) { $scope.disciplinasOfertadasTurma[$indexD].professores[$index] = responseA.data; }
                                                });
                                            });
                                        });
                                    }
                                });
                            });
                        }
                    });
                    $timeout(function () { $('#remove-modal-professor').closeModal(); }, 300);
                });
            };

            /* MODAL DE CONFIRMAÇÃO DO BOTAO DE VOLTAR */
            $scope.prepararVoltar = function (objeto) {
                if (objeto.nome && !objeto.id) {
                    $('#modal-certeza').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };

            /* PREPARAÇÃO PARA REMOÇÃO DE TURMA */
            $scope.prepararRemover = function (turma) {
                if(turma.quantidadeAlunos > 0) {
                    Servidor.customToast('Esta turma possui enturmações, portanto não é possível removê-la.');
                } else {
                    $scope.turmaRemover = turma; $('#remove-modal-turma').openModal();
                }                
            };

            /* REMOÇÃO DE TURMA */
            $scope.remover = function () {
                $scope.mostraProgresso();
                Servidor.remover($scope.turmaRemover, 'Turma');
                $timeout(function () { $scope.buscarTurmas(); $scope.fechaProgresso(); }, 1000);
            };

            /* CONTROLE DE SELECT */
            $scope.verificaUnidade = function (id) { if (id === $scope.turma.unidadeEnsino.id) { return true; } };
            $scope.verificaCurso = function (id) { if ($scope.turma.id) { if (id === $scope.cursoTurma.id) { return true; } } };
            $scope.verificaEtapa = function (id) { if (id === $scope.turma.etapa.id) { return true; } };
            $scope.verificaTurno = function (id) { if (id === $scope.turma.turno.id) { return true; } };
            $scope.verificaQuadroHorario = function (id) { if (id === $scope.turma.quadroHorario.id) { return true; } };
            $scope.verificaCalendario = function (id) { if (id === $scope.turma.calendario.id) { return true; } };

            /* TELA DE ERRO */
            $scope.reiniciaErroBusca = function () { $scope.nenhumaEnturmacao = false; $scope.adicionarAlunos = false; };

            /*-------------------------------------------------------------Enturmação------------------------------------------------------------*/
            $scope.disciplinasCursadas = [];
            $scope.enturmacoes = [];
            $scope.nenhumaEnturmacao = false;
            $scope.enturmandoAlunos = false;
            $scope.adicionarAlunos = false;
            $scope.mostraEnturmacoes = false;
            $scope.enturmarAlunosDireto = false;
            $scope.matriculaBusca = {
                'aluno': '',
                'status': '',
                'codigo': '',
                'curso': null,
                'unidade': null
            };
            $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};

            /*Form enturmação*/
            $scope.enturmarAlunos = function () {
                $scope.enturmandoAlunos = true;
                $scope.adicionarAlunos = false;
                $scope.nenhumaEnturmacao = false;
                $scope.mostraEnturmacoes = false;
                $scope.mostraQuadroHorario = false;
                $scope.botaoEnturmacaoAutomatica = false;
                if ($scope.opcaoForm === 'alunos') {
                    $scope.enturmarAlunosDireto = true;
                } else {
                    $scope.enturmarAlunosDireto = false;
                }
                $scope.voltarAlunos = true;
                Servidor.verificaLabels();
                $scope.verificarDiaLetivo($scope.turma.calendario, new Date().toJSON().split('T')[0]);
            };

            $scope.verificarDiaLetivo = function(calendario, data) {
                var promise = Servidor.buscar('calendarios/'+calendario.id+'/dias', {data: data});
                promise.then(function(response) {
                    if (response.data.length) {
                        var dia = response.data[0];
                        if (dia.letivo) {
                            $scope.botaoEnturmacaoAutomatica = false;
                        } else {
                            var d = data.split('-');
                            data = new Date(d[0], d[1], parseInt(d[2]-1)).toJSON().split('T')[0];
                            $scope.verificarDiaLetivo(calendario, data);
                        }
                    } else {
                        $scope.botaoEnturmacaoAutomatica = true;
                    }
                });
            };

            $scope.voltarParaAlunos = function () {
                $scope.enturmacaoAutomatica = false;
                $scope.enturmandoAlunos = false;
                $scope.voltarAlunos = false;
                $scope.horarioAula = false;
                $scope.alunoPresenca = false;
                $scope.alunoNotas = false;
                $scope.mostraMedias = false;
                $scope.buscaFrequenciaAluno.data = null;
                $scope.disciplinaCursada.id = null;
                $scope.buscaFrequenciaAluno.mes = '';
                $scope.disciplinasFrequencia = [];
                $scope.frequenciasDoAluno = [];
                $scope.disciplinasDia = [];
                $scope.disciplinasCursadasFrequencia = [];
                $scope.frequenciasMes = [];
                $scope.medias = [];
                $scope.idDia = null;
                $('#opcaoPesquisaPresencaAluno').material_select('destroy');
                $('#opcaoPesquisaPresencaAluno').material_select();
                $timeout(function () {
                    $scope.reiniciaFrequencia();
                    $scope.index = null;
                    $scope.opcaoPesquisa = '';
                    $scope.limpaOpcao();
                    $scope.disciplinasCursadasFrequencia = [];
                    $scope.disciplinasOfertadas = [];
                    $scope.disciplinasDia = [];
                    $scope.aulas = [];
                    $('#aulaForm, #dForm, #disciplinasDoDia, #opcaoPesquisaPresencaAluno, #disciplinaCursada').material_select('destroy');
                    $('#aulaForm, #dForm, #disciplinasDoDia, #opcaoPesquisaPresencaAluno, #disciplinaCursada').material_select();
                }, 300);
                $scope.matriculas = [];
            };

            /*Carrega enturmacoes da turma*/
            $scope.buscarEnturmacoes = function (turma, enturmando) {
                $scope.mostraLoader();
                window.scrollTo(0, 0);
                if ($scope.opcaoForm !== 'turma') {
                    $scope.enturmarAlunosDireto = false;
                    $scope.opcaoForm = 'alunos';
                } else {
                    $scope.enturmarAlunosDireto = false;
                }
               var promise = Servidor.buscarUm('turmas', turma.id);
                promise.then(function (response) {
                    $scope.turma = response.data;
                    var promise = Servidor.buscar('vagas', {turma: $scope.turma.id});
                    var solicitacoes = 0;
                    promise.then(function(response){
                        //Testando Commit
                        var vagas = response.data;
                        $scope.adicionarAlunos = true;
                        $('#enturmarAlunos').show();
                        $scope.mostraEnturmacoes = true;
                        $('#voltar').show();
                        $scope.editando = true;
                        $scope.mostraLoader();
                        if (enturmando) {
                            $scope.voltarParaAlunos();
                        }
                        var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': 0});
                        promise.then(function (response) {
                            $scope.enturmacoes = response.data;
                            if ($scope.enturmacoes.length === 0) {
                                enturmando = false;
                                $scope.mensagem = ' Clique no botão + para enturmar';
                                $scope.semEnturmacoes = true;
                                $scope.adicionarAlunos = true;
                                $('#enturmarAlunos').show();
                                if (!enturmando) {
                                    $scope.mostraForm = false;
                                    $scope.enturmarAlunos();
                                } else {
                                    if ($scope.origemBotao) {
                                        $scope.trocarTab('form');
                                    } else {
                                        $scope.fecharFormulario();
                                    }
                                }
                            } else {
                                $scope.enturmacoes.forEach(function(e) {
                                    Servidor.buscarUm('matriculas', e.matricula.id).then(function(response) {
                                        e.matricula = response.data;
                                    });
                                });
                                $scope.nenhumaEnturmacao = false;
                            }
                            if (($scope.enturmacoes.length < $scope.turma.limiteAlunos) && !enturmando) {
                                $scope.adicionarAlunos = true;
                            }
                            $('.tooltipped').tooltip('remove');
                            $timeout(function () {
                                $('.tooltipped').tooltip({delay: 50});
                                $scope.fechaLoader();
                                $('.collapsible').collapsible();
                            }, 500);
                        });
                    });
                });
            };

            $scope.carregarFormularioEnturmacaoAutomatica = function() {
                var promise = Servidor.buscar('etapas', {curso: $scope.turma.etapa.curso.id});
                promise.then(function(response) {
                    $scope.etapas = response.data;
                    var encontrou = false;
                    $scope.etapas.forEach(function(etapa) {
                        if (etapa.ordem === $scope.turma.etapa.ordem-1) {
                            encontrou = true;
                            $scope.etapa = etapa;
                            $scope.etapa.turmas = [];
                            $scope.enturmacaoAutomatica = true;
                            $scope.buscarEnturmacoesEtapa(etapa.id);
                            $timeout(function(){
                                $('.dropdown-button-enturmacao-automatica').dropdown({
                                    inDuration: 300,
                                    outDuration: 225,
                                    constrain_width: false,
                                    hover: false,
                                    gutter: 0,
                                    belowOrigin: false,
                                    alignment: 'left'
                                });
                                $('#etapaEnturmacaoAutomatica').material_select();
                            }, 50);
                        }
                    });
                    if (!encontrou) {
                        $scope.enturmacaoAutomatica = false;
                        Servidor.customToast('Não há etapa que antecede ' + $scope.turma.etapa.nomeExibicao + '.');
                    }
                });
            };

            $scope.buscarEnturmacoesEtapa = function(etapa) {
                $scope.enturmacoes = [];
                var promise = Servidor.buscar('turmas', {etapa: etapa});
                promise.then(function(response) {
                    $scope.etapa.turmas = response.data;
                    $scope.etapa.turmas.forEach(function(turma) {
                        var promise = Servidor.buscar('enturmacoes', {turma: turma.id});
                        promise.then(function(response) {
                            response.data.forEach(function(enturmacao) {
                                $scope.enturmacoes.push(enturmacao);
                            });
                        });
                    });
                });
            };

            $scope.filtrarEnturmacoesPorTurma = function(turma) {
                if ($('#filtro-turma'+turma).prop('checked')) {
                    $('.turma'+turma).removeClass('hide');
                } else {
                    $('.turma'+turma).addClass('hide');
                }
            };

            $scope.selecionarEnturmacoes = function(opcao) {
                switch(opcao) {
                    case 'todos':
                        $('.enturmacao:not(.hide) input').prop('checked', true);
                    break;
                    case 'nenhum':
                        $('.enturmacao:not(.hide) input').prop('checked', false);
                    break;
                    case 'mesclar':
                        $('.enturmacao:not(.hide) input:even').prop('checked', true);
                        $('.enturmacao:not(.hide) input:odd').prop('checked', false);
                    break;
                }
            };

            $scope.enturmarMatriculas = function() {
                $scope.mostraLoader();
                $scope.matriculas.forEach(function(m) {
                    if ($('#'+m.codigo+'turma').prop('checked')) {
                        var promise = Servidor.finalizar({
                            matricula: {id: m.id},
                            turma: {id: $scope.turma.id}
                        }, 'enturmacoes', '');
                        promise.then(function(response) {
                            $scope.fechaLoader();
                            $scope.buscarMatriculas($scope.matriculaBusca, true);
                        });
                    }
                });
                //$scope.buscarEnturmacoes($scope.turma, false);
                /*var promise = Servidor.buscar('vagas', {turma: $scope.turma.id});
                promise.then(function(response) {
                    var vagas = response.data;
                    $scope.matriculas.forEach(function(m) {
                        if ($('#'+m.codigo+'turma').prop('checked')) {
                            $scope.requisicoes++;
                            var promise = Servidor.finalizar({
                                matricula: {id: m.id},
                                turma: {id: $scope.turma.id}
                            }, 'enturmacoes', '');
                            promise.then(function(response) {
                                $scope.requisicoes--;
                                var enturmacao = response.data;
                                enturmacao.achouVaga = false;
                                vagas.forEach(function(v) {
                                    if (!enturmacao.achouVaga && (!v.enturmacao || v.enturmacao === undefined) && (!v.solicitacao || v.solicitacao === undefined)) {
                                        v.enturmacao = response.data.id;
                                        enturmacao.achouVaga = true;
                                        $scope.requisicoes++;
                                        var promise = Servidor.finalizar(v, 'vagas', '');
                                        promise.then(function() {
                                            if (--$scope.requisicoes === 0) {
                                                $scope.fechaLoader();
                                                
                                            }
                                        });
                                    }
                                });
                            });
                        }
                    });
                });*/
            };

            $scope.separarAprovadosReprovados = function() {
                $scope.aprovados = [];
                $scope.reprovados = [];
                $scope.requisicoes = 0;
                $scope.enturmacoes.forEach(function(e) {
                    if ($('#ent'+e.id+':not(.hide) input').prop('checked')) {
                        $scope.requisicoes++;
                        var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: e.id});
                        promise.then(function(response) {
                            e.disciplinasCursadas = response.data;
                            if (alunoAprovado(e.disciplinasCursadas)) {
                                $scope.aprovados.push(e);
                            } else {
                                $scope.reprovados.push(e);
                            }
                            if (--$scope.requisicoes === 0) {
                                if ($scope.reprovados.length) {
                                    $timeout(function() {
                                        $('#alunos-reprovados-modal').openModal();
                                        $('.collapsible').collapsible({ accordion : false });
                                    }, 250);
                                } else {
                                    $scope.enturmarAutomaticamente();
                                }
                            }
                        });
                    }
                });
            };

            $scope.enturmarAutomaticamente = function() {
                var promise = Servidor.buscar('vagas', {turma: $scope.turma.id});
                promise.then(function(response) {
                    var vagas = response.data;
                    $scope.reprovados.forEach(function(r, i) {
                        if ($('#rep'+r.id).prop('checked') !== undefined && $('#rep'+r.id).prop('checked')) {
                            $scope.aprovados.push(r);
                        }
                    });
                    $timeout(function() {
                        $scope.requisicoes = 0;
                        $scope.aprovados.forEach(function(e) {
                            e.encerrado = true;
                            $scope.requisicoes++;
                            Servidor.finalizar(e, 'enturmacoes', '').then(function() {
                                Servidor.finalizar({matricula:{id:e.matricula.id}, turma:{id:$scope.turma.id}}, 'enturmacoes', '').then(function() {
                                    if (--$scope.requisicoes === 0) {
                                        $scope.buscarEnturmacoes($scope.turma, true);
                                    }
                                });
                            });
                            var achouVaga = false;
                            vagas.forEach(function(v) {
                                if (!achouVaga && (v.enturmacao === undefined || !v.enturmacao) && (v.solicitacao === undefined || !v.solicitacao)) {
                                    v.enturmacao = e.id;
                                    $scope.requisicoes++;
                                    Servidor.finalizar(v, 'vagas', '').then(function() {
                                        if (--$scope.requisicoes === 0) {
                                            $scope.buscarEnturmacoes($scope.turma, true);
                                        }
                                    });
                                    achouVaga = true;
                                }
                            });
                        });
                    }, 500);
                });
            };

            var alunoAprovado = function(disciplinas) {
                if (!disciplinas.length) { return false; }
                var retorno = true;
                disciplinas.forEach(function(d) {
                    if (d.status !== 'APROVADO') {
                        retorno = false;
                    }
                });
                return retorno;
            };

            $scope.selecionarAlunosAptos = function() {
                $scope.requisicoes = 0;
                $scope.enturmacoes.forEach(function(e) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: e.id});
                    promise.then(function(response) {
                        e.apto = alunoAprovado(response.data);
                        response.data.forEach(function(c) {
                            $scope.disciplinasOfertadas.forEach(function(o, i) {
                                if (c.disciplina.id === o.disciplina.id) {
                                    $scope.disciplinasOfertadas.splice(i, 1);
                                }
                            });
                        });
                        if (--$scope.requisicoes) {
                            $timeout(function() {
                                $('#disciplinaOferecida').material_select();
                            }, 100);
                        }
                    });
                });
            };

            $scope.oferecerDisciplina = function() {
                if($scope.disciplinaOferecida !== undefined && $scope.disciplinaOferecida.id) {
                    $scope.mostraLoader();
                    $scope.requisicoes = 0;
                    var aptos = 0;
                    $scope.enturmacoes.forEach(function(e) {
                        if(e.apto) {
                            aptos++;
                            $scope.requisicoes++;
                            var disciplinaCursada = {
                                matricula: {id: e.matricula.id},
                                disciplina: {id: $scope.disciplinaOferecida.id},
                                enturmacao: {id: e.id}
                            };
                            var promise = Servidor.finalizar(disciplinaCursada, 'disciplinas-cursadas', '');
                            promise.then(function() { if (--$scope.requisicoes === 0) { $scope.fechaLoader(); } });
                        }
                    });
                    if(!aptos) {
                        Servidor.customToast('Não há alunos aptos para oferecer nova disciplina.');
                        $scope.requisicoes = 0;
                        $scope.fechaLoader();
                    }
                } else {
                    Servidor.customToast('Selecione uma disciplina à ser ofertada.');
                }
            };

            $scope.atualizarVagasPreenchidas = function() {
                var cont = 0;
                $('.enturmacao:not(.hide) input').each(function() {
                    ($(this).prop('checked')) ? cont++ : cont;
                });
                $scope.alunosCompativeisSelecionados = cont;
            };

            /*Abre matricula para mais informações*/
            $scope.infoAluno = function (enturmacao) {
                $scope.mostraLoader();
                $scope.matricula = enturmacao.matricula;
                $('#info-aluno').openModal();
                var promise = Servidor.buscarUm('pessoas', enturmacao.matricula.aluno.id);
                promise.then(function (response) {
                    $scope.aluno = response.data;
                    var promise = Servidor.buscar('telefones', {'pessoa': $scope.aluno.id});
                    promise.then(function (response) {
                        $scope.telefones = response.data;
                    });
                });
                $timeout(function () {
                    $scope.fechaLoader();
                    $timeout(function () {
                        $scope.initMap(false, "info-map");
                    }, 500);
                }, 300);
            };

            /*Verifica as disciplinas cursadas com ofertadas*/
            $scope.verificaDisciplinas = function () {
                var cont = 0;
                $scope.matriculas = $scope.matriculajs;
                var tamanho = $scope.matriculajs.length - 1;
                $scope.matriculajs.forEach(function (m, index) {
                    var promise = Servidor.buscar('disciplinas-cursadas', {'matricula': m.id, 'etapa': $scope.turma.etapa.id});
                    promise.then(function (response) {
                        cont++;
                        if (!response.data.length) {
                            $scope.matriculas.splice(index, 1);
                        }
                        if (index === tamanho) {
                            //$scope.matriculas = $scope.matriculajs;
                            $scope.fechaLoader();
                        }
                    });
                });
            };

            $scope.reiniciarBuscaMatriculas = function () {
                $scope.matriculaBusca = {
                    'aluno': '',
                    'codigo': '',
                    'curso': $scope.turma.etapa.curso.id,
                    'unidade': $scope.turma.unidadeEnsino.id
                };
            };

            /*Busca matriculas no curso*/
            $scope.buscarMatriculas = function (matricula) {
                $scope.matriculas = [];
                $scope.matriculasVerificar = [];
                var promise = Servidor.buscar('disciplinas-ofertadas', {turma: $scope.turma.id});
                promise.then(function(response) {
                    $scope.disciplinasOfertadas = response.data;
                });
                var promise = Servidor.buscarUm('etapas',$scope.turma.etapa.id);
                promise.then(function(response){                    
                    $scope.matriculaBusca.curso = response.data.curso.id;
                    $scope.matriculaBusca.unidade = $scope.turma.unidadeEnsino.id;
                    $scope.mostraLoader();
                    $scope.adicionarAlunos = true;
                    var promise = Servidor.buscar('matriculas', {'codigo': matricula.codigo, 'aluno_nome': matricula.aluno,
                        'unidadeEnsino': $scope.matriculaBusca.unidade, 'curso': $scope.matriculaBusca.curso});
                    promise.then(function (response) {
                        if(response.data.length){
                            $scope.matriculasVerificar = response.data;
    //                            $scope.fechaLoader();
    //                            $scope.verificarDisciplinasCursadas();
                            $scope.alunosCompativeisSelecionados = 0;
                            $scope.enturmacoesEncerradas(response.data);
                        }else{
                            $scope.fechaLoader();
                            Servidor.customToast('Nenhuma matricula encontrada.');
                        }
                    });
                });
            };

            $scope.selecionarTodasMatriculasCompativeis = function() {
                var bool = $('#enturmacoesCheckAll').prop('checked');
                $('.matricula-compativel').prop('checked', bool);
                $scope.alunosCompativeisSelecionados = (bool) ? $scope.matriculas.length : 0;
            };

            $scope.selecionarUmaMatriculaCompativel = function(cod) {
                var bool = true;
                $scope.matriculas.forEach(function(m) {
                    ($('#'+m.codigo+'turma').prop('checked')) ? bool : bool = false;
                });
                $scope.alunosCompativeisSelecionados += ($('#'+cod+'turma').prop('checked')) ? 1 : -1;
                $('#enturmacoesCheckAll').prop('checked', bool);
            };

            $scope.enturmacoesEncerradas = function(matriculas) {
                var compativeis = [];
                $scope.requisicoes = 0; $scope.matriculas = [];
                matriculas.forEach(function(m) {
                    $scope.requisicoes++;
                    //var promise = Servidor.buscar('enturmacoes', {matricula: m.id, encerrado: 0});
                    var promise = Servidor.buscar('enturmacoes', {matricula: m.id});
                    promise.then(function(response) {
                        if (response.data.length === 0) {
                            $scope.matriculas.push(m);
                            $scope.fechaLoader();
                        } else {
                            response.data.forEach(function(res, i){
                                if (!res.isEmAndamento) { $scope.matriculas.push(m); }
                            });
                            //$scope.matriculas = response.data;
                            $scope.fechaLoader();
                            /*if (!response.data.length) {
                                compativeis.push(m);
                            }
                            if (--$scope.requisicoes === 0) {
                                if (compativeis.length) {
                                    $scope.alunosDisciplinasCompativeis(compativeis);
                                } else {
                                    $scope.fechaLoader();
                                    Servidor.customToast('Nenhuma matrícula compatível encontrada.');
                                }
                            } else {
                                $scope.fechaLoader();
                            }*/
                        }
                    });
                });
            };

            $scope.alunosDisciplinasCompativeis = function(matriculas) {
                $scope.requisicoes = 0;
                matriculas.forEach(function(m) {
                    $scope.requisicoes++;
                    var promise = Servidor.buscar('disciplinas-cursadas', {matricula: m.id, status:'CURSANDO', etapa:$scope.turma.etapa.id});
                    promise.then(function(response) {
                        if (response.data.length) {
                            $scope.matriculas.push(m);
                        }
                        if (--$scope.requisicoes === 0) {
                            if (!$scope.matriculas.length) {
                                Servidor.customToast('Nenhuma matrícula compatível encontrada.');
                            }
                            $scope.fechaLoader();
                        }
                    });
                });
            };

            /*Verifica as matriculas que possuem disciplinas na etapa da turma*/
            $scope.verificarDisciplinasCursadas = function () {
                $scope.matriculasVerificadasDisciplinas = [];
                var cont = 0;
                $scope.matriculasVerificar.forEach(function (m, index) {
                    var promise = Servidor.buscar('matriculas/' + m.id + '/disciplinas-cursadas', {'status': 'CURSANDO', 'etapa': $scope.turma.etapa.id});
                    promise.then(function (response) {
                        cont++;
                        if (response.data.length) {
                            m.disciplinas = response.data;
                            $scope.matriculasVerificadasDisciplinas.push(m);
                        }
                        if (cont === $scope.matriculasVerificar.length) { $scope.verificarEnturmacoesAtivas(); }
                    });
                });
            };

            /*Verifica se tem enturmacao ativa na mesmo etapa da turma*/
            $scope.verificarEnturmacoesAtivas = function () {
                var pos = 0; var cont = 0;
                $scope.matriculasVerificadasDisciplinas.forEach(function (m, indexM) {
                    var promise = Servidor.buscarUm('matriculas', m.id);
                    promise.then(function (response) {
                        cont++;
                        var promise = Servidor.buscar('enturmacoes',{matricula: $scope.matricula.id, encerrado: false});
                        promise.then(function(response){
                            if (response.data.length) {
                                var temEnturmacao = 0;
                                var contEnturmacoes = 0;
                                response.data.forEach(function (e, index) {
                                    var promiseE = Servidor.buscarUm('turmas', e.turma.id);
                                    promiseE.then(function (responseE) {
                                        contEnturmacoes++;
                                        if(responseE.data.etapa.id === $scope.turma.etapa.id){ temEnturmacao++; }
                                        if(temEnturmacao === 0 && contEnturmacoes === response.data.length){
                                            $scope.matriculas[pos] = m;
                                            pos++;
                                        }
                                    });
                                });
                            } else {
                                $scope.matriculas[pos] = m;
                                pos++;
                            }
                        });
                        if(cont === $scope.matriculasVerificadasDisciplinas.length && !$scope.matriculas.length){
                            $scope.fechaLoader();
                            Servidor.customToast('Nenhuma matricula encontrada.');
                        }
                    });
                });
            };

            /*Salvar Eturmações*/
            $scope.finalizarEnturmacao = function () {
                var cont = 0;
                var tamanho = $scope.matriculas.length;
                /*Percorre todas as matriculas e verifica quais estão selecionadas*/
                for (var i = 0; i < tamanho; i++) {
                    if ($("#" + $scope.matriculas[i].codigo + $scope.opcaoForm).is(':checked')) {
                        cont++;
                        $scope.enturmacao.turma.id = $scope.turma.id;
                        $scope.enturmacao.matricula.id = $scope.matriculas[i].id;
                        var promise = Servidor.finalizar($scope.enturmacao, 'enturmacoes', 'Enturmação');
                        promise.then(function (result) {
                            $scope.turma = result.data.turma;
                            var idEnturmacao = result.data.id;
                            var id = result.data.matricula.id;
                            var promise = Servidor.buscar('matriculas/' + id + '/disciplinas-cursadas', {'etapa': $scope.etapa.id});
                            promise.then(function (response) {
                                $scope.disciplinasCursadas = response.data;
                                var tamanho = $scope.disciplinasCursadas.length;
                                for (var i = 0; i < tamanho; i++) {
                                    $scope.disciplinasCursadas[i].enturmacao = idEnturmacao;
                                }
                            });
                            var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': false});
                            promise.then(function (response) {
                                $scope.enturmacoes = response.data;
                                $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};
                                $timeout(function () {
                                    $scope.buscarMatriculas($scope.matriculaBusca, false);
                                }, 100);
                            });
                        });
                    }
                    $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};
                }
                if (cont === 0) {
                    Servidor.customToast('Nenhuma matricula selecionada');
                }
            };

            /*Desmarca todas as matriculas*/
            $scope.removerTodasMatriculas = function () {
                var tamanho = $scope.matriculas.length;
                for (var i = 0; i < tamanho; i++) {
                    $('#' + $scope.matriculas[i].codigo + $scope.opcaoForm).removeAttr('checked', 'checked');
                }
            };
            /*-------------------------------------------------------------Fim Enturmação------------------------------------------------------------*/

            /*-------------------------------------------------------------Quadro de Horario------------------------------------------------------------*/
            $scope.segunda = [];
            $scope.terca = [];
            $scope.quarta = [];
            $scope.quinta = [];
            $scope.sexta = [];
            $scope.horariosDisciplina = [];
            $scope.horariosDisciplinaParaExcluir = [];
            $scope.mostraQuadroHorario = false;
            $scope.quadroHorario = {
                'nome': null,
                'inicio': null,
                'modelo': {id: null},
                'unidadeEnsino': {id: null},
                'turno': {id: null},
                'diasSemana': [
//                    {diaSemana: "2"},
//                    {diaSemana: "3"},
//                    {diaSemana: "4"},
//                    {diaSemana: "5"},
//                    {diaSemana: "6"}
                ]
            };

            /*Tab gerar PDF*/
            $scope.gerarPdf =  function(turma){
                $scope.mostraPdf =  true;
                $scope.trocarTab('pdf');
                $scope.buscarEnturmacoes(turma, true);
            };

            /*Tab Quadro de Horário*/
            $scope.quadroHorarioTurma = function (turma) {
                $scope.mostraQuadroHorario = true;
                window.scrollTo(0, 0);
                $('#voltar').show();
                $('.btn-add').hide();
                $scope.mostraLoader(true);
                $scope.isAulaGerada();
                var promise = Servidor.buscarUm('turmas', turma.id);
                promise.then(function (response) {
                    $scope.turma = response.data;
                    if (!$scope.quadroHorario.id) {
                        $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id);
                        $scope.buscarDisciplinasOfertadas('quadro');
                    } else {
                        $scope.buscarDisciplinasOfertadas('quadro');
                    }
                });
                $timeout(function () {
                    $('.date').mask('00/00/0009');
                }, 500);
            };

            /*Criar Aulas da Turmas*/
            $scope.gerarAulas = function () {
                $scope.mostraLoader(true);
                //$scope.dataFinalPresencas();
                var promise = Servidor.finalizar(null, 'turmas/' + $scope.turma.id + '/aulas', 'AULAS');
                promise.then(function (response) {
                    //$scope.fechaLoader();
                    $scope.quadroHorarioTurma($scope.turma);
                    $scope.turma.status = 'EM_ANDAMENTO';
                });
            };

            /*Criar Novas Aulas da Turmas*/
            $scope.gerarNovasAulas = function (inicioQuadro) {
                $scope.mostraLoader(true);
                var promise = Servidor.finalizar({ 'dataInicio': inicioQuadro }, 'turmas/' + $scope.turma.id + '/novas-aulas', 'AULAS');
                promise.then(function (response) {
                    //$scope.fechaLoader();
                    $scope.quadroHorarioTurma($scope.turma);
                    $scope.turma.status = 'EM_ANDAMENTO';
                });
            };
            
            $scope.isAulaGerada = function (){
                var promiseAulas = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', null);
                promiseAulas.then(function(response){
                    if (response.data.length === 0) {
                        $scope.aulaGerada = false;
                    } else {
                        $scope.aulaGerada = true;
                    }
                });
            };

            $scope.verificarDataInicial = function () {
                var promiseAulas = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', null);
                promiseAulas.then(function(response){
                    if (response.data.length > 0) {
                        var index = response.data.length - 1;
                        var aula = response.data[index];
                        var ultimaAula = aula.dia.data;
                        var inicioQuadro = moment(ultimaAula,'YYYY-MM-DD').add(1,'day');
                        $scope.gerarNovasAulas(inicioQuadro.format('YYYY-MM-DD'));
                    } else {
                        $scope.gerarAulas();
                    }
                });
            };

            $scope.abrirModalProfessor = function(disciplina) {
                $scope.disciplinaProfessor.nome = disciplina.nome;
                $scope.disciplinaProfessor.id = disciplina.id;
                $scope.nomeProfessor = '';
                $timeout(function () {
                    $('#modal-professor').openModal();
                    $timeout(function () {
                        $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                    }, 200);
                }, 200);
            };

            $scope.abrirModalExclusaoGradeHorario = function() {
                $('#remove-modal-quadro-horario').openModal();
                $scope.dataInicioQuadroHorario = '';
//                $scope.horariosDisciplina = [];
//                $scope.reiniciarSemanas();
//                $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id);
//                $scope.ativarDragAndDrop();
            };

            $scope.prepararNovaGradeHorario = function() {
                $scope.reiniciarSemanas();
                $scope.horariosDisciplina = [];
                $scope.horariosDisciplinaParaExcluir = [];
                $scope.buscarUmQuadroHorario($scope.turma.quadroHorario.id);
                $scope.ativarDragAndDrop();
            };

            $scope.removerGradeHorario = function() {
                $scope.aulas = [];
                var inicio = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
                var promise = Servidor.buscar('turmas/'+$scope.turma.id+'/aulas');
                promise.then(function(response) {
                    var aulas = response.data;
                    if (aulas.length > 0) {
                        aulas.forEach(function(aula, i) {
                            if (dateTime.dateLessOrEqual(inicio, aula.dia.data)) { $scope.aulas.push(aula.id); }
                            if (aulas.length-1 === i) {
                                $timeout(function () {
                                    if ($scope.aulas.length > 0) { Servidor.excluirLote({'ids': $scope.aulas }, 'aulas'); }
                                    $scope.removerHorariosDisciplinas();
                                }, 1000);
                            }
                        });
                    } else {
                        $scope.removerHorariosDisciplinas();
                    }
                });
            };

            $scope.removerHorariosDisciplinas = function() {
                $scope.disciplinasGradeAntiga = [];
                for (var i=0; i<$scope.segunda.length; i++) { if ($scope.segunda[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.segunda[i].disciplina.id); } }
                for (var i=0; i<$scope.terca.length; i++) { if ($scope.terca[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.terca[i].disciplina.id); } }
                for (var i=0; i<$scope.quarta.length; i++) { if ($scope.quarta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.quarta[i].disciplina.id); } }
                for (var i=0; i<$scope.quinta.length; i++) { if ($scope.quinta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.quinta[i].disciplina.id); } }
                for (var i=0; i<$scope.sexta.length; i++) { if ($scope.sexta[i].disciplina !== undefined) { $scope.disciplinasGradeAntiga.push($scope.sexta[i].disciplina.id); } }
                $timeout(function () { $scope.removerLoteDisciplinas(); }, 1500);
            };

            $scope.removerLoteDisciplinas = function() {
                if ($scope.disciplinasGradeAntiga.length === 0) {
                    var inicioQuadro = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
                    $('#remove-modal-quadro-horario').closeModal();
                    $scope.prepararNovaGradeHorario();
                    $scope.gerarNovasAulas(inicioQuadro);
                    $scope.fechaLoader();
                } else {
                    for (var i=0; i<$scope.disciplinasGradeAntiga.length; i++) {
                        if ($scope.disciplinasGradeAntiga[i] !== undefined) {
                            Servidor.excluirLote({'ids': $scope.disciplinasGradeAntiga }, 'horarios-disciplinas');
                        };
                        var inicioQuadro = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
                        if ($scope.disciplinasGradeAntiga.length-1 === i) {
                            $('#remove-modal-quadro-horario').closeModal();
                            $scope.prepararNovaGradeHorario();
                            $scope.gerarNovasAulas(inicioQuadro);
                            $scope.fechaLoader();
                        }
                    }
                }
            };

            $scope.dataFinalPresencas = function () {
                var frequenciasDisciplinas = [];
                $scope.disciplinasOfertadas.forEach(function(ofertada, i) {
                    var promise = Servidor.buscar('frequencias', {disciplina: ofertada.id});
                    promise.then(function(response) {
                        frequenciasDisciplinas.push(response.data);
                        if (i === $scope.disciplinasOfertadas.length-1) {
                            var dataFinal = $scope.verificaUltimaDataQuadroHorario(frequenciasDisciplinas);
                        }
                    });
                });
            };

            $scope.verificaPresencas = function() {
                if ($scope.dataInicioQuadroHorario.length === 10) {
                    $scope.mostraLoader(true);
                    var frequenciasDisciplinas = [];
                    $scope.disciplinasOfertadas.forEach(function(ofertada, i) {
                        var promise = Servidor.buscar('frequencias', {disciplina: ofertada.id, turma: $scope.turma.id});
                        promise.then(function(response) {
                            frequenciasDisciplinas.push(response.data);
                            if (i === $scope.disciplinasOfertadas.length-1) {
                                $scope.verificaMaiorDataPresenca(frequenciasDisciplinas);
                            }
                        });
                    });
                } else {
                    Servidor.customToast('Esta data não está disponível para efetuar chamada.');
                }
            };

            $scope.verificaUltimaDataQuadroHorario = function(disciplinas) {
                var maior = '0000-00-00';
                disciplinas.forEach(function(d) {
                    d.forEach(function(f) {
                        if (dateTime.dateLessThan(maior, f.aula.dia.data)) {
                            maior = f.aula.dia.data;
                        }
                    });
                });
                maior = maior.split('-');
                var dia = parseInt(maior[2])+1; var mes = maior[1]; var ano = maior[0];
                if (parseInt(dia) < 10) { dia = '0'+dia; }
                return ano + '-' + mes + '-' + dia;
            };

            $scope.verificaMaiorDataPresenca = function(disciplinas) {
                var dataInicio = dateTime.converterDataServidor($scope.dataInicioQuadroHorario);
                var maiorData = '0000-00-00';
                disciplinas.forEach(function(frequencias) {
                    frequencias.forEach(function(frequencia) {
                        if (dateTime.dateLessOrEqual(dataInicio, frequencia.aula.dia.data)) {
                            if (dateTime.dateLessOrEqual(maiorData, frequencia.aula.dia.data)) {
                                maiorData = frequencia.aula.dia.data;
                            }
                        }
                    });
                });
                var arrayData = maiorData.split('-');
                var dia = parseInt(arrayData[2])+1;
                if (dia < 10) { dia = '0'+dia; }
                maiorData = arrayData[0] + '-' + arrayData[1] + '-' + dia;
                if (dateTime.dateLessOrEqual(dataInicio, maiorData)) {
                    maiorData = dateTime.converterDataForm(maiorData);
                    $scope.maiorData = maiorData;
                    Servidor.customToast('Devido as frequencias geradas, a data de início da nova grade deverá ser ' + maiorData);
                    $scope.fechaLoader();
                } else {
                    $scope.removerGradeHorario();
                }
            };

            $scope.verificaPendencias = function(dataInicio) {
                if (dataInicio.length === 10) {
                    $scope.mostraLoader(true);
                    $scope.avaliacoesPendentes = [];
                    /* Verifica as avaliações com a aula de entrega após a data de início do novo Quadro de Horários */
                    var promise = Servidor.buscarUm('etapas', $scope.etapa.id);
                    promise.then(function(response) {
                        if (response.data.sistemaAvaliacao.tipo === "QUALITATIVO") {
                            var enderecoAvaliacao = 'avaliacoes-qualitativas';
                        } else {
                            enderecoAvaliacao = 'avaliacoes-quantitativas';
                        }
                        $scope.disciplinasOfertadas.forEach(function(of, i) {
                            promise = Servidor.buscar(enderecoAvaliacao, {disciplina: of.id});
                            promise.then(function(response) {
                                var avaliacoes = response.data;
                                avaliacoes.forEach(function(avaliacao) {
                                    if (dateTime.dateLessOrEqual(dataInicio, avaliacao.aulaEntrega.dia.data)) {
                                        $scope.avaliacoesPendentes.push(avaliacao);
                                    }
                                });
                                if (i === $scope.disciplinasOfertadas.length-1) {
                                    $timeout(function(){ $scope.fechaLoader(); }, 500);
                                }
                            });
                        });
                    });
                }
            };

            $scope.buscarUmQuadroHorario = function (id) {
                var promise = Servidor.buscarUm('quadro-horarios', id);
                promise.then(function (response) {
                    $scope.quadroHorario = response.data;
                    $scope.quadroHorario.inicio = Servidor.formatarHora($scope.quadroHorario.inicio);
                    $scope.naoResetados = true;
                    $scope.buscarHorarios();
                });
            };

            $scope.buscarHorarios = function () {
                var promise = Servidor.buscar('quadros-horarios/' + $scope.quadroHorario.id + '/horarios', null);
                promise.then(function (response) {
                    $scope.horarios = response.data;
                    $scope.horarios.forEach(function (horario) {
                        horario.inicio = Servidor.formatarHora(horario.inicio);
                        horario.termino = Servidor.formatarHora(horario.termino);
                    });
                    $scope.organizaHorariosPorDia();
                });
            };

            $scope.organizaHorariosPorDia = function () {
                $scope.horarios.forEach(function (horario) {
                    switch (horario.diaSemana.diaSemana) {
                        case "2":
                            $scope.segunda.push(horario);
                            break;
                        case "3":
                            $scope.terca.push(horario);
                            break;
                        case "4":
                            $scope.quarta.push(horario);
                            break;
                        case "5":
                            $scope.quinta.push(horario);
                            break;
                        case "6":
                            $scope.sexta.push(horario);
                            break;
                    }
                });
            };

            $scope.buscarDisciplinasOfertadas = function (op) {
                $scope.frequencia.turma.id = $scope.turma.id;
                var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': $scope.turma.id});
                promise.then(function (response) {
                    $scope.disciplinasOfertadas = response.data;
                    if ($scope.disciplinasOfertadas.length === 0) {
                        $scope.reiniciarSemanas();
                        $scope.buscarHorarios();
                    }
                    if (op === 'quadro') {
                        $scope.ativarDragAndDrop();
                        $timeout(function () {
                            $('#dForm').material_select('destroy');
                            $('#dForm').material_select();
                            $scope.buscarHorariosDisciplinas();
                            $scope.editando = true;
                        }, 500);
                    }
                });
            };
            $scope.aulasCriadas = false;

            $scope.buscarHorariosDisciplinas = function () {
                $scope.cont = 0;
                $scope.novaGrade = false;
                $scope.botaoFlag = true;
                $scope.quadroCompleto = false;
                $scope.horarios.forEach(function (horario, i) {
                    $scope.disciplinasOfertadas.forEach(function (disciplina, j) {
                        var promise = Servidor.buscar('horarios-disciplinas', {'horario': horario.id, 'disciplina': disciplina.id});
                        promise.then(function (response) {
                            if (response.data.length > 0) {
                                $scope.cont++;
                                horario.disciplina = response.data[0];
                                $scope.horariosDisciplina.push(response.data[0]);
                                if (response.data[0].id && $scope.cont === $scope.horarios.length) {
                                    $scope.aulasCriadas = true;
                                    $scope.botaoFlag = false;
                                    $scope.quadroCompleto = true;
                                }
                                if ($scope.cont > 0) { $scope.novaGrade = true; }
                            }
                            if ($scope.cont === $scope.horarios.length) {
                                $scope.botaoFlag = false;
                                $scope.excluirDisciplina = false;
                                $scope.quadroCompleto = true;
                                if ($scope.cont > 0) { $scope.novaGrade = true; }
                            }
                            if (j === $scope.disciplinasOfertadas.length-1 && i === $scope.horarios.length-1) {
                                $timeout(function(){ $scope.fechaLoader(); }, 500);
                            }
                        });
                    });
                });
            };

            $scope.removerDisciplina = function (horarioDisciplina) {
                $scope.cont--;
                if (horarioDisciplina.id !== undefined && horarioDisciplina.id) {
                    $('#disciplina'+horarioDisciplina.id).remove();
                    $('#disciplina'+horarioDisciplina.id).remove();
                } else {
                    $('#horario'+horarioDisciplina).remove();
                    $('#horario'+horarioDisciplina).remove();
                }
                if (horarioDisciplina.id) {
                    $scope.horariosDisciplina.forEach(function (horario, i) {
                        if (parseInt(horario.horario.id) === horarioDisciplina.horario.id) {
                            $scope.horariosDisciplina.splice(i, 1);
                        }
                    });
                    Servidor.remover(horarioDisciplina, '');
                } else {
                    $scope.horariosDisciplina.forEach(function (horario, i) {
                        if (parseInt(horario.horario.id) === horarioDisciplina) {
                            $scope.horariosDisciplina.splice(i, 1);
                        }
                    });
                    $scope.horariosSalvos.forEach(function (salvo) {
                        if (salvo.horario.id === horarioDisciplina) {
                            Servidor.remover(salvo, '');
                        }
                    });
                }
            };

            $scope.horariosSalvos = [];
            $scope.excluirDisciplina = true;

            $scope.ativarDragAndDrop = function () {
                $timeout(function () {
                    $(".disciplina").draggable({
                        appendTo: "body",
                        helper: "clone"
                    });
                    $(".horario").droppable({
                        activeClass: "ui-state-default",
                        hoverClass: "ui-state-hover",
                        accept: ":not(.ui-sortable-helper)",
                        drop: function (event, ui) {
                            $scope.horariosDisciplina.forEach(function (horario) {
                                if (horario.horario.id === parseInt(event.target.id)) {
                                    $scope.ocupado = true;
                                }
                            });
                            if ($scope.ocupado) {
                                Materialize.toast("Horario já possui uma disciplina", 1000);
                                $scope.ocupado = false;
                            } else {
                                $scope.cont++;
                                var object = {
                                    'disciplina': {'id': parseInt(ui.draggable.context.id)},
                                    'horario': {'id': parseInt(event.target.id)}
                                };
                                $("<div id='horario"+event.target.id+"' class='aula-preenchida center h" + event.target.id + "'></div>").text(ui.draggable.text()).appendTo(this);
                                $($compile("<i data-ng-click='removerDisciplina(" + event.target.id + ")' class='material-icons tiny disabled remove-grid-disciplina " + event.target.id + "'>clear</i>")($scope)).appendTo('.h'+event.target.id);
                                $scope.horariosDisciplina.push(object);

                                var promise = Servidor.finalizar(object, 'horarios-disciplinas', '');
                                promise.then(function (response) {
                                    $scope.excluirDisciplina = true;
                                    $scope.horariosSalvos.push(response.data);
                                });
                                $scope.$apply();
                            }
                        }
                    });
                }, 500);
            };

            $scope.finalizarHorarios = function () {
                $scope.mostraProgresso();
                var objetoAdd = {'horarios': []};
                objetoAdd.horarios = $scope.horariosDisciplina;
                var array = [];
                $scope.horariosDisciplinaParaExcluir.forEach(function (horarioDisciplina) {
                    array.push(horarioDisciplina.id);
                });
                var result = Servidor.finalizar(objetoAdd, 'horarios-disciplinas', 'Horarios');
                var result2 = Servidor.excluirLote({'ids': array}, 'horarios-disciplinas');
                if (result && result2) {
                    $timeout(function () {
                        $scope.fechaProgresso();
                    }, 1000);
                }
            };

            /*-------------------------------------------------------------Fim Quadro de Horario------------------------------------------------------------*/


            /*-------------------------------------------------------------Frequencia------------------------------------------------------------*/
            $scope.fazerChamada = false;
            $scope.presencas = false;
            $scope.desabilitarBotao = false;
            $scope.semEnturmacoes = false;
            $scope.justificativaDeFalta = null;
            $scope.opcaoPesquisa = null;
            $scope.frequenciasAlunosTurma = [];
            $scope.frequenciasAlunos = [];
            $scope.frequenciasDoAluno = [];
            $scope.frequenciasMes = [];
            $scope.aulaDisciplina = {};
            $scope.verificaChamada = '';
            $scope.frequencia = {
                'turma': {id: null},
                'disciplina': {id: null},
                'aula': {id: null}
            };
            $scope.buscaFrequenciaAluno = {
                'matricula': null,
                'disciplina': {'id': null},
                'data': null,
                'diaAula': null,
                'mes': '',
                'aula': null
            };

            /*Preparar para realizar chamada*/
            $scope.realizarChamada = function (turma, tab) {
                $('#voltar').show();
                $('.btn-add').hide();
                callDatepicker();
                $scope.mostraLoader();
                var promise = Servidor.buscarUm('turmas', turma.id);
                promise.then(function (response) {
                    $scope.turma = response.data;
                    $scope.enturmacoes = [];
                    var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': false});
                    promise.then(function (response) {
                        if (response.data.length > 0) {
                            var enturmacoes = response.data;
                            enturmacoes.forEach(function(enturmacao) {
                                var promise = Servidor.buscarUm('enturmacoes', enturmacao.id);
                                promise.then(function(response) {
                                    $scope.enturmacoes.push(response.data);
                                    if ($scope.enturmacoes.length === enturmacoes.length) {
                                        $scope.fechaLoader();
                                    }
                                });
                            });
                        } else {
                            $scope.mensagem = '';
                            $scope.semEnturmacoes = true;
                            $scope.fechaLoader();
                        }
                        $timeout(function () {
                            $scope.editando = true;
                            Servidor.verificaLabels();
                        }, 300);
                    });
                });
                $scope.nenhumaEnturmacao = false;
                $scope.adicionarAlunos = false;
                $scope.voltarAlunos = false;
                var promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                promise.then(function (response) {
                    $scope.disciplinasOfertadas = response.data;
                });
                $scope.frequencia.turma.id = turma.id;
                $scope.fazerChamada = true;
                $scope.ativaTab = tab;
            };

            /*Seleciona o dia para Chamada*/
            $scope.escolheDiaChamada = function (data) {
                if (!data) { data = $scope.diaChamada; }
                $scope.presencas = false;
                $scope.horarioAula = false;
                $scope.idDia = null;
                $scope.aulaDisciplina = {};
                $scope.aulasDia = [];                
                if (data !== undefined && data.length === 10) {
                    var dataServidor = Servidor.getDate();
                    dataServidor.then(function(response) {
                        var dataAtual = response.data;
                        $scope.aulasDia = [];                        
                        var chamada = data.split('/').join("");
                        chamada =  chamada.slice(4,8) + '-' + chamada.slice(2,4) + '-' + chamada.slice(0,2);
                        if (dateTime.dateLessOrEqual(chamada, dataAtual)) {
                            var promise = Servidor.buscar('calendarios/' + $scope.turma.calendario.id + '/dias', {'data': chamada});
                            promise.then(function (response) {
                                if (response.data.length) {
                                    $scope.idDia = response.data[0].id;
                                    $scope.enturmacoesDisciplinas = $scope.enturmacoes;
                                    $scope.buscarAulasDia();
                                } else {
                                    Servidor.customToast('Este dia não consta no calendário escolar desta turma.');
                                }
                            });
                        } else {
                            Servidor.customToast('Esta aula ainda não ocorreu.');
                        }
                    });                        
                }
            };

            /*Verifica se a data de enturmacao é menor que data da chamada*/
            $scope.verificaDataEnturmacoes = function (origem) {
                $scope.mostraLoader();
                $scope.enturmacoesDisciplinas = [];
                var chamada = $scope.diaChamada.split('/').join('');
                chamada = chamada.slice(0,2) + '/' + chamada.slice(2,4) + '/' + chamada.slice(4,8);
                var cont = 0;
                $scope.enturmacoes.forEach(function (e, $index) {
                    cont++;
                    var enturmado = e.dataCadastro.split('T')[0];
                    enturmado = enturmado.split('-');
                    enturmado = enturmado[2] + '/' + enturmado[1] + '/' + enturmado[0];
                    if (enturmado <= chamada) {
                        $scope.enturmacoesDisciplinas.push(e);
                    }
                });
                $scope.buscarAulasDia();
            };

            /*Busca as aulas e as disciplinas do dia*/
            $scope.buscarAulasDia = function () {
                $scope.mostraLoader();
                $scope.aulasDia = [];
                $scope.index = null;
                $scope.frequencia.aula.id = null;
                $scope.horarioAula = false;
                $scope.arrayHorarios = [];
                var promise = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', {'dia': $scope.idDia});
                promise.then(function (response) {
                    if(!response.data.length) {
                        return Servidor.customToast('Não há aulas neste dia.');
                    }
                    response.data.forEach(function (d, $index) {
                        var array = d.horario.inicio.split(':');
                        $scope.arrayHorarios.push(array[0] + " " + $index);
                        if ($index === response.data.length - 1) {
                            $scope.arrayHorarios.sort();
                            $scope.arrayHorarios.forEach(function (h, $indexH) {
                                var arrayHorario = h.split(' ');
                                var posicao = arrayHorario[1];
                                $scope.aulasDia[$indexH] = response.data[posicao];
                            });
                        }
                    });
                    $timeout(function () {
                        $scope.fechaLoader();
                        $('select').material_select('destroy');
                        $('select').material_select();
                    }, 150);
                });
            };

            /*Carrega a aula selecionada para chamada*/
            $scope.carregarAulas = function (index) {
                $scope.frequenciasAlunosTurma = [];
                $scope.presencas = false;
                $scope.horarioAula = false;
                $scope.mostraLoader();
                $('#todasEnturmacoes')[0].checked = true;
                if (index === 'todas') {
                    $scope.setaPresenca();
                } else {
                    var promise = Servidor.buscarUm('aulas', $scope.aulasDia[index].id);
                    promise.then(function (response) {
                        $scope.aulaDisciplina = response.data;
                        var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.aulaDisciplina.disciplinaOfertada.id);
                        promise.then(function (responseD) {
                            $scope.aulaDisciplina.disciplinaOfertada = responseD.data;
                            $scope.marcarFrequenciaDisciplina();
                        });
                    });
                }
            };

            /*Marca frequencia de uma aula selecionada*/
            $scope.marcarFrequenciaDisciplina = function () {
                var aulaId = $scope.aulaDisciplina.id;
                $scope.frequenciasTurma = [];
                $scope.frequenciasAlunosTurma = [];
                $scope.mostraLoader();
                $scope.enturmacoesDisciplinas.forEach(function (e, $index) {
                    var promise = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'aula': aulaId});
                    promise.then(function (response) {
                        $scope.frequenciasTurma = response.data;
                        if ($scope.frequenciasTurma.length) {
                            $scope.desabilitarBotao = false;
                            if (response.data[0].status === 'PRESENCA') {
                                $('#' + response.data[0].status + e.id + e.matricula.id + aulaId)[0].checked = true;
                            }
                            $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].disabled = true;
                        } else {
                            $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].disabled = false;
                            $('#PRESENCA' + e.id + e.matricula.id + aulaId)[0].checked = true;
                            $scope.justificativaDeFalta = null;
                            var frequencia = {
                                'status': 'PRESENCA',
                                'disciplinaCursada': {'id': null},
                                'aula': {'id': aulaId}
                            };
                            var promise = Servidor.buscarUm('disciplinas-ofertadas', $scope.aulaDisciplina.disciplinaOfertada.id);
                            promise.then(function (response) {
                                var id = response.data.disciplina.id;
                                var promise = Servidor.buscar('matriculas/' + e.matricula.id + '/disciplinas-cursadas', {'disciplina': id});
                                promise.then(function (response) {
                                    e.matricula.disciplinaCursada = response.data;
                                    frequencia.disciplinaCursada.id = response.data[0].id;
                                    $scope.frequenciasAlunosTurma.push(frequencia);
                                });
                            });
                        }
                        if ($index === $scope.enturmacoesDisciplinas.length -1) {
                            $timeout(function() {
                                $scope.horarioAula = true;
                                $scope.fechaLoader();
                            }, 500);
                        }
                    });
                });
            };

            /*Marca frequencia para todas as aulas*/
            $scope.setaPresenca = function () {
                $scope.aulaDisciplina = {};
                $scope.horarioAula = false;
                $scope.frequenciasTurma = [];
                $scope.frequenciasAlunosTurma = [];
                var cont = 0;
                $scope.enturmacoesDisciplinas.forEach(function (e, $index) {
                    $scope.aulasDia.forEach(function (a, i) {
                        var promise = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'aula': a.id});
                        promise.then(function (response) {
                            cont++;
                            $scope.frequenciasTurma = response.data;
                            if ($scope.frequenciasTurma.length) {
                                $scope.desabilitarBotao = false;
                                if (response.data[0].status === 'PRESENCA') {
                                    $('#' + response.data[0].status + e.id + e.matricula.id + a.id)[0].checked = true;
                                }
                                $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = true;
                            } else {
                                $scope.desabilitarBotao = true;
                                $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = false;
                                $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].checked = true;
                                $scope.justificativaDeFalta = null;
                                var frequencia = {
                                    'status': 'PRESENCA',
                                    'disciplinaCursada': {'id': null},
                                    'aula': {'id': a.id}
                                };
                                var promise = Servidor.buscarUm('disciplinas-ofertadas', a.disciplinaOfertada.id);
                                promise.then(function (response) {
                                    var id = response.data.disciplina.id;
                                    var promise = Servidor.buscar('matriculas/' + e.matricula.id + '/disciplinas-cursadas', {'disciplina': id});
                                    promise.then(function (response) {
                                        if (response.data.length) {
                                            frequencia.disciplinaCursada.id = response.data[0].id;
                                            e.matricula.disciplinaCursada = response.data;
                                            $scope.frequenciasAlunosTurma.push(frequencia);
                                        } else {
                                            $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].disabled = true;
                                            $('#PRESENCA' + e.id + e.matricula.id + a.id)[0].checked = false;
                                        }
                                    });
                                });
                            }
                            if ($index === $scope.enturmacoesDisciplinas.length-1 && $scope.aulasDia.length-1 === i) {
                                $scope.presencas = true;
                                $timeout(function() {
                                   $scope.fechaLoader();
                                }, 500);
                            }
                        });
                    });
                });
            };

            /*Marcar todas as enturmacoes com presenca*/
            $scope.selecionarTodasEnturmacoes = function (idAula) {
                $scope.mostraLoader();
                if($('#todasEnturmacoes')[0].checked){
                    var enturmacoesAtivas = 0;
                    $scope.enturmacoesDisciplinas.forEach(function(e, index){
                        if($('#PRESENCA'+e.id+e.matricula.id+idAula)[0].checked){
                            enturmacoesAtivas++;
                        }
                        if(index === $scope.enturmacoesDisciplinas.length-1){
                            if(enturmacoesAtivas === $scope.enturmacoesDisciplinas.length){
                                $('#todasEnturmacoes')[0].checked = false;
                                $scope.fechaLoader();
                                Servidor.customToast('Todas as frequências já estão marcadas');
                            }else{
                                $scope.marcarFrequenciaDisciplina();
                            }
                        }
                    });
                }else{
                    $scope.enturmacoesDisciplinas.forEach(function(e, indexE){
                        $('#PRESENCA'+e.id+e.matricula.id+idAula)[0].checked = false;
                        if(indexE === $scope.enturmacoesDisciplinas.length-1){
                            $scope.frequenciasAlunosTurma.forEach(function (f,indexF) {
                                f.status = 'FALTA';
                                if(indexF === $scope.frequenciasAlunosTurma.length-1){
                                    $timeout(function(){
                                        $scope.fechaLoader();
                                    },200);
                                }
                            });
                        }
                    });
                }
            };

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
                            $scope.frequenciasAlunosTurma.forEach(function(f) {
                                if (f.disciplinaCursada.id === cursada.id) {
                                    f.status = (bool) ? 'PRESENCA' : 'FALTA';
                                }
                            });
                        });
                    });
                });
            };

            $scope.verificaFaltaPresenca = function(enturmacao, aula) {
                var presenca = $('#PRESENCA'+enturmacao.id+enturmacao.matricula.id+aula.id)[0];
                if (presenca.checked) {
                    $scope.setaFrequencia(enturmacao.matricula.id, aula.id, enturmacao.id);
                } else {
                    presenca.checked = true;
                    $scope.frequencia = {
                        matricula: enturmacao.matricula,
                        enturmacao: enturmacao,
                        status: 'FALTA',
                        aula: aula
                    };
                    $('#modal-justificativa').openModal();
                }
            };

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
                                        if ($scope.frequencia.justificativa === undefined ) {
                                            $scope.frequencia.justificativa = null;
                                        }
                                        if ($scope.frequencia.justificativa) {
                                            freq.justificativa = $scope.frequencia.justificativa;
                                            freq.status = 'FALTA_JUSTIFICADA';
                                        } else {
                                            freq.status = 'FALTA';
                                        }
                                        var enturmacao = $scope.frequencia.enturmacao;
                                        $('#PRESENCA'+enturmacao.id+enturmacao.matricula.id+$scope.frequencia.aula.id)[0].checked = false;
                                    }
                                });
                            });
                        });
                    }
                });
            };

            /*Altera o status para chamada*/
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
                                        if ($('#PRESENCA' + enturmacao + matricula + aula)[0].checked) {
                                            f.status = 'PRESENCA';
                                            f.justificativa = '';
                                        } else {
                                            f.status = 'FALTA';
                                            if($('#todasEnturmacoes')[0].checked){
                                                $('#todasEnturmacoes')[0].checked = false;
                                            }
                                        }
                                    }
                                });
                            });
                        });
                    }
                });
            };

            $scope.dispensarTurma = function() {
                $scope.frequenciasAlunosTurma.forEach(function(freq) {
                    freq.status = 'DISPENSA';
                    freq.justificativa = '';
                });
                $scope.finalizarChamada();
            };

            $scope.verificaTurmaDispensada = function() {
                if ($scope.index === 'todas') {
                    $scope.finalizarChamada();
                } else {
                    var cont = 0;
                    $scope.frequenciasAlunosTurma.forEach(function(freq) {
                        if (freq.status === 'FALTA') { cont++; }
                    });
                    if (cont === $scope.frequenciasAlunosTurma.length) {
                        $('#modal-falta-dispensa').openModal();
                    } else {
                        $scope.finalizarChamada();
                    }
                }
            };

            /*Salva Chamada*/
            $scope.finalizarChamada = function () {
                $scope.mostraProgresso();
                if ($scope.frequenciasAlunosTurma.length) {
                    var objetoAdd = {'frequencias': []};
                    objetoAdd.frequencias = $scope.frequenciasAlunosTurma;
                    var result = Servidor.finalizar(objetoAdd, 'frequencias/*', 'Chamada');
                    result.then(function (response) {
                        $timeout(function () {
                            $scope.frequenciasAlunosTurma = [];
                            $scope.frequenciasTurma = [];
                            if ($scope.index === 'todas') {
                                $scope.setaPresenca();
                            } else {
                                $scope.marcarFrequenciaDisciplina();
                            }
                        }, 350);
                        var frequencia = {
                            'status': null,
                            'disciplinaCursada': {'id': null},
                            'aula': {'id': null},
                            'justificativa': null
                        };
                        $scope.fechaProgresso();
                    });
                }
            };

            $scope.alunoNotas = false;
            $scope.notasMatricula = function (matricula) {
                $scope.mostraLoader();
                $scope.matricula = matricula;
                if(!$scope.ativaTab){
                    $('#notasMatriculaID').addClass('card-panel');
                }
                $scope.buscarDisciplinasOfertadas();
                $('#info-aluno').closeModal();
                if ($scope.turma.etapa.sistemaAvaliacao.tipo === 'QUANTITATIVO') {
                    $scope.sistemaAvaliacao = 'quantitativas';
                } else {
                    $scope.sistemaAvaliacao = 'qualitativas';
                }
                var promise = Servidor.buscar('enturmacoes', {'matricula': matricula.id, 'turma': $scope.turma.id, 'encerrado': 0});
                promise.then(function (response) {
                    $scope.etapaEnturmacao = response.data[0].turma.etapa.id;
                    $scope.buscarDisciplinasCursadas(matricula.id, $scope.etapaEnturmacao);
                });
            };

            $scope.mostraMedias = false;
            $scope.buscarMedias = function (id) {
                $scope.disciplinasCursadas.forEach(function (d, index) {
                    var promise = Servidor.buscar('medias', {'disciplinaCursada': d.id});
                    promise.then(function (response) {
                        if (response.data.length) {
                            $scope.disciplinasCursadas[index].medias = response.data;
                            $scope.mostraEnturmacoes = false;
                            $scope.adicionarAlunos = false;
                            $scope.voltarAlunos = true;
                            $scope.alunoNotas = true;
                            $scope.mostraMedias = true;
                        }else if (index === $scope.disciplinasCursadas.length-1 && !response.data.length){
                            Servidor.customToast('Aluno não possui Médias');
                        }
                        if (index === $scope.disciplinasCursadas.length-1) {
                            $scope.fechaLoader();
                        }
                    });
                });
            };

            $scope.carregaMedia = function (media) {
                $scope.media = media;
                if (!media.notas.length) {
                    Servidor.customToast(media.nome + ' não possui nenhuma nota.');
                } else {
                    $scope.disciplinasCursadas.forEach(function (d) {
                        if (d.id === parseInt($scope.disciplinaCursada.id)) {
                            $scope.ofertada = d.disciplinaOfertada;
                        }
                    });
                    if ($scope.sistemaAvaliacao === 'qualitativas') {
                        $scope.media.notas.forEach(function (n, $indexN) {
                            var promise = Servidor.buscarUm('notas-qualitativas', n.id);
                            promise.then(function (response) {
                                $scope.media.notas[$indexN].habilidadesAvaliadas = response.data.habilidadesAvaliadas;
                            });
                        });
                        $timeout(function () {
                            $('#nota-aluno').openModal();
                            $('.collapsible').collapsible({accordion: false});
                        }, 500);
                    } else {
                        $scope.media.notas.forEach(function (n, $index) {
                            var promise = Servidor.buscarUm('avaliacoes-' + $scope.sistemaAvaliacao, n.avaliacao.id);
                            promise.then(function (response) {
                                $scope.media.notas[$index].avaliacao = response.data;
                                if ($index === $scope.media.notas.length - 1) {
                                    $('#nota-aluno').openModal();
                                }
                            });
                        });
                    }
                }
            };

            $scope.presencaMatricula = function (matricula) {
                $scope.mostraLoader();
                $scope.voltarAlunos = true;
                $scope.nenhumaEnturmacao = false;
                $scope.adicionarAlunos = false;
                $scope.fazerChamada = false;
                $scope.opcaoPesquisa = '';
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

            var novaSemana = function(){
                return {
                    domingo: {dia:{}},
                    segunda: {dia:{}},
                    terca: {dia:{}},
                    quarta: {dia:{}},
                    quinta: {dia:{}},
                    sexta: {dia:{}},
                    sabado: {dia:{}}
                };
            };

            $scope.criarCalendario = function(mes) {
                if (!mes && mes !== 0) { mes = parseInt(new Date().toJSON().split('T')[0].split('-')[1]); }
                if (mes < 1 || mes > 12) { Servidor.customToast('Excedeu limite do calendário.'); return; }
                $scope.semanas = [];
                var semana = novaSemana();
                var dia = 1;
                var ano = new Date().getFullYear();
                var data = new Date(ano, mes-1, dia).toJSON().split('T')[0];
                var comparaMes = mes;
                while(mes === comparaMes) {
                    var diaSemana = new Date(data).toDateString().split(' ')[0];
                    switch(diaSemana) {
                        case 'Sun': semana.domingo.dia.data = data; break
                        case 'Mon': semana.segunda.dia.data = data; break
                        case 'Tue': semana.terca.dia.data = data; break
                        case 'Wed': semana.quarta.dia.data = data; break
                        case 'Thu': semana.quinta.dia.data = data; break
                        case 'Fri': semana.sexta.dia.data = data; break
                        case 'Sat':
                            semana.sabado.dia.data = data;
                            $scope.semanas.push(semana);
                            semana = novaSemana();
                        break
                    }
                    data = new Date(ano, mes-1, ++dia).toJSON().split('T')[0];
                    comparaMes = parseInt(data.split('-')[1]);
                };
                $scope.mes = {numero: parseInt(mes), nome: dateTime.converterMes(mes) };
            };

            $scope.selecionarAula = function(aula) {
                $scope.avaliacoesPendentes.forEach(function(av) {
                    if ($scope.avaliacao.id === av.id) {
                        av.aulaEntrega = aula;
                        delete $scope.avaliacao;
                    }
                });
            };

            $scope.buscarAulas = function(disciplina, mes) {
                var params = {disciplina:disciplina, mes:mes};
                var promise = Servidor.buscar('turmas/'+$scope.turma.id+'/aulas', params);
                promise.then(function(response) {
                   $scope.aulas = response.data;
                });
            };

            /*Buscar id do dia*/
            $scope.buscarDiaFrequencia = function () {
                $scope.frequenciasDoAluno = [];
                $scope.disciplinasFrequencia = [];
                $scope.index = '';
                if ($scope.buscaFrequenciaAluno.data.length === 10) {
                    var arrayData = $scope.buscaFrequenciaAluno.data.split('/');
                    var data = arrayData[2] + "-" + (arrayData[1]) + "-" + arrayData[0];
                    var promise = Servidor.buscarUm('turmas', $scope.turma.id);
                    promise.then(function (response) {
                        $scope.turma = response.data;
                        var promise = Servidor.buscar('calendarios/' + $scope.turma.calendario.id + '/dias', {'data': data});
                        promise.then(function (response) {
                            if (response.data.length) {
                                if (response.data[0].letivo === false) {
                                    Servidor.customToast('Dia não letivo');
                                } else {
                                    $scope.idDiaFrequenciaAluno = response.data[0].id;
                                    $scope.buscaFrequenciaAluno.diaAula = $scope.idDiaFrequenciaAluno;
                                    $scope.buscarDisciplinasFrequencia();
                                }
                            } else {
                                Servidor.customToast('Dia não cadastrado');
                            }
                        });
                    });
                }
            };

            /*Limpa campos de pesquisa frequencia, Dia e Mes*/
            $scope.limpaOpcao = function () {
                $scope.index = null;
                if ($scope.opcaoPesquisa === 'mes') {
                    $scope.buscaFrequenciaAluno.data = null;
                    $scope.frequenciasDoAluno = [];
                    $scope.disciplinasCursadasFrequencia = [];
                    $scope.buscarDisciplinasCursadas($scope.matricula.id, $scope.turma.etapa.id);
                } else if ($scope.opcaoPesquisa === 'dia') {
                    $scope.buscaFrequenciaAluno.mes = '';
                    $scope.frequenciasMes = [];
                    $('#aulaData').focus();
                    $timeout(function () {
                        $('#disciplinasDoDia').material_select('destroy');
                        $('#disciplinasDoDia').material_select();
                    }, 100);
                }
            };

            /*Busca frequencias da disciplina no mês*/
            $scope.buscarFrequenciasMes = function () {
                $scope.frequenciasMes = [];
                var id = $scope.matriculaPresenca.id;
                if ($scope.index === 'todas') {
                    var disciplina = '';
                } else {
                    disciplina = $scope.disciplinasCursadas[$scope.index];
                }
                var promise = Servidor.buscar('frequencias', {'matricula': id, 'mes': $scope.buscaFrequenciaAluno.mes, 'disciplina': disciplina.id});
                promise.then(function (response) {
                    if (response.data.length > 0) {
                        $scope.frequenciasMes = response.data;
                    } else {
                        Servidor.customToast('Nenhuma Frequência registrada');
                    }
                });
            };

            /*Buscar as disciplinas do dia*/
            $scope.buscarDisciplinasFrequencia = function () {
                $scope.disciplinasFrequencia = [];
                $scope.arrayHorarios = [];
                var promisse = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', {'dia': $scope.buscaFrequenciaAluno.diaAula});
                promisse.then(function (response) {
                    var cont = 0;
                    response.data.forEach(function (a, $index) {
                        var array = a.horario.inicio.split(':');
                        $scope.arrayHorarios.push(array[0] + " " + $index);
                        cont++;
                        if (cont === response.data.length) {
                            $scope.arrayHorarios.sort();
                            $scope.arrayHorarios.forEach(function (h, $indexH) {
                                var arrayHorario = h.split(' ');
                                var posicao = arrayHorario[1];
                                $scope.disciplinasFrequencia[$indexH] = response.data[posicao];
                            });
                        }
                    });
                    $timeout(function () {
                        $('select').material_select('destroy');
                        $('select').material_select();
                    }, 200);
                });
            };

            /*Horario da aula da disciplina*/
            $scope.carregarHorarioDisciplinas = function () {
                var id = $scope.matriculaPresenca.id;
                var promise = Servidor.buscar('frequencias', {'matricula': id, 'aula': $scope.disciplinasFrequencia[$scope.index].id});
                promise.then(function (response) {
                    $scope.frequenciasDoAluno = response.data;
                    if ($scope.frequenciasDoAluno.length === 0) {
                        Servidor.customToast('Nenhuma frequência registrada');
                    } else {
                        $scope.textoJustificativa = $scope.frequenciasDoAluno[0].justificativa;
                    }
                    $timeout(function () {
                        $('#statusFrequencia').material_select('destroy');
                        $('#statusFrequencia').material_select();
                    }, 100);
                });
            };

            /*Altera Status frequencia ja existente*/
            $scope.alteraStatusDaFrequencia = function (status, frequencia) {
                if (frequencia) { $scope.frequencia = frequencia; }
                $scope.frequencia.status = status;
                if (status === 'PRESENCA' && $scope.frequencia.justificativa === undefined) {
                    $scope.frequencia.justificativa = null;
                }
                if ($scope.frequencia.justificativa && status !== 'PRESENCA') {
                    $scope.frequencia.status += '_JUSTIFICADA';
                }
                var promise = Servidor.finalizar($scope.frequencia, 'frequencias', 'Status');
                promise.then(function (response) {
                    $scope.frequenciasDoAluno.forEach(function(freq) {
                        if (freq === response.data.id) {
                            freq = response.data;
                        }
                    });
                    $scope.frequencia = {};
                    $('#modal-justificativa-diarios').closeModal();
                });
            };

            /*Verifica o status da frequencia para o select*/
            $scope.verificaStatusFrequencia = function (status) {
                if ($scope.frequenciasDoAluno.length > 0) {
                    if (status === 'FALTA' && $scope.frequenciasDoAluno[0].justificativa) {
                        $scope.textoJustificativa = $scope.frequenciasDoAluno[0].justificativa;
                        return true;
                    } else {
                        if (status === $scope.frequenciasDoAluno[0].status) {
                            return true;
                        }
                    }
                }
            };

            /*Abrir modal para justificativa de falta*/
            $scope.justificarFalta = function (frequencia) {
                $scope.frequencia = frequencia;
                $timeout(function () {
                    $scope.desabilitarBotao = true;
                    $('#modal-justificativa').openModal();
                }, 100);
            };
            
            $scope.nomeUnidade2 = null;
            $scope.buscarDisciplinasCursadas = function (id, etapa) {
                var promise = Servidor.buscar('matriculas/' + id + '/disciplinas-cursadas', {'etapa': etapa});
                promise.then(function (response) {
                    if (response.data.length) {
                        $scope.buscarMedias();
                        $timeout(function () {
                            $scope.fechaLoader();
                            $('select').material_select('destroy');
                            $('select').material_select();
                        }, 100);
                    } else {
                        $scope.fechaLoader();
                    }
                });
            };

            $scope.reiniciaFrequencia = function () {
                $scope.frequenciasAlunosTurma = [];
                $scope.frequencia.aula.id = null;
                $scope.diaChamada = null;
                $scope.presencas = false;
            };
            /*-------------------------------------------------------------Fim Frequecia------------------------------------------------------------*/

            /*Carrega o calendario*/
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

            /*Reinicia a variavel turma*/
            $scope.reiniciar = function () {
                $scope.turma = {
                    'nome': '',
                    'apelido': '',
                    'calendario': {id: null},
                    'turno': {id: null},
                    'etapa': {id: null},
                    'unidadeEnsino': {id: null},
                    'quadroHorario': {id: null},
                    'periodo': {id: null}
                };
                $scope.frequencia = {
                    'turma': {id: null},
                    'disciplina': {id: null},
                    'aula': {id: null}
                };
                $scope.quadroHorario = {
                    'nome': null,
                    'inicio': null,
                    'modelo': {id: null},
                    'unidadeEnsino': {id: null},
                    'turno': {id: null},
                    'diasSemana': []
                };
                $scope.disciplinasOfertadas = [];
                $scope.etapas = [];
                $scope.enturmacoes = [];
                $scope.reiniciarBusca();
                $scope.nenhumaEnturmacao = false;
            };

            $scope.reiniciarSemanas = function () {
                $scope.segunda = [];
                $scope.terca = [];
                $scope.quarta = [];
                $scope.quinta = [];
                $scope.sexta = [];
            };

            /*Fecha Modal aberto*/
            $scope.fecharModal = function () {
                $('.lean-overlay').hide();
                $('.modal').closeModal();
            };
            $scope.disciplinaCursada = {
                'id': null
            };

            /*Volta a pagina fechando o formulario*/
            $scope.fecharFormulario = function () {
                $scope.turma = { 'nome': '', 'apelido': '', 'calendario': {id: null}, 'limiteAlunos': null, 'turno': {id: null}, 'etapa': {id: null}, 'unidadeEnsino': {id: null}, 'quadroHorario': {id: null}, 'periodo': {id: null} }; // ESTRUTURA DE TURMA
                $scope.enturmacoesNotas = [];
                $scope.nomeUnidade2 = null;
                $scope.mostraProfessores = false;
                $scope.ativaTab = false;
                $scope.formTurma = false;
                $scope.semEnturmacoes = false;
                $scope.editando = false;
                $scope.mostraPdf =  false;
                $scope.mostraProfessores = false;
                $scope.adicionarAlunos = false;
                $scope.mostraEnturmacoes = false;
                $scope.alunoPresenca = false;
                $scope.voltarAlunos = false;
                $scope.presencas = false;
                $scope.fazerChamada = false;
                $scope.horarioAula = false;
                $scope.enturmarAlunosDireto = false;
                $scope.mostraQuadroHorario = false;
                $scope.alunoNotas = false;
                $scope.mostraMedias = false;
                $scope.index = null;
                $scope.frequencia.aula.id = null;
                $scope.opcaoForm = '';
                $scope.quadroHorario = {
                    'nome': null,
                    'inicio': null,
                    'modelo': {id: null},
                    'unidadeEnsino': {id: null},
                    'turno': {id: null},
                    'diasSemana': []
                };
                $scope.diaChamada = '';
                $scope.disciplinaCursada.id = null;
                $scope.reiniciarSemanas();
                $scope.enturmacoes = [];
                $scope.horariosDisciplina = [];
                $scope.disciplinasOfertadas = [];
                $scope.aulasDia = [];
                $scope.medias = [];
                $scope.aulaDisciplina = {};
                $scope.quadroHorario.id = null;
                $scope.horarios = null;
                $scope.horariosDisciplinaParaExcluir = [];
                Servidor.resetarValidador('validate-turma');
                $('#paginaAlunos, #paginaQuadro, #paginaProfessores, #paginaFrequenciaAluno').addClass('card-panel');
                $('#dForm, #aulaForm').material_select('destroy');
                $('.btn-add').show();
                $timeout(function () { $scope.fechaLoader(); }, 100);
            };

            /*Inicialização da pagina*/
            $scope.buscarUnidades();
            $scope.inicializar();
        }]);
})();

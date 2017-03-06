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
    var turmaAlunosModule = angular.module('turmaAlunosModule', ['servidorModule', 'turmaDirectives', 'erudioConfig']);
    //DEFINIÇÃO DO CONTROLADOR
    turmaAlunosModule.controller('TurmaAlunosController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin();
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','alunos');
        //ATRIBUTOS
        $scope.enturmacoes = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Alunos da Turma"; $scope.mostraAlunos = true;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = '/#/turmas/' + $routeParams.id; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
            
        //ALFABATIZAR ALUNO
        $scope.alfabetizar = function(enturmacao) {
            $scope.mostraProgresso();
            var promise = Servidor.buscarUm('matriculas', enturmacao.matricula.id);
            promise.then(function(response) {
                var matricula = response.data; matricula.alfabetizado = (enturmacao.matricula.alfabetizado) ? "" : new Date().getFullYear();
                var promise = Servidor.finalizar(matricula, 'matriculas', 'Aluno');
                promise.then(function() { $scope.fechaProgresso(); enturmacao.matricula.alfabetizado = matricula.alfabetizado; });
            });
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
            
        //BUSCAR ENTURMACOES
        $scope.buscarEnturmacoes = function () {
            $scope.mostraProgresso();            
            var promise = Servidor.buscar('vagas', {turma: $scope.turma.id}); var solicitacoes = 0;
            promise.then(function(response){
                var vagas = response.data;
                for (var j=0; j<vagas.length; j++) { if (vagas[j].solicitacaoVaga !== undefined) { solicitacoes++; } }
                $scope.turma.quantidadeAlunos = $scope.turma.quantidadeAlunos + solicitacoes; $scope.solicitacoes = solicitacoes;
                var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': 0});
                promise.then(function (response) {
                    $scope.enturmacoes = response.data;
                    if ($scope.enturmacoes.length === 0) {
                        //TRATAR
                    } else {
                        $scope.enturmacoes.forEach(function(e) { Servidor.buscarUm('matriculas', e.matricula.id).then(function(response) { e.matricula = response.data; });});
                        $scope.nenhumaEnturmacao = false;
                    }
                    $('.tooltipped').tooltip('remove');
                    $timeout(function () { $('.tooltipped').tooltip({delay: 50}); $scope.fechaProgresso(); $('.collapsible').collapsible(); }, 500);
                });
            });
        };
        
        //TELA DE ENTURMACAO DE ALUNOS
        $scope.enturmarAlunos = function () { Servidor.verificaLabels(); $scope.verificarDiaLetivo($scope.turma.calendario, new Date().toJSON().split('T')[0]); };
        
        //VERIFICA DIA LETIVO
        $scope.verificarDiaLetivo = function(calendario, data) {
            var promise = Servidor.buscar('calendarios/'+calendario.id+'/dias', {data: data});
            promise.then(function(response) {
                if (response.data.length) {
                    var dia = response.data[0];
                    if (dia.letivo) { $scope.botaoEnturmacaoAutomatica = false;
                    } else { var d = data.split('-'); data = new Date(d[0], d[1], parseInt(d[2]-1)).toJSON().split('T')[0]; $scope.verificarDiaLetivo(calendario, data); }
                } else { $scope.botaoEnturmacaoAutomatica = true; }
            });
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            $('.material-tooltip').remove(); var promise = Servidor.buscarUm('turmas',$routeParams.id); $('.title-module').html($scope.titulo);
            promise.then(function(response){
                $scope.turma = response.data;
                if ($scope.turma.quantidadeAlunos) { $scope.buscarEnturmacoes();
                } else { window.location = '/#/turmas/' + $scope.turma.id + '/alunos/enturmar/novo'; }
                $timeout(function () { $('.tooltipped').tooltip({delay: 50}); Servidor.entradaPagina();}, 1000);
            });
        };
        
        /* ATRIBUTOS GERAIS */
        //$scope.opcoesTurma = false; // VARIAVEL DE CONTROLE PARA TELA DE PROFESSORES
        //$scope.abrirResultadosBusca = false; // VARIAVEL PARA ABRIR RESULTADOS DE BUSCA    
        //$scope.ativaTab = false; // CONTROLE DAS ABAS DE CONTEUDO DE TURMA
        //$scope.excluirDisciplina = false; // VARIAVEL DE CONTROLE DE EXCLUSAO DE DISCIPLINA DA TURMA
        //$scope.role = 'TURMA'; // NOME DA PERMISSAO
        //$scope.permissao = true; // CONTROLE DE EXIBICAO DO MÓDULO
        //$scope.instituicao = parseInt(sessionStorage.getItem('instituicao'));
        //$scope.dataInicioQuadroHorario = '';
        //$scope.aulaGerada = false;

        /* ATRIBUTOS DE TURMA */
         // ARRAY DE UNIDADES DE ENSINO
        //$scope.calendarios = []; // ARRAY DE CALENDARIOS ACADEMICOS
        //$scope.turnos = []; // ARRAY DE TURNOS
        //$scope.unidade = {'id':null}; // ESTRUTURA DE UNIDADE DE ENSINO
        //$scope.curso = { 'id': null }; // ESTRUTURA DE CURSO
        // // ESTRUTURA DE ETAPA
        //$scope.mostraProfessores = false; // CONTROLE DE EXIBIÇÃO DE PROFESSORES
        // // ARRAY DE DISCIPLINAS OFERTADAS POR TURMA
        //$scope.nomeProfessor = null; // LABEL COM O NOME DO PROFESSOR
        //$scope.formTurma = false; // VARIAVEL DE CONTROLE DE EXIBICAO DO FORMULÁRIO DA TURMA
        //$scope.opcaoForm = ''; // VARIAVEL DE CONTROLE DO FORM
        // // ESTRUTURA DE DISCIPLINA DA TURMA
        // // ESTRUTURA DE TURMA
        //$scope.cursoTurma = {'id': null}; // ESTRUTURA DO CURSO DA TURMA
        //$scope.TurmaService = TurmaService;
        //$scope.MatriculaService = MatriculaService;

        /*$scope.$watch('MatriculaService.abrirFormulario', function(abreForm) {                
            if(abreForm) {
                $scope.carregarTurma(TurmaService.turma, 'alunos');
                MatriculaService.fechaForm();
            }
        });*/

        /*$scope.selecionarOpcaoLista = function(turma, opcao) {
            var promise = Servidor.buscarUm('turmas', turma.id);
            promise.then(function(response) {
                $scope.turma = response.data;
                $scope.trocarTab(opcao);
            });
        };

        /* CONTROLE DAS TABS */
        /*$scope.trocarTab = function (tab) {
            $scope.ativaTab = true;
            $scope.oferecendoDisciplina = false;
            switch (tab) {
                case 'pdf':
                        $scope.mostraPdf = true; $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                        $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                        $scope.fazerChamada = false;
                    break;
                case 'form':
                    if ($scope.turma.id) {
                        $scope.mostraForm = true; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                        $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                        $scope.fazerChamada = false;
                    } break;
                case 'alunos':
                    if ($scope.turma.id) {
                        $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                        $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                        $scope.fazerChamada = false;
                        if ($scope.turma.quantidadeAlunos) {
                            $scope.buscarEnturmacoes($scope.turma, true);
                        } else {
                            $scope.enturmarAlunos();
                            Servidor.customToast('Turma não possui nenhum aluno enturmado');
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
                        $scope.fazerChamada = false;
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
                        $scope.fazerChamada = false;
                        $('#paginaQuadro').removeClass('card-panel'); $scope.quadroHorarioTurma($scope.turma);
                    } else {
                        Servidor.customToast('Preencha os dados cadastrais primeiro.');
                    } break;
                case 'professores':
                    if ($scope.turma.id) {
                        $scope.mostraForm = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false; $scope.mostraEnturmacoes = false;
                        $scope.mostraProfessores = true; $scope.alunoPresenca = false; $scope.mostraQuadroHorario = false; $scope.adicionarAlunos = false;
                        $scope.fazerChamada = false;
                        $('#paginaProfessores').removeClass('card-panel'); 
                    } else {
                        Servidor.customToast('Preencha os dados cadastrais primeiro.');
                    } break;
                case 'chamada':
                    if ($scope.turma.id) {
                        $scope.mostraForm = false; $scope.mostraQuadroHorario = false; $scope.enturmandoAlunos = false; $scope.voltarAlunos = false;
                        $scope.mostraEnturmacoes = false; $scope.mostraProfessores = false; $scope.alunoPresenca = false; $scope.adicionarAlunos = false;
                        $scope.realizarChamada($scope.turma, true);
                        $scope.editando = true;
                    } else {
                        Servidor.customToast('Preencha os dados cadastrais primeiro.');
                    } break;
            };
        };

        /* INICIALIZAR MAPA */
        /*var callDatepicker = function() {
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

        /* CONTROLE DE SELECTS */
        /*$scope.verificaUnidadeBusca = function (id) { if (id === $scope.unidade.id) { return true; } };
        $scope.verificaEtapaBusca = function (id) { if (id === $scope.etapa.id) { return true; } };
        $scope.verificaCursoBusca = function (id) { if (id === $scope.curso.id) { return true; } };

        /* VERIFICANDO PERMISSAO DE ESCRITA PARA INPUTS */
        /*$scope.verificarEscritaInput = function () {
            var result = Servidor.verificaEscrita($scope.role);
            if (result) { return ''; } else { return 'disabled'; }
        };

        /* VERIFICANDO PERMISSAO DE ESCRITA */
        /*$scope.verificarEscrita = function () {
            var result = Servidor.verificaEscrita($scope.role);
            if (result) { return true; } else { return false; }
        };
        $scope.verificarEscrita = function() {
            return Servidor.verificaEscrita('TURMA');
        };

        /* TELA DE ERRO */
        /*$scope.reiniciaErroBusca = function () { $scope.nenhumaEnturmacao = false; $scope.adicionarAlunos = false; };

        /*-------------------------------------------------------------Enturmação------------------------------------------------------------*/
        /*
        $scope.enturmacoes = [];
        $scope.nenhumaEnturmacao = false;
        $scope.enturmandoAlunos = false;
        $scope.adicionarAlunos = false;
        $scope.mostraEnturmacoes = false;
        $scope.enturmarAlunosDireto = false;
        
        $scope.enturmacao = {'turma': {id: null}, 'matricula': {id: null}};

        /*$scope.voltarParaAlunos = function () {
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

         /*Verifica as disciplinas cursadas com ofertadas*/
        /*$scope.verificaDisciplinas = function () {
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

        /*Verifica as matriculas que possuem disciplinas na etapa da turma*/
        /*$scope.verificarDisciplinasCursadas = function () {
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
        /*$scope.verificarEnturmacoesAtivas = function () {
            var pos = 0; var cont = 0;
            $scope.matriculasVerificadasDisciplinas.forEach(function (m, indexM) {
                var promise = Servidor.buscarUm('matriculas', m.id);
                promise.then(function (response) {
                    cont++;
                    if (response.data.enturmacoesAtivas.length) {
                        var temEnturmacao = 0;
                        var contEnturmacoes = 0;
                        response.data.enturmacoesAtivas.forEach(function (e, index) {
                            var promiseE = Servidor.buscarUm('turmas', e.turma.id);
                            promiseE.then(function (responseE) {
                                contEnturmacoes++;
                                if(responseE.data.etapa.id === $scope.turma.etapa.id){ temEnturmacao++; }
                                if(temEnturmacao === 0 && contEnturmacoes === response.data.enturmacoesAtivas.length){
                                    $scope.matriculas[pos] = m;
                                    pos++;
                                }
                            });
                        });
                    } else {
                        $scope.matriculas[pos] = m;
                        pos++;
                    }
                    if(cont === $scope.matriculasVerificadasDisciplinas.length && !$scope.matriculas.length){
                        $scope.fechaLoader();
                        Servidor.customToast('Nenhuma matricula encontrada.');
                    }
                });
            });
        };

        /*Salvar Eturmações*/
        /*

        /*Desmarca todas as matriculas*/
        /*
        /*-------------------------------------------------------------Fim Enturmação------------------------------------------------------------*/

        /*-------------------------------------------------------------Quadro de Horario------------------------------------------------------------*/
        /*$scope.segunda = [];
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
        /*$scope.gerarPdf =  function(turma){
            $scope.mostraPdf =  true;
            $scope.trocarTab('pdf');
            $scope.buscarEnturmacoes(turma, true);
        };

        /*Tab Quadro de Horário*/
        /*$scope.quadroHorarioTurma = function (turma) {
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
        /*$scope.gerarAulas = function () {
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
        /*$scope.gerarNovasAulas = function (inicioQuadro) {
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
                /*var promise = Servidor.buscarUm('etapas', $scope.etapa.id);
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
        /*$scope.fazerChamada = false;
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


        /*Preparar para realizar chamada*/
        /*$scope.realizarChamada = function (turma, tab) {
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
        /*$scope.escolheDiaChamada = function (data) {
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
        /*$scope.verificaDataEnturmacoes = function (origem) {
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
        /*$scope.buscarAulasDia = function () {
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
        /*$scope.carregarAulas = function (index) {
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
        /*$scope.marcarFrequenciaDisciplina = function () {
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
        /*$scope.setaPresenca = function () {
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
        /*$scope.selecionarTodasEnturmacoes = function (idAula) {
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



        /*Altera o status para chamada*/
        /*$scope.setaFrequencia = function (matricula, aula, enturmacao) {
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
        /*$scope.finalizarChamada = function () {
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
        /*

        /*Limpa campos de pesquisa frequencia, Dia e Mes*/
        /*

        /*Busca frequencias da disciplina no mês*/
        /*

        /*Buscar as disciplinas do dia*/
        /*

        /*Horario da aula da disciplina*/
        /*

        /*Altera Status frequencia ja existente*/
        /*

        /*Verifica o status da frequencia para o select*/
        /*$scope.verificaStatusFrequencia = function (status) {
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
        /*

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
        /*$scope.calendario = function () {
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
        /*$scope.reiniciar = function () {
            $scope.turma = {
                'nome': '',
                'apelido': '',
                'calendario': {id: null},
                'turno': {id: null},
                'etapa': {id: null},
                'unidadeEnsino': {id: null},
                'quadroHorario': {id: null}
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
        /*
        $scope.disciplinaCursada = {
            'id': null
        };

        /*Volta a pagina fechando o formulario*/
        /*$scope.fecharFormulario = function () {
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
        };*/

        //INICIALIZANDO
        //$scope.buscarUnidades();
        //($scope.escrita) ? $scope.buscarCursos(true) : $scope.buscarCursos(false);
        $scope.inicializar(); 
    }]);
})();
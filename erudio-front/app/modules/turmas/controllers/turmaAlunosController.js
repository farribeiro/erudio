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
    turmaAlunosModule.controller('TurmaAlunosController', ['$scope', 'Servidor', '$timeout', '$templateCache', 'ErudioConfig', '$routeParams', '$rootScope', function ($scope, Servidor, $timeout, $templateCache, ErudioConfig, $routeParams, $rootScope) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.isAdmin = Servidor.verificaAdmin(); $scope.config = ErudioConfig; $scope.cssUrl = ErudioConfig.extraUrl;
        $scope.escrita = Servidor.verificaEscrita('TURMA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateCustom('turmas','alunos');
        //ATRIBUTOS
        $scope.enturmacoes = []; $scope.progresso = false; $scope.cortina = false; $scope.titulo = "Alunos da Turma"; $scope.mostraAlunos = true;
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        //PREPARA VOLTAR
        $scope.prepararVoltar = function (objeto) { window.location = $scope.config.dominio+'/#/turmas/' + $routeParams.id; };
        //MOSTRA LABELS MENU FAB
        $scope.mostrarLabels = function () { $('.toolchip').fadeToggle(250); };
        
        //VER MEDIAS
        $scope.verMedias = function (enturmacao) {
            $rootScope.enturmacaoMedias = enturmacao;
            window.location.href = ErudioConfig.dominio + '/#/matriculas/'+enturmacao.matricula.id+'/enturmacoes';
        };
        
        //ALFABATIZAR ALUNO
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
                $scope.aluno = response.data; $('#info-aluno').modal('open');
                var promise = Servidor.buscar('telefones', {'pessoa': $scope.aluno.id});
                promise.then(function (response) { $scope.telefones = response.data; });
                $timeout(function () { $scope.fechaProgresso();
                if ($scope.aluno.endereco !== undefined && $scope.aluno.endereco !== null) {
                    if ($scope.aluno.endereco.latitude !== undefined) { $scope.initMap(false, "info-map"); }
                }
            }, 300);
            });
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
                $scope.turma = response.data; $('#info-aluno').modal();
                if ($scope.turma.quantidadeAlunos) { $scope.buscarEnturmacoes(); }
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
                $scope.mostraProgresso();
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
                        promise.then(function() { if (--$scope.requisicoes === 0) { $scope.fechaProgresso(); } });
                    }
                });
                if(!aptos) {
                    Servidor.customToast('Não há alunos aptos para oferecer nova disciplina.');
                    $scope.requisicoes = 0;
                    $scope.fechaProgresso();
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
                        $scope.fechaProgresso();
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
                        $scope.fechaProgresso();
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
        /*
        /*Tab gerar PDF*/
        /*$scope.gerarPdf =  function(turma){
            $scope.mostraPdf =  true;
            $scope.trocarTab('pdf');
            $scope.buscarEnturmacoes(turma, true);
        };

        $scope.abrirModalProfessor = function(disciplina) {
            $scope.disciplinaProfessor.nome = disciplina.nome;
            $scope.disciplinaProfessor.id = disciplina.id;
            $scope.nomeProfessor = '';
            $timeout(function () {
                $('#modal-professor').modal();
                $timeout(function () {
                    $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                }, 200);
            }, 200);
        };

        $scope.verificaPendencias = function(dataInicio) {
            if (dataInicio.length === 10) {
                $scope.mostraProgresso(true);
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
                                $timeout(function(){ $scope.fechaProgresso(); }, 500);
                            }
                        });
                    });
                });
            }
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
        /*Preparar para realizar chamada*/
        /*

        /*Verifica se a data de enturmacao é menor que data da chamada*/
        /*$scope.verificaDataEnturmacoes = function (origem) {
            $scope.mostraProgresso();
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

        /*Salva Chamada*/
        /*

        
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
                        $scope.fechaProgresso();
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
                        $('#nota-aluno').modal();
                        $('.collapsible').collapsible({accordion: false});
                    }, 500);
                } else {
                    $scope.media.notas.forEach(function (n, $index) {
                        var promise = Servidor.buscarUm('avaliacoes-' + $scope.sistemaAvaliacao, n.avaliacao.id);
                        promise.then(function (response) {
                            $scope.media.notas[$index].avaliacao = response.data;
                            if ($index === $scope.media.notas.length - 1) {
                                $('#nota-aluno').modal();
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
            
            $scope.medias = [];
            $scope.aulaDisciplina = {};
            $scope.quadroHorario.id = null;
            $scope.horarios = null;
            $scope.horariosDisciplinaParaExcluir = [];
            Servidor.resetarValidador('validate-turma');
            $('#paginaAlunos, #paginaQuadro, #paginaProfessores, #paginaFrequenciaAluno').addClass('card-panel');
            $('#dForm, #aulaForm').material_select('destroy');
            $('.btn-add').show();
            $timeout(function () { $scope.fechaProgresso(); }, 100);
        };*/

        //INICIALIZANDO
        //$scope.buscarUnidades();
        //($scope.escrita) ? $scope.buscarCursos(true) : $scope.buscarCursos(false);
        $scope.inicializar(); 
    }]);
})();
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
    var mainModule = angular.module('mainModule', ['servidorModule', 'mainDirectives', 'instituicaoModule', 'cursoModule', 'etapaModule', 'regimeModule', 'moduloModule', 'turnoModule',
        'unidadeModule', 'tipoModule', 'turmaModule', 'calendarioModule', 'eventoModule', 'quadroHorarioModule', 'modeloQuadroHorarioModule', 'dateTimeModule', 'matriculaModule',
        'pessoaModule', 'funcionarioModule', 'cargosModule', 'diarioFrequenciasModule', 'tiposAvaliacoesModule', 'habilidadeModule', 'avaliacaoModule', 'disciplinaModule', 'makePdfModule',
        'movimentacoesModule','usuarioModule','permissaoModule','vagaModule','historicoEscolarModule','boletimEscolarModule', 'espelhoNotasModule','registroMatriculasModule', 'alunosDefasadosModule',
        'grupoPermissaoModule','cursoFormModule']);


    mainModule.filter('telefone', function () {
        return function (telefone) {
            if (!telefone) {
                return '';
            }
            var value = telefone.toString().trim().replace(/^\+/, '');

            if (value.match(/[^0-9]/)) {
                return telefone;
            }
            if (value.length >= 10) {
                var codigoPostal, numero;
                codigoPostal = value.slice(0, 2);
                numero = value.slice(2);
                numero = numero.slice(0, numero.length/2) + '-' + numero.slice(numero.length - numero.length/2);
                return ("(" + codigoPostal + ") " + numero).trim();
            }
            return telefone;
        };
    });

    mainModule.filter('horario', function () {
        return function (horario) {
            if (!horario){
                return '';
            }
            if (horario.length === 8) {
                var array = horario.split(':');
                horario = array[0] + ':' + array[1];
            }
            return horario;
        };
    });

    mainModule.filter('cpf', function() {
        return function(cpf) {
            if (!cpf) { return ''; }
            if (cpf.length === 11) {
                cpf = cpf.slice(0,3) + '.' + cpf.slice(3,6) + '.' + cpf.slice(6,9) + '-' + cpf.slice(9,12);
            }
            return cpf;
        };
    });

    mainModule.filter('comecarEm', function() {
        return function(vetor, comecarEm) {
            if(vetor === undefined) { return null; }
            if (comecarEm < 0) { comecarEm = 1; }
            if (comecarEm > vetor.length) { return null; }
            if (vetor !== undefined && comecarEm-1 < vetor.length) {
                var novoVetor = [];
                for (var i = comecarEm-1; i < vetor.length; i++) {
                    novoVetor.push(vetor[i]);
                }
                return novoVetor;
            }
            return vetor;
        };
    });

    mainModule.filter('quantidade', function() {
        return function(quantidade) {
            quantidade = parseInt(quantidade);
            var array = [];
            for (var i = 0; i < quantidade; i++) {
                array.push(i+1);
            }
            return array;
        };
    });

    mainModule.controller('MainController', ['$scope', '$timeout', 'Servidor', 'dateTime', 'AvaliacaoService', 'PessoaService', 'FuncionarioService', 'MatriculaService', 'TurmaService', function ($scope, $timeout, Servidor, dateTime, AvaliacaoService, PessoaService, FuncionarioService, MatriculaService, TurmaService) {

        this.tab = 'home';
        this.tabAtual = "";
        this.leftDropdown = '';
        this.profile = sessionStorage.getItem('profile');
        $scope.avatar = sessionStorage.getItem('avatar');
        this.baseUpload = sessionStorage.getItem('baseUploadUrl');
        $scope.inicializando = false;
        $scope.AvaliacaoService = AvaliacaoService;
        $scope.PessoaService = PessoaService;
        $scope.FuncionarioService = FuncionarioService;
        $scope.MatriculaService = MatriculaService;
        $scope.TurmaService = TurmaService;
        //CARREGA MÓDULO
        this.carregarConteudo = function (modulo, options) { return "app/modules/" + modulo + "/partials/" + modulo + options + ".html"; };

        //SELECIONA MÓDULO
        this.selecionar = function (modulo, options) {
            if (options === undefined) { options = ""; }
            if (modulo !== this.tab) {
                //url amigavel
                if (modulo !== 'main') {
                    if (!sessionStorage.getItem('alocacao') && !Servidor.verificarPermissoes("SUPER_ADMIN")) {
                        return Servidor.customToast('Selecione uma alocação.');
                    }
                    //var urlStr = window.location.href; var arr = urlStr.split('/'); var str = arr[arr.length-2];
                    //Servidor.mudarUrl(str,modulo);
                }
                this.tab = modulo;
                sessionStorage.setItem('module', this.tab);
                sessionStorage.setItem('moduleOptions', options);
                this.tabAtual = this.carregarConteudo(modulo, options);
                Servidor.removeTooltipp();
                $scope.inicializar(); $scope.inicializando = true;
            }
        };

        //SAI DO SISTEMA
        this.logout = function () { sessionStorage.clear(); window.location = 'login.html'; };

        //ABRE MODAL DE FOTO
        this.modalAvatar = function () { $('#modalAvatarFoto').openModal(); $scope.labelUpload = 'INSERIR FOTO'; };

        //EVENTO DE BOTAO
        this.uploadAction = function () { $('#upload-file').click(); };

        //ENVIA FOTO
        $scope.sendUpload = function () {
            $('.upload-click').hide(); $('.upload-send').show();
            var formData = new FormData();
            formData.append('file',$('#upload-file')[0].files[0]);
            formData.append('username',sessionStorage.getItem('username'));
            var url = sessionStorage.getItem('baseUrl') + '/assets';
            $.ajax({
                url: url, type: 'POST', data: formData, async: true,
                complete: function (){
                    $('#modalAvatar').closeModal(); $('.upload-click').show(); $('.upload-send').hide(); $scope.searchAvatar();
                }, cache: false, contentType: false, processData: false
            });
        };

        //BUSCA AVATAR
        $scope.searchAvatar = function () {
            var usuario = sessionStorage.getItem('pessoaId');
            var promise = Servidor.buscar('pessoas', {usuario: usuario});
            promise.then(function (response) {
                var avatar = response.data[0].avatar;
                if (avatar !== undefined) {
                    var promise = Servidor.buscarUm('assets', response.data[0].avatar);
                    promise.then(function (response) {
                        sessionStorage.setItem('avatar',response.data.file);
                        $timeout(function() { $scope.avatar = response.data.file; }, 500);
                    });
                }
            });
        };

        //ATRIBUTOS
        $scope.estadosCivis = []; $scope.racas = []; $scope.buscarParticularidades = []; $scope.vinculos = []; $scope.alocacoes = [];
        $scope.disciplinas = []; $scope.eventos = []; $scope.estados = []; $scope.cidade = {}; $scope.cidades = []; $scope.horarios = [];
        $scope.habilidades = []; $scope.conceitos = []; $scope.alunos = []; $scope.medias = []; $scope.disciplinasCursadas = [];
        $scope.medias = []; $scope.alocacao = {'id': null}; $scope.disciplina = {'id': null}; $scope.evento = {'id': null}; $scope.calendario = {'id': null};
        $scope.mes = {'nome': '', 'num': null}; $scope.media = { 'aluno': {'nome': ''} }; $scope.chamada = {'dia': ''}; $scope.buscaObservacao = {data: '', inicio: '', termino: ''};
        $scope.aulas = []; $scope.frequenciasAlunosTurma = []; $scope.quantitativo = false; $scope.mostraPerfil = false; $scope.mostraCalendario = false;
        $scope.mostraFrequencia = false; $scope.mostraDiarioNota = false; $scope.mostraAvaliacoes = false; $scope.mostraCampoJustificativa = false;
        $scope.fazerChamada = false; $scope.imprimirChamada = false; $scope.fechandoMedia = false; $scope.aluno = {'nome': null}; $scope.alunos = [];
        $scope.letivos = 0; $scope.efetivos = 0; $scope.naoLetivos = 0; $scope.cidadeId = null; $scope.cortina = false; $scope.AvaliacaoService = AvaliacaoService;
        $scope.mostraCortina = function () { $scope.cortina = true; }; $scope.fechaCortina = function () { $scope.cortina = false; };
        $scope.vinculo = {
            'codigo': null, 'status': '', 'tipoContrato': '', 'cargaHoraria': '', 'funcionario': {}, 'cargo': { 'nome': '', 'professor': false },
            'instituicao': null, 'alocacao': [], 'id': null
        };
        $scope.limparVinculo = function () {
            $scope.vinculo = {
                'codigo': null, 'status': '', 'tipoContrato': '', 'cargaHoraria': '', 'funcionario': {}, 'cargo': { 'nome': '', 'professor': false },
                'instituicao': null, 'alocacao': [], 'id': null
            };
        };
        $scope.semana = {
            'domingo': {'dia': {'data': null}, 'horario': {'id': null}},
            'segunda': {'dia': {'data': null}, 'horario': {'id': null}},
            'terca': {'dia': {'data': null}, 'horario': {'id': null}},
            'quarta': {'dia': {'data': null}, 'horario': {'id': null}},
            'quinta': {'dia': {'data': null}, 'horario': {'id': null}},
            'sexta': {'dia': {'data': null}, 'horario': {'id': null}},
            'sabado': {'dia': {'data': null}, 'horario': {'id': null}}
        };
        $scope.limparSemana = function () {
            $scope.semana = {
                'domingo': {'dia': {'data': null}, 'horario': {'id': null}},
                'segunda': {'dia': {'data': null}, 'horario': {'id': null}},
                'terca': {'dia': {'data': null}, 'horario': {'id': null}},
                'quarta': {'dia': {'data': null}, 'horario': {'id': null}},
                'quinta': {'dia': {'data': null}, 'horario': {'id': null}},
                'sexta': {'dia': {'data': null}, 'horario': {'id': null}},
                'sabado': {'dia': {'data': null}, 'horario': {'id': null}}
            };
        };

        // ESTRUTURA PESSOA FISICA
        $scope.pessoa = {
            'nome': null, 'dataNascimento': null, 'email': null, 'genero': null,
            'raca': null, 'estadoCivil': {'id': null}, 'particularidades': [], 'nomePai': null,
            'nomeMae': null, 'naturalidade': null, 'nacionalidade': null, 'numeroRG': null,
            'dataExpedicaoRg': null, 'orgaoExpedidorRg': null, 'cpfCnpj': null, 'certidaoNascimento': null,
            'dataExpedicaoCertidaoNascimento': null, 'codigoInep': null, 'nis': null, 'endereco': null
        };

        // VERIFICA RAÇA
        $scope.verificaRaca = function (id) {
            if ($scope.pessoa.raca) { if (id === $scope.pessoa.raca.id) { return true; } }
        };

        /* Recebe informacoes pessoa */
        $scope.recebePessoa = function () {
            var usuario = sessionStorage.getItem('pessoaId');
            var promise = Servidor.buscarUm('pessoas', usuario);
            promise.then(function (response) {
                $scope.pessoa = response.data;
                $scope.pessoa.dataNascimento = dateTime.converterDataForm ($scope.pessoa.dataNascimento);
                $timeout(function() {
                    $('#genero, #nacionalidade, #alunoRaca, #estadoCivil').material_select();
                    Servidor.verificaLabels();
                }, 50);
                $scope.buscaEstados();
            });
        };

        // Busca estados
        $scope.buscaEstados = function () {
            Servidor.buscarEstados().getList().then(function (response) {
                $scope.estados = response.plain();
                if ($scope.pessoa.endereco.id !== undefined && $scope.pessoa.endereco.id !== undefined) {
                    $scope.buscaCidades($scope.pessoa.endereco.cidade.estado.id);
                } else {
                    $timeout(function(){
                        $('#cidade').material_select('destroy');
                        $('#cidade').material_select();
                    }, 50);
                }
                $timeout(function () {
                    $('#estado').material_select('destroy');
                    $('#estado').material_select();
                }, 50);
            });
        };

        /* Seleciona o estado correto */
        $scope.selecionaEstado = function (estado) {
            var estadoo = estado;
            $scope.estados.forEach(function (e) {
                if (e.id === parseInt(estadoo.id)) {
                    $scope.pessoa.endereco.cidade.estado = e;
                }
            });
            $scope.cidadeId = null;
            $scope.pessoa.endereco.cidade.id = null;
            $scope.pessoa.endereco.cidade.nome = null;
        };

        /* Busca de Cidades - SelectBox */
        $scope.buscaCidades = function (id) {
            var promise = Servidor.buscar('cidades', {'estado': id});
            promise.then(function (response) {
                $scope.cidades = response.data;
                $timeout(function(){
                    $('#cidade, #estado').material_select('destroy');
                    $('#cidade, #estado').material_select();
                }, 50);
            });
        };

        /* Seleciona Cidade */
        $scope.selecionaCidade = function () {
            var cidade = null;
            for (var i = 0; i < $scope.cidades.length; i++) {
                if ($scope.cidades[i].id === parseInt($scope.cidadeId)) {
                    cidade = $scope.cidades[i];
                }
            }
            $scope.pessoa.endereco.cidade = cidade;
            $scope.pessoa.endereco.bairro = null;
            $scope.pessoa.endereco.logradouro = null;
        };

        /* CARREGA O VINCULO SELECIONADO */
        $scope.carregarVinculo = function (vinculoId) {
            //se for vinculo diferente, zera alocação e disciplina
            if(vinculoId !== $scope.vinculo.id) {
                $scope.alocacoes = []; $scope.alocacao.id = '';
                $timeout(function() { $('#alocacoes').material_select(); }, 10);
                $scope.disciplinas = []; $scope.disciplina.id = '';
                $timeout(function() { $('#disciplinas').material_select(); }, 10);
            }
            //busca vinculo novo
            var promise = Servidor.buscarUm('vinculos', vinculoId);
            promise.then(function (response) {
                if(sessionStorage.getItem('vinculo') !== vinculoId) {
                    sessionStorage.setItem('instituicao', response.data.instituicao.id); sessionStorage.setItem('vinculo', response.data.id);
                }
                $scope.vinculo = response.data; $scope.vinculo.id = '';
                $scope.vinculo.id = sessionStorage.getItem('vinculo');
                $timeout(function(){ $('#vinculos').material_select('destroy'); $('#vinculos').material_select(); }, 150);
            });
        };

        /* GUARDA A ALOCAÇÃO */
        $scope.carregarAlocacao = function (alocacaoId) {
            $scope.reiniciar();
            $scope.disciplinas = []; $scope.disciplina.id = '';
            $timeout(function() { $('#disciplinas').material_select(); }, 10);
            var promise = Servidor.buscarUm('alocacoes', alocacaoId);
            promise.then(function (response) {
                $scope.alocacao = response.data;
                sessionStorage.setItem('unidade', response.data.instituicao.id);
                if(sessionStorage.getItem('alocacao') !== alocacaoId) { sessionStorage.setItem('alocacao', alocacaoId); }
                $scope.alocacao.id = ''; $scope.alocacao.id = sessionStorage.getItem('alocacao');
                $timeout(function() { $('#alocacoes').material_select(); }, 150);
                $scope.disciplinas = [];
                $scope.alocacao.disciplinasMinistradas.forEach(function(d, $index){
                    var promise = Servidor.buscarUm('disciplinas-ofertadas', d.id);
                    promise.then(function (response) {
                        if(response.data.turma.status !== 'ENCERRADO'){ $scope.disciplinas.push(response.data); }
                        if($index === $scope.alocacao.disciplinasMinistradas.length -1){
                            var disciplina = sessionStorage.getItem('disciplina');
                            if (disciplina === undefined || !disciplina && $scope.alocacao.disciplinasMinistradas.length === 1) {
                                $scope.carregarDisciplina($scope.disciplinas[0].id);
                            }
                            $timeout(function() { $('#disciplinas').material_select('destroy'); $('#disciplinas').material_select();}, 150);
                        }
//                        $scope.buscarEventosMes(new Date().getMonth()+1);
                        $scope.buscarHorarios();
                    });
                });
            });
        };

        /* BUSCA EVENTOS DO MES */
        $scope.buscarEventosMes = function (mes, turmaId, indice) {
            $scope.eventos = [];
            var promise = Servidor.buscarUm('turmas', $scope.disciplina.turma.id);
            promise.then(function(response) {
                $scope.disciplina.turma = response.data;
                var promise = Servidor.buscar('calendarios/'+$scope.disciplina.turma.calendario.id+'/meses/'+parseInt(new Date().getMonth()+1));
                promise.then(function(response) {
                    var dias = response.data;
                    if (dias.length) {
                        for (var i = 0; i < dias.length; i++) {
                            if (dias[i].eventos.length) {
                                for (var j = 0; j < dias[i].eventos.length; j++) {
                                    dias[i].eventos[j].data = dias[i].data;
                                    $scope.eventos.push(dias[i].eventos[j]);
                                }
                            }
                        }
                    } else if ($scope.disciplinas.length > 1) {
                        return $scope.buscarEventosMes(mes, $scope.disciplinas[1].turma.id);
                    }
                    $timeout(function () {
                        $('.collapsible').collapsible({accordion: false}); $scope.fechaCortina();
                    }, 500);
                }, function(error) {
                    if ($scope.disciplinas.length > 1) {
                        return $scope.buscarEventosMes(mes, $scope.disciplinas[1].turma.id);
                    } else {
                        $scope.fechaCortina();
                    }
                });
            });
//            (indice) ? indice : indice = 0;
//            (turmaId) ? turmaId : turmaId = $scope.disciplinas[indice].turma.id;
//            var promise = Servidor.buscarUm('turmas', turmaId);
//            promise.then(function (response) {
//                promise = Servidor.buscar('calendarios/' + response.data.calendario.id + '/meses/' + mes);
//                promise.then(function (response) {
//                    var dias = response.data;
//                    if (dias.length) {
//                        dias.forEach(function(d) {
//                            d.eventos.forEach(function(e) {
//                                e.data = d.data;
//                                $scope.eventos.push(e);
//                            });
//                        });
//                    } else if ($scope.disciplinas.length > indice) {
//                        return $scope.buscarEventosMes(mes, $scope.disciplinas[++indice].turma.id, indice);
//                    }
//                    $timeout(function () {
//                        $('.collapsible').collapsible({accordion: false});
//                        $scope.fechaCortina();
//                    }, 500);
//                }, function(error) {
//                    if ($scope.disciplinas.length > 1) {
//                        return $scope.buscarEventosMes(mes, $scope.disciplinas[++indice].turma.id, indice);
//                    } else {
//                        $scope.fechaCortina();
//                    }
//                });
//            });
        };

        /* Busca as habilidades de uma avaliação */
        $scope.buscarHabilidades = function (avaliacao) {
            var promise = Servidor.buscarUm('avaliacoes-qualitativas', avaliacao.id);
            promise.then(function (response) {
                for (var i = 0; i < $scope.avaliacoesQualitativas.length; i++) {
                    if ($scope.avaliacoesQualitativas[i].id === avaliacao.id) {
                        $scope.avaliacoesQualitativas[i].habilidades = response.data.habilidades;
                    }
                }
            });
        };

        /* Abre o input para fechar média manualmente */
        $scope.mostraFechamentoMedia = function(bool) {
            $scope.fechandoMedia = bool;
            $scope.media.valorNovo = $scope.media.valor;
            $timeout(function() { Servidor.verificaLabels(); }, 50);
        };

        /* Executa o fechamento de uma determinada média */
        $scope.fecharMedia = function(manual) {
            if (!$scope.quantitativo && !manual) {
                $scope.media.notas.forEach(function(nota) {
                    if (nota.avaliacao.fechamentoMedia) { $scope.mostraCortina(); }
                });
            } else { $scope.mostraCortina(); }
            if ($scope.cortina) {
                var notas = angular.copy($scope.media.notas);
                delete $scope.media.notas;
                if (manual) {
                    $scope.media.calculoAutomatico = false; $scope.media.valor = $scope.media.valorNovo;
                } else {
                    $scope.media.calculoAutomatico = true;
                    delete $scope.media.valor;
                    delete $scope.media.valorNovo;
                }
                var media = $scope.media;
                media.disciplinaCursada = { id: media.disciplinaCursada.id };
                var promise = Servidor.finalizar(media, 'medias/'+$scope.media.id, 'Média');
                promise.then(function(response) {
                    $scope.fechandoMedia = false;
                    $scope.media.valor = (response.data.valor) ? $scope.formatarValorNota(response.data.valor) : "ND";
                    $scope.media.notas = notas;
                    $scope.alunos.forEach(function(aluno, i) {
                        if (parseInt(aluno.id) === parseInt($scope.media.aluno.id)) {
                            aluno.medias.forEach(function(media, j) {
                                if (parseInt(media.id) === parseInt($scope.media.id)) {
                                    $scope.alunos[i].medias[j].valor = $scope.formatarValorNota(response.data.valor);
                                }
                            });
                        }
                    });
                    $timeout(function() { console.log($scope.media.notas); $('.collapsible').collapsible({accordion: false}); $('select.conceito').material_select(); $scope.fechaCortina(); }, 500);
                });
            } else {
                Materialize.toast('Não existe nenhuma avaliação de fechamento de média automática.', 3500);
            }
        };

        /* Executa o fechamento automático das médias dos alunos de uma turma */
        $scope.fecharMediaGeral = function(m) {
            $scope.mostraCortina();
            var mediasValidas = $scope.alunos.length;
            $scope.alunos.forEach(function(aluno, i) {
                aluno.medias.forEach(function(media, j) {
                    if (media.numero === m) {
                        var promise = Servidor.buscarUm('medias', media.id);
                        promise.then(function(response) {
                            media = response.data;
                            if (media.notas.length) {
                                if (!$scope.quantitativo) {
                                    var prosseguir;
                                    media.notas.forEach(function(nota) { if (nota.avaliacao.fechamentoMedia) { prosseguir = true; } });
                                } else { prosseguir = true; }
                                if (prosseguir) {
                                    delete media.notas;
                                    delete media.valor;
                                    var mediaPromise = media;
                                    mediaPromise.disciplinaCursada = {id: media.disciplinaCursada.id};
                                    promise = Servidor.finalizar(mediaPromise, 'medias/'+$scope.media.id, '');
                                    promise.then(function(response) {
                                        $scope.alunos[i].medias[j].valor = $scope.formatarValorNota(response.data.valor);
                                        $scope.fechaCortina();
                                    });
                                } else {
                                    Servidor.customToast(media.nome + ' de ' + aluno.matricula.aluno.nome.split(' ')[0] + ' não foi fechada por não existir uma avaliação de fechamento de média');
                                    $scope.fechaCortina();
                                }
                            } else {
                                mediasValidas--;
                                if (!mediasValidas) {
                                    Servidor.customToast(media.nome + ' não possui nenhuma nota.');
                                    $scope.fechaCortina();
                                }
                            }
                        });
                    }
                });
            });
        };

        /* Put de notas qualitativas */
        $scope.alterarNotaQualitativa = function(nota) {
            if (nota) {
                $scope.mostraCortina();
                var promise = Servidor.buscarUm('notas-qualitativas', nota.id);
                promise.then(function(response) {
                    response.data.habilidadesAvaliadas = nota.habilidadesAvaliadas;
                    var promiseD = Servidor.buscarUm('disciplinas-ofertadas', response.data.avaliacao.disciplina.id);
                    promiseD.then(function(responseD){
                        response.data.avaliacao.disciplina = responseD.data;
                        delete response.data.media.disciplinaCursada.disciplinaOfertada.professores;
                        var promise = Servidor.finalizar(response.data, 'notas-qualitativas', 'Nota');
                        promise.then(function() { $timeout(function() { $scope.fechaCortina(); }, 500); });
                    });
                });
            }
        };

        /* Put de notas quantitativas */
        $scope.alterarNotaQuantitativa = function(nota) {
            if (nota.valor && (nota.valor.length !== 2 || parseInt(nota.valor) === 10)) {
                if (nota.valor.length > 2) {
                    var inteiro = parseInt(nota.valor.split('.')[0]);
                    var decimal = parseInt(nota.valor.split('.')[1]);
                    var valorNota = parseFloat(inteiro + '.' + decimal);
                } else { valorNota = parseInt(nota.valor); }
                if (valorNota >= 0 && valorNota <= 10) {
                    var promise = Servidor.buscarUm('notas-quantitativas', nota.id);
                    promise.then(function(response) {
                        var promiseA = Servidor.buscarUm('disciplinas-ofertadas', response.data.avaliacao.disciplina.id);
                        promiseA.then(function(responseA){
                            response.data.avaliacao.disciplina = responseA.data;
                            response.data.valor = valorNota;
                            delete response.data.media.disciplinaCursada.disciplinaOfertada.professores;
                            var promiseN = Servidor.finalizar(response.data, 'notas-quantitativas', 'Nota');
                            promiseN.then(function(responseN){
                            });
                        });
                    });
                }
            }
        };

        /* Busca os conceitos de avaliação qualitativa*/
        $scope.buscarConceitos = function() {
            var promise = Servidor.buscar('avaliacoes-qualitativas/conceitos');
            promise.then(function(response) {
                $scope.conceitos = response.data;
                $timeout(function() { $('.conceito').material_select('destroy'); $('.conceito').material_select(); $scope.fechaCortina(); }, 50);
            });
        };

        /* BUSCA HORARIOS DO DIA */
        $scope.buscarHorarios = function () {
            Servidor.verificaLabels();
            $scope.horarios = [];
            $scope.disciplinas.forEach(function(d){
                var promise = Servidor.buscarUm('turmas',d.turma.id);
                promise.then(function(response) {
                    var turma = response.data;
                    var promise = Servidor.buscar('calendarios/' + turma.calendario.id + '/dias', {'data': new Date().toJSON().split('T')[0]});
                    promise.then(function (response) {
                        if(response.data.length) {
                            var dia = response.data[0];
                            var promise = Servidor.buscar('turmas/' + d.turma.id + '/aulas', {'dia': dia.id, 'disciplina': d.id});
                            promise.then(function (response) {
                                if(response.data.length){
                                    response.data[0].horario.inicio = dateTime.formatarHorario(response.data[0].horario.inicio);
                                    response.data[0].horario.termino = dateTime.formatarHorario(response.data[0].horario.termino);
                                    $scope.horarios.push(response.data[0]);
                                    $scope.horarios[$scope.horarios.length-1].turma = turma;
                                    $timeout(function () { $('.collapsible').collapsible({accordion: false}); }, 500);
                                }
                            });
                        }
                    });
                });
            });
        };

        /* Volta para a pagina de lista de avaliacoes */
        $scope.voltarAvaliacoes = function() { $scope.alunos = []; };

        /* Prepara o formulário de dar notas */
        $scope.carregarAvaliacao = function() {
            $('#btn-avaliar').removeClass('disabled');
            if($scope.sistemaAvaliacao.tipo === 'QUANTITATIVO') {
                for(var i = 0; i < $scope.alunos.length; i++) { $scope.alunos[i].nota = ''; }
                $scope.quantitativa = true;
                var endereco = 'notas-quantitativas';
                $timeout(function(){ $('.nota').mask('99.99', {reverse:true}); $scope.fechaCortina(); }, 1000);
            } else {
                for(var i = 0; i < $scope.alunos.length; i++) {
                    $scope.alunos[i].habilidadesAvaliadas = [];
                    for (var j = 0; j < $scope.avaliacao.habilidades.length; j++) {
                        $scope.alunos[i].habilidadesAvaliadas.push({
                            'habilidade': {'id': $scope.avaliacao.habilidades[j].id, 'nome': $scope.avaliacao.habilidades[j].nome},
                            'conceito': {'id': null}
                        });
                    }
                }
                endereco = 'notas-qualitativas';
                $timeout(function() { $scope.fechaCortina(); $scope.buscarConceitos(); }, 1000);
                $scope.quantitativa = false;
            }
            var promise = Servidor.buscar(endereco, {'avaliacao': $scope.avaliacao.id});
            promise.then(function (response) {
                var notas = response.data;
                var notasAbertas = $scope.alunos.length;
                $scope.alunos.forEach(function (aluno) {
                    notas.forEach(function (nota, i) {
                        if (aluno.disciplinaCursada.id === nota.media.disciplinaCursada.id) {
                            if ($scope.quantitativo) {
                                $('#aluno' + aluno.id).find('input').prop('disabled', true);
                                notasAbertas--;
                                aluno.nota = nota.valor;
                                notas.splice(i, 1);
                            } else {
                                nota.habilidadesAvaliadas.forEach(function(avaliadas) {
                                    aluno.habilidadesAvaliadas.forEach(function(alunoAvaliadas) {
                                        if (avaliadas.habilidade.id === alunoAvaliadas.habilidade.id) {
                                            alunoAvaliadas.conceito = avaliadas.conceito;
                                            $('#hab' + avaliadas.habilidade.id + aluno.id).prop('disabled', true);
                                        }
                                    });
                                });
                                notas.splice(i, 1);
                            }
                        }
                    });
                    if(!notasAbertas) { $('#btn-avaliar').addClass('disabled'); }
                });
                $timeout(function() { $('select').material_select(); }, 500);
            });
        };

        /* Carrega o objeto de disciplina cursada para dentro do objeto aluno */
        $scope.alocarDisciplinaCursadaAluno = function(avaliacao) {
            $scope.alunos.forEach(function(aluno, i) {
                $scope.disciplinasCursadas.forEach(function(cursada, j) {
                    if(aluno.matricula.id === cursada.matricula.id){
                        aluno.disciplinaCursada = {id: cursada.id};
                        $scope.disciplinasCursadas.splice(j, 1);
                    }
                });
                if (aluno.disciplinaCursada === undefined) {
                    $scope.alunos.splice(i, 1);
                }
            });
            if (avaliacao) {
                $scope.carregarAvaliacao();
            } else {
                $scope.carregarDiarioNota();
            }
        };

        /* Busca as médias de uma turma */
        $scope.carregarDiarioNota = function() {
            $scope.medias = [];
            var promise = Servidor.buscarUm('turmas', $scope.disciplina.turma.id);
            promise.then(function(response) {
                if (response.data.etapa.sistemaAvaliacao.tipo === 'QUANTITATIVO'){ $scope.quantitativo = true; } else { $scope.quantitativo = false; }
                $scope.requisicoes = $scope.alunos.length;
                $scope.alunos.forEach(function(aluno, i) {
                    aluno.medias = [];
                    if (aluno.disciplinaCursada === undefined) {
                        $scope.alunos.splice(i, 1);
                        if (--$scope.requisicoes === 0) {
                            $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: true, gutter: 0, belowOrigin: true, alignment: 'left' });
                            $timeout(function () { $scope.fechaCortina(); }, 500);
                        }
                    } else {
                        var promise = Servidor.buscar('medias', {'disciplinaCursada': aluno.disciplinaCursada.id});
                        promise.then(function(response) {
                            if (!$scope.medias.length) { $scope.medias = response.data; }
                            response.data.forEach(function(media) {
                                if (!parseInt(media.valor)) {
                                    media.valor = 'ND';
                                } else {
                                    media.valor = $scope.formatarValorNota(media.valor);
                                }
                                aluno.medias.push(media);
                                if (--$scope.requisicoes === 0) {
                                    //$('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: true, gutter: 0, belowOrigin: true, alignment: 'left' });
                                    $timeout(function () { $scope.fechaCortina(); }, 500);
                                }
                            });
                        });
                    }
                });
            });
        };

        /* Busca os alunos da turma que esta tendo a avaliacao */
        $scope.buscarAlunos = function (avaliacao){
            $scope.mostraCortina();
            $scope.avaliacao = avaliacao;
            var promise = Servidor.buscar('enturmacoes', {'encerrado': 0, 'turma': $scope.disciplina.turma.id});
            promise.then(function (response) {
                if(response.data.length) {
                    $scope.alunos = response.data;
                    var promise = Servidor.buscar('disciplinas-cursadas', {'turma': $scope.disciplina.turma.id, 'disciplina': $scope.disciplina.disciplina.id, status:'CURSANDO'});
                    promise.then(function(response) {
                        $scope.disciplinasCursadas = response.data;
                        $scope.alocarDisciplinaCursadaAluno(avaliacao);
                        $timeout(function () { $('.collapsible').collapsible({accordion: false}); }, 250);
                    });
                } else {
                    Materialize.toast('Não há alunos na turma ' + response.data.turma.nomeExibicao, 2500);
                }
            });
        };

        /* Post de nota quantitativa */
        $scope.darNotaQuantitativa = function() {
            $scope.alunos.forEach(function(aluno) {
                var inteiro = parseInt(aluno.nota.split('.')[0]);
                var decimal = parseInt(aluno.nota.split('.')[1]);
                var valorNota = parseFloat(inteiro + '.' + decimal);
                if (valorNota >= 0 && valorNota <= 10) {
                    var promise = Servidor.buscar('medias', {'disciplinaCursada': aluno.disciplinaCursada.id});
                    promise.then(function(response) {
                        var medias = response.data;
                        var requisicoes = 0;
                        medias.forEach(function(media) {
                            if (parseInt(media.numero) === parseInt($scope.avaliacao.media)) {
                                requisicoes++;
                                var promise = Servidor.buscar('notas-quantitativas', {'media': media.id, 'avaliacao': $scope.avaliacao.id});
                                promise.then(function(response) {
                                    if (!response.data.length) {
                                        var nota = { 'valor': valorNota, 'avaliacao': $scope.avaliacao, 'media': media };
                                        var promise = Servidor.finalizar(nota, 'notas-quantitativas', 'Nota de ' + aluno.matricula.aluno.nome);
                                        promise.then(function () { $('#aluno' + aluno.id).find('input').prop('disabled', true); });
                                    }
                                    if(--requisicoes === 0) {
                                        $scope.voltarAvaliacoes();
                                    }
                                });
                            }
                        });
                    });
                } else {
                    Materialize.toast('A nota do aluno ' + aluno.matricula.aluno.nome + ' está incorreta.', 2500);
                    return 0;
                }
            });
        };

        $scope.darNotaQualitativaPorAluno = function(enturmacao) {
            var cont = 0;
            enturmacao.habilidadesAvaliadas.forEach(function(hab) {
               if (hab.conceito.id) { cont++; }
            });
            if (cont === enturmacao.habilidadesAvaliadas.length) {
                var promise = Servidor.buscar('medias', {disciplinaCursada: enturmacao.disciplinaCursada.id});
                promise.then(function(response) {
                    var medias = response.data;
                    medias.forEach(function(media) {
                        if (parseInt(media.numero) === parseInt($scope.avaliacao.media)) {
                            var promise = Servidor.buscar('notas-qualitativas', {'media': media.id, 'avaliacao': $scope.avaliacao.id});
                            promise.then(function(response) {
                                if (!response.data.length) {
                                    $("#aluno" + enturmacao.id).find('select').prop('disabled', true);
                                    var nota = { 'habilidadesAvaliadas': enturmacao.habilidadesAvaliadas, 'avaliacao': {'id': $scope.avaliacao.id}, 'media': {'id': media.id} };
                                    var promise = Servidor.finalizar(nota, 'notas-qualitativas', 'Nota de ' + enturmacao.matricula.aluno.nome);
                                    promise.then(function () { $("#aluno" + enturmacao.id).find('select').prop('disabled', true); return true; });
                                } else {
                                    var habilidades = angular.copy(enturmacao.habilidadesAvaliadas);
                                    nota = response.data[0];
                                    nota.habilidadesAvaliadas.forEach(function(notaHab) {
                                        habilidades.forEach(function(hab, i) {
                                            if (notaHab.habilidade.id === hab.habilidade.id) {
                                                notaHab.conceito = hab.conceito;
                                                habilidades.splice(i, 1);
                                            }
                                        });
                                    });
                                    habilidades.forEach(function(hab) {
                                       nota.habilidadesAvaliadas.push({habilidade: hab.habilidade, conceito: hab.conceito});
                                    });
                                    Servidor.finalizar(nota, 'notas-qualitativas', 'Nota de ' + enturmacao.matricula.aluno.nome);
                                    //$timeout(function(){ $('.collapsible').collapsible({ accordion : false }); }, 500);
                                }
                            });
                        }
                    });
                });
            }
        };

        /* Post de nota qualitativa */
        $scope.darNotaQualitativa = function() {
            $scope.alunos.forEach(function(aluno) {
                for (var j = 0; j < aluno.habilidadesAvaliadas.length; j++) {
                    if (!aluno.habilidadesAvaliadas[j].conceito.id) {
                        Materialize.toast('A nota do(a) aluno(a) ' + aluno.matricula.aluno.nome + ' está incorreta.', 2500);
                        return 0;
                    }
                }
                var promise = Servidor.buscar('medias', {'disciplinaCursada': aluno.disciplinaCursada.id});
                promise.then(function(response) {
                    var medias = response.data;
                    medias.forEach(function(media) {
                        if (parseInt(media.numero) === parseInt($scope.avaliacao.media)) {
                            var promise = Servidor.buscar('notas-qualitativas', {'media': media.id, 'avalaicao': $scope.avaliacao.id});
                            promise.then(function(response) {
                                if (!response.data.length) {
                                    var nota = { 'habilidadesAvaliadas': aluno.habilidadesAvaliadas, 'avaliacao': {'id': $scope.avaliacao.id}, 'media': {'id': media.id} };
                                    var promise = Servidor.finalizar(nota, 'notas-qualitativas', 'Nota de ' + aluno.matricula.aluno.nome);
                                    promise.then(function () { $("#aluno" + aluno.id).find('select').prop('disabled', true); return true; });
                                }
                            });
                        }
                    });
                });
            });
        };

        /* Dá as notas aos alunos */
        $scope.avaliar = function () {
            if (!$('#btn-avaliar').hasClass('disabled')) {
                if($scope.quantitativa) {
                    $scope.darNotaQuantitativa();
                } else {
                    $scope.darNotaQualitativa();
                }
            } else {
                Materialize.toast('Todas as notas desta avaliação já foram atribuídas.', 3500);
            }
        };

        /* Faz a separação das avaliações pelas suas médias */
        $scope.separarAvaliacoesPorMedia = function(avaliacoes) {
            $scope.medias = [];
            var promise = Servidor.buscarUm('turmas', $scope.disciplina.turma.id);
            promise.then(function(response) {
            if (response.data.etapa.sistemaAvaliacao.tipo === 'QUANTITATIVO'){ $scope.quantitativo = true; } else { $scope.quantitativo = false; }
                for (var i = 1; i <= response.data.etapa.sistemaAvaliacao.quantidadeMedias; i++) {
                    var auxAvaliacoes = [];
                    var media = {};
                    for (var j = 0; j < avaliacoes.length; j++ ) {
                        if (avaliacoes[j].media === i) { auxAvaliacoes.push(avaliacoes[j]); }
                    }
                    media = {
                        'nome': 'M'+i,
                        'numero': i,
                        'avaliacoes': auxAvaliacoes
                    };
                    if(media) { $scope.medias.push(media); }
                }
            });
        };

        /* Busca as avaliações de uma determinada disciplina */
        $scope.buscarAvaliacoes = function() {
            var promise = Servidor.buscarUm('etapas', $scope.disciplina.turma.etapa.id);
            promise.then(function(response) {
                $scope.sistemaAvaliacao = response.data.sistemaAvaliacao;
                if($scope.sistemaAvaliacao.tipo === 'QUANTITATIVO') {
                    $scope.buscarAvaliacoesQuantitativas($scope.disciplina);
                } else {
                    $scope.buscarAvaliacoesQualitativas($scope.disciplina);
                }
            });
        };

        $scope.buscarAvaliacoesQuantitativas = function(disciplina) {
            var promise = Servidor.buscar('avaliacoes-quantitativas', {disciplina: disciplina.id});
            promise.then(function (response) {
                $scope.avaliacoesQuantitativas = response.data;
                if (response.data.length) {
                    $scope.separarAvaliacoesPorMedia(response.data);
                    $timeout(function(){ $('.collapsible').collapsible({accordion: false}); }, 500);
                }
            });
        };

        $scope.buscarAvaliacoesQualitativas = function(disciplina) {
            var promise = Servidor.buscar('avaliacoes-quantitativas', {disciplina: $scope.disciplina.id, media: ''});
            promise.then(function (response) {
                $scope.avaliacoesQuantitativas = [];
                var promise = Servidor.buscar('avaliacoes-qualitativas', {disciplina: $scope.disciplina.id, media: ''});
                promise.then(function (response) {
                    $scope.avaliacoesQualitativas = response.data;
                    if(response.data.length) {
                        $scope.separarAvaliacoesPorMedia(response.data);
                        $timeout(function(){ $('.collapsible').collapsible({accordion: false}); }, 500);
                    }
                });
            });
        };

        /* Guarda a disciplina */
        $scope.carregarDisciplina = function (disciplinaId) {
            $scope.mostraCortina();
            var promise = Servidor.buscarUm('disciplinas-ofertadas', disciplinaId);
            promise.then(function (response) {
                if(sessionStorage.getItem('disciplina') !== disciplinaId) { sessionStorage.setItem('disciplina', disciplinaId); }
                $scope.disciplina = response.data; $scope.disciplina.id = '';
                $scope.disciplina.id = sessionStorage.getItem('disciplina');
                $timeout(function(){ $('#disciplinas').material_select(); },150);
                if ($scope.mostraAvaliacoes){ $scope.verificaOpcoes('avaliacoes'); }
                if ($scope.mostraPerfil) { $scope.verificaOpcoes('perfil'); }
                if ($scope.mostraCalendario) { $scope.montaCalendario(); }
                if ($scope.mostraFrequencia) { $scope.verificaOpcoes('frequencia'); }
                if ($scope.mostraDiarioNota) { $scope.verificaOpcoes('diario-nota'); }
                if ($scope.mostraObservacoes) { $scope.verificaOpcoes('observacoes'); }
                $('.tooltipped').tooltip('remove');
                $('.tooltipped').tooltip();
                $scope.fechaCortina();
            });
        };

        /* Guarda o evento */
        $scope.carregarEvento = function (evento, dia) {
            if (evento) { $scope.evento = evento; }
            $scope.dia = dia;
            $timeout(function() {
                $('#info-evento-modal').openModal();
                if(evento) { $('#event'+evento.id).addClass('active'); }
                $('.collapsible-event').collapsible({ accordion : false });
            }, 250);
        };

        /* Busca as alocacoes de um vinculo */
        $scope.buscarAlocacoes = function (vinculoId) {
            var promise = Servidor.buscar('alocacoes', {'vinculo': vinculoId});
            promise.then(function (response) {
                $scope.alocacoes = response.data;
                var alocacao = sessionStorage.getItem('alocacao');
                if (alocacao === undefined || !alocacao && $scope.alocacoes.length === 1) { $scope.carregarAlocacao(response.data[0].id); }
                $timeout(function () { $('#alocacoes').material_select(); }, 100);
            });
        };

        $scope.verificaSelectVinculo = function (id) {
            var vinculo = $scope.vinculo.id;
            if (parseInt(vinculo) === parseInt(id)) {
                return true;
            } else {
                return false;
            }
        };

        $scope.verificaSelectAlocacao = function (id) {
            var alocacao = $scope.alocacao.id;
            if (parseInt(alocacao) === parseInt(id)) {
                return true;
            } else {
                return false;
            }
        };

        $scope.verificaSelectDisciplina = function (id) {
            var disciplina = $scope.disciplina.id;
            if (parseInt(disciplina) === parseInt(id)) {
                return true;
            } else {
                return false;
            }
        };

        $scope.verificaEstadoCivil = function (id) {
            if ($scope.pessoa.estadoCivil) {
                if (id === $scope.pessoa.estadoCivil.id) {
                    return true;
                }
            }
        };

        $scope.verificaSelectCidade = function (id) {
            if ($scope.endereco !== undefined && $scope.endereco.cidade !== undefined) {
                var cidade = $scope.pessoa.endereco.cidade.id;
                if (parseInt(cidade) === parseInt(id)) {
                    return true;
                } else {
                    return false;
                }
            }
        };

        $scope.verificaSelectEstado = function (id) {
            if ($scope.endereco !== undefined && $scope.endereco.cidade !== undefined) {
                var estado = $scope.pessoa.endereco.cidade.estado.id;
                if (parseInt(estado) === parseInt(id)) {
                    return true;
                } else {
                    return false;
                }
            }
        };

        //LIMPA VARIAVEIS
        $scope.reiniciar = function () {
            $scope.aulas = []; $scope.alunos = []; $scope.frequenciasTurma = []; $scope.habilidades = [];
            $scope.presencas = false; $scope.mostraCalendario = false; $scope.mostraPerfil = false; $scope.mostraFrequencia = false;
            $scope.mostraAvaliacoes = false; $scope.mostraDiarioNota = false; $scope.mostraObservacoes = false; $scope.fazerChamada = false;
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

        //MENU DE OPÇÕES INICIAL
        $scope.verificaOpcoes = function (opcao) {
            $scope.reiniciar(); $scope.mostraCortina();
            $('.tooltipped').tooltip('remove');
            switch (opcao) {
                case 'perfil':
                    $scope.buscaRaca();
                    $scope.buscarParticularidades();
                    $scope.recebePessoa();
                    $scope.buscarEstadoCivil();
                    $scope.mostraPerfil = true;
                    break
                case 'calendario':
                    $scope.mostraCalendario = true;
                    if (!$scope.semanas) { $scope.montaCalendario(); };
                    break
                case 'frequencia':
                    $scope.buscarEnturmacoes();
//                    $('.data').mask('00/00/0000');
                    callDatepicker();
                    var data = new Date();
                    var dia = data.getDate();
                    var mes = data.getMonth();
                    mes++;
                    var ano = data.getFullYear();
                    if(dia < 10){ dia = '0' + dia; }
                    if(mes < 10){ mes = '0' + mes; }
                    $scope.chamada.dia = dia + '/' + mes + '/' + ano;
                    $timeout(function () {
                        Servidor.verificaLabels();
                        $scope.mostraFrequencia = true;
                    }, 100);
                    break
                case 'avaliacoes':
                    $scope.alunos = [];
                    $scope.buscarAvaliacoes();
                    $scope.mostraAvaliacoes = true;
                    break
                case 'diario-nota':
                    $scope.mostraDiarioNota = true;
                    $scope.buscarAlunos();
                    $scope.buscarConceitos();
                    $timeout(function() {
                        $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: true, gutter: 0, belowOrigin: true, alignment: 'left' });
                    }, 1000);
                    break
                case 'observacoes':
                    $scope.buscaObservacao = {data: '', inicio: '', termino: ''};
                    var promise = Servidor.buscar('turmas/'+$scope.disciplina.turma.id+'/aulas', {disciplina: $scope.disciplina.id});
                    promise.then(function(response) {
                        $scope.aulas = response.data;
                        $scope.aulasObservacao = [];
                        $scope.observacaoPeriodo = false;
                        $scope.mostraObservacoes = true;
//                        $('.data').mask('00/00/0000');
                        callDatepicker();
                    });
                    break
            }
            $('.tooltipped').tooltip('remove');
            $timeout(function () {
                Servidor.verificaLabels(); window.scrollTo(0, 500);
                $('.collapsible').collapsible({accordion: false});
                $('.tooltipped').tooltip({delay: 50});
                $timeout(function() { $scope.fechaCortina(); }, 500);
            }, 150);
        };

        $scope.buscarAulas = function(data) {
            $('.picker__year').css('margin-left', '7rem');
            if(data.length === 10) {
                data = data.split('/').join('');
                data = data.slice(4,8) + '-' + data.slice(2,4) + '-' + data.slice(0,2);
                var promise = Servidor.getDate();
                promise.then(function(response) {
                    var dataAtual = response.data;
                    if (dateTime.validarData(data) && dateTime.dateLessOrEqual(data, dataAtual)) {
                        var term = new Date().toJSON().split('T')[0];
                        if (dateTime.dateLessOrEqual(data, term)) {
                            $scope.aulasObservacao = [];
                            $scope.aulas.forEach(function(aula) {
                                if (aula.dia.data === data) {
                                    $scope.aulasObservacao.push(aula);
                                    $scope.buscarObservacoes(aula);
                                }
                            });
                            if (!$scope.aulasObservacao.length) {
                                Servidor.customToast('Não há nenhuma aula neste dia.');
                            }
                        }
                    } else {
                        Servidor.customToast('Data inválida.');
                    }
                });
            }
        };

        $scope.buscarPeriodoAulas = function(inicio, termino) {
            if (inicio === undefined || termino === undefined) { return; }
            if (inicio.length === 10 && termino.length === 10) {
                inicio = inicio.split('/').join('');
                inicio = inicio.slice(0,2) + '/' + inicio.slice(2,4) + '/' + inicio.slice(4,8);
                termino = termino.split('/').join('');
                termino = termino.slice(0,2) + '/' + termino.slice(2,4) + '/' + termino.slice(4,8);
                if (dateTime.validarData(inicio)) {
                    var data = new Date().toJSON().split('T')[0];
                    var dataTerm = termino.split('/')[2] + '-' + termino.split('/')[1] + '-' + termino.split('/')[0];
                    if (dateTime.validarData(termino) && dateTime.dateLessOrEqual(dataTerm, data)) {
                        inicio = dateTime.converterDataServidor(inicio);
                        termino = dateTime.converterDataServidor(termino);
                        $scope.aulasObservacao = [];
                        $scope.aulas.forEach(function(aula) {
                            if (dateTime.dateBetween(aula.dia.data, inicio, termino)) {
                                $scope.aulasObservacao.push(aula);
                                $scope.buscarObservacoes(aula);
                            }
                        });
                        if (!$scope.aulasObservacao.length) {
                            Servidor.customToast('Não há nenhuma aula neste período.');
                        }
                    } else {
                        Servidor.customToast('Data de término inválida.');
                    }
                } else {
                    Servidor.customToast('Data de início inválida.');
                }
            }
        };

        $scope.adicionarObservacao = function(aula) {
            if (aula.observacoes === undefined) { aula.observacoes = []; }
            if (aula.observacao !== undefined && aula.observacao) {
                $scope.salvarObservacao({
                    aula: {id: aula.id},
                    observacao: aula.observacao
                });
            } else {
                Servidor.customToast("Observação inválida.");
            }
        };

        $scope.removerObservacao = function(aula, observacao) {
            Servidor.remover(observacao, 'Observação');
            aula.observacoes.forEach(function(ob, i) {
                if (ob.id === observacao.id) { aula.observacoes.splice(i, 1); }
            });
        };

        $scope.buscarObservacoes = function(aula) {
            var promise = Servidor.buscar('aula-observacoes', {aula: aula.id});
            promise.then(function(response) {
                $('.tooltipped').tooltip('remove');
                if (response.data.length) {
                    var observacoes = response.data;
                    $scope.aulasObservacao.forEach(function(aulaO) {
                        if (aulaO.id === aula.id) {
                            aulaO.observacoes = observacoes;
                        }
                    });
                    $timeout(function() { $('.tooltipped').tooltip(); }, 250);
                }
            });
        };

        $scope.salvarObservacao = function(observacao) {
            var promise = Servidor.finalizar(observacao, 'aula-observacoes', 'Observação');
            promise.then(function(response) {
                var observacao = response.data;
                $scope.aulasObservacao.forEach(function(aula) {
                    if (aula.id === observacao.aula.id) {
                        aula.observacoes.push(observacao);
                        aula.observacao = '';
                    }
                });
            });
        };

        $scope.salvarObservacoes = function() {
            $scope.aulasObservacao.forEach(function(aula, i) {
                aula.route = 'aulas';
                Servidor.finalizar(aula, 'aulas', '').then(function() {
                    if (i === $scope.aulasObservacao.length - 1) {
                        Servidor.customToast('Observações salvas com sucesso.');
                    }
                });
            });
        };

        /* Salva os dados que foram alterados */
        $scope.atualizarPessoa = function () {
            Servidor.finalizar($scope.pessoa, 'pessoas', 'Modificações');
        };

        /* Salva os dados que foram alterados */
        $scope.atualizarEndereco = function () {
            var endereco = angular.copy($scope.pessoa.endereco);
            endereco.route = 'enderecos';
            var promise = Servidor.finalizar(endereco, 'enderecos', 'Endereço');
            promise.then(function (response) {
                $scope.pessoa.endereco = response.id;
            });
        };

        /*Busca enturmacoes da turma*/
        $scope.buscarEnturmacoes = function (opcao) {
            $scope.enturmacoes = [];
            var promise = Servidor.buscarUm('turmas', $scope.disciplina.turma.id);
            promise.then(function (response) {
                $scope.turma = response.data;
                var promise = Servidor.buscar('enturmacoes', {'turma': $scope.turma.id, 'encerrado': 0});
                $('.tooltipped').tooltip('remove');
                promise.then(function (response) {
                    $scope.enturmacoes = response.data;
                    $timeout(function(){ $('.tooltipped').tooltip({delay: 50});}, 100);
                    //$scope.verificaDataEnturmacoes();
                    $scope.enturmacoesDisciplinas = response.data;
                    $scope.marcarFrequencia();
                });
            });
        };

        /*Prepara realizar chamada*/
        $scope.realizarChamada = function (opcao) {
             $scope.aulas = [];
             $scope.presencas = false;
            if(opcao === 'voltar'){
                $scope.mostraFrequencia = true;
                $scope.fazerChamada = false;
            }else{
                $scope.mostraCortina();
                $scope.fazerChamada = true;
                $scope.mostraFrequencia = false;
                $scope.opcaoFrequencia = 'chamada';
                $scope.escolheDiaChamada();
            }
        };

        /*Prepara vizualização de frequencias do aluno*/
        $scope.frequenciaAluno = function (enturmacao) {
            $scope.matricula = enturmacao.matricula;
            $scope.opcaoFrequencia = 'aluno';
            $scope.aulas = [];
            $scope.mostraCampoJustificativa = false;
            $scope.escolheDiaChamada();
            $('#frequencia-aluno').openModal();
        };

        /*Busca id do dia e as aulas*/
        $scope.escolheDiaChamada = function () {
            $scope.aulas = [];
            $scope.frequenciasTurma = [];
            $scope.presencas = false;
            $scope.imprimirChamada = false;
            if ($scope.chamada.dia.length === 10) {
                $scope.mostraCortina();
                var data = $scope.chamada.dia.split('/');
                data = data[0] + data[1] + data[2];
                data = data.slice(4, 8) + '-' + data.slice(2, 4) + '-' + data.slice(0, 2);
                var dataAtual = new Date().toJSON().split('T')[0];
                if (dateTime.dateLessOrEqual(data, dataAtual)) {
                    var promise = Servidor.buscar('calendarios/' + $scope.turma.calendario.id + '/dias', {'data': data});
                    promise.then(function (response) {
                        if (response.data.length) {
                            $scope.idDia = response.data[0].id;
                            var promiseA = Servidor.buscar('turmas/' + $scope.turma.id + '/aulas', {'dia': $scope.idDia, 'disciplina': $scope.disciplina.id});
                            promiseA.then(function (responseA) {
                                $scope.aulas = responseA.data;
                                if ($scope.aulas.length) {
                                    if($scope.opcaoFrequencia === 'chamada'){
                                        $scope.imprimirChamada = true;
                                        $scope.buscarEnturmacoes('chamada');
                                        //$scope.verificaDataEnturmacoes();
                                        $scope.horarioAula = true;
                                    }else{
                                        $scope.fechaCortina();
                                        $scope.buscarFrequenciasAluno();
                                    }
                                }else{
                                    $scope.fechaCortina();
                                }
                            });
                        } else {
                            Servidor.customToast('Não há frequências neste dia.');
                            $scope.fechaCortina();
                        }
                    });
                } else {
                    Servidor.customToast('Data inválida.');
                    $scope.fechaCortina();
                }
            }
        };

        /*Busca frequencias do aluno na disciplina,por data*/
        $scope.buscarFrequenciasAluno = function () {
            var matricula = $scope.matricula.id;
            $scope.frequenciasAluno = [];
            $scope.aulas.forEach(function(a){
                var promise = Servidor.buscar('frequencias',{'matricula': matricula, 'aula': a.id});
                promise.then(function(response){
                    if(response.data.length) {
                        response.data.forEach(function(f) {
                            $scope.frequenciasAluno.push(f);
                            if(f.status === 'PRESENCA') {
                                $('#' + f.status + a.id + matricula)[0].checked = true;
                            } else if (f.status === 'FALTA_JUSTIFICADA') {
                                a.justificativa = f.justificativa;
                            }
                        });
                    } else {
                        Servidor.customToast('Nenhuma frequência registrada');
                    }
                });
            });
        };

        /*Fecha Modal aberto*/
        $scope.fecharModal = function () {
            $('.lean-overlay').hide();
            $('.modal').closeModal();
        };

        /*Altera o status da frequencia do aulo em cada aula*/
        $scope.setaFrequenciaAluno = function (aula, matricula) {
            if($scope.aulas.length > 1){ var matricula = $scope.matricula.id; }
            var promise = Servidor.buscar('matriculas/' + matricula + '/disciplinas-cursadas', {'disciplina': $scope.disciplina.disciplina.id});
            promise.then(function (response) {
                $scope.frequenciasAluno.forEach(function (f, $index) {
                    if (response.data[0].id === f.disciplinaCursada.id && f.aula.id === aula) {
                        if ($('#PRESENCA' + aula + matricula)[0].checked) {
                            f.status = 'PRESENCA';
                            f.justificativa = '';
                        } else {
                            f.status = 'FALTA';
                        }
                        Servidor.finalizar($scope.frequenciasAluno[$index], 'frequencias', 'Frequência');
                    }
                });
            });
        };

        $scope.verificaJustificarFalta = function(aula, matricula) {
            var presenca = $('#PRESENCA' + aula.id + matricula.id)[0];
            if (presenca.checked) {
                $scope.setaFrequenciaAluno(aula.id, matricula.id);
                aula.justificativa = '';
            } else {
                aula.matricula = matricula;
                aula.novaJustificativa = '';
                $scope.aula = aula;
                $scope.mostraCampoJustificativa = true;
            }
        };

        $scope.fechaCampoJustificativa = function(falta) {
            if (falta) {
                var status = 'FALTA';
                var justificativa = $scope.aula.novaJustificativa;
                if (justificativa !== undefined && justificativa) {
                    status = 'FALTA_JUSTIFICADA';
                } else {
                    var justificativa = '';
                }
                $scope.frequenciasAluno.forEach(function(freq) {
                    if (freq.aula.id === $scope.aula.id) {
                        freq.status = status;
                        freq.justificativa = justificativa;
                        var promise = Servidor.finalizar(freq, 'frequencias', 'Frequência');
                        promise.then(function(response) {
                            $scope.aulas.forEach(function(a) {
                                if (a.id === freq.aula.id) {
                                    a.justificativa = freq.justificativa;
                                }
                            });
                        });
                    }
                });
            } else {
                $('#PRESENCA' + $scope.aula.id + $scope.aula.matricula.id)[0].checked = true;
            }
            $scope.mostraCampoJustificativa = false;
            $scope.aula = {};
        };

        /*Verifica se a data de enturmacao é menor que data da chamada*/
        $scope.verificaDataEnturmacoes = function () {
            $scope.mostraCortina();
            $scope.enturmacoesDisciplinas = [];
            var qtdEnturmacoes = 0;
            $timeout(function(){
                qtdEnturmacoes = $scope.enturmacoes.length;
                var arrayDia = $scope.chamada.dia.split('/');
                //var diaChamada = parseInt(arrayDia[0]+arrayDia[1]+arrayDia[2]);
                var cont = 0;
                $scope.enturmacoes.forEach(function(e, $index){
                    var promise = Servidor.buscarUm('enturmacoes', e.id);
                    promise.then(function(response){
                        cont++;
                        var chamada = $scope.chamada.dia.split('/').join('');
                        chamada = chamada.slice(0,2) + '/' + chamada.slice(2,4) + '/' + chamada.slice(4,8);
                        var enturmado = response.data.dataCadastro.split('T')[0];
                        enturmado = enturmado.split('-');
                        enturmado = enturmado[2] + '/' + enturmado[1] + '/' + enturmado[0];
                        console.log(chamada, enturmado);
                        console.log(dateTime.validarDataAgendamento(chamada, enturmado));
                        if(dateTime.validarDataAgendamento(chamada, enturmado)){
                            $scope.enturmacoesDisciplinas.push(response.data);
                        }
                        if(cont === qtdEnturmacoes){
                            $scope.marcarFrequencia();
                        }
                    });
                });
            },300);
        };

        /*Frequencias já registradas e novas frequencias*/
        $scope.marcarFrequencia = function () {
            $scope.frequenciasAlunosTurma = [];
            $scope.enturmacoesDisciplinas.forEach(function (m, $index) {
                $scope.aulas.forEach(function (a,i) {
                    var promise = Servidor.buscar('frequencias', {'matricula': m.matricula.id, 'aula': a.id});
                    promise.then(function (response) {
                        $scope.frequenciasTurma = response.data;
                        if ($scope.frequenciasTurma.length) {
                            $scope.desabilitarBotao = false;
                            $scope.verificaChamada = 'Chamada já realizada';
                            if (response.data[0].status === 'PRESENCA') {
                                $('#' + response.data[0].status + m.matricula.id + a.id)[0].checked = true;
                            }
                            $('#PRESENCA' + m.matricula.id + a.id)[0].disabled = true;
                        } else {
                            $scope.desabilitarBotao = true;
                            $('#PRESENCA' + m.matricula.id + a.id)[0].disable = false;
                            $('#PRESENCA' + m.matricula.id + a.id)[0].checked = true;
                            $scope.justificativaDeFalta = null;
                            $scope.verificaChamada = 'Nova Chamada';
                            var frequencia = {
                                'status': 'PRESENCA',
                                'disciplinaCursada': {'id': null},
                                'aula': {'id': a.id}
                            };
                            var promise = Servidor.buscar('matriculas/' + m.matricula.id + '/disciplinas-cursadas', {'disciplina': $scope.disciplina.disciplina.id});
                            promise.then(function (response) {
                                frequencia.disciplinaCursada.id = response.data[0].id;
                                $scope.frequenciasAlunosTurma.push(frequencia);
                            });
                        }
                        if ($index === $scope.enturmacoesDisciplinas.length-1 && i === $scope.aulas.length-1) {
                            $timeout(function () {
                                $scope.presencas = true;
                                $scope.fechaCortina();
                            }, 500);
                        }
                    });
                });
            });
        };

        /*Altera o status na Chamada*/
        $scope.setaFrequencia = function (matricula, aula) {
            var promise = Servidor.buscar('matriculas/' + matricula + '/disciplinas-cursadas', {'disciplina': $scope.disciplina.disciplina.id});
            promise.then(function (response) {
                $scope.frequenciasAlunosTurma.forEach(function (f) {
                    if (response.data[0].id === f.disciplinaCursada.id && f.aula.id === aula) {
                        if ($('#PRESENCA' + matricula + aula)[0].checked) {
                            f.status = 'PRESENCA';
                        } else {
                            f.status = 'FALTA';
                        }
                    }
                });
            });
        };

        /*Salva Chamada*/
        $scope.finalizarChamada = function () {
            var objetoAdd = {'frequencias': []};
            objetoAdd.frequencias = $scope.frequenciasAlunosTurma;
            var result = Servidor.finalizar(objetoAdd, 'frequencias/*', 'Chamada');
            result.then(function (response) {
                $timeout(function () {
                    $scope.marcarFrequencia();
                }, 350);
                var frequencia = {
                    'status': null,
                    'disciplinaCursada': {'id': null},
                    'aula': {'id': null},
                    'justificativa': null
                };
            });
        };

        /* Colore os dias do calendário */
        $scope.colorirDias = function () {
            var currentData = new Date().toJSON().split('T')[0];
            for (var i = 0; i < $scope.dias.length; i++) {
                var id = $scope.dias[i].data;
                if ($scope.dias[i].efetivo) {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#c8e6c9'});
                } else if ($scope.dias[i].letivo) {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#ffecb3'});
                } else {
                    $('.dia' + id).find('.dia-de-semana').css({'background-color': '#f5f5f5'});
                }
                if ($scope.dias[i].data === currentData) {
                    $('.dia' + id).find('.date').css({'color': '#fafafa', 'background-color': '#039be5'});
                }
            }
        };

        /* Carrega os dados dos dias buscados para os dias do calendário */
        $scope.preencherDias = function () {
            for (var i = 0; i < $scope.dias.length; i++) {
                for (var j = 0; j < $scope.semanas.length; j++) {
                    if ($scope.dias[i].data === $scope.semanas[j].domingo.dia.data) {
                        $scope.semanas[j].domingo.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].segunda.dia.data) {
                        $scope.semanas[j].segunda.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].terca.dia.data) {
                        $scope.semanas[j].terca.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].quarta.dia.data) {
                        $scope.semanas[j].quarta.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].quinta.dia.data) {
                        $scope.semanas[j].quinta.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].sexta.dia.data) {
                        $scope.semanas[j].sexta.dia = $scope.dias[i];
                    }
                    if ($scope.dias[i].data === $scope.semanas[j].sabado.dia.data) {
                        $scope.semanas[j].sabado.dia = $scope.dias[i];
                    }
                }
            }
        };

        /* Formata valor de nota para ser exibida ao usuário */
        $scope.formatarValorNota = function(valor) {
            valor = valor.toString();
            if (valor.length > 4) {
                if (!valor.slice(3,4)) { valor = valor.slice(0,3); } else { valor = valor.slice(0, 4); }
            }
            var inteiro = valor.split('.')[0];
            var decimal = valor.split('.')[1];
            if (!parseInt(decimal)) {
                valor = parseInt(inteiro);
            } else {
                if (decimal.length > 1) {
                    if (!parseInt(decimal.slice(1))) { decimal = decimal.slice(0,1); }
                }
                valor = parseFloat(inteiro + '.' + decimal);
            }
            return valor;
        };

        /* Diario de notas */
        $scope.preparaAluno = function (media, aluno) {
            $scope.fechandoMedia = false;
            if (aluno) { $scope.aluno = aluno; } else { $scope.aluno = {}; }
            $scope.mostraCortina();
            var promise = Servidor.buscarUm('medias', media.id);
            promise.then(function (response) {
                $scope.media = response.data;
                if (response.data.valor ) { $scope.media.valor = $scope.formatarValorNota(response.data.valor); }
                $scope.media.aluno =  { 'id': aluno.id, 'nome': $scope.aluno.matricula.aluno.nome.split(' ')[0] };
                if ($scope.media.valor) { $scope.opcaoMedia = 'ALTERAR'; } else { $scope.opcaoMedia = 'FECHAR'; }
                if ($scope.quantitativo) { var endereco = 'avaliacoes-quantitativas'; } else { endereco = 'avaliacoes-qualitativas'; }
                if ($scope.media.notas.length) {
                    $scope.media.notas.forEach(function (nota, i) {
                        if (nota.valor) { nota.valor = $scope.formatarValorNota(nota.valor); }
                        var promise = Servidor.buscarUm(endereco, nota.avaliacao.id);
                        promise.then(function (response) {
                            nota.avaliacao = response.data;
                            if (!$scope.quantitativo) {
                                promise = Servidor.buscarUm('notas-qualitativas', nota.id);
                                promise.then(function(response) {
                                    nota.habilidadesAvaliadas = response.data.habilidadesAvaliadas;
                                    $('.tooltipped').tooltip('remove');
                                    if (i === $scope.media.notas.length-1) {
                                        $timeout(function() {
                                            $('.collapsible').collapsible({accordion: false});
                                            $('.conceito').material_select('destroy');
                                            $('.conceito').material_select();
                                            $('.tooltipped').tooltip({delay: 50});
                                        }, 500);
                                    }
                                    $scope.notas = $scope.media.notas;
                                });
                            }
                            if (i === $scope.media.notas.length-1) {
                                $timeout(function () {
                                    $('.nota').keypress(function(event) {
                                        var tecla = (window.event) ? event.keyCode : event.which;
                                        if (tecla === 44) {
                                            event.keyCode = event.which = 46;
                                            return true;
                                        } else {
                                            return true;
                                        }
                                    });
                                    $('.nota').mask('99.99', {reverse: true});
                                    $('#info-aluno').openModal();
                                    $scope.fechaCortina();
                                }, 500);
                            }
                        });
                    });
                } else {
                    Servidor.customToast(media.nome + ' não possui nenhuma nota.');
                    $timeout(function () {
                        $('.nota').mask('99.99', {reverse: true});
                        $('#info-aluno').openModal();
                        $scope.fechaCortina();
                    }, 250);
                }
            });
        };

        /* busca os dias de um mês */
        $scope.buscarMes = function (mes) {
            var promise = Servidor.buscarUm('turmas', $scope.disciplina.turma.id);
            promise.then(function (response) {
                $scope.calendario = response.data.calendario;
                promise = Servidor.buscar('calendarios/' + response.data.calendario.id + '/meses/' + mes);
                promise.then(function (response) {
                    $scope.dias = response.data;
                    $scope.preencherDias();
                    $scope.colorirDias();
                    if (!$scope.letivos && !$scope.efetivos) {
                        $scope.contarDiasLetivosEfetivos();
                    }
                }, function(error) {
                    $scope.fechaCortina();
                    Materialize.toast('Calendário da turma ' + $scope.disciplina.turma.nome + '  não existe.', 3000);
                });
            });
        };

        /* Contador dos dias efetivos, letivos e não letivos */
        $scope.contarDiasLetivosEfetivos = function() {
            var letivos = 0;
            var efetivos = 0;
            var naoLetivos = 0;
            for (var i = 1; i < 13; i++) {
                var promise = Servidor.buscar('calendarios/' + $scope.calendario.id + '/meses/' + i);
                promise.then(function(response) {
                    for (var j = 0; j < response.data.length; j++) {
                        if (response.data[j].letivo) {
                            letivos++;
                            if (response.data[j].efetivo) { efetivos++; }
                        } else {
                            naoLetivos++;
                        }
                    }
                    $scope.letivos = letivos;
                    $scope.efetivos = efetivos;
                    $scope.naoLetivos = naoLetivos;
                });
            }
        };

        $scope.abrirModalSobre = function() { $('#sobre-modal').openModal(); };

        $scope.abrirModalRemarcar = function(avaliacao) {
            $scope.avaliacao = avaliacao;
            $scope.avaliacao.novaAulaEntrega = avaliacao.aulaEntrega;
            $scope.mostraCortina();
            if ($scope.aulas.length) {
                $scope.montaCalendario();
                $timeout(function() { $('#modal-remarcar-avaliacao').openModal(); $scope.fechaCortina(); }, 250);
            } else {
                var promise = Servidor.buscar('turmas/'+$scope.disciplina.turma.id+'/aulas', {disciplina:$scope.disciplina.id});
                promise.then(function(response) {
                   $scope.aulas = response.data;
                   $scope.montaCalendario();
                   $timeout(function() { $('#modal-remarcar-avaliacao').openModal(); $scope.fechaCortina(); }, 250);
                });
            }
        };

        $scope.incluirAulas = function() {
            $scope.aulas.forEach(function(aula) {
                $scope.semanas.forEach(function(semana) {
                    switch(aula.dia.data) {
                        case semana.domingo.dia.data: semana.domingo = aula; break
                        case semana.segunda.dia.data: semana.segunda = aula; break
                        case semana.terca.dia.data: semana.terca = aula; break
                        case semana.quarta.dia.data: semana.quarta = aula; break
                        case semana.quinta.dia.data: semana.quinta = aula; break
                        case semana.sexta.dia.data: semana.sexta = aula; break
                        case semana.sabado.dia.data: semana.sabado = aula; break
                    }
                });
            });
        };

        $scope.selecionarAula = function(aula) {
            if (aula.id) {
                $scope.avaliacao.novaAulaEntrega = aula;
            } else {
                Servidor.customToast('Não há aula neste dia.');
            }
        };

        $scope.remarcarAvaliacao = function(avaliacao) {
            avaliacao.aulaEntrega = avaliacao.novaAulaEntrega;
            var promise = Servidor.finalizar(avaliacao, 'avaliacoes', 'Avaliação');
            promise.then(function(response) {
                $scope.buscarAlunos(response.data);
            });
        };

        /* Carrega conteúdo relacionado ao calendário */
        $scope.montaCalendario = function (mes) {
            if (!mes && mes !== 0) { mes = new Date().toJSON().split('T')[0].split('-')[1]; }
            if (parseInt(mes) < 13 && parseInt(mes) > 0) {
                $scope.semanas = [];
                $scope.limparSemana();
                var ano = new Date().toJSON().split('T')[0].split('-')[0];
                var dia = 1;
                var data = new Date(ano, mes - 1, dia).toJSON().split('T')[0];
                var comparaMes = mes;
                while (parseInt(mes) === parseInt(comparaMes)) {
                    var diaSemana = new Date(ano, mes - 1, dia).toDateString().split(' ')[0];
                    if (data==='2016-10-16') { diaSemana = 'Sun'; data = '2016-10-16'; }
                    if (diaSemana === 'Sun') {
                        $scope.semana.domingo.dia.data = data;
                    } else if (diaSemana === 'Mon') {
                        $scope.semana.segunda.dia.data = data;
                    } else if (diaSemana === 'Tue') {
                        $scope.semana.terca.dia.data = data;
                    } else if (diaSemana === 'Wed') {
                        $scope.semana.quarta.dia.data = data;
                    } else if (diaSemana === 'Thu') {
                        $scope.semana.quinta.dia.data = data;
                    } else if (diaSemana === 'Fri') {
                        $scope.semana.sexta.dia.data = data;
                    } else if (diaSemana === 'Sat') {
                        $scope.semana.sabado.dia.data = data;
                        $scope.semanas.push($scope.semana);
                        $scope.limparSemana();
                    }
                    dia++;
                    data = new Date(ano, mes - 1, dia).toJSON().split('T')[0];
                    comparaMes = data.split('-')[1];
                }
                if ($scope.semana.domingo.dia.data) {
                    $scope.semanas.push($scope.semana);
                }
                switch(parseInt(mes)) {
                    case 1:
                        $scope.mes.nome = 'JANEIRO';
                        $scope.mes.num = 1;
                    break
                    case 2:
                        $scope.mes.nome = 'FEVEREIRO';
                        $scope.mes.num = 2;
                    break
                    case 3:
                        $scope.mes.nome = 'MARÇO';
                        $scope.mes.num = 3;
                    break
                    case 4:
                        $scope.mes.nome = 'ABRIL';
                        $scope.mes.num = 4;
                    break
                    case 5:
                        $scope.mes.nome = 'MAIO';
                        $scope.mes.num = 5;
                    break
                    case 6:
                        $scope.mes.nome = 'JUNHO';
                        $scope.mes.num = 6;
                    break
                    case 7:
                        $scope.mes.nome = 'JULHO';
                        $scope.mes.num = 7;
                    break
                    case 8:
                        $scope.mes.nome = 'AGOSTO';
                        $scope.mes.num = 8;
                    break
                    case 9:
                        $scope.mes.nome = 'SETEMBRO';
                        $scope.mes.num = 9;
                    break
                    case 10:
                        $scope.mes.nome = 'OUTUBRO';
                        $scope.mes.num = 10;
                    break
                    case 11:
                        $scope.mes.nome = 'NOVEMBRO';
                        $scope.mes.num = 11;
                    break
                    case 12:
                        $scope.mes.nome = 'DEZEMBRO';
                        $scope.mes.num = 12;
                    break
                }
                if ($scope.mostraCalendario) { $scope.buscarMes(mes); }
                if ($scope.mostraAvaliacoes) { $scope.incluirAulas(mes); }
            } else {
                Materialize.toast('Excedeu limite do calendário.', 2500);
            }
        };

        /* Busca os estados civis */
        $scope.buscarEstadoCivil = function () {
            var promise = Servidor.buscar('estados-civis', null);
            promise.then(function (response) { $scope.estadosCivis = response.data; $timeout(function() {$('select').material_select();}, 50); });
            Servidor.verificaLabels();
        };

        /* Busca as raças */
        $scope.buscaRaca = function () {
            var promise = Servidor.buscar('racas', null);
            promise.then(function (response) { $scope.racas = response.data; $timeout(function() { $('select').material_select(); },10); });
            Servidor.verificaLabels();
        };

        /* Busca as particularidades */
        $scope.buscarParticularidades = function () {
            var promise = Servidor.buscar('particularidades', null);
            promise.then(function (response) { $scope.particularidades = response.data.plain(); });
            Servidor.verificaLabels();
        };

        /* INICIALIZA A PAGINA */
        $scope.inicializar = function () {
            var sessionId = sessionStorage.getItem('sessionId');
            if (sessionId) { $('body').css('opacity',1); }

            $timeout(function () {
                $('.modal-trigger').leanModal();
                Servidor.verificarMenu();
            }, 50);

            if (sessionStorage.getItem('module') === 'main') {
                $scope.vinculos = []; $scope.limparVinculo();
                var strPessoa = sessionStorage.getItem('pessoa');
                var pessoa = JSON.parse(strPessoa);
                $scope.usuario = { nome: pessoa.nome };
                var promise = Servidor.buscar('vinculos', {funcionario: pessoa.id, status: 'ATIVO'});
                promise.then(function (response) {
                    if (response.data.length) {
                        $scope.vinculos = response.data;
                        $timeout(function () { $('#vinculos').material_select(); }, 500);
                        if (sessionStorage.getItem('vinculo')) {
                            $scope.carregarVinculo(sessionStorage.getItem('vinculo'));
                            $scope.buscarAlocacoes(sessionStorage.getItem('vinculo'));
                            if (sessionStorage.getItem('alocacao')) {
                                $scope.carregarAlocacao(sessionStorage.getItem('alocacao'));
                                if (sessionStorage.getItem('disciplina')) {
                                    $scope.carregarDisciplina(sessionStorage.getItem('disciplina'));
                                    $timeout(function(){ Servidor.entradaPagina(); $scope.inicializando = false; }, 500);
                                } else {
                                    $timeout(function () { $('#disciplinas').material_select(); Servidor.entradaPagina(); $scope.inicializando = false; }, 500);
                                }
                            } else {
                                $timeout(function () { $('#alocacoes, #disciplinas').material_select(); Servidor.entradaPagina(); $scope.inicializando = false; }, 500);
                            }
                        } else {
                            if (response.data.length === 1) {
                                $scope.carregarVinculo(response.data[0].id);
                                $scope.buscarAlocacoes(response.data[0].id);
                            } else {
                                $timeout(function () { $('#vinculos, #alocacoes, #disciplinas').material_select(); Servidor.entradaPagina(); $scope.inicializando = false; }, 500);
                            }
                        }
                    } else {
                        Servidor.entradaPagina();
                        $scope.inicializando = false;
                    }
                });
            }
        };

        $scope.mostrarFAB = function () {
            var retorno = false;
            if (!$scope.mostraPerfil && !$scope.mostraCalendario && !$scope.mostraFrequencia && !$scope.mostraAvaliacoes && !$scope.mostraDiarioNota && !$scope.fazerChamada && !$scope.mostraObservacoes && $scope.disciplina.id) { retorno = true; }
            return retorno;
        };

        $scope.verificarFAB = function () {
            var retorno = true;
            if ($scope.disciplina.id) { retorno = false; }
            return retorno;
        };

        $scope.mostrarLabels = function() {
            $('.chip').fadeToggle();
        };

        /*Acessar modulo via URL*/
        /*var url = window.location.href;
        this.urlArray = url.split('/');
        var urlSize = this.urlArray.length;
        if (this.urlArray[urlSize-1] !== "" && this.urlArray[urlSize-1] !== "index.html") {
            this.selecionar(this.urlArray[urlSize-1],'');
        } else {
            this.selecionar(sessionStorage.getItem('module'), sessionStorage.getItem('moduleOptions'));
        }*/

        $scope.verificarPermissoes = function (role){
            return Servidor.verificarPermissoes(role);
        };

        if ($scope.inicializando === false) { $scope.inicializar(); };
        this.selecionar('main','');
        /*Acessar modulo via URL*/
        /*var url = window.location.href;
        this.urlArray = url.split('/');
        var urlSize = this.urlArray.length;
        if (this.urlArray[urlSize-1] !== "" && this.urlArray[urlSize-1] !== "index.html") {
            this.selecionar(this.urlArray[urlSize-1],'');
        } else {
            this.selecionar(sessionStorage.getItem('module'), sessionStorage.getItem('moduleOptions'));
        }*/
    }]);

})();

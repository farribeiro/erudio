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
    var matriculaModule = angular.module('matriculaModule', ['matriculaDirectives', 'servidorModule','erudioConfig']);

    matriculaModule.service('MatriculaService', [function () {
        this.voltaMatricula = false;
        this.aluno = {};
        this.matricula = {};
        this.abrirFormulario = false;
        this.abreForm = function() { this.abrirFormulario = true; };
        this.fechaForm = function() { this.abrirFormulario = false; };
    }]);

    matriculaModule.controller('MatriculaController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'PessoaService', 'MatriculaService', 'TurmaService', '$compile', 'dateTime', 'makePdf', 'ErudioConfig', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, PessoaService, MatriculaService, TurmaService, $compile, dateTime, makePdf, ErudioConfig) {
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('MATRICULA');
        $scope.alunoService = PessoaService;
        $scope.matriculaService = MatriculaService;
        $scope.TurmaService = TurmaService;
        $scope.mostraCadastros = false;
        $scope.mostraCadastro = false;
        $scope.isAdmin = Servidor.verificaAdmin();
        var unidadeEnsino = JSON.parse(sessionStorage.getItem('unidade'));
        if (unidadeEnsino === undefined || unidadeEnsino === null) { Servidor.customToast('Seleciona uma unidade para trabalhar na página inicial.'); $scope.unidadeAlocacao = null; } else { $scope.unidadeAlocacao = unidadeEnsino.id; }
        $scope.requisicoes = 0;
        
        $scope.$watch('requisicoes', function(requisicoes) {
            if(requisicoes) {
                $scope.mostraProgresso(); $scope.mostraLoader();
            } else {
                $scope.fechaProgresso(); $scope.fechaLoader();
            }
        });

        $scope.$watch('matriculaService.voltaMatricula', function (query){
            //$scope.inicializar();
            if (query) {
                if (MatriculaService.aluno.id) { $scope.matricular(MatriculaService.aluno); }
                $scope.matricular();
                MatriculaService.voltaMatricula = false;
            }
        });

        $scope.$watch('matriculaService.voltaMatricula', function (query){
            //$scope.inicializar();
            if (query) {
                if (MatriculaService.aluno.id) { $scope.matricular(MatriculaService.aluno); }
                $scope.matricular();
                MatriculaService.voltaMatricula = false;
            }
        });

        $scope.$watch('TurmaService.abrirFormulario', function(abreForm) {
            if(abreForm) {
                $scope.mostraOpcoes(TurmaService.enturmacao.matricula, 'enturmacoes', true);
            }
        });

        // Atributos Específicos
        $scope.vaga = { 'turma':null, 'enturmacao':null };
        $scope.estados = [];
        $scope.cidades = [];
        $scope.unidade = {'id': null};
        $scope.matricula = {'codigo': null, 'aluno': {'id': null}, 'unidadeEnsino': {'id': null}, 'curso': {'id': null}};
        $scope.enturmacao = {'turma': {id: null}, 'matricula': {'id': null}};
        $scope.etapa = {'id': null};
        $scope.turmaMatricula = {'id': null};
        $scope.pessoaBusca = {
            'nome': null, 'sobrenome': null,
            'cpf': null, 'dataNascimento': null,
            'nomeMae': null, 'codMatricula': null,
            'certidao': null, 'livro': null,
            'folha': null, 'termo': null,
            'certidaoFormatada': null
        };

        $scope.matriculaBusca = {
            'aluno': null,
            'status': null,
            'codigo': null,
            'curso': null,
            'unidade': null,
            'dataNascimento': null
        };

        $scope.disciplinaId = null;
        $scope.editando = true;
        $scope.matriculando = false;
        $scope.mostraAlunos = false;
        $scope.enturmando = false;
        $scope.buscaMatricula = false;
        $scope.mostraEnturmacao = false;
        $scope.mostraMatriculas = false;
        $scope.mostraFrequencia = false;
        $scope.enturmarAluno = false;
        $scope.tab = true;
        $scope.cpfBusca = null;
        $scope.certidaoBusca = 'none';
        $scope.certidaoAntigaBusca = 'none';
        $scope.cadDocumento = 'certidao';
        $scope.progresso = '';
        $scope.loader = '';
        $scope.opcaoDeEnvio = '';
        $scope.acao = '';
        $scope.abrirOpcoes = false;
        $scope.mostraFrequencia = false;
        $scope.documento = "";
        $scope.matriculas = [];
        $scope.turmas = [];
        $scope.mostraMovimentacoes = false;
        $scope.unidades = [];
        $scope.enturmacoes = [];
        $scope.cursos = [];
        $scope.disciplinasOfertadasTurma = [];
        $scope.predicate = 'nome';
        $scope.predicateMatricula = 'id';
        $scope.predicateEnturmacao = 'nome';
        $scope.mostaDisciplinas = false;
        $scope.mostraNotas = false;
        $scope.facilAcesso = false;
        $scope.paginaAtual = 1;
        $scope.quantidadePaginas = 0;

        /*Controle da barra de progresso */
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.mostraLoader(); };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.fechaLoader(); };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };

        $scope.collapsible = function () {
            $timeout(function () { $('.collapsible').collapsible({accordion: false}); }, 50);
        };

        $scope.editandoo = false;

        //getpdf
        $scope.getPDF = function (url){
            $scope.mostraProgresso();
            var promise = Servidor.getPDF(url);
            promise.then(function(){ $scope.fechaProgresso(); });
        };

        // Vai para o módulo de pessoas
        $scope.intraForms = function () {
            PessoaService.aluno = true;
            PessoaService.abreForm();
            MatriculaService.matricula = $scope.matricula;
            $('.tooltipped').tooltip('remove');
        };

        /* contato Matricula Aluno*/
        $scope.matriculaContato = function () {
            var promise = Servidor.buscar('telefones', {pessoa: $scope.matricula.aluno.id});
            promise.then(function (response) {
                $scope.matricula.aluno.telefones = response.data;
            });
        };

        /* Reinicia estrutura de busca*/
        $scope.reiniciarPessoaBusca = function () {
            $scope.adicionaAluno = false;
            $timeout(function () {
                $('select').material_select('destroy');
                $('select').material_select();
            });
            $scope.documento = null;
            $scope.verificaDocumento();
            $scope.pessoaBusca = {
                'nome': null, 'sobrenome': null,
                'cpf': null, 'dataNascimento': null,
                'dataNascimentoFormatada': null, 'nomeMae': null,
                'certidao': null, 'livro': null,
                'folha': null, 'termo': null,
                'certidaoFormatada': null
            };
            $scope.matriculaBusca = {
                'aluno': null, 'status': null, 'dataNascimento': null,
                'codigo': null, 'curso': null, 'unidade': null
            };
        };

        /*Reinicia estrutura de Matricula*/
        $scope.reiniciar = function () {
            $scope.matricula = {
                'codigo': '',
                'aluno': {id: null},
                'unidadeEnsino': {id: parseInt($scope.matricula.unidadeEnsino.id) },
                'curso': {id: null}
            };
        };

        /*Reinicis estrutura de Enturmação*/
        $scope.reiniciarEnturmacao = function () {
            $scope.enturmacao = {
                'turma': {id: null}, 'matricula': {id: null}
            };
        };

        /* Inicializando */
        $scope.inicializar = function (inicializaContador, carregando) {
            $("#matricula").hide();
            $timeout(function () {
                $scope.permissao = Servidor.verificarTipoAcesso('ROLE_MATRICULA');
                $scope.getCursos();
                $scope.buscarUnidades();
                $(".cpfBuscaMask").mask('000.000.000-00');
                $('select').material_select('destroy');
                $('select').material_select();
                $('ul.tabs').tabs();
                if (inicializaContador) {
                    $('.counter').each(function () {
                        $(this).characterCounter();
                    });
                }
                $('#novaMatricula').keydown(function (event) {
                    if ($scope.editando) {
                        var keyCode = (event.keyCode ? event.keyCode : event.which);
                        if (keyCode === 13) {
                            $timeout(function () {
                                if ($scope.habilitaClique) {
                                    $('#buscarMatriculas').trigger('click');
                                }
                                else {
                                    $scope.habilitaClique = true;
                                }
                            }, 300);
                        }
                    }
                });
                $('#nascAlunoMatricula').mask('99/99/9999');
                $('.dropdown').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: true,
                    hover: false,
                    gutter: 45,
                    belowOrigin: true,
                    alignment: 'left'
                });
                $('#dataNascimento').mask('00/00/0000');
                $('.collapsible').collapsible({ accordion: false });
                $scope.calendario();
                $('.tooltipped').tooltip({delay: 50});
                $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: true,
                    hover: false,
                    gutter: 45,
                    belowOrigin: true,
                    alignment: 'left'
                });
                if (carregando) { $timeout(function() { Servidor.entradaPagina(); Servidor.inputNumero(); }, 500); }
            }, 300);
        };

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

        $scope.buscarPessoas = function() {
            if ($scope.nomePessoa && $scope.nomePessoa !== undefined && $scope.nomePessoa.length > 3) {
                var params = { 'nome': null, 'cpf': null };
                if (parseInt($scope.nomePessoa)) {
                    params.cpf = $scope.nomePessoa;
                } else {
                    params.nome = $scope.nomePessoa;
                }
                var promise = Servidor.buscar('pessoas', params);
                promise.then(function(response) {
                    if (response.data.length) {
                        $scope.pessoas = response.data;
                    } else {
                        $scope.pessoas = [];
                    }
                });
            } else {
                $scope.pessoas = [];
            }
        };

        $scope.carregarPessoa = function(pessoa) {
            $scope.matricula.aluno = pessoa;
            $scope.nomePessoa = pessoa.nome;
            if ($scope.unidades.length === 1) {
                $scope.matricula.unidadeEnsino.id = parseInt($scope.unidades[0].id);
            }
            $timeout(function() {
                $('select').material_select();
                $('#unidadeMatriculaFormAutoComplete').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: true,
                    hover: false,
                    gutter: 45,
                    belowOrigin: true,
                    alignment: 'left'
                });
            }, 50);
        };

        // Realiza a busca de cursos
        $scope.getCursos = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('cursos', null);
            promise.then(function (response) {
                $scope.cursos = response.data;
                $timeout(function () {
                    $('#cursoBusca, #unidadeBusca').material_select('destroy');
                    $('#cursoBusca, #unidadeBusca').material_select();
                    $scope.fechaProgresso();
                }, 500);
            });
        };

        /*Formata a data para o formato aceitavel pelo servidor*/
        $scope.converterData = function (data) {
            var arrayData = data.split('/');
            data = new Date(arrayData[2], (arrayData[1] - 1), arrayData[0]).toJSON().split('T')[0];
            return data;
        };

        /* Veirifica CPF */
        $scope.verificaCpf = function (cpf) {
            if (cpf.length === 14) {
                cpf = cpf.split(".").join("");
                cpf = cpf.split("-").join("");
                if (!Servidor.validarCpf(cpf)) {
                    Servidor.customToast('CPF inválido');
                }
            }
        };

        /*Reverte as dadas vindas dos servidor para o formatado dd/mm/aaaa*/
        $scope.reverteData = function (data) {
            if (data === null) {
                return data;
            } else {
                var arrayData = data.split('-');
                data = arrayData[2] + "/" + (arrayData[1]) + "/" + arrayData[0];
                return data;
            }
        };

        /*Seleciona o id de uma unidade para uso futuro*/
        $scope.selecionaUnidade = function (unidade, nomeUnidade) {
            if (unidade) {
                $scope.nomeUnidade = angular.copy(unidade.nomeCompleto);
                if ($scope.matriculando) {
                    $scope.matricula.unidadeEnsino = unidade;
                } else {
                    $scope.matriculaBusca.unidade = unidade.id;
                }
            } else {
                var unidade = null;
                for (var i = 0; i < $scope.unidades.length; i++) {
                    if ($scope.unidades[i].id === parseInt($scope.matriculaBusca.unidade)) {
                        unidade = $scope.unidades[i];
                    }
                }
                $scope.matriculaBusca.unidade = unidade.id;
            }
        };

        /*Realiza a busca de matrículas*/
        $scope.buscarMatriculas = function (matricula, pagina, origem) {
            if (pagina !== $scope.paginaAtual) {
                if (origem === 'botao') {
                    $scope.mostraProgresso();
                    $scope.matriculas = [];
                }
                if (!pagina) {
                    $scope.paginaAtual = 0;
                    $(".paginasLista0").addClass('active');
                } else {
                    $scope.paginaAtual = pagina;
                }
                if (origem === 'botao' && $scope.qtdPaginas || pagina === '') {
                    for (var i = 1; i <= $scope.qtdPaginas; i++) {
                        $(".paginasLista" + parseInt(i)).remove();
                    }
                }
                if (matricula.codigo !== '' || matricula.aluno !== '' || matricula.unidade !== null || matricula.curso !== null || matricula.status !== null) {
                    $scope.mostraProgresso(); var dataNasc = null;
                    if (matricula.dataNascimento !== null) {
                        dataNasc = dateTime.converterDataServidor(matricula.dataNascimento);
                    }
                    //if(!$scope.isAdmin && (matricula.aluno || matricula.codigo)) { matricula.unidade = null; }
                    if ($scope.unidade.id === "") { matricula.unidade = null; } else { matricula.unidade = $scope.unidade.id; }
                    var promise = Servidor.buscar('matriculas', {'codigo': matricula.codigo, 'aluno_nome': matricula.aluno,
                        'curso': matricula.curso, 'status': matricula.status, 'aluno_dataNascimento': dataNasc});
                    promise.then(function (response) {
                        $('#btn-cadastro-matricula').show();
                        if (response.data.length === 0) {
                            Servidor.customToast('Nenhuma Matrícula encontrada.');
                            $scope.fechaProgresso();
                        } else {
                            if (origem === 'botao') {
                                $scope.quantidadePaginas = Math.ceil(response.data.length / 50);
                            }
                            $scope.matriculas = response.data;
                            $timeout(function () {
                                window.scrollTo(0, 600);
                                $('.modal-trigger').leanModal();
                                $('.tooltipped').tooltip({delay: 50});
                                /*Inicializando controles via Jquery Mobile */
                                if ($(window).width() < 993) {
                                    $(".swipeable").on("swiperight", function () {
                                        $('.swipeable').removeClass('move-right');
                                        $(this).addClass('move-right');
                                    });
                                    $(".swipeable").on("swipeleft", function () {
                                        $('.swipeable').removeClass('move-right');
                                    });
                                }
                                $scope.fechaProgresso();
                            }, 500);
                        }
                    });
                } else {
                    $scope.fechaProgresso();
                    Servidor.customToast('Preencha ao menos um item para buscar');
                }
            }
        };

        $scope.atualizarPagina = function(pagina, subs) {
            if (subs) {
                $scope.paginaAtual = pagina;
            } else {
                if ($scope.paginaAtual + pagina > 0 && $scope.paginaAtual + pagina <= $scope.quantidadePaginas) {
                    $scope.paginaAtual += pagina;
                }
            }
        };

        /* Busca de aluno */
        $scope.buscarAlunos = function (pessoaBusca, pagina, origem) {
            if (pagina !== $scope.paginaAtual) {
                if (origem === 'botao') {
                    $scope.mostraProgresso();
                    $scope.alunos = [];
                }
                if (!pagina) {
                    $scope.paginaAtual = 0;
                    $(".paginasLista0").addClass('active');
                } else {
                    $scope.paginaAtual = pagina;
                }
                if (origem === 'botao' && $scope.qtdPaginas) {
                    for (var i = 1; i <= $scope.qtdPaginas; i++) {
                        $(".paginasLista" + parseInt(i)).remove();
                    }
                }
                if(pessoaBusca.cpf){
                    pessoaBusca.cpf = pessoaBusca.cpf.replace(/\D/g,'');
                }
                if ($scope.validarBusca()) {
                    var promise = Servidor.buscar('pessoas', {'page': pagina, 'nome': pessoaBusca.nome,
                        'sobrenome': pessoaBusca.sobrenome, 'cpf': pessoaBusca.cpf,
                        'dataNascimento': pessoaBusca.dataNascimento,
                        'nomeMae': pessoaBusca.nomeMae, 'certidaoNascimento': pessoaBusca.certidaoFormatada
                    });
                    promise.then(function (response) {
                        if (response.data.length === 0) {
                            Materialize.toast('Nenhum aluno encontrado', 1000);
                            $scope.mostraAlunos = false;
                            $scope.editando = true;
                            $timeout(function () {
                                $scope.fechaProgresso();
                                $('#botaoAdd').show();
                                $scope.adicionaAluno = true;
                            }, 100);
                        } else {
                            $scope.alunos = response.data;
                            if (origem === 'botao') {
                                $scope.qtdPaginas = Math.ceil(response.data.length / 50);
                                for (var i = 1; i < $scope.qtdPaginas; i++) {
                                    var a = '<li class="waves-effect paginasLista' + i + '" data-ng-click="alterarPagina(' + parseInt(i) + '); buscarAlunos(pessoaBusca, ' + parseInt(i) + ');"><a href="#!">' + parseInt(i + 1) + '</a></li>';
                                    $(".paginasLista" + parseInt(i - 1)).after($compile(a)($scope));
                                }
                            }
                            $timeout(function () {
                                $scope.inicializar(false);
                                $scope.mostraAlunos = true;
                                $scope.fechaProgresso();
                                $timeout(function () {
                                    $('#botaoAdd').show();
                                    $scope.adicionaAluno = true;
                                }, 100);
                            }, 500);
                        }
                    });
                } else {
                    $scope.fechaProgresso();
                }
            }
        };

        /* altera a pagina ativa */
        $scope.alterarPagina = function (pagina) {
            for (var i = 0; i <= $scope.qtdPaginas; i++) {
                $(".paginasLista" + parseInt(i)).removeClass('active');
                if (pagina === i) {
                    $(".paginasLista" + parseInt(i)).addClass('active');
                }
            }
        };

        /* Validar Busca de Aluno */
        $scope.validarBusca = function () {
            if ($scope.pessoaBusca.dataNascimento !== '' && $scope.pessoaBusca.dataNascimento !== null) {
                var arrayData = $scope.pessoaBusca.dataNascimento.split('/');
                $scope.data = new Date(arrayData[2], (arrayData[1] - 1), arrayData[0]);
                var stringDate = $scope.data.toISOString().split("T");
                $scope.pessoaBusca.dataNascimento = stringDate[0];
            }
            if ($scope.documento === 'certidao') {
                $scope.pessoaBusca.certidaoFormatada = $scope.pessoaBusca.certidao;
            } else if ($scope.documento === 'certidao-antiga') {
                var contadorDocumento = 0;
                if ($scope.pessoaBusca.termo !== null && $scope.pessoaBusca.termo !== '') {
                    contadorDocumento++;
                    $scope.pessoaBusca.termo = $scope.completaDigitos($scope.pessoaBusca.termo, 7);
                } else {
                    $scope.pessoaBusca.termo = "";
                }
                if ($scope.pessoaBusca.livro !== null && $scope.pessoaBusca.livro !== '') {
                    contadorDocumento++;
                    $scope.pessoaBusca.livro = $scope.completaDigitos($scope.pessoaBusca.livro, 5);
                } else {
                    $scope.pessoaBusca.livro = "";
                }
                if ($scope.pessoaBusca.folha !== null && $scope.pessoaBusca.folha !== '') {
                    contadorDocumento++;
                    $scope.pessoaBusca.folha = $scope.completaDigitos($scope.pessoaBusca.folha, 3);
                } else {
                    $scope.pessoaBusca.folha = "";
                }
                if (contadorDocumento > 0) {
                    $scope.pessoaBusca.certidaoFormatada = $scope.pessoaBusca.livro + $scope.pessoaBusca.folha + $scope.pessoaBusca.termo;
                }
            }
            var dadosBusca = [$scope.pessoaBusca.nome, $scope.pessoaBusca.sobrenome, $scope.pessoaBusca.cpf,
                $scope.pessoaBusca.dataNascimento, $scope.pessoaBusca.nomeMae,
                $scope.pessoaBusca.certidaoFormatada];
            var contador = 0;
            for (var i = 0; i < dadosBusca.length; i++) {
                if (dadosBusca[i] !== null && dadosBusca[i] !== '' && dadosBusca[i] !== undefined) {
                    contador++;
                }
            }
            if (contador >= 1) {
                return true;
            } else {
                Servidor.customToast('Nenhum campo preenchido.');
                return false;
            }
        };

        $scope.mostraInfor = function (aluno) {
            var promise = Servidor.buscarUm('pessoas', aluno.id);
            promise.then(function (response) {
                $scope.aluno = response.data;
                var promise = Servidor.buscar('telefones', {'pessoa': $scope.aluno.id});
                promise.then(function (responseT) {
                    $scope.aluno.telefones = responseT.data;
                    $('#matricula-info-aluno').openModal();
                });
            });
        };
        $scope.modalAjuda = function(){
            $("#modal-ajuda").openModal();
        };

        /*Reseta a estrutura dos documentos que não foram selecionados*/
        $scope.verificaDocumento = function () {
            switch ($scope.documento) {
                case 'cpf':
                    $scope.cpfBusca = null;
                    $scope.certidaoBusca = 'none';
                    $scope.certidaoAntigaBusca = 'none';
                    $scope.pessoaBusca.certidao = null;
                    $scope.pessoaBusca.termo = null;
                    $scope.pessoaBusca.livro = null;
                    $scope.pessoaBusca.folha = null;
                    break;
                case 'certidao':
                    $scope.certidaoBusca = '';
                    $scope.cpfBusca = null;
                    $scope.certidaoAntigaBusca = 'none';
                    $scope.pessoaBusca.cpf = null;
                    $scope.pessoaBusca.termo = null;
                    $scope.pessoaBusca.livro = null;
                    $scope.pessoaBusca.folha = null;
                    break;
                case 'certidao-antiga':
                    $scope.certidaoAntigaBusca = '';
                    $scope.cpfBusca = null;
                    $scope.certidaoBusca = 'none';
                    $scope.pessoaBusca.cpf = null;
                    $scope.pessoaBusca.certidao = null;
                    break;
                case 'none':
                    $scope.cpfBusca = null;
                    $scope.certidaoBusca = 'none';
                    $scope.certidaoAntigaBusca = 'none';
                    $scope.pessoaBusca.cpf = null;
                    $scope.pessoaBusca.certidao = null;
                    $scope.pessoaBusca.termo = null;
                    $scope.pessoaBusca.livro = null;
                    $scope.pessoaBusca.folha = null;
                    break;
            };
        };

        /*Completa os digitos das certidoes*/
        $scope.completaDigitos = function (valor, digitos) {
            var novoValor = valor;
            if (novoValor.length > 0 && novoValor.length < digitos) {
                var falta = digitos - valor.length;
                for (var i = 0; i < falta; i++) {
                    novoValor = '0' + novoValor;
                }
                return novoValor;
            }
            return novoValor;
        };

        /*Monta certidão modelo antigo*/
        $scope.montaCertidao = function () {
            if ($scope.cadDocumento === 'certidao-antiga') {
                var arrayData = $scope.aluno.dataNascimento.split('-');
                $scope.aluno.certidaoNascimento = arrayData[0] + "1" + $scope.livroCad + $scope.folhaCad + $scope.termoCad;
                $scope.aluno.certidaoNascimento = $scope.completaDigitos($scope.aluno.certidaoNascimento, '32');
                return $scope.aluno.certidaoNascimento;
            } else {
                return $scope.aluno.certidaoNascimento = $scope.certidaoCad;
            }
        };

        $scope.buscarUniforme = function(matricula) {
            var promise = Servidor.buscar('uniformes', {matricula: matricula.id});
            promise.then(function(response) {
                $scope.matricula.uniforme = response.data[0];
            });
        };

        $scope.salvarUniforme = function (uniforme) {
            if (uniforme.uniformeNumero && uniforme.calcadoNumero) {
                uniforme.matricula = {id:$scope.matricula.id};
                var promise = Servidor.finalizar(uniforme, 'uniformes', 'Uniforme');
                promise.then(function (response) {
                    $scope.matricula.uniforme = response.data;
                });
            } else {
                Servidor.customToast('Preencha os campos obrigatórios.');
            }
        };

        $scope.removerUniforme = function (uniforme) {
            var promise = Servidor.buscarUm('uniformes', uniforme.id);
            promise.then(function (response) {
                Servidor.remover(response.data, 'Uniforme');
                $scope.matricula.uniforme = {};
            });
        };

        /*Matricula*/
        $scope.matricular = function (aluno) {
            $scope.mostraProgresso();
            $('#botaoVoltar').removeClass('btn-voltar');
            $('#botaoVoltar').addClass('btn-voltar-buscas');
            //$scope.adicionaAluno = false;
            $('.btn-voltar').show();
            $scope.matricula.disc = false;
            $scope.nomePessoa = '';
            $scope.nomeUnidade = '';
            if(aluno) {
                $scope.matricula = MatriculaService.matricula;
                $scope.matricula.aluno = aluno;
                $scope.nomePessoa = aluno.nome;
            }
//            $scope.aluno = aluno;
//            $scope.matricula.aluno.id = aluno.id;
//            $scope.alunoMatricula = aluno;
//            $scope.alunoMatricula = aluno.nome;
            $scope.editando = false;
            //$scope.mostraAlunos = false;
            //$scope.tab = false;
            $scope.matriculando = true;
            //$scope.enturmando = false;
            $timeout(function () {
                $scope.fechaProgresso();
                Servidor.verificaLabels();
                Servidor.cardEntra('.form-geral');
                $('.dropdown-button').dropdown({
                    inDuration: 300,
                    outDuration: 225,
                    constrain_width: true,
                    hover: false,
                    gutter: 45,
                    belowOrigin: true,
                    alignment: 'left'
                });
            }, 100);
        };

        $scope.finalizarMatricula = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('matriculas', {'aluno': $scope.matricula.aluno.id, 'curso': $scope.matricula.curso.id});
            promise.then(function (response) {
                if (response.data.length) {
                    Materialize.toast($scope.matricula.aluno.nome.toUpperCase() + ' já está matriculado(a) nesse curso na unidade ' + response.data[0].unidadeEnsino.nomeCompleto + '.', 7000);
                    $scope.fechaProgresso();
                } else if ($scope.validar()) {
                    $scope.matricula.codigo = null;
                    var matricula = {
                        aluno: {id: $scope.matricula.aluno.id },
                        curso: {id: $scope.matricula.curso.id },
                        unidadeEnsino: {id: $scope.matricula.unidadeEnsino.id },
                        codigo: null
                    };
                    var promise = Servidor.finalizar(matricula, 'matriculas', 'Matricula');
                    promise.then(function (responseMatricula) {
                        $scope.matricula.id = responseMatricula.data.id;
                        $scope.buscarEtapas(responseMatricula.data.curso.id);
                        $scope.fechaProgresso();
                    }); $scope.fechaProgresso();
                } else {
                    $scope.fechaProgresso();
                }
            });
        };

        $scope.validar = function () {
            var auxiliar = 0;
            if ($scope.matricula.unidadeEnsino.id === null || $scope.matricula.curso.id === null) {
                auxiliar++;
                Servidor.customToast('Campos obrigatórios não preenchidos.');
            } else if ($scope.matricula.unidadeEnsino.id === null) {
                auxiliar++;
                Servidor.customToast('Selecione uma Unidade de Ensino');
            }
            if (auxiliar === 0) {
                return true;
            } else {
                return false;
            }
        };

        $scope.solicitarVaga = function(vaga) {
            var merge = Servidor.finalizar(vaga, 'vagas', '');
            merge.then(function () {
                if ($scope.mostraEnturmacoes) {
//                    $scope.buscarTurmas();
                    $scope.enturmacao = {
                        matricula: {id: $scope.matricula.id},
                        etapa: {id: null},
                        turma: {id: null}
                    };
                    $scope.disciplinas = [];
                    $scope.turmas = [];
                    $timeout(function() {$('#enturmacaoTurma').material_select();}, 50);
                    $scope.selecionaOpcao('enturmacoes');
                } else {
                    $scope.fecharFormulario();
                    $scope.fechaProgresso();
                }
            });
        };

        // VER SE TEM SOLICITACAO
        // MESMA PESSOA -> ENTURMA SENAO NAO
        $scope.verificarVagaDisponivel = function(pessoaId, turmaId) {
//            $scope.mostraProgresso();
//            if (!pessoaId && !turmaId) { $scope.fechaProgresso(); return Servidor.customToast('Preencha os campos obrigatórios.'); }
//            var promise = Servidor.buscar('solicitacao-vagas', {pessoa: pessoaId});
//            promise.then(function(response) {
//                var solicitacoes = response.data;
//                var promise = Servidor.buscar('vagas', {turma: turmaId});
//                promise.then(function(response) {
//                    var vagas = response.data;
//                    var vagaDisponivel = null;
//                    var possuiSolicitacao = null;
//                    vagas.forEach(function(vaga) {
//                        if (vaga.enturmacao === undefined || !vaga.enturmacao) {
//                            if (vaga.solicitacao === undefined || !vaga.solicitacao) {
//                                vagaDisponivel = vaga;
//                            } else {
//                                solicitacoes.forEach(function(solicitacao) {
//                                    if (parseInt(vaga.solicitacaoVaga) === solicitacao.id) {
//                                        possuiSolicitacao = true;
                                        $scope.finalizarEnturmacao();
//                                    }
//                                });
//                            }
//                        }
//                    });
//                    $timeout(function() {
//                        if (vagaDisponivel && !possuiSolicitacao) {
//                            $scope.finalizarEnturmacao(vagaDisponivel);
//                        } else {
//                            $scope.fechaProgresso();
//                            Servidor.customToast('Nao ha vagas disponiveis para esta turma.');
//                        }
//                    }, 500);
//                });
//            });
        };

        $scope.finalizarEnturmacao = function() {
            var enturmacao = {
                matricula: {id: $scope.matricula.id},
                turma: {id: $scope.enturmacao.turma.id}
            };
            $scope.requisicoes++;
            var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
            promise.then(function (response) {            
                $scope.enturmacao.matricula = $scope.matricula;
                $scope.enturmacao.id = response.data.id; $scope.enturmacoes.push(response.data);
                $scope.requisicoes--; $scope.fechaProgresso(); $scope.fechaLoader(); $scope.fecharFormularioEnturmacao($scope.matricula.id);
//                vaga.enturmacao = response.data.id;
//                $scope.solicitarVaga(vaga);    
            }, function (response){
                if (response.status === 400) { Servidor.customToast(response.data); $scope.fechaProgresso(); $scope.fechaLoader(); }
            });
        };

        /* Enturmação */
        $scope.enturmar = function () {
            $scope.mostraProgresso();
            //if ($scope.mostraEnturmacoes) {
                if ($scope.turmaMatricula.id) {
                    $scope.disciplinasCursadas = [];
                    $scope.enturmacao.matricula = $scope.matricula;
                    $scope.enturmacao.turma = {id: parseInt($scope.turmaMatricula.id)};
                    $scope.enturmacao.matricula = { id: $scope.enturmacao.matricula.id, aluno: {id: $scope.matricula.aluno.id, nome: $scope.matricula.aluno.nome} };
                    if($scope.matricula.status === 'TRANCADO' || $scope.matricula.status === 'ABANDONO'){
                        $scope.matricula.status = 'CURSANDO'; var matricula = $scope.matricula;
                        matricula.curso = {id: $scope.matricula.curso.id};
                        matricula.unidadeEnsino = {id: $scope.matricula.unidadeEnsino.id};
                        matricula.aluno = {id: $scope.matricula.aluno.id};
                        var promise = Servidor.finalizar($scope.matricula, 'matriculas', 'Matricula');
                        promise.then(function(response) {
                            $scope.enturmacao.matricula = response.data;
                            promise = Servidor.buscar('disciplinas-cursadas', {matricula: response.data.id});
                            promise.then(function(response) {
                                var disciplinas = response.data;
                                disciplinas.forEach(function(disc, i) {
                                    disc.status = 'CURSANDO';
                                    Servidor.finalizar(disc, 'disciplinas-cursadas', '');
                                    if (i === disciplinas.length-1) {
                                        $scope.verificarVagaDisponivel($scope.enturmacao.matricula.aluno.id, $scope.turmaMatricula.id); $scope.fechaProgresso();
                                        // $scope.fecharFormularioEnturmacao($scope.matricula.id); $scope.fecharFormulario();
                                    }
                                });
                            });
                        });
                    } else {
                        $scope.verificarVagaDisponivel($scope.enturmacao.matricula.aluno.id, $scope.turmaMatricula.id);
                        $scope.fechaProgresso(); $scope.fecharFormularioEnturmacao($scope.matricula.id); $scope.fecharFormulario();
                    }
                } else {
                    Materialize.toast('Há campos obrigatórios não preenchidos.', 2500);
                    $scope.fechaProgresso(); $scope.fecharFormularioEnturmacao($scope.matricula.id); $scope.fecharFormulario();
                }
            //}
            /* else if (!$scope.matricula.disc) {
                $scope.fechaProgresso(); $scope.fecharFormularioEnturmacao($scope.matricula.id); $scope.fecharFormulario();
                Materialize.toast('Escolha as disciplinas que serão cursadas para poder enturmar.', 4000);
            }*/
        };

        $scope.reenturmar = function () {
            $scope.mostraProgresso();
            $scope.matricula.status = 'CURSANDO';
            var promise = Servidor.finalizar($scope.matricula, 'matriculas', '');
            promise.then(function(response) {
                promise = Servidor.buscar('disciplinas-cursadas', {matricula: response.data.id});
                promise.then(function(response) {
                    var disciplinas = response.data;
                    disciplinas.forEach(function(disc, i) {
//                        if (disc.status === 'INCOMPLETO') {
                            disc.status = 'CURSANDO';
                            var promise = Servidor.finalizar(disc, 'disciplinas-cursadas', '');
                            promise.then(function() {
                                if (i === disciplinas.length-1) {
                                    $scope.enturmacao.encerrado = false;
                                    var promise = Servidor.finalizar($scope.enturmacao, 'enturmacoes', 'Enturmação');
                                    promise.then(function(response) {
                                        $scope.enturmacao = response.data;
                                        $scope.solicitarVaga(response.data);
                                        $scope.fechaProgresso();
                                    });
                                }
                            });
//                        }
                    });
                });
            });
        };

        $scope.buscarCurso = function () {
            var promise = Servidor.buscarUm('cursos', $scope.enturmacao.matricula.curso.id);
            promise.then(function (response) {
                $scope.curso = response.data;
                var promiseE = Servidor.buscar('etapas', {curso: $scope.curso.id});
                promiseE.then(function (resposta) {
                    $scope.etapas = resposta.data;
                    $timeout(function () {
                        $('select').material_select('destroy');
                        $('select').material_select();
                    }, 300);
                });
            });
        };

        $scope.buscarUnidades = function (nomeUnidade) {
            var params = {nome: null};
            var permissao = true;
            if (nomeUnidade !== undefined && nomeUnidade) {
                params.nome = nomeUnidade;
                if (nomeUnidade.length > 4) { permissao = true; } else { permissao = false; }
            }
            if(permissao) {
                if ($scope.unidadeAlocacao !== null) {
                    if ($scope.isAdmin) {
                        var promise = Servidor.buscar('unidades-ensino', params);
                        promise.then(function (response) {
                            if ($scope.isAdmin) {
                                $scope.unidades = response.data;
                            } else {
                                $scope.unidades.push(response.data);
                                $scope.matriculaBusca.unidade = response.data.id;
                            }
                            $timeout(function () {
                                $('select').material_select('destroy');
                                $('select').material_select();
                            }, 250);
                        });
                    } else {                        
                        var promise = Servidor.buscarUm('users',sessionStorage.getItem('pessoaId'));
			promise.then(function(response) {
				var user = response.data;
				$scope.atribuicoes = user.atribuicoes;
				$timeout(function () {
                                    var hasGeral = false;
				    for (var i=0; i<$scope.atribuicoes.length; i++) {
					//if ($scope.atribuicoes[i] !== undefined) {
                                        if ($scope.atribuicoes[i].instituicao.instituicaoPai !== undefined) { $scope.unidades.push($scope.atribuicoes[i].instituicao); }
                                        else { hasGeral = true; }
                                        if (i === $scope.atribuicoes.length-1) {
                                            if (hasGeral) {
                                                var promise = Servidor.buscar('unidades-ensino', {'nome': nomeUnidade});
                                                promise.then(function (response) {
                                                    $scope.unidades = response.data;
                                                    $timeout(function () {
                                                        $('select').material_select('destroy');
                                                        $('select').material_select();
                                                        $scope.fechaProgresso(); }, 500);
                                                });
                                            } else {
                                                if (i === $scope.atribuicoes.length-1) {
                                                    if ($scope.unidades.length === 1) { $scope.unidade = $scope.unidades[0]; }//$scope.buscarCursos(); }
                                                    $timeout(function () { $('select').material_select('destroy'); $('select').material_select();$scope.fechaProgresso(); }, 500);
                                                }
                                            }
                                        }
					//} else {
                                            //$scope.fechaProgresso();
					//}
				    }
				},500);
			});
                    }
                } else { console.log($scope.config); window.location.href = $scope.config.dominio+'/#/'; }
            }
        };

        $scope.buscarEnturmacoes = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('enturmacoes', {'matricula': $scope.enturmacao.matricula.id, 'encerrado': false});
            promise.then(function (response) {
                $scope.enturmacoes = response.data;
                $timeout(function () {
                    $scope.fechaProgresso();
                }, 1000);
            });
        };

        $scope.mostraTurmas = false;
        $scope.nenhumaTurma = false;
        $scope.mostraEnturmar = false;
        $scope.nenhumaEncerrada = false;
        $scope.enturmacoesMatricula = [];
        $scope.ativo = '0';

        $scope.gerarPdf = function(){
            if (!$scope.matricula.vinculo) {
                $scope.mostraProgresso();
                $timeout(function(){ $scope.gerarPdf(); }, 5000);
            } else {
                makePdf.atestadoMatriculaFrequencia($scope.matricula);
            }
        };

        $scope.TurmasMatricula = function (matricula) {
            $scope.mostraProgresso();
            $scope.matricula = matricula;
            $timeout(function () {
                $('.btn-voltar').show();
                $scope.editando = false;
                $scope.mostraTurmas = true;
                $scope.fechaProgresso();
                $('#unidadeTurma').material_select('destroy');
                $('#unidadeTurma').material_select();
                $scope.buscarTurmas();
            }, 550);
        };

        $scope.disciplinaCursadaInformacoes = function (disciplina) {
            var promise = Servidor.buscarUm('disciplinas-cursadas', disciplina.id);
            promise.then(function (response) {
                $scope.disciplinaCursada = response.data;
            });
        };

        $scope.buscarTurmasEnturmacao = function(etapa, sel) {
            var promise = Servidor.buscar('turmas', {etapa: etapa, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function(response) {
                $scope.turmas = response.data;
                if(!$scope.turmas.length) { Servidor.customToast('Esta etapa não possui nenhuma turma.'); }
                $scope.turmas.forEach(function(t, i) {
                    $scope.enturmacoes.forEach(function(e) {
                        if (t.id === e.turma.id) {
                            $scope.turmas.splice(i, 1);
                        }
                    });
                });
                setTimeout(function() {
                    $(sel).material_select(); $scope.fechaProgresso();
                }, 250);
            });
        };

        $scope.buscarDisciplinasEnturmacao = function(etapa) {
            $scope.disciplinas = [];
            $scope.mostraProgresso();
            var promise = Servidor.buscar('disciplinas-cursadas', {etapa: etapa, matricula: $scope.matricula.id, status: 'CURSANDO'});
            promise.then(function(response) {
                if(!response.data.length) {
                    var promise = Servidor.buscar('disciplinas', {etapa: etapa});
                    promise.then(function(response) {
                        response.data.forEach(function(d) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscarUm('disciplinas', d.id);
                            promise.then(function(response) {
                                $scope.disciplinas.push(response.data);
                                if(--$scope.requisicoes === 0) { $scope.fechaProgresso(); }
                            });
                        });
                    });
                } else {
                    $scope.fechaProgresso();
                }
            });
        };

        $scope.buscarTurmas = function () {
            $scope.mostraTurmas = true;
            $scope.mostraProgresso();
            $scope.enturmacoesMatricula = [];
            var promise = Servidor.buscar('enturmacoes', {'matricula': $scope.matricula.id, 'encerrado': $scope.ativo});
            promise.then(function (response) {
                if (!response.data.length && $scope.ativo === '0') {
                    $scope.menssagemErro = 'Nenhuma turma ativa, clique no + para enturmar.';
                    $scope.nenhumaEncerrada = false;
                    $scope.nenhumaTurma = true;
                } else if (!response.data.length && $scope.ativo === '1') {
                    $scope.menssagemErro = 'Nenhuma turma encerrada.';
                }
                $scope.enturmacoesMatricula = response.data;
                $scope.enturmacoesMatricula.forEach(function (enturmacao, indexE) {
                    var promiseE = Servidor.buscarUm('enturmacoes', enturmacao.id);
                    promiseE.then(function (responseE) {
                        $scope.enturmacoesMatricula[indexE] = responseE.data;
                        if (indexE === response.data.length - 1) {
                            if ($scope.ativo === '1' && response.data.length === 0) {
                                $scope.nenhumaEncerrada = true;
                            } else {
                                $scope.nenhumaEncerrada = false;
                                $scope.nenhumaTurma = false;
                                $scope.enturmacoesMatricula.forEach(function (e, index) {
                                    var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao:e.id});
                                    promise.then(function (response) {
                                        if (response.data.length) {
                                            $scope.enturmacoesMatricula[index].disciplinasCursadas = response.data;
                                            $scope.enturmacoesMatricula[index].disciplinasCursadas.forEach(function (d, $indexD) {
                                                var promiseD = Servidor.buscar('frequencias', {'matricula': e.matricula.id, 'disciplina': d.id});
                                                promiseD.then(function (responseD) {
                                                    if (responseD.data.length) {
                                                        $scope.enturmacoesMatricula[index].disciplinasCursadas[$indexD].frequencia = responseD.data.length;
                                                    }
                                                    if (index === $scope.enturmacoesMatricula.length - 1 && $indexD === $scope.enturmacoesMatricula[index].disciplinasCursadas.length - 1) {
                                                        $scope.carregarDisciplinas();
                                                        $scope.fechaProgresso();
                                                    }
                                                });
                                            });
                                        } else {
                                            if (index === $scope.enturmacoesMatricula.length - 1) {
                                                $scope.carregarDisciplinas();
                                                $scope.fechaProgresso();
                                            }
                                        }
                                    });
                                });
                            }
                            $scope.mostraEnturmar = true;
                        }
                    });
                });
            });
            $scope.fechaProgresso();
        };

        $scope.carregarDisciplinas = function () {
            $scope.mostraListaDisciplinas = false;
            var cont = 0;
            $timeout(function () {
                $scope.enturmacoesMatricula.forEach(function (e, index) {
                    var promise = Servidor.buscarUm('etapas', e.turma.etapa.id);
                    promise.then(function (response) {
                        cont++;
                        if (response.data.sistemaAvaliacao.tipo === 'QUANTITATIVO') {
                            $scope.sistemaAvaliacao = 'quantitativas';
                        } else {
                            $scope.sistemaAvaliacao = 'qualitativas';
                        }
                        if (e.disciplinasCursadas) {
                            e.disciplinasCursadas.forEach(function (d, indexD) {
                                var promise = Servidor.buscar('medias', {'disciplinaCursada': d.id});
                                promise.then(function (response) {
                                    if (response.data.length) {
                                        e.disciplinasCursadas[indexD].medias = response.data;
                                        $scope.enturmacoesMatricula[index].disciplinasCursadas[indexD].medias.forEach(function (m, indexM) {
                                            if (m.valor) {
                                                if (m.valor.split('.')[1] < 1) {
                                                    $scope.enturmacoesMatricula[index].disciplinasCursadas[indexD].medias[indexM].valor = m.valor.split('.')[0];
                                                    $scope.fechaProgresso();
                                                }
                                            }
                                            if (cont === $scope.enturmacoesMatricula.length) {
                                                $scope.fechaProgresso();
                                                $scope.mostraListaDisciplinas = true;
                                            }
                                        });
                                    } else {
                                        $scope.fechaProgresso();
                                        $scope.mostraListaDisciplinas = true;
                                    }
                                });
                            });
                        } else if (cont === $scope.enturmacoesMatricula.length) {
                            $scope.fechaProgresso();
                            $scope.mostraListaDisciplinas = true;
                        }
                    });
                });
            }, 100);
        };

        $scope.carregaMedia = function (media) {
            $scope.media = media;
            if (media === 'SN') {
                Servidor.customToast('Esta média não possui notas.');
            }
            if (!media.notas.length) {
                if ($scope.sistemaAvaliacao === 'qualitativas') {
                    Servidor.customToast(media.nome + ' não possui nenhum conceito.');
                } else {
                    Servidor.customToast(media.nome + ' não possui nenhuma nota.');
                }
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
                        $('#notas-disciplina').openModal();
                        $('.collapsible').collapsible({accordion: false});
                    }, 500);
                } else {
                    $scope.media.notas.forEach(function (n, $index) {
                        var promise = Servidor.buscarUm('avaliacoes-' + $scope.sistemaAvaliacao, n.avaliacao.id);
                        promise.then(function (response) {
                            $scope.media.notas[$index].avaliacao = response.data;
                            if ($scope.media.notas[$index].valor.split('.')[1] < 1) {
                                $scope.media.notas[$index].valor = $scope.media.notas[$index].valor.split('.')[0];
                            };
                            if ($index === $scope.media.notas.length - 1) {
                                $('#notas-disciplina').openModal();
                            }
                        });
                    });
                }
            }
        };

        /* Abrir frequencias */
        $scope.frequenciasMatricula = function (matricula) {
            $scope.mostraProgresso();
            $scope.matricula = matricula;
            $timeout(function () {
                $('.btn-voltar').show();
                $scope.fechaProgresso();
                $('#').material_select('destroy');
                $('#').material_select();
            }, 550);
        };

        $scope.mostrarLabels = function() {
            $('.toolchip').fadeToggle();
        };

        /*mostrar opçoes */
        $scope.mostraOpcoes = function (matricula, opcao, status) {
            if(!$scope.isAdmin && !sessionStorage.getItem('visãoGeral')) {
                return Servidor.customToast('Este aluno não está matriculado em sua unidade.');
            }
            $scope.facilAcesso = status;
            $scope.mostraProgresso();
            $scope.frequentaStatus = 'frequenta';
            var promise = Servidor.buscarUm('matriculas', matricula.id);
            promise.then(function (response) {
                $scope.matricula = response.data;
                $scope.buscarUniforme($scope.matricula);
                var promise = Servidor.buscarUm('pessoas', matricula.aluno.id);
                promise.then(function(response) {
                    $scope.matricula.aluno = response.data;
                    if($scope.matricula.status === 'FALECIDO' || $scope.matricula.status === 'TRANCADO' || $scope.matricula.status === 'ABANDONO'){
                        $scope.frequentaStatus = 'não Frequenta';
                    }
                    var promiseB = Servidor.buscar('telefones', {pessoa: $scope.matricula.unidadeEnsino.id});
                    promiseB.then(function(response){
                       $scope.matricula.unidadeEnsino.telefones = response.data;
                    });
                    Servidor.removeTooltipp();
                    var promise = Servidor.buscarUm('pessoas', $scope.matricula.aluno.id);
                    promise.then(function (response) {
                        $scope.matricula.aluno = response.data;
                        $scope.aluno = response.data;
                        if($scope.aluno.genero === 'f' || $scope.aluno.genero === 'F'){
                            $scope.genero = 'a aluna';
                        }else {
                            $scope.genero = 'o aluno';
                        };
                        var promise = Servidor.buscar('enturmacoes',{matricula: $scope.matricula.id, curso:$scope.matricula.curso.id, encerrado: false});
                        promise.then(function(response){
                            if (response.data.length) {
                                var promise2 = Servidor.buscarUm('turmas', response.data[0].turma.id);
                                promise2.then(function(response2){
                                    $scope.matricula.turmaAtual = response2.data;
                                    $scope.matricula.etapaAtual = response2.data.etapa;
                                });
                            }
                        });
                        
                    });
                    $timeout(function () {
                        $scope.fechaProgresso();
                        $('.tooltipped').tooltip({delay: 50});
                        $('.btn-voltar').show();
                        $scope.editando = false;
                        $scope.abrirOpcoes = true;
                        $scope.informacaoatual = true;
                        $scope.mostraEnturmacao = false;
                        $scope.matriculaContato();
                        if (opcao) {
                            $scope.selecionaOpcao(opcao);
                        } else {
                            var promise = Servidor.buscar('etapas-ofertadas', {'curso': $scope.matricula.curso.id, 'unidadeEnsino': $scope.matricula.unidadeEnsino.id});
                            promise.then(function (response) {
                                $scope.etapas = response.data;
                                $scope.disciplinasCursadas = [];
                                $scope.etapa.id = null;
                                $timeout(function () {
                                    $('#etapaDisciplina').material_select();
                                }, 50);
                            });
                        }
                        $scope.fechaProgresso();
                    }, 250);
                });
            });
        };

        $scope.dataAtual = function () {
            $scope.dataEstrutura = {
                'dia': '',
                'mes': '',
                'ano': '',
                'completa': ''
            };
            var data = new Date();
            $scope.dataEstrutura.dia = data.getDate();
            $scope.dataEstrutura.mes = data.getMonth() + 1;
            $scope.dataEstrutura.ano = data.getFullYear();
            if ($scope.dataEstrutura.mes < 10) {
                $scope.dataEstrutura.mes = '0' + (data.getMonth() + 1);
            }
            if ($scope.dataEstrutura.dia < 10) {
                $scope.dataEstrutura.dia = '0' + data.getDate();
            }

            $scope.dataEstrutura.completa = 'Itajaí(SC), ' + $scope.dataEstrutura.dia + ' de ' + dateTime.converterMes($scope.dataEstrutura.mes) + ' de ' + $scope.dataEstrutura.ano;
        };

        /* Volta para tela de Matricula */
        $scope.voltarFormulario = function () {
            $scope.editando = false;
            $scope.enturmando = false;
            $scope.matriculando = true;
            $scope.buscarMatriculas($scope.matriculaBusca);
            $scope.cadastrando = false;
            $scope.mostraAlunos = false;
            $scope.mostraEnturmacao = false;
        };

        $scope.fecharFormularioEnturmacao = function (matricula) {
            var promise = Servidor.buscar('enturmacoes', {matricula: matricula});
            promise.then(function (response) {
                $scope.enturmacoes = response.data;
                $timeout(function () {
                    $scope.buscarUnidades();
                    $scope.buscarTurmas();
                    $scope.buscarEnturmacoes();
                    $scope.matriculas = null;
                }, 500);
            });
        };

        /* Checa os labels nao ativos dos inputs da busca */
        $scope.verificaLabelsBusca = function () {
            $('.input-field').each(function () {
                $(this).find('label').removeClass('active');
            });
        };

        $scope.trocarTab = function (tab) {
            if (tab === 'matricula') {
                $scope.buscaMatricula = true;
            } else {
                $scope.buscaMatricula = false;
            }
            $scope.alunos = [];
            $scope.matriculas = [];
            $scope.reiniciarPessoaBusca();
        };

        $scope.reiniciarMatriculaBusca = function() {
            $scope.matriculaBusca = {
                'aluno': null,
                'status': null,
                'codigo': null,
                'curso': null,
                'unidade': $scope.matriculaBusca.unidade,
                'dataNascimento': null
            };
            if ($scope.isAdmin) {
                $scope.matriculaBusca.unidade = null;
            }
            $scope.nomeUnidade = '';
            $timeout(function() {
                $('#unidadeBusca, #cursoBusca, #statusBusca').material_select('destroy');
                $('#unidadeBusca, #cursoBusca, #statusBusca').material_select();
            }, 50);
        };

        $scope.fecharFormulario = function () {
            $scope.fechaProgresso();
            $scope.mostraFrequencias = false;
            $scope.mostraNotas = false;
            $scope.mostraMovimentacoes = false;
            $scope.mostraDisciplinas = false;
            $scope.nenhumaTurma = false;
            $scope.abrirOpcoes = true;
            $scope.mostraEnturmar = false;
            $scope.frequencias = [];
            $scope.etapa = {'id': null};
            $scope.disciplina = [];
            $scope.informacaoatual = true;
            $scope.matriculaDisciplina = false;
            $scope.addenturmacao = false;
            $scope.nenhumaTurma = false;
            $scope.mostraEnturmar = false;
            $scope.atestado = false;
            $scope.imprimirhistorico = false;
            $scope.fechaProgresso();
            Servidor.cardEntraVolta('.form-geral');
            $scope.disciplinasTurma = false;
            $scope.erro = false;
            $scope.etapa = null;
            $scope.disciplinasOfertadas = [];
            $scope.etapas = [];
            $scope.disciplinasCursadas = [];
            $scope.colocaDisciplina = [];
            $scope.listaDisciplinasCursadas = [];
            $scope.enturmacoesMatricula = [];
            $scope.turmas = [];
            $scope.enturmacoesMatricula = [];
            $scope.disciplinasCurso = [];
            $scope.movimentacoes = [];
            $scope.movimentacao = null;
            $scope.turma = null;
            $scope.adicionaAluno = false;
            $scope.enturmando = false;
            $scope.tab = true;
            $scope.matriculando = false;
            $scope.cadastrando = false;
            $scope.mostraAlunos = false;
            $scope.mostraEnturmacao = false;
            $scope.matriculaDisciplina = false;
            $scope.mostraTurmas = false;
            $scope.mostraMovimentacoes = false;
            $scope.mostrarDisciplinas = false;
            $scope.cadastrarDisciplinasCursadas = false;
            $scope.ativo = '';
            $scope.reiniciar();
            $scope.reiniciarEnturmacao();
            $scope.reiniciarMatriculaBusca();
//            $scope.matriculas = [];
            $scope.verificaDocumento();
            $scope.editando = true;
            $scope.mostraFrequencia = false;
            $scope.abrirOpcoes = false;
            $timeout(function () {
                $("#mapa").html('');
                $('select').material_select('destroy');
                $('select').material_select();
                Servidor.verificaLabels();
                $scope.fechaProgresso();
                $("#validate").show();
                $("#alunosLista").show();
            }, 500);
        };

        /*---------------------------------------------------------------------------------------------------------------*/
        $scope.opcaoMatricula = 'MATRICULAR EM DISCIPLINAS';
        $scope.mostraDisciplinasCursadas = false;
        $scope.disciplinasCursadas = [];
        $scope.disciplinas = null;

        $scope.buscaDisciplinasCursadas = function (matricula) {
            var promisse = Servidor.buscar('enturmacoes', {'matricula': matricula.id});
            promisse.then(function (response) {
                $scope.enturmacoes = response.data;
            });
            $('.btn-voltar').hide();
            $scope.tab = false;
            $scope.enturmando = false;
            $scope.inicializar(true);
            $scope.disciplinasTurma = false;
            $scope.matricula = matricula;
            $('#matriculasLista').hide();
            $scope.mostraDisciplinasCursadas = true;
            var promise = Servidor.buscar('matriculas/' + matricula.id + '/disciplinas-cursadas');
            promise.then(function (response) {
                $scope.disciplinas = response.data;
                $scope.fechaProgresso();
            });
        };

        $scope.erro = false;

        $scope.disciplinasCursadasTurma = function (enturmacao) {
            $scope.enturmacaoTurma = enturmacao;
            $scope.disciplinasTurma = true;
            $scope.mostraDisciplinasCursadas = false;
            $scope.enturmando = false;
            var id = enturmacao.matricula.id;
            var promise = Servidor.buscar('matriculas/' + id + '/disciplinas-cursadas', {'enturmacao': enturmacao.id});
            promise.then(function (response) {
                $scope.disciplinas = response.data;
                if ($scope.disciplinas.length === 0) {
                    $scope.erro = true;
                }
                $scope.fechaProgresso();
            });
        };

        $scope.voltarEnturmacao = function () {
            $scope.erro = false;
            //$scope.enturmacao = null;
            $scope.disciplinasTurma = false;
            $scope.enturmando = true;
        };

        $scope.abreDisciplina = function (disciplina) {
            $('#disciplina-modal-aluno').openModal();
            $scope.disciplina = disciplina;
        };

        /*Fecha Modal aberto*/
        $scope.fecharModal = function () {
            $('.lean-overlay').hide();
            $('#disciplina-modal-aluno').closeModal();
        };

        $scope.disciplinasOfertadas = [];
        $scope.integral = '- Etapa não Integral';
        $scope.colocaDisciplina = [];
        $scope.listaDisciplinasCursadas = [];
        $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} };
        $scope.limparDisciplinaCursada = function() {
            $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} };
        };

        $scope.enturmarMatricula = function (matricula) {
            $scope.matricula = matricula;
            $scope.enturmacao = {};
            $scope.disciplinasCurso = [];
            $scope.etapas = [];
            $scope.turmas = [];
            if ($scope.matricula.status === 'TRANCADO') {
                var promise = Servidor.buscar('enturmacoes', {encerrado: 1, matricula: $scope.matricula.id});
                promise.then(function(response) {
                    if (response.data) {
                        promise = Servidor.buscarUm('enturmacoes', response.data[response.data.length-1].id);
                        promise.then(function(response) {
                            if (new Date().getFullYear() === parseInt(response.data.dataCadastro.split('-')[0])) {
                                $scope.enturmacao = response.data;
                                $scope.turmas.push($scope.enturmacao.turma);
                                $timeout(function () {
                                    $('#turmaDisciplinas').material_select('destroy');
                                    $('#turmaDisciplinas').material_select();
                                    $scope.fechaProgresso();
                                }, 100);
                            }
                        });
                    }
                });
            }
            var promise = Servidor.buscar('etapas', {'curso': $scope.matricula.curso.id});
            promise.then(function (response) {
                $scope.etapasCurso = response.data;
                $scope.etapa = {id: null};
                $scope.turmaMatricula = {id: null};
                $('#modal-enturmar').openModal();
                $timeout(function () {
                    $('#etapaCursoEnturmar').material_select('destroy');
                    $('#etapaCursoEnturmar').material_select();
                    Servidor.verificaLabels();
                    $scope.fechaProgresso();
                }, 100);
            });
        };

        $scope.reiniciaMatriculaDisciplina = function () {
            $scope.opcaoMatricula = '';
            $scope.mostraDisciplinasCursadas = false;
            $scope.disciplinasCursadas = [];
            $scope.disciplinasOfertadas = [];
            $scope.colocaDisciplina = [];
            $scope.listaDisciplinasCursadas = [];
            $scope.etapaCurso = null;
            $scope.disciplinas = null;
            $scope.disciplinasTurma = false;
            $scope.disciplinaCursada = {
                disciplina: {id: null},
                matricula: {id: null}
            };
        };

        /*Controle para matricular nas disciplinas*/
        $scope.matricularNasDisciplinas = function (matricula) {
            $('#modal-disciplinas').openModal();
            $scope.etapa.id = null;
            $scope.disciplinasCursadas = [];
            $timeout(function() {
               $('#etapaCursoDisciplinaCursada').material_select();
               Servidor.verificaLabels(); $scope.fechaProgresso();
            }, 50);
        };

        $scope.gerarFrequenciaFinal = function(disciplina) {
            var promise = Servidor.buscar('frequencias', {matricula: $scope.matricula.id, disciplina: disciplina});
            promise.then(function(response) {
                var frequencias = response.data;
                var presencas = 0;
                frequencias.forEach(function(dia) { if (dia.status === 'PRESENCA') { presencas++; } });
                var faltas = presencas - response.data.length;
                var promise = Servidor.buscar('enturmacoes',{matricula: $scope.matricula.id, encerrado: false});
                promise.then(function(response){
                    var promise2 = Servidor.buscar('turmas/'+response.data[0].turma.id+'/aulas', {disciplina: disciplina});
                    promise2.then(function(response2) {
                        var aulas = response2.data.length;
                        var porcentagem = (presencas * 100) / aulas;
                        $scope.fechaProgresso();
                    });
                });
            });
        };

        $scope.buscarDisciplinasCursadasEnturmacao = function(etapaId) {
            var promise = Servidor.buscar('disciplinas-cursadas', {etapa: etapaId, matricula: $scope.matricula.id});
            promise.then(function(response) {
                if (response.data) {
                    $scope.turmasCompativeis(etapaId, response.data);
                } else {
                    $scope.turmas = [];
                    Materialize.toast('Não há nenhuma turma compatível nesta etapa', 2500);
                }
                $timeout(function(){
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula').material_select('destroy');
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula').material_select(''); $scope.fechaProgresso();
                },250);
            });
        };

        /*Busca de etapas no Curso*/
        $scope.buscarEtapas = function (id, verifica) {
            $scope.etapas = [];
            var promise = Servidor.buscar('etapas', {'curso': id});
            promise.then(function (response) {
                $scope.etapas = response.data;
                $timeout(function () {
                    $scope.editando = false;
                    if (verifica === 'frequencia') {
                        $scope.matriculaDisciplina = false;
                        $('#etapaCurso').material_select('destroy');
                        $('#etapaCurso').material_select();
                    } else {
                        $('select').material_select('destroy');
                        $('select').material_select();
                    }
                    $scope.mostraTurmas = false;
                    $scope.nenhumaTurma = false;
                    $('.btn-voltar').show();
                    $('#matriculasLista').hide();
                    $scope.fechaProgresso();
                }, 150);
            });
        };

        $scope.turmasCompativeis = function (id, cursadas) {
            if (!id) { id = $scope.etapa.id; } $scope.turmas = [];
            var requisicoesTurmasCompativeis = 0; $scope.requisicoes++;
            var promise = Servidor.buscar('turmas', {'etapa': id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function (response) {
                $scope.turmas = response.data; $scope.fechaProgresso();
                $timeout(function(){
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('destroy');
                    $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('');                                
                },50);
                /*$scope.requisicoes--;
                var turmas = response.data;
                if(!turmas.length && $scope.etapa.nomeExibicao !== undefined) {
                    Servidor.customToast('Nao há turmas em ' + $scope.etapa.nomeExibicao + '.');
                    $scope.fechaProgresso();
                }
                turmas.forEach(function (turma, i) {
                    $scope.requisicoes++; var compativeis = 0;
                    requisicoesTurmasCompativeis++;
                    promise = Servidor.buscar('disciplinas-ofertadas', {'turma': turma.id});
                    promise.then(function (response) {                        
                        var ofertadas = response.data;
                        cursadas.forEach(function (cursada) {
                            ofertadas.forEach(function (ofertada) {
                                if (cursada.disciplina.id === ofertada.disciplina.id) {
                                    if (++compativeis === cursadas.length) {
                                        $scope.turmas.push(turma);
                                    }
                                }
                            });
                        });
                        $scope.requisicoes--;
                        if (--requisicoesTurmasCompativeis === 0) {
                            console.log($scope.turmas);
                            if ($scope.turmas.length === 0) {
                                $scope.turmas = [];
                                Materialize.toast('Não há nenhuma turma compatível nesta etapa', 2500);
                            }
                            $timeout(function(){
                                $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('destroy');
                                $('#turmaDisciplinas, #turmaEnturmacaoDisciplinasMatricula, #enturmacaoTurma').material_select('');                                
                            },50);
                        }
                    });
                });*/
            });
        };

        /* Busca turmas naquela */
        $scope.buscarTurmasDisciplinas = function (id) {
            /*$scope.turmaMatricula.id = null;
            $scope.disciplinasOfertadas = [];
            $scope.mostraProgresso();
            $scope.buscarEtapaCurso(id);
            var promiseD = Servidor.buscar('disciplinas', {'curso': $scope.matricula.curso.id, 'etapa': id});
            promiseD.then(function (responseD) {
                if (responseD.data.length) {
                    $scope.disciplinasCurso = responseD.data;
                    $scope.selecionarTodasDisciplinas();
                } else {
                    $scope.fechaProgresso();
                    Servidor.customToast('Nao ha disciplinas nesta etapa.');
                }turmaEnturmacaoDisciplinasMatricula
            });*/
            $scope.mostraProgresso();
            var promise = Servidor.buscar('turmas',{etapa: id, curso: $scope.matricula.curso.id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
            promise.then(function(response){
                $scope.turmas = response.data; $timeout(function(){ $('#turmaEnturmacaoDisciplinasMatricula').material_select(); $('#enturmacaoTurma').material_select(); $scope.fechaProgresso();  },500);
            });
        };

        /* Busca uma etapa */
        $scope.buscarEtapaCurso = function (id) {
            $scope.mostraProgresso();
            var promise = Servidor.buscarUm('etapas', id);
            promise.then(function (response) {
                $scope.fechaProgresso();
                $scope.etapaCurso = response.data;
            });
        };

        /* Todas disciplina ofertadas */
        $scope.selecionarTodasDisciplinas = function () {
            $scope.mostraProgresso();
            $scope.disciplinasCursadas = [];
            var disciplinaCursada = {
                'matricula': $scope.matricula.id,
                'disciplina': null,
                'id': null
            };
            var requisicoes = 0;
            if(!$scope.disciplinasCurso.length) {
                $scope.fechaProgresso();
            }
            $scope.disciplinasCurso.forEach(function (d, index) {                                
                requisicoes++;
                var promise = Servidor.buscarUm('disciplinas', d.id);
                promise.then(function (response) {
                    disciplinaCursada.disciplina = angular.copy(response.data);
                    if (response.data.opcional) {
                        $scope.disciplinasCursadas.push(angular.copy(disciplinaCursada));
                    } else {
                        d = disciplinaCursada;
                    }
                    if(--requisicoes === 0) {
                        $scope.fechaProgresso();
                    }
                });
            });
        };

        /*Controla disciplinas selecionada*/
        $scope.selecionaDisciplina = function (disciplina) {
            var qtd = $scope.disciplinasCurso.length;
            $scope.disciplinasCurso.forEach(function (d, index){
                if(d.id === disciplina.disciplina.id) {
                    $scope.disciplinasCurso.splice(index, 1);
                }
            });
            if(qtd === $scope.disciplinasCurso.length) {
                $scope.disciplinasCurso.push(disciplina.disciplina);
            }
        };

        $scope.verificaDisciplinasCursadas = function(){
            var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id});
            promise.then(function(response){
                var disciplinasCursadas = response.data;
                disciplinasCursadas.forEach(function (b) {
                    $scope.disciplinasCurso.forEach(function(d, i){
                        if (b.disciplina.id === d.id) {
                            $scope.disciplinasCurso.splice(i, 1);
                        }
                    });
               });
               $scope.salvarDisciplinasCursadas();
               $scope.fechaProgresso();
            });
        };

        /*Salva lista de sisciplinas cursadas*/
        $scope.salvarDisciplinasCursadas = function () {
            if ($scope.matricula.id) {
                $scope.mostraProgresso();
                if ($scope.etapa.id) {
                    if ($scope.disciplinasCurso !== undefined && $scope.disciplinasCurso.length) {
                        var promise = Servidor.buscarUm('matriculas', $scope.matricula.id);
                        promise.then(function(response) {
                            $scope.matricula = response.data;
                            $scope.matricula.status = 'CURSANDO';
                            var promise = Servidor.finalizar($scope.matricula, 'matriculas', null);
                            promise.then(function(response) {
                                var cursadas = [];
                                $scope.disciplinasCursadas = [];
                                $scope.disciplinasCurso.forEach(function (d) {
                                    $scope.disciplinaCursada.matricula.id = $scope.matricula.id;
                                    if (d.id) {
                                        $scope.disciplinaCursada.disciplina = d;
                                        $scope.disciplinaCursada.nome = d.nome;
                                        $scope.disciplinaCursada.nomeExibicao = d.nomeExibicao;
                                    } else {
                                        $scope.disciplinaCursada.disciplina = d.disciplina;
                                        $scope.disciplinaCursada.nome = d.disciplina.nome;
                                        $scope.disciplinaCursada.nomeExibicao = d.disciplina.nomeExibicao;
                                    }
                                    cursadas.push($scope.disciplinaCursada);                                    
                                    Servidor.finalizar($scope.disciplinaCursada, 'disciplinas-cursadas', null);
                                    $scope.limparDisciplinaCursada();
                                    if(cursadas.length === $scope.disciplinasCurso.length) {
                                        if($scope.mostraDisciplinas) {
                                            $scope.selecionaOpcao('enturmacoes');
                                        } else {
                                            $scope.turmasCompativeis($scope.etapa.id, cursadas);
                                            $scope.matricula.disc = true;
                                        }
                                        Servidor.customToast('Etapa salva com sucesso.');
                                        $scope.fechaProgresso();
                                    }
                                });
                                $timeout(function() {
                                   $('select').material_select('destroy');
                                   $('select').material_select();
                                   $scope.fechaProgresso();
                                }, 250);
                            });
                        });
                    } else {                        
                        Servidor.customToast('Esta etapa não possue disciplinas.');
                        $scope.fechaProgresso();
                    }
                } else {
                    Servidor.customToast('Existem campos obrigatórios não preenchidos');
                    $scope.fechaProgresso();
                }                  
            } else {
                $scope.fechaProgresso();
                Materialize.toast('Precisa efetuar a matrícula antes de alocar as disciplinas.', 4000);
            }
        };

        $scope.abrirModalTransferenciaLocal = function(matricula) {
            $scope.transferencia = {justificativa: null};
            $scope.matricula = matricula;
            $('#transferir-para-mim-modal').openModal();
        };

        $scope.transferenciaLocal = function(matricula, justificativa) {
            if(justificativa !== undefined && justificativa) {
                var transferencia = {
                    status: 'ACEITO',
                    justificativa: justificativa,
                    matricula: {id: matricula.id},
                    unidadeEnsinoDestino: {id: parseInt(sessionStorage.getItem('unidade'))},
                    unidadeEnsinoOrigem: {id: matricula.unidadeEnsino.id}
                };
                $scope.mostraProgresso();
                var promise = Servidor.finalizar(transferencia, 'transferencias', 'Transferência');
                promise.then(function(response) {
                    var matricula = response.data.matricula;
                    $scope.matriculas.forEach(function(m) {
                        if(m.id === matricula.id) {
                            m = matricula;
                        }
                    });
                    $scope.fechaProgresso();
                });
            } else {
                Servidor.customToast('Preencha a justificativa.');
            }
        };

        $scope.mostraMovimentacoes = false;
        $scope.cadastrarDisciplinasCursadas = false;
        $scope.movimentacoes = [];
        $scope.etapasCurso = [];
        $scope.statusTransferencia = null;
        $scope.etapaDisciplinas = null;

        $scope.historicoDisciplinas = function (matricula) {
            $scope.mostraProgresso();
            Servidor.cardSai(['.card-result', '.card-search', '.info-card'], false);
            $scope.cadastrarDisciplinasCursadas = true;
            $scope.matricula = matricula;
            var promise = Servidor.buscar('etapas', {'curso': $scope.matricula.curso.id});
            promise.then(function (response) {
                $scope.etapasCurso = response.data;
            });
            $timeout(function () {
                $('.btn-voltar').show();
                $scope.editando = false;
                $scope.mostrarDisciplinas = true;
                $scope.fechaProgresso();
                $('#etapaDisciplina').material_select('destroy');
                $('#etapaDisciplina').material_select();
            }, 300);
        };

        $scope.buscarDisciplinasCursadas = function (etapa, collapsible) {
            $scope.disciplinasCursadas = [];
            var promise = Servidor.buscarUm('etapas', etapa);
            promise.then(function (response) {
                var etapa = response.data;
                var promise = Servidor.buscar('matriculas/' + $scope.matricula.id + '/disciplinas-cursadas', {'etapa': etapa.id});
                promise.then(function (responseLista) {
                    if (responseLista.data.length) {
                        $scope.addenturmacao = false;
                        responseLista.data.forEach(function (r, $index) {
                            var promise = Servidor.buscarUm('disciplinas-cursadas', r.id);
                            promise.then(function (response) {
                                $scope.disciplinasCursadas.push(response.data);
                                if ($index === responseLista.data.length - 1) {
                                    if ($scope.disciplinasCursadas.length && etapa.integral) {
                                        $scope.addenturmacao = false;
                                    }
                                    else {
                                        $scope.addenturmacao = true;
                                    }
                                    $scope.collapsible();
                                }
                                $scope.collapsible();
                                $scope.fechaProgresso();
                            });
                        });
                    }
                    else {
                        $scope.addenturmacao = true;
                        Servidor.customToast('Nenhuma disciplina nessa etapa');
                    }
                });
            });
            $timeout(function () {
                $('#disciplinaFrequencia').material_select('destroy');
                $('#disciplinaFrequencia').material_select();
            }, 350);
        };

        $scope.tipoMovimentacao = 'todas';
        $scope.historicoMovimentacoes = function (matricula) {
            $scope.mostraProgresso();
            Servidor.cardSai(['.card-result', '.card-search', '.info-card'], false);
            $scope.matricula = matricula;
            $timeout(function () {
                $('.btn-voltar').show();
                $scope.editando = false;
                $scope.mostraMovimentacoes = true;
                $scope.fechaProgresso();
                $scope.buscarTodasMovimentacoes();
                $('#movimentacaoTipo').material_select('destroy');
                $('#movimentacaoTipo').material_select();
            }, 300);
        };

        $scope.escolheOpcaoBusca = function () {
            $scope.movimentacoes = [];
            $scope.statusTransferencia = '';
            $('#statusTransferenciaBusca').material_select('destroy');
            $('#statusTransferenciaBusca').material_select();
            if ($scope.tipoMovimentacao === 'todas') {
                $scope.buscarTodasMovimentacoes();
            } else if ($scope.tipoMovimentacao === 'unidade') {
                $scope.buscarTransferencias();
            } else if ($scope.tipoMovimentacao === 'turma') {
                $scope.buscarMovimentacoesTurma();
            } else if ($scope.tipoMovimentacao === 'desligamentos') {
                $scope.buscarDesligamentosTurma();
            }
        };
        
        /*Busca Movimentacoes de turma*/
        $scope.buscarDesligamentosTurma = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('desligamentos', {'matricula': $scope.matricula.id});
            promise.then(function (response) {
                if (response.data.length > 0) {
                    response.data.forEach(function (m) {
                        var promise = Servidor.buscarUm('desligamentos', m.id);
                        promise.then(function (response) {
                            var objetoMovimentacaoTurma = {
                                'id': m.id,
                                'tipo': 'DESLIGAMENTO',
                                'data': response.data.dataCadastro,
                                'justificativa': response.data.justificativa
                            };
                            $scope.movimentacoes.push(objetoMovimentacaoTurma);
                            $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75);
                            $scope.fechaProgresso();
                        });
                    });
                } else {
                    Servidor.customToast('Nenhuma movimentação registrada');
                }
            });
            $scope.fechaProgresso();
        };

        /*Abrir modal justificativa*/
        $scope.abrirModalJustificativa = function (justificativa) {
           $timeout(function () {
                $('#modal-frequenciaJustificativa').openModal();
            }, 100);
        };

        /*Busca Movimentacoes de turma*/
        $scope.buscarMovimentacoesTurma = function () {
            $scope.mostraProgresso();
            $scope.movimentacoes = [];
            var promise = Servidor.buscar('movimentacoes-turma', {'matricula': $scope.matricula.id});
            promise.then(function (response) {
                if (response.data.length > 0) {
                    response.data.forEach(function (m) {
                        var promise = Servidor.buscarUm('movimentacoes-turma', m.id);
                        promise.then(function (response) {
                            var objetoMovimentacaoTurma = {
                                'id': m.id,
                                'tipo': 'MOVIMENTAÇÃO DE TURMA',
                                'data': response.data.dataCadastro,
                                'origem': 'turma ' + response.data.enturmacaoOrigem.turma.nome,
                                'destino': 'turma ' + response.data.enturmacaoDestino.turma.nome,
                                'justificativa': response.data.justificativa
                            };
                            $scope.movimentacoes.push(objetoMovimentacaoTurma);
                            $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75);
                             $scope.fechaProgresso();
                        });
                    });
                } else {
                    Servidor.customToast('Nenhuma movimentação registrada');
                }
            });
            $scope.fechaProgresso();
        };

        $scope.vinculo = function (){
            var vinculoId = sessionStorage.getItem('vinculo');
            var promise = Servidor.buscarUm('vinculos', vinculoId);
            promise.then(function(response){
                $scope.usuario = {
                    'nome': response.data.funcionario.nome,
                    'vinculo':response.data.cargo
                };
            });
        };

        $scope.buscarNotas = function () {
            $scope.notas = [];
            var promise = Servidor.buscar('notas', {'matricula': $scope.matricula.id, 'disciplina': $scope.disciplinaId});
            promise.then(function (response) {
                $scope.notas = response.data; $scope.fechaProgresso();
            });
        };

        $scope.buscarFrequencias = function () {
            $scope.frequencias = [];
            var promise = Servidor.buscar('frequencias', {'matricula': $scope.matricula.id, 'disciplina': $scope.disciplinaId});
            promise.then(function (response) {
                $scope.frequencias = response.data;
                $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75); $scope.fechaProgresso();
            });
        };

        $scope.buscarHistorico = function () {
            if ($scope.disciplinaHistorico === 'frequencias')
            {
                $scope.buscarFrequencias();
                $scope.notas = [];
            } else {
                $scope.buscarNotas();
                $scope.frequencias = [];
            }
        };

        /*Abrir uma opção de matricula*/
        $scope.selecionaOpcao = function (opcao) {
            $scope.mostraDisciplinas = false;
            $scope.informacaoatual = false;
            $scope.mostraFrequencias = false;
            $scope.mostraMovimentacoes = false;
            $scope.mostraEnturmacoes = false;
            $scope.mostraEnturmar = false;
            $scope.movimentacoes = [];
            $scope.addenturmacao = false;
            $scope.imprimirhistorico = false;
            $scope.nenhumaTurma = false;
            $scope.atestado = false;
            switch (opcao) {
                case 'home':
                    $scope.informacaoatual = true;
                break
                case 'movimentacoes':
                    $scope.mostraMovimentacoes = true;
                    $scope.buscarTodasMovimentacoes();
                break
                case 'enturmacoes':
                    $scope.requisicoes++; $scope.mostraEnturmacoes = true;
                    var promise = Servidor.buscar('enturmacoes', {matricula: $scope.matricula.id, curso: $scope.matricula.curso.id, encerrado: false});
                    promise.then(function(response) {                        
                        $scope.enturmacoes = response.data;
                        var enturmacoesAtivas = $scope.enturmacoes.filter(function(enturmacao) {
                            return !enturmacao.encerrado;
                        });
                        $timeout(function(){ $('.collapsible').collapsible(); },500);
                        /*var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id, status:'CURSANDO'});
                        promise.then(function(response) {
                            var disciplinasAtivas = response.data;
                            $scope.requisicoes--;
                            if(!disciplinasAtivas.length && !enturmacoesAtivas.length) {
                                $scope.selecionaOpcao('disciplinas');
                            } else {
                                if(TurmaService.abrirFormulario) {
                                    var index = $scope.enturmacoes.length-1;
                                    $scope.buscarMediasFrequenciasAluno(index);
                                    $scope.buscarSistemaAvaliacao($scope.enturmacoes[index].turma.etapa);
                                }
                                if($scope.matricula.curso.especializado) {
                                    $scope.requisicoes++;
                                    var promise = Servidor.buscar('etapas', {curso:$scope.matricula.curso.id});
                                    promise.then(function(response) {
                                        $scope.etapas = response.data;
                                        $scope.turmas = [];
                                        var achou;
                                        for(var i = 0; i < $scope.etapas.length; i++) {
                                            achou = false;
                                            for(var j = 0; j < $scope.enturmacoes.length; j++) {
                                                if($scope.etapas[i].id === $scope.enturmacoes[j].turma.etapa.id) {
                                                    achou = true;
                                                }
                                                if(achou) {
                                                    $scope.etapas.splice(i, 1);
                                                }
                                            }
                                        }
                                        setTimeout(function() {
                                            $('#enturmacaoEtapa').material_select();
                                            $scope.requisicoes--;
                                        }, 500);
                                    });
                                }
                                if (!$scope.enturmacoes.length || $scope.verificaCadastroEnturmacoes($scope.enturmacoes)) {
                                    Servidor.customToast('Este aluno não possui enturmações.');
                                    $scope.enturmacao = {
                                        matricula: {id: $scope.matricula.id},
                                        etapa: {id: null},
                                        turma: {id: null}
                                    };
                                    if (disciplinasAtivas.length) {
                                        $scope.requisicoes++;
                                        var promise = Servidor.buscarUm('disciplinas', disciplinasAtivas[0].disciplina.id);
                                        promise.then(function(response) {
                                            $scope.etapa = response.data.etapa;
                                            if($scope.matricula.curso.especializado) {
                                                var promise = Servidor.buscar('turmas', {etapa: $scope.etapa.id, unidadeEnsino: $scope.matricula.unidadeEnsino.id});
                                                promise.then(function(response) {                                                    
                                                    $scope.turmas = response.data;
                                                    $timeout(function() { $scope.requisicoes--; $('#enturmacaoTurma').material_select(); }, 500);
                                                });
                                            } else {
                                                $scope.requisicoes--;
                                                $scope.turmasCompativeis(null, disciplinasAtivas);
                                            }                                        
                                        });
                                    } else {
                                        Servidor.customToast('Este aluno não está cursando nenhuma disciplina.');
                                    }
                                }
                                $scope.mostraEnturmacoes = true; $scope.fechaProgresso();
                                $timeout(function() { $('.collapsible').collapsible({ accordion : false }); $('.tooltipped').tooltip({delay:50}); }, 250);
                            }
                        });  */                          
                    });
                    
                    $scope.camposNovaEtapa = false;
                    $scope.mostraDisciplinas = true;
                    var promise = Servidor.buscar('etapas-ofertadas', {'curso': $scope.matricula.curso.id, 'unidadeEnsino': $scope.matricula.unidadeEnsino.id});
                    promise.then(function (response) {
                        var etapas = response.data;
                        $scope.possiveisEtapas = response.data;
                        $timeout(function() { $('select').material_select(); }, 50);
                        $scope.etapas = etapas;
                        $scope.requisicoes = 0;
                        /*etapas.forEach(function(e) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id, etapa: e.id});
                            promise.then(function(response) {
                                if (response.data.length) {
                                    e.disciplinasCursadas = response.data;
                                    var promise = Servidor.buscarUm('etapas', e.id);
                                    promise.then(function(response) {
                                        response.data.disciplinasCursadas = e.disciplinasCursadas;
                                        $scope.etapas.push(response.data);
                                        if (--$scope.requisicoes === 0) {
                                            $scope.camposNovaEtapa = $scope.verificaCadastroDisciplinas($scope.etapas);
                                            $timeout(function(){ $('.collapsible').collapsible({ accordion : false });}, 50);
                                        }
                                    });
                                } else {
                                    $scope.requisicoes--;
                                }
                            });
                        });*/
                    });
                break
                case 'disciplinas':
                    $scope.camposNovaEtapa = false;
                    $scope.mostraDisciplinas = true;
                    var promise = Servidor.buscar('etapas-ofertadas', {'curso': $scope.matricula.curso.id, 'unidadeEnsino': $scope.matricula.unidadeEnsino.id});
                    promise.then(function (response) {
                        var etapas = response.data;
                        $scope.possiveisEtapas = response.data;
                        $timeout(function() { $('select').material_select(); }, 50);
                        $scope.etapas = etapas;
                        $scope.requisicoes = 0;
                        etapas.forEach(function(e) {
                            $scope.requisicoes++;
                            var promise = Servidor.buscar('disciplinas-cursadas', {matricula: $scope.matricula.id, etapa: e.id});
                            promise.then(function(response) {
                                if (response.data.length) {
                                    e.disciplinasCursadas = response.data;
                                    var promise = Servidor.buscarUm('etapas', e.id);
                                    promise.then(function(response) {
                                        response.data.disciplinasCursadas = e.disciplinasCursadas;
                                        $scope.etapas.push(response.data);
                                        if (--$scope.requisicoes === 0) {
                                            //$scope.camposNovaEtapa = $scope.verificaCadastroDisciplinas($scope.etapas);
                                            $timeout(function(){ $('.collapsible').collapsible({ accordion : false });}, 50);
                                        }
                                    });
                                } else {
                                    $scope.requisicoes--;
                                }
                            });
                        });
                    });
                break
                case 'pdf':
                    $scope.dataAtual();
//                    $scope.vinculo();
                    if ($scope.matricula.vinculo === undefined || !$scope.matricula.vinculo) {
                        $scope.carregarDadosPdf();
                    } else {
                        $scope.atestado = true;
                    }
                break
            }
        };
        
        // Verifica a possibilidade de cadastrar novas disciplinas
        $scope.verificaCadastroDisciplinas = function(etapas) {
            var retorno = true;
            etapas.forEach(function(e) {
                e.disciplinasCursadas.forEach(function(dc) {
                    if(dc.status === "CURSANDO") {
                        retorno = false;
                    }
                });
            });
            $scope.fechaProgresso();
            return retorno;
        };
        
        $scope.verificaCadastroEnturmacoes = function(enturmacoes) {
            var retorno = true;
            enturmacoes.forEach(function(e) {
                if(!e.encerrado) {
                    retorno = false;
                }
            });
            $scope.fechaProgresso();
            return retorno;
        };

        $scope.buscarMediasFrequenciasAluno = function(index) {
            var promise = Servidor.buscarUm('enturmacoes',$scope.enturmacoes[index].id);
            promise.then(function(response){
                var botao = $('#btn-ent'+$scope.enturmacoes[index].id);
                if (botao.text() === "keyboard_arrow_down") {
                    botao.text("keyboard_arrow_up");
                } else {
                    botao.text("keyboard_arrow_down");
                }
                if ($scope.enturmacoes[index].matricula.disciplinas !== undefined) { return; }
                $scope.requisicoes++;
                var promise = Servidor.buscar('disciplinas-cursadas', {enturmacao: $scope.enturmacoes[index].id});
                promise.then(function(response) {
                    $scope.requisicoes--;
                    $scope.enturmacoes[index].matricula.disciplinas = response.data;
                    $scope.enturmacoes[index].matricula.disciplinas.forEach(function(cursada) {
                        $scope.requisicoes++;
                        if (cursada.mediaPreliminar === undefined) {
                            cursada.mediaPreliminar = 'ND';
                        }
                        var promise = Servidor.buscar('medias', {disciplinaCursada: cursada.id});
                        promise.then(function(response) {
                            cursada.medias = response.data;
                            $scope.requisicoes--;
                            $scope.fechaProgresso();
                        });
                        $scope.requisicoes++;
                        $scope.fechaProgresso();
                        /*var promise = Servidor.buscar('frequencias', {disciplina: cursada.id});
                        promise.then(function(response) {                        
                            cursada.faltas = 0;
                            var frequencias = response.data;
                            frequencias.forEach(function(frequencia) {
                                if (frequencia.status === 'FALTA') {
                                    cursada.faltas++;
                                }
                            });
                            $scope.requisicoes--;
                            $scope.fechaProgresso();
                        });*/
                    });
                    $scope.fechaProgresso();
                });
            });
        };

        $scope.buscarInformacoesDisciplina = function(indice) {
            var botao = $('#btn-etapa'+$scope.etapas[indice].id);
            if (botao.text() === "keyboard_arrow_down") {
                botao.text("keyboard_arrow_up");
            } else {
                botao.text("keyboard_arrow_down");
            }
            if ($scope.etapas[indice].disciplinasCursadas !== undefined) {
                $scope.etapas[indice].disciplinasCursadas.forEach(function(cursada) {
                    //if (cursada.porcentagem === undefined) {
                        var promise = Servidor.buscarUm('disciplinas-cursadas', cursada.id);
                        promise.then(function(response) {
                            Servidor.buscar('medias', {disciplinaCursada: cursada.id}).then(function(response) {
                                cursada.medias = response.data;
                            });
                            /*if(response.data.enturmacao !== undefined) {
                                cursada.enturmacao = response.data.enturmacao;
                                Servidor.buscar('frequencias', {disciplina: cursada.id}).then(function(response) {
                                    cursada.faltas = 0;
                                    cursada.presencas = 0;
                                    var frequencias = response.data;
                                    frequencias.forEach(function(frequencia) {
                                        if(frequencia.status === 'PRESENCA') {
                                            cursada.presencas++;
                                        }
                                    });
                                    if (cursada.enturmacao.turma !== undefined && cursada.disciplinaOfertada !== undefined) {
                                        promise = Servidor.buscar('turmas/'+cursada.enturmacao.turma.id+'/aulas', {disciplina: cursada.disciplinaOfertada.id});
                                        promise.then(function(response) {
                                            var aulas = response.data.length;
                                            if (aulas) {
                                                cursada.porcentagem = ((cursada.presencas * 100) / aulas)+'%';
                                                if(cursada.porcentagem.length > 5) {
                                                    cursada.porcentagem = cursada.porcentagem.slice(0, 5)+'%';
                                                }
                                            } else {
                                                cursada.porcentagem = "ND";
                                            }
                                            $scope.fechaProgresso();
                                        });
                                    }
                                });
                            } else {
                                cursada.porcentagem = "ND";
                            }*/
                        });
                    //}
                });
            }
        };

        $scope.buscarSistemaAvaliacao = function(etapa) {
            $scope.requisicoes++;
            var promise = Servidor.buscarUm('etapas', etapa.id);
            promise.then(function(response) {                
                if (response.data.sistemaAvaliacao.tipo === 'QUALITATIVO') {
                    $scope.sistemaAvaliacao = 'qualitativas';
                } else {
                    $scope.sistemaAvaliacao = 'quantitativas';
                }
                $scope.requisicoes--;
                $scope.fechaProgresso();
            });
            $scope.fechaProgresso();
        };
        
        $scope.reativarMatricula = function(matricula) {
            matricula.status = 'CURSANDO';
            $scope.mostraProgresso();
            var promise = Servidor.finalizar(matricula, 'matriculas', null);
            promise.then(function(response) {
                Servidor.customToast('Matrícula reativada com sucesso.');
                $scope.fechaProgresso();
            });
        };

        $scope.reativarEnturmacao = function(enturmacao, indice) {
            $scope.mostraProgresso();
            $scope.requisicoes = 0;
            var promise = Servidor.buscarUm('matriculas', enturmacao.matricula.id);
            promise.then(function(response) {
                var matricula = response.data;
                matricula.status = 'CURSANDO';
                matricula.aluno = {id: response.data.aluno.id};
                matricula.unidadeEnsino = {id: response.data.unidadeEnsino.id};
                var promise = Servidor.finalizar(matricula, 'matriculas', null);
                promise.then(function() {
                    $scope.enturmacoes[indice].matricula.status = 'CURSANDO';
                    enturmacao.encerrado = false;
                    var promise = Servidor.finalizar(enturmacao, 'enturmacoes', 'Enturmação');
                    promise.then(function() {
                        $scope.enturmacoes[indice].encerrado = false;
                        enturmacao.matricula.disciplinas.forEach(function(disciplina) {
                            disciplina.status = 'CURSANDO';
                            $scope.requisicoes++;
                            var promise = Servidor.finalizar(disciplina, 'disciplinas-cursadas', null);
                            promise.then(function() {
                                if (--$scope.requisicoes === 0) {
                                    $scope.fechaProgresso();
                                }
                            });
                        });
                    });
                });
            });
        };

        $scope.carregarDadosPdf = function() {
            var promise = Servidor.buscarUm('unidades-ensino', $scope.matricula.unidadeEnsino.id);
            promise.then(function(response) {
                $scope.matricula.unidadeEnsino = response.data;
                promise = Servidor.buscar('telefones', {pessoa: response.data.id});
                promise.then(function(response) {
                    $scope.matricula.unidadeEnsino.telefones = response.data;
                    promise = Servidor.buscar('enturmacoes', {matricula: $scope.matricula.id});
                    promise.then(function(response) {
                        if(response.data.length) {
                            $scope.matricula.etapaAtual = response.data[0].turma.etapa;
                        } else {
                            $scope.matricula.etapaAtual = 'ND';
                        }
                        promise = Servidor.buscarUm('vinculos', sessionStorage.getItem('vinculo'));
                        promise.then(function(response){
                            if ($scope.matricula.codigo === undefined) { $scope.matricula.codigo = '0000000000'; }
                            $scope.matricula.vinculo = response.data;
                            $scope.fechaProgresso();
                            $scope.atestado = true;
                        });
                    });
                });
            });
        };

        /*Busca transferencias*/
        $scope.buscarTransferencias = function () {
            $scope.mostraProgresso();
            $scope.movimentacoes = [];
            var promise = Servidor.buscar('transferencias', {'matricula': $scope.matricula.id, 'status': $scope.statusTransferencia});
            promise.then(function (response) {
                if (response.data.length > 0) {
                    response.data.forEach(function (m) {
                        var promise = Servidor.buscarUm('transferencias', m.id);
                        promise.then(function (response) {
                            var objetoTransferencia = {
                                'id': m.id,
                                'tipo': 'TRANSFERÊNCIA DE UNIDADE',
                                'data': response.data.dataEncerramento,
                                'origem': m.unidadeEnsinoOrigem.nome,
                                'destino': m.unidadeEnsinoDestino.nome,
                                'justificativa': response.data.justificativa
                            };
                            $scope.movimentacoes.push(objetoTransferencia); $scope.fechaProgresso();
                            $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75);
                        });
                    });
                } else {
                    Servidor.customToast('Nenhuma transfência registrada');
                }
            });
            $scope.fechaProgresso();
        };

        /*Busca todas as Movimentacoes da matricula*/
        $scope.buscarTodasMovimentacoes = function () {
            $scope.mostraProgresso();
            var promise = Servidor.buscar('transferencias', {'matricula': $scope.matricula.id, 'status': $scope.statusTransferencia});
            promise.then(function (response) {
                if (response.data.length > 0) {
                    response.data.forEach(function (m) {
                        var promise = Servidor.buscarUm('transferencias', m.id);
                        promise.then(function (response) {
                            var objetoTransferencia = {
                                'id': m.id,
                                'tipo': 'TRANSFERÊNCIA DE UNIDADE',
                                'data': response.data.dataEncerramento,
                                'origem': m.unidadeEnsinoOrigem.nome,
                                'destino': m.unidadeEnsinoDestino.nome,
                                'justificativa': response.data.justificativa
                            };
                            $scope.movimentacoes.push(objetoTransferencia);
                            $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75);
                        });
                    });
                }
                var promise = Servidor.buscar('movimentacoes-turma', {'matricula': $scope.matricula.id});
                promise.then(function (response) {
                    if (response.data.length > 0) {
                        response.data.forEach(function (m) {
                            var promise = Servidor.buscarUm('movimentacoes-turma', m.id);
                            promise.then(function (response) {
                                var objetoMovimentacaoTurma = {
                                    'id': m.id,
                                    'tipo': 'MOVIMENTAÇÃO DE TURMA',
                                    'data': response.data.dataCadastro,
                                    'origem': 'turma ' + response.data.enturmacaoOrigem.turma.nome,
                                    'destino': 'turma ' + response.data.enturmacaoDestino.turma.nome,
                                    'justificativa': response.data.justificativa
                                };
                                $scope.movimentacoes.push(objetoMovimentacaoTurma);
                                $scope.fechaProgresso();
                                $timeout(function(){ $('.tooltipped').tooltip({delay: 50});},75);
                            });
                        });
                    }
                });
            });
            $scope.fechaProgresso();
        };

        /*Fecha Modal aberto*/
        $scope.fecharModal = function () {
            $('.lean-overlay').hide();
            $('.modal').closeModal();
            $scope.fechaProgresso();
        };

        /*abrir modal para removar*/
        $scope.preparaRemover = function () {
            $scope.matriculaRemover = matricula;
            $scope.index = index;
            $scope.fechaProgresso();
        };

        $scope.prepararRemoverEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); $scope.mostraLoader();
            $('#collapsible-enturmacao-'+enturmacao.id).collapsible();
            var promise = Servidor.buscarUm('enturmacoes', enturmacao.id);
            promise.then(function(response) {
                $scope.enturmacao = enturmacao;
                $('#remover-enturmacao-modal').openModal();
                $scope.fechaProgresso(); $scope.fechaLoader();
            });
        };
        
        $scope.removerEnturmacao = function(enturmacao) {
            $scope.mostraProgresso(); $scope.mostraLoader();
            Servidor.remover(enturmacao, 'Enturmação');
            $scope.enturmacoes = $scope.enturmacoes.filter(function(e) {
                return e.id !== enturmacao.id;
            });
            $scope.fechaProgresso(); $scope.fechaLoader(); $scope.fecharFormulario();
        };

        /*Remover*/
        $scope.remover = function () {
            var id = $scope.matriculaRemover;
            Servidor.remover($scope.matriculaRemover, 'Matricula'); $scope.fechaProgresso();
        };

        $scope.abrirMovimentacao = function (movimentacao) {
            $scope.mostraProgresso();
            $scope.tipo = movimentacao.tipo;
            if (movimentacao.tipo === 'TRANSFERÊNCIA DE UNIDADE') {
                var promise = Servidor.buscarUm('transferencias', movimentacao.id);
                promise.then(function (response) {
                    $scope.movimentacao = response.data;
                    $('#modal-transferecia').openModal();
                });
            } else if (movimentacao.tipo === 'DESLIGAMENTO') {
                var promise = Servidor.buscarUm('desligamentos', movimentacao.id);
                promise.then(function (response) {
                    $scope.movimentacao = response.data;
                    $('#modalMovimenta').openModal();
                });
            } else {
                var promise = Servidor.buscarUm('movimentacoes-turma', movimentacao.id);
                promise.then(function (response) {
                    $scope.movimentacao = response.data;
                    $('#modalMovimenta').openModal();
                });
            }
            $scope.fechaProgresso();
        };

        $scope.inicializar(true, true);
    }]);
})();

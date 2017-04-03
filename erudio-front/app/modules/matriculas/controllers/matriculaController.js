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
    var matriculaModule = angular.module('matriculaModule', ['matriculaDirectives', 'servidorModule', 'erudioConfig', 'elementosModule']);

    matriculaModule.service('MatriculaService', [function () {
        this.voltaMatricula = false;
        this.aluno = {};
        this.matricula = {};
        this.abrirFormulario = false;
        this.abreForm = function() { this.abrirFormulario = true; };
        this.fechaForm = function() { this.abrirFormulario = false; };
    }]);

    matriculaModule.controller('MatriculaController', ['$scope', '$filter', 'Servidor', 'Restangular', '$timeout', '$templateCache', 'PessoaService', 'MatriculaService', '$compile', 'dateTime', 'makePdf', 'ErudioConfig', 'Elementos' , '$sce', function ($scope, $filter, Servidor, Restangular, $timeout, $templateCache, PessoaService, MatriculaService, $compile, dateTime, makePdf, ErudioConfig, Elementos, $sce) {
        //VERIFICA PERMISSÕES E LIMPA CACHE
        $templateCache.removeAll(); $scope.config = ErudioConfig;
        $scope.escrita = Servidor.verificaEscrita('MATRICULA') || Servidor.verificaAdmin();
        //CARREGA TELA ATUAL
        $scope.tela = ErudioConfig.getTemplateLista('matriculas'); $scope.lista = true;
        //ATRIBUTOS
        $scope.titulo = "Matrícula e Enturmação"; $scope.buscaAvancada = false; $scope.curso = {id:null}; $scope.cursos = []; $scope.unidades = [];
        $scope.matriculaBusca = { 'aluno': null, 'status': null, 'codigo': null, 'curso': null, 'unidade': null };
        //ABRE AJUDA
        $scope.ajuda = function () { $('#modal-ajuda-matricula').modal('open'); };
        //CONTROLE DO LOADER
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        
        //SELECIONA UNIDADES
        $scope.selecionaUnidade = function (unidade, nomeUnidade) {
            if ($scope.matriculaBusca.unidade !== null) { unidade = $scope.matriculaBusca.unidade; }
            if (unidade !== null && unidade !== undefined) {
                var promise = Servidor.buscarUm('unidades-ensino',unidade);
                promise.then(function(response){
                    var unity = response.data; $scope.nomeUnidade = angular.copy(unity.nomeCompleto); $timeout(function(){ $scope.getCursos(); },500);
                    if ($scope.matriculando) { $scope.matricula.unidadeEnsino = unity; } else { $scope.matriculaBusca.unidade = unity.id; }
                });
            } else {
                var unidade = null;
                for (var i = 0; i < $scope.unidades.length; i++) { if ($scope.unidades[i].id === parseInt($scope.matriculaBusca.unidade)) { unidade = $scope.unidades[i]; } }
                $scope.matriculaBusca.unidade = unidade.id; $timeout(function(){ $scope.getCursos(); },500);
            }
        };
        
        //BUSCAR UNIDADES
        $scope.buscarUnidades = function (nomeUnidade) {
            var params = {nome: null}; var permissao = true;
            if (nomeUnidade !== undefined && nomeUnidade !== null) { params.nome = nomeUnidade; if (nomeUnidade.length > 4) { permissao = true; } else { permissao = false; } } else { params.nome = ''; }
            if(permissao) {
                var promise = null; promise = Servidor.buscar('unidades-ensino', params);
                promise.then(function (response) {
                    if ($scope.isAdmin) { $scope.unidades = response.data;
                    } else { $scope.unidades = response.data; $scope.matriculaBusca.unidade = response.data.id; }
                    $timeout(function () { $('select').material_select('destroy'); $('select').material_select(); }, 500);
                });
            }
        };
        
        //BUSCA CURSOS
        $scope.getCursos = function () {
            $scope.mostraProgresso(); var promise = Servidor.buscar('cursos-ofertados', null);
            promise.then(function (response) {
                $scope.cursos = response.data;
                $timeout(function () { $('#cursoBusca, #unidadeBusca').material_select('destroy'); $('#cursoBusca, #unidadeBusca').material_select(); $scope.fechaProgresso(); }, 500);
            });
        };
        
        //BUSCAR MATRICULAS
        $scope.buscarMatriculas = function (matricula) {
            $scope.mostraProgresso(); $scope.matriculas = [];
            if (matricula.codigo !== "" || matricula.aluno !== "" || matricula.unidade !== null || matricula.curso !== null || matricula.status !== "") {
                if(!$scope.isAdmin && (matricula.aluno || matricula.codigo)) { matricula.unidade = null; }
                var promise = Servidor.buscar('matriculas', {'codigo': matricula.codigo, 'aluno_nome': matricula.aluno, 'unidadeEnsino': matricula.unidade, 'curso': matricula.curso, 'status': matricula.status});
                promise.then(function (response) {
                    if (response.data.length === 0) { Servidor.customToast('Nenhuma Matrícula encontrada.'); $scope.fechaProgresso();
                    } else {
                        $scope.matriculas = response.data;
                        //$scope.tabela = $sce.trustAsHtml(Elementos.criarTabela($scope.matriculas,['','Código','Aluno','Curso','Status','Unidade de Ensino'],['codigo','aluno.nome','curso.nome','status','unidadeEnsino.nomeCompleto']));
                        $timeout(function () { $('.modal-trigger').modal(); $('.tooltipped').tooltip({delay: 50}); $scope.fechaProgresso(); }, 500);
                    }
                });
            } else { $scope.fechaProgresso(); Servidor.customToast('Não se pode buscar com campos vazios.'); }
        };
        
        //REINICIA BUSCA DE MATRICULAS
        $scope.reiniciarMatriculaBusca = function() {
            $scope.matriculaBusca = { 'aluno': '', 'status': '', 'codigo': '', 'curso': null, 'unidade': $scope.matriculaBusca.unidade };
            if ($scope.isAdmin) { $scope.matriculaBusca.unidade = null; } $scope.nomeUnidade = '';
            $timeout(function() { $('#unidadeBusca, #cursoBusca, #statusBusca').material_select('destroy'); $('#unidadeBusca, #cursoBusca, #statusBusca').material_select(); }, 50);
        };
        
        //INICIALIZAR
        $scope.inicializar = function () {
            //$("#matricula").hide();
            $scope.mostraProgresso(); $('.title-module').html($scope.titulo); $('.material-tooltip').remove(); $scope.buscarUnidades();
            $timeout(function () {
                $('#modal-ajuda-matricula').modal();
                //$scope.permissao = Servidor.verificarTipoAcesso('ROLE_MATRICULA');
                //$scope.getCursos();
                //$(".cpfBuscaMask").mask('000.000.000-00');
                $('select').material_select('destroy'); $('select').material_select();
                //$('ul.tabs').tabs();
                /*if (inicializaContador) {
                    $('.counter').each(function () {
                        $(this).characterCounter();
                    });
                }*/
                /*$('#novaMatricula').keydown(function (event) {
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
                });*/
                $('.dropdown').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                //$('#dataNascimento').mask('00/00/0000');
                //$('.collapsible').collapsible({ accordion: false });
                //$scope.calendario();
                $('.tooltipped').tooltip({delay: 50});
                $('.dropdown-button').dropdown({ inDuration: 300, outDuration: 225, constrain_width: true, hover: false, gutter: 45, belowOrigin: true, alignment: 'left' });
                $scope.fechaProgresso(); //Servidor.entradaPagina();
                //if (carregando) { $timeout(function() {  Servidor.inputNumero(); }, 500); }
            }, 500);
        };
        
        /*$scope.alunoService = PessoaService;
        $scope.matriculaService = MatriculaService;
        $scope.TurmaService = TurmaService;
        $scope.mostraCadastros = false;
        $scope.mostraCadastro = false;
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.unidadeAlocacao = parseInt(sessionStorage.getItem('unidade'));
        $scope.requisicoes = 0;
        
        // BUSCA ETAPAS
        $scope.buscarEtapas = function (id, verifica) {
            $scope.etapas = [];
            var promise = Servidor.buscar('etapas', {'curso': id});
            promise.then(function (response) {
                $scope.etapas = response.data;
                $timeout(function () {
                    $scope.editando = false;
                    if (verifica === 'frequencia') { $scope.matriculaDisciplina = false; $('#etapaCurso').material_select('destroy'); $('#etapaCurso').material_select();
                    } else { $('select').material_select('destroy'); $('select').material_select(); } $scope.fechaProgresso();
                }, 150);
            });
        };
        
        $scope.$watch('requisicoes', function(requisicoes) {
            if(requisicoes) {
                $scope.mostraProgresso(); $scope.mostraProgresso();
            } else {
                $scope.fechaProgresso(); $scope.fechaProgresso();
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
        
        
        $scope.turmaMatricula = {'id': null};
        $scope.pessoaBusca = {
            'nome': null, 'sobrenome': null,
            'cpf': null, 'dataNascimento': null,
            'nomeMae': null, 'codMatricula': null,
            'certidao': null, 'livro': null,
            'folha': null, 'termo': null,
            'certidaoFormatada': null
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
        $scope.cpfBusca = 'none';
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

        $scope.collapsible = function () {
            $timeout(function () { $('.collapsible').collapsible({accordion: false}); }, 50);
        };

        $scope.editandoo = false;

        // Vai para o módulo de pessoas
        
        
        $scope.matriculaContato = function () {
            var promise = Servidor.buscar('telefones', {pessoa: $scope.matricula.aluno.id});
            promise.then(function (response) {
                $scope.matricula.aluno.telefones = response.data;
            });
        };
        
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
                'aluno': '', 'status': '',
                'codigo': '', 'curso': null, 'unidade': null
            };
        };
        
        $scope.reiniciar = function () {
            $scope.matricula = {
                'codigo': '',
                'aluno': {id: null},
                'unidadeEnsino': {id: parseInt($scope.matricula.unidadeEnsino.id) },
                'curso': {id: null}
            };
        };

        
        $scope.reiniciarEnturmacao = function () {
            $scope.enturmacao = {
                'turma': {id: null}, 'matricula': {id: null}
            };
        };

        $scope.calendario = function () {
            $('.datepicker').pickadate({
                selectMonths: true, selectYears: 15,
                max: 1, labelMonthNext: 'PRÓXIMO MÊS', labelMonthPrev: 'MÊS ANTERIOR', labelMonthSelect: 'SELECIONE UM MÊS', labelYearSelect: 'SELECIONE UM ANO',
                monthsFull: ['JANEIRO', 'FEVERIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO'],
                monthsShort: ['JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ'],
                weekdaysFull: ['DOMINGO', 'SEGUNDA', 'TERÇA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO'],
                weekdaysShort: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
                weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'], today: 'HOJE', clear: 'LIMPAR', close: 'FECHAR', format: 'dd/mm/yyyy'
            });
        };

        

        
 
        $scope.converterData = function (data) {
            var arrayData = data.split('/');
            data = new Date(arrayData[2], (arrayData[1] - 1), arrayData[0]).toJSON().split('T')[0];
            return data;
        };
        
        $scope.verificaCpf = function (cpf) {
            if (cpf.length === 14) {
                cpf = cpf.split(".").join("");
                cpf = cpf.split("-").join("");
                if (!Servidor.validarCpf(cpf)) {
                    Servidor.customToast('CPF inválido');
                }
            }
        };
        
        $scope.reverteData = function (data) {
            if (data === null) {
                return data;
            } else {
                var arrayData = data.split('-');
                data = arrayData[2] + "/" + (arrayData[1]) + "/" + arrayData[0];
                return data;
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
        
        $scope.alterarPagina = function (pagina) {
            for (var i = 0; i <= $scope.qtdPaginas; i++) {
                $(".paginasLista" + parseInt(i)).removeClass('active');
                if (pagina === i) {
                    $(".paginasLista" + parseInt(i)).addClass('active');
                }
            }
        };

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
                    $('#matricula-info-aluno').modal();
                });
            });
        };
        $scope.modalAjuda = function(){
            $("#modal-ajuda").modal();
        };

        $scope.verificaDocumento = function () {
            switch ($scope.documento) {
                case 'cpf':
                    $scope.cpfBusca = '';
                    $scope.certidaoBusca = 'none';
                    $scope.certidaoAntigaBusca = 'none';
                    $scope.pessoaBusca.certidao = null;
                    $scope.pessoaBusca.termo = null;
                    $scope.pessoaBusca.livro = null;
                    $scope.pessoaBusca.folha = null;
                    break;
                case 'certidao':
                    $scope.certidaoBusca = '';
                    $scope.cpfBusca = 'none';
                    $scope.certidaoAntigaBusca = 'none';
                    $scope.pessoaBusca.cpf = null;
                    $scope.pessoaBusca.termo = null;
                    $scope.pessoaBusca.livro = null;
                    $scope.pessoaBusca.folha = null;
                    break;
                case 'certidao-antiga':
                    $scope.certidaoAntigaBusca = '';
                    $scope.cpfBusca = 'none';
                    $scope.certidaoBusca = 'none';
                    $scope.pessoaBusca.cpf = null;
                    $scope.pessoaBusca.certidao = null;
                    break;
                case 'none':
                    $scope.cpfBusca = 'none';
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
        

        
        
        $scope.enturmar = function () {
            $scope.mostraProgresso();
            if ($scope.mostraEnturmacoes || $scope.matricula.disc) {
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
            } else if (!$scope.matricula.disc) {
                $scope.fechaProgresso(); $scope.fecharFormularioEnturmacao($scope.matricula.id); $scope.fecharFormulario();
                Materialize.toast('Escolha as disciplinas que serão cursadas para poder enturmar.', 4000);
            }
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

        $scope.voltarFormulario = function () {
            $scope.editando = false;
            $scope.enturmando = false;
            $scope.matriculando = true;
            $scope.buscarMatriculas($scope.matriculaBusca);
            $scope.cadastrando = false;
            $scope.mostraAlunos = false;
            $scope.mostraEnturmacao = false;
        };

        

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
            $('#disciplina-modal-aluno').modal();
            $scope.disciplina = disciplina;
        };

        $scope.fecharModal = function () {
            $('.lean-overlay').hide();
            $('#disciplina-modal-aluno').closeModal();
        };

        $scope.disciplinasOfertadas = [];
        $scope.integral = '- Etapa não Integral';
        $scope.colocaDisciplina = [];
        $scope.listaDisciplinasCursadas = [];
        $scope.disciplinaCursada = { 'disciplina': {id: null}, 'matricula': {id: null} };
        

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
                
                $scope.turmaMatricula = {id: null};
                $('#modal-enturmar').modal();
                $timeout(function () {
                    $('#etapaCursoEnturmar').material_select('destroy');
                    $('#etapaCursoEnturmar').material_select();
                    
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

        $scope.matricularNasDisciplinas = function (matricula) {
            $('#modal-disciplinas').modal();
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
                promise = Servidor.buscar('turmas/'+$scope.matricula.enturmacoesAtivas[0].turma.id+'/aulas', {disciplina: disciplina});
                promise.then(function(response) {
                    var aulas = response.data.length;
                    var porcentagem = (presencas * 100) / aulas;
                    $scope.fechaProgresso();
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

        $scope.abrirModalTransferenciaLocal = function(matricula) {
            $scope.transferencia = {justificativa: null};
            $scope.matricula = matricula;
            $('#transferir-para-mim-modal').modal();
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
            }
        };

        $scope.abrirModalJustificativa = function (justificativa) {
           $timeout(function () {
                $('#modal-frequenciaJustificativa').modal();
            }, 100);
        };

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
                    $scope.requisicoes++;
                    
                break
                case 'disciplinas':
                    $scope.camposNovaEtapa = false;
                    $scope.mostraDisciplinas = true;
                    var promise = Servidor.buscar('etapas', {'curso': $scope.matricula.curso.id});
                    promise.then(function (response) {
                        var etapas = response.data;
                        $scope.possiveisEtapas = response.data;
                        $timeout(function() { $('select').material_select(); }, 50);
                        $scope.etapas = [];
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
                                            $scope.camposNovaEtapa = $scope.verificaCadastroDisciplinas($scope.etapas);
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

        $scope.fecharModal = function () {
            $('.lean-overlay').hide();
            $('.modal').closeModal();
            $scope.fechaProgresso();
        };

        $scope.preparaRemover = function () {
            $scope.matriculaRemover = matricula;
            $scope.index = index;
            $scope.fechaProgresso();
        };

        
        
        

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
                    $('#modal-transferecia').modal();
                });
            } else {
                var promise = Servidor.buscarUm('movimentacoes-turma', movimentacao.id);
                promise.then(function (response) {
                    $scope.movimentacao = response.data;
                    $('#modalMovimenta').modal();
                });
            }
            $scope.fechaProgresso();
        };*/
            
        $scope.inicializar();
    }]);
})();

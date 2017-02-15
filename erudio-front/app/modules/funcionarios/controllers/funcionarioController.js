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
    var funcionarioModule = angular.module('funcionarioModule', ['servidorModule', 'funcionarioDirectives', 'pessoaModule']);

    funcionarioModule.service('FuncionarioService', [function() {
        this.abrirFormulario = false;
        this.voltarFuncionarios = false;
        this.vinculo = {};
        this.abreForm = function (){
            this.abrirFormulario = true;
        };
        this.fechaForm = function() {
            this.abrirFormulario = false;
        };
    }]);

    funcionarioModule.controller('funcionarioController', ['$scope', 'Servidor', 'Restangular', 'FuncionarioService', 'PessoaService', '$timeout', '$templateCache','$compile', function ($scope, Servidor, Restangular, FuncionarioService, PessoaService, $timeout, $templateCache,$compile) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('FUNCIONARIO');         
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.vinculos = [];
        $scope.instituicoes = [];
        $scope.unidades = [];
        $scope.cargos = [];
        $scope.alocacoes = [];
        $scope.nomePessoa = '';
        $scope.totalCargaHoraria = 0;
        $scope.totalUnidadesEscolares = 0;
        $scope.FuncionarioService = FuncionarioService;
        $scope.PessoaService = PessoaService;        
        $scope.unidadeId = parseInt(sessionStorage.getItem('unidade'));
        $scope.nomeRemover = '';
        $scope.nomeUnidade = '';

        // Recebe dados da pessoa do módulo de pessoas
        $scope.$watch("FuncionarioService.voltarFuncionarios", function(query){
            if (query){
                $scope.carregar(FuncionarioService.vinculo);
            }
        });

        // Vai para o módulo de pessoas
        $scope.intraForms = function () {
            PessoaService.aluno = false;
            PessoaService.funcionario = true;
            PessoaService.abreForm();
            FuncionarioService.vinculo = $scope.vinculo;
            $('.tooltipped').tooltip('remove');
        };

        // Estruturas
        $scope.vinculo = {
            'codigo': null,
            'status': '',
            'tipoContrato': '', // EFETIVO ou TEMPORARIO
            'cargaHoraria': '', // MAXIMO 40Hrs
            'funcionario': {}, // PESSOA
            'cargo': {
                'nome': '',
                'professor': false // TRUE ou FALSE
            },
            'instituicao': null,
            'alocacao': []
        };

        $scope.vinculoAtivo = {'id': null};

        $scope.vinculoBusca = {
            'funcionario': {'nome': null, 'cpfCnpj': null},
            'status': '',
            'cargo': {'nome': null},
            'codigo': null
        };

        $scope.alocacao = {
            'cargaHoraria': '',
            'instituicao': {
                'id': null
            },
            'vinculo': {}
        };

        $scope.cargo = {
            'nome': '',
            'professor': false
        };

        // Controle de pagina
        $scope.editando = false;
        $scope.loader = false;
        $scope.progresso = false;

        // Controle da barra de progresso e loader
        $scope.mostraProgresso = function () { $scope.progresso = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; };
        $scope.mostraLoader = function () { $scope.loader = true; };
        $scope.fechaLoader = function () { $scope.loader = false; };

        // Reseta a estrutura de vinculo
        $scope.limpaVinculo = function() {
            $scope.vinculo = {
                'codigo': null,
                'status': '',
                'tipoContrato': '', // EFETIVO ou TEMPORARIO
                'cargaHoraria': '', // MAXIMO 40Hrs
                'funcionario': {}, // PESSOA
                'cargo': {
                    'nome': '',
                    'professor': '' // TRUE ou FALSE
                },
                'instituicao': null,
                'alocacao': []
            };
            $scope.vinculoBusca = {
                'funcionario': {'nome': null, 'cpfCnpj': null},
                'status': '',
                'cargo': {id: null},
                'codigo': null
            };
        };

        // Reseta a estrutura de alocacao
        $scope.limpaAlocacao = function() {
            $scope.nomeUnidade = '';
            $scope.alocacao = {
                'cargaHoraria': null,
                'instituicao': {
                    'id': null
                },
                'vinculo': {}
            };
        };

        /* Verifica se o usuario deseja descartar os dados preenchidos */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.funcionario.id && !objeto.id) {
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

        // Reseta a estrutura de cargo
        $scope.limpaCargo = function() {
            $scope.cargo = {
                'id': '',
                'nome': '',
                'professor': false
            };
        };

        // Realiza a busca de instituicoes
        $scope.buscarInstituicoes = function() {
            var promise = Servidor.buscar('instituicoes', null);
            promise.then(function(response) {
                $scope.instituicoes = response.data;
            });
        };

        // Realiza a busca de unidades
        $scope.selecionarUnidade = function(unidade) {
            $scope.alocacao.instituicao = unidade;
            $scope.nomeUnidade = unidade.tipo.sigla + ' ' + unidade.nome;
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
            var vinculo = $scope.vinculoBusca;
            if(!vinculo.funcionario.nome && !vinculo.codigo && !vinculo.status && !vinculo.cargo.id && !vinculo.funcionario.cpfCnpj){
                return false;
            }else{
                return true;
            }
        };

        // Realiza a busca de vinculos
        $scope.buscarVinculos = function (vinculo, pagina, origem) {
            if($scope.validarBusca()){
                if (pagina !== $scope.paginaAtual) {
                    if (pagina === 0) {
                        pagina = '0';
                    }
                    if (!pagina) {
                        $scope.paginaAtual = 0;
                        $(".paginasLista0").addClass('active');
                    } else {
                        $scope.paginaAtual = pagina;
                    }
                    if (origem === 'botao' && $scope.qtdPaginas || pagina === '0') {
                        for (var i = 1; i <= $scope.qtdPaginas; i++) {
                            $(".paginasLista" + parseInt(i)).remove();
                        }
                    }
                    if(vinculo.funcionario.cpfCnpj){
                        var cpf = vinculo.funcionario.cpfCnpj.split(".").join("");
                        cpf = cpf.split("-").join("");
                    }
                    $scope.mostraLoader();
                    var promise = Servidor.buscar('vinculos', {
                        'cargo': vinculo.cargo.id,
                        'status': vinculo.status,
                        'funcionario_cpf': cpf,
                        'funcionario_nome': vinculo.funcionario.nome,
                        'codigo' : vinculo.codigo
                    });
                    promise.then(function (response) {
                        if (origem === 'botao' || pagina === '') {
                            $scope.qtdPaginas = Math.ceil(response.data.length / 50);
                            for (var i = 1; i < $scope.qtdPaginas; i++) {
                                var a = '<li class="waves-effect paginasLista' + i + '" data-ng-click="alterarPagina(' + parseInt(i) + '); buscarVinculos(vinculo, ' + parseInt(i) + ');"><a href="#!">' + parseInt(i + 1) + '</a></li>';
                                $(".paginasLista" + parseInt(i - 1)).after($compile(a)($scope));
                            }
                        }
                        if(response.data.length) {
                            $scope.vinculos = response.data;
                        } else {
                            $scope.vinculos = [];
                            Servidor.customToast('Nenhum funcionário encontrado.');
                        }
                        $timeout(function () {
                            $('#btn-carregar').show();
                            window.scrollTo(0, 300);
                            $scope.fechaLoader();
                            $('.tooltipped').tooltip('remove');
                            $('.tooltipped').tooltip({delay: 50});
                        }, 300);
                    });
                };
            }else{Servidor.customToast('Preencha um campo para realizar a busca.');}
        };

        // Realiza a busca de cargos
        $scope.buscarCargos = function(){
            var promise = Servidor.buscar('cargos', null);
            promise.then(function(response) {
                $scope.cargos = response.data;
            });
        };

        // Realiza a busca de pessoas
        $scope.buscarPessoas = function(){
            if ($scope.nomePessoa.length > 2) {
                if (parseInt($scope.nomePessoa)) {
                    var params = {'cpf': $scope.nomePessoa};
                } else {
                    params = {'nome': $scope.nomePessoa};
                }
                var promise = Servidor.buscar('pessoas', params);
                promise.then(function(response) {
                    $scope.pessoas = response.data;
                });
            } else {
                $scope.pessoas = [];
            }
        };

        /* Verifica se o cpf é invalido */
        $scope.verificaCpf = function (cpf) {
            if(cpf !== undefined && cpf.length === 14){
                var newCpf = cpf.split(".").join("");
                newCpf = newCpf.split("-").join("");
                if(!Servidor.validarCpf(newCpf)){
                    Servidor.customToast('CPF inválido');
                }
            }
        };

        $scope.carregarInstituicao = function() {
            var id = parseInt($scope.vinculo.instituicao.id);
            $scope.instituicoes.forEach(function(instituicao) {
                if (id === instituicao.id) {
                    $scope.vinculo.instituicao = instituicao;
                }
            });
        };
        
        $scope.buscarUnidades = function() {
            if($scope.nomeUnidade !== undefined && $scope.nomeUnidade.length > 4) {
                var promise = Servidor.buscar('unidades-ensino', {'nome': $scope.nomeUnidade});
                promise.then(function(response) {
                    $scope.unidades = response.data;
                });
            } else {
                $scope.unidades = [];
            }
        };
        
        $scope.order = function (predicate) {            
            $scope.reverse = ($scope.predicate === predicate) ? !$scope.reverse : false;
            $scope.predicate = predicate;  
        };
        
        $scope.ativarVinculo = function(vinculo) {
            $scope.mostraLoader();
            vinculo.status = 'ATIVO';
            var promise = Servidor.finalizar(vinculo, 'vinculos', 'Vínculo');
            promise.then(function(response) {
                $scope.fechaLoader();
                $scope.vinculos.forEach(function(vinculo) {
                    if (vinculo.id === response.data.id) {
                        vinculo.status = 'ATIVO';
                    }
                });
            });
        };

        // Finaliza o vinculo
        $scope.vincular = function() {
            var vinculo = $scope.vinculo;
            if (!vinculo.cargo.id) { return Servidor.customToast('Selecione um cargo.'); }
            if (!vinculo.funcionario.id) { return Servidor.customToast('Selecione uma pessoa.'); }
            if (!vinculo.tipoContrato) { return Servidor.customToast('Selecione um tipo de contrato.'); }
            if (!vinculo.status) { return Servidor.customToast('Selecione um status.'); }
            if (vinculo.cargaHoraria >= $scope.totalCargaHoraria) {
                vinculo.instituicao = {id: $scope.vinculo.instituicao.id};
                $scope.mostraLoader();
                delete vinculo.funcionario.dataExpedicaoCertidaoNascimento;
                delete vinculo.funcionario.dataNascimento;
                var promise = Servidor.buscar('vinculos', {funcionario: vinculo.funcionario.id, status:'ATIVO'});
                promise.then(function(response) {
                    if(!response.data.length || vinculo.id) {
                        var result = Servidor.finalizar(vinculo, 'vinculos', 'Funcionario');
                        result.then(function(response) {
                            vinculo = response.data;
                            if (!$scope.vinculo.id) {
                                $scope.vinculo = response.data;
                                $timeout(function() {
                                    $('ul.tabs').tabs('select_tab', 'tabAlocacao');
                                    $scope.fechaLoader();
                                }, 250);                        
                            } else {
                                if($scope.alocacao.cargaHoraria && $scope.alocacao.instituicao.id) {
                                    if(!$scope.prepararSalvarAlocacao()) {
                                        return $scope.fechaLoader();
                                    }
                                }
                                $scope.fecharFormulario();
                                $scope.limpaVinculo();
                            }                    
                        });
                    } else {
                        $scope.fechaLoader();
                        Servidor.customToast('Esta pessoa já possui um vinculo ativo');
                    }
                });                    
            } else {
                Materialize.toast('Carga horária inválida.', 2000);                
            }
        };

        // Volta para a pagina de busca
        $scope.fecharFormulario = function() {
            $scope.mostraLoader();
            $scope.editando = false;
            $scope.nomePessoa = '';
            $timeout(function(){
                $scope.fechaLoader();
            },300);
        };

        // Prepara o formulario
        $scope.carregar = function(vinculo) {
            $scope.mostraLoader();
            $scope.totalCargaHoraria = 0;
            $scope.totalUnidadesEscolares = 0;
            $scope.alocacoes = [];
            $scope.limpaAlocacao();            
            if (vinculo) {
                $scope.vinculo = angular.copy(vinculo);
                if(!$scope.isAdmin) {
                    $scope.alocacao.instituicao = $scope.unidade;
                    $scope.nomeUnidade = $scope.unidade.nomeCompleto;
                }
                if (vinculo.id) {
                    var promise = Servidor.buscar('alocacoes', {'vinculo': $scope.vinculo.id});
                    promise.then(function(response) {
                        $scope.alocacoes = response.data;
                        if (response.data.length) {
                            $scope.totalCargaHoraria = $scope.verificarCargaHoraria(0);
                            $scope.totalUnidadesEscolares = $scope.quantidadeDeUnidades();
                            $timeout(function(){ 
                                $('.tooltipped').tooltip('remove'); 
                                $('.tooltipped').tooltip({delay: 50});                                 
                            }, 50);
                            $scope.prepararFormulario();
                        }
                    });
                }
            } else {
                $scope.vinculo.funcionario = {id: null, nome: ''};
                $scope.limpaVinculo();
                $scope.vinculo.status = 'ATIVO';
                $scope.vinculo.instituicao = $scope.instituicoes[0];
                $scope.prepararFormulario();
            }
        };
        
        $scope.prepararFormulario = function() {
            $timeout(function() {
                $scope.editando = true;
                $('ul.tabs').tabs();
                $('select').material_select('destroy');
                $('select').material_select();                
                Servidor.inputNumero();
                Servidor.verificaLabels();
                $scope.fechaLoader();                
                $('#unidadeAlocacao, #cargosForm, #funcionario').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true,
                        hover: false,
                        gutter: 45,
                        belowOrigin: true,
                        alignment: 'left'
                    }
                );
                $timeout(function() { $('ul.tabs').tabs('select_tab', 'tabVinculo'); }, 250);
            }, 500);
        };

        // Liga um cargo ao vinculo
        $scope.carregarCargo = function(cargo){
            $scope.vinculoBusca.cargo = cargo;
            $scope.vinculo.cargo = cargo;
            $timeout(function() {
                Servidor.verificaLabels();
            }, 100);
        };

        // Liga uma pessoa ao vinculo
        $scope.carregarFuncionario = function(pessoa) {
            if(pessoa.cpfCnpj !== undefined && pessoa.cpfCnpj) {
                var promise = Servidor.buscar('vinculos', {'funcionario': pessoa.id, 'status': 'ATIVO'});
                promise.then(function(response) {
                    if (response.data.length) {
                        $scope.vinculoAtivo = response.data[0];
                        $scope.nomePessoa = '';
                        Servidor.customToast(pessoa.nome.split(' ')[0] + ' já possui um vínculo ativo.');
                    } else {
                        $scope.nomePessoa = pessoa.nome;
                        $scope.vinculo.funcionario = pessoa;
                        $timeout(function() {
                            Servidor.verificaLabels();
                        },100);
                    }                    
                });
            } else {
                $scope.nomePessoa = "";
                return Servidor.customToast(pessoa.nome.split(' ')[0] + ' não possui cpf.');
            }
        };

        // Reseta os parametros de busca
        $scope.limparBusca = function () {
            $scope.limpaVinculo();
            $scope.limpaCargo();
            $scope.vinculos = [];
            $timeout(function() {
               $('#statusBusca').material_select();
               $('#cargoVinculoBusca').material_select();
            },250);
        };

        // Fecha o modal de exclusao
        $scope.fecharModal = function (){
            $('#funcionarioModel, #removerVinculo').closeModal();
            $('.lean-overlay').remove();           
            $scope.alocacao.cargaHoraria = null ;
        };

        /* Carrega o calendario */
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
                weekdayShort: ['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SAB'],
                weekdaysLetter: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
                today: 'HOJE',
                clear: 'LIMPAR',
                close: 'FECHAR',
                format: 'dd/mm/yyyy'
            });
        };

        $scope.verificaCargaHorariaAlocacoes = function (alocacao) {
            if($scope.alocacoes.length){
                var qtdHorasAlocadas = 0;
                $scope.alocacoes.forEach(function(a, index){
                    qtdHorasAlocadas =+ qtdHorasAlocadas + a.cargaHoraria;
                    if(index === $scope.alocacoes.length-1){
                        if(qtdHorasAlocadas === $scope.vinculo.cargaHoraria){
                            Servidor.customToast('Carga horária completa.');
                            $scope.abrirAlocacao = false;
                        }else{
                            $scope.abrirFormAlocacao(alocacao);
                        }
                    }
                });
            }else{
                $scope.abrirFormAlocacao();
            }
        };

        // Fecha o formulario de alocacao
        $scope.fecharFormAlocacao = function () {
            $scope.abrirAlocacao = false;
        };

        // Conta em quantas unidades diferentes a pessoa está alocada
        $scope.quantidadeDeUnidades = function() {
            var unidades = [];
            var igual;
            for (var i = 0; i < $scope.alocacoes.length; i++) {
                igual = false;
                for (var j = 0; j < unidades.length; j++) {
                    if ($scope.alocacoes[i].instituicao.id === unidades[j].id) {
                        igual = true;
                    }
                }
                if (!igual) { unidades.push($scope.alocacoes[i].instituicao); }
            }
            return unidades.length;
        };

        // Prepara para remover e abre o modal
        $scope.prepararRemover = function (vinculo) {
            $scope.vinculoRemover = vinculo;
            if (vinculo.status !== 'DESLIGADO') {
                if (vinculo.funcionario.genero === 'M') { var artigo = 'o'; } else { artigo = 'a'; }
                $('.remove-content').html('O vínculo d'+artigo+' <strong>' + vinculo.funcionario.nome.split(' ')[0].toUpperCase() + '</strong> atualmente está <strong>'+vinculo.status +'</strong>, deseja realmente desligar este vinculo?');
                var nome = $scope.vinculoRemover.funcionario.nome.split(' ');
                $scope.nomeRemover = nome[0] + ' ' + nome[1];
                $('#removerVinculo').openModal();
            } else {
                Materialize.toast('Este vínculo já está desligado.');
            }
        };

        // Exclui um vinculo
        $scope.removerVinculo = function(){
            $scope.vinculoRemover.status  = 'DESLIGADO';
            var promise = Servidor.finalizar($scope.vinculoRemover,'vinculo','');
            promise.then(function(response){
                if(response.data) {
                    Materialize.toast('Vínculo desligado com sucesso.', 2500);
                }
                $scope.buscarVinculos();
            });
        };
        
        $scope.prepararSalvarAlocacao = function() {
            if($scope.vinculo.cargaHoraria) {
                if($scope.alocacao.cargaHoraria && $scope.alocacao.instituicao.id) {                    
                    var limite = $scope.vinculo.cargaHoraria;                
                    var soma = $scope.verificarCargaHoraria($scope.alocacao.cargaHoraria);
                    if (soma <= limite) {
                        $scope.salvarAlocacao();
                        return true;
                    } else {
                        Servidor.customToast('Carga horária ultrapassou o limite.');
                        return false;
                    }         
                } else {
                    Servidor.customToast('Preencha os campos obrigatórios.');
                    return false;
                }
            } else {
                Servidor.customToast('Vínculo não possui carga horária.');
            }            
        };

        // Salva a alocacao
        $scope.salvarAlocacao = function () {
            if ($scope.vinculo.id) {
                $scope.alocacao.vinculo = {id: $scope.vinculo.id };               
                var promise = Servidor.finalizar($scope.alocacao, 'alocacoes', 'Alocação');
                promise.then(function(response) {                    
                    $scope.totalCargaHoraria = $scope.verificarCargaHoraria(response.data.cargaHoraria);
                    $scope.alocacoes.push(response.data);                        
                    if($scope.isAdmin) {
                        $scope.limpaAlocacao();
                    } else {
                        $scope.alocacao.instituicao = $scope.unidade;
                        $scope.alocacao.cargaHoraria = null;
                    }
                    $timeout(function() { $('#btn-remover-alocacao').tooltip({delay: 50}); }, 100);
                });
            } else if (!$scope.alocacao.id) {
                var promise = Servidor.buscarUm('unidades-ensino', $scope.alocacao.instituicao.id);
                promise.then(function (response) {
                    $scope.alocacao.instituicao = response.data;
                    $scope.totalCargaHoraria = $scope.verificarCargaHoraria($scope.alocacao.cargaHoraria);
                    $scope.alocacoes.push($scope.alocacao);                        
                    $scope.limpaAlocacao();
                    $timeout(function() { $('#btn-remover-alocacao').tooltip({delay: 50}); }, 100);
                });
            }
        };

        // Soma as cargas horarias de cada alocacao
        $scope.verificarCargaHoraria = function (cargaHoraria){
            for(var i = 0 ; i < $scope.alocacoes.length; i++){
                cargaHoraria += $scope.alocacoes[i].cargaHoraria;
            }
            return cargaHoraria;
        };

        // Realiza a busca de alocacoes
        $scope.buscarAlocacoes = function() {
            var promise = Servidor.buscar('alocacoes', {'vinculo': $scope.vinculo.id});
            promise.then(function(response) {
                $scope.alocacoes = response.data;
                $scope.totalCargaHoraria = $scope.verificarCargaHoraria(0);
                $scope.totalUnidadesEscolares = $scope.quantidadeDeUnidades();
                $timeout(function(){
                $('.tooltipped').tooltip('remove'); $('.tooltipped').tooltip({delay: 50});
            }, 150);
            });
        };

        // Exclui uma alocacao
        $scope.excluirAlocacao = function(alocacao){
            if (alocacao.id) {
                Servidor.remover(alocacao, 'Alocação');
                $timeout(function(){
                    $scope.totalCargaHoraria = $scope.verificarCargaHoraria(-alocacao.cargaHoraria);
                    $scope.buscarAlocacoes();
                    $('.tooltipped').tooltip('remove'); $('.tooltipped').tooltip({delay: 50});
                },250);
            } else {
                for (var i = 0 ; i < $scope.alocacoes.length;i++){
                    if ($scope.alocacoes[i].cargaHoraria === alocacao.cargaHoraria && alocacao.instituicao.id === $scope.alocacoes[i].instituicao.id){
                        $scope.alocacoes.splice (i,1);
                        $scope.totalCargaHoraria = $scope.verificarCargaHoraria(0);
                        $scope.totalUnidadesEscolares = $scope.quantidadeDeUnidades();
                        Materialize.toast("Alocação deletada com sucesso!" ,1000);
                    }
                }
            }
        };

        $scope.inicializar = function(){    
            $scope.permissao = Servidor.verificarTipoAcesso('ROLE_FUNCIONARIO');
            $timeout(function(){
                $('.tooltipped').tooltip('remove'); $('.tooltipped').tooltip({delay: 50});
                Servidor.verificaLabels();
                $('#cargos').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true, // Does not change width of dropdown to that of the activator
                        hover: false, // Activate on hover
                        gutter: 45, // Spacing from edge
                        belowOrigin: true, // Displays dropdown below the button
                        alignment: 'left' // Displays dropdown with edge aligned to the left of button
                    }
                );
                $('.cpfMask').mask('000.000.000-09');
                $('.counter').each(function () { $(this).characterCounter(); });                
                $('.modal-trigger').leanModal({
                    dismissible: true, // Modal can be dismissed by clicking outside of the modal
                    opacity: .5, // Opacity of modal background
                    in_duration: 300, // Transition in duration
                    out_duration: 200 // Transition out duration
                });                
                $scope.calendario();
                $('ul.tabs').tabs();
                // Inicializando Selects
                $('select').material_select('destroy');
                $('select').material_select();                
                Servidor.inputNumero();
                Servidor.entradaPagina();
                if(!$scope.isAdmin) {
                    var promise = Servidor.buscarUm('unidades-ensino', sessionStorage.getItem('unidade'));
                    promise.then(function(response) {
                        $scope.unidade = response.data;
                    });
                }
            }, 500);
        };
        $scope.buscarCargos();
        $scope.buscarInstituicoes();
        $scope.inicializar();
    }]);
})();

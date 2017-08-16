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
    var pessoaModule = angular.module('pessoaModule', ['servidorModule', 'pessoaDirectives']);

    pessoaModule.service('PessoaService', [function () {
        this.abrirFormulario = false;
        this.aluno = false;
        this.funcionario = false;
        this.abreForm = function () {
            this.abrirFormulario = true;
        };
        this.fechaForm = function() {
            this.abrirFormulario = false;
        };
    }]);

    pessoaModule.controller('pessoaController', ['$scope', 'Servidor', 'Restangular', 'PessoaService', 'FuncionarioService', 'MatriculaService', '$timeout', '$templateCache', '$compile', 'dateTime', function ($scope, Servidor, Restangular, PessoaService, FuncionarioService, MatriculaService, $timeout, $templateCache, $compile, dateTime) {
            $templateCache.removeAll();
            
            $scope.inicializando = false;
            $scope.escrita = Servidor.verificaEscrita('PESSOA');
            $scope.isAdmin = Servidor.verificaAdmin();
            $scope.$watch("PessoaService", function (query) {                
                if (PessoaService.aluno) {
                    $scope.voltarMatricula = true;
                } else if (PessoaService.funcionario) {
                    $scope.voltarFuncionario = true;
                }
                if (PessoaService.abrirFormulario) {
                    $('.tooltipped').tooltip('remove');
                    if (!$scope.inicializando) {
                        $scope.inicializar(false, true);
                    }
                    $scope.cadastrarPessoa();
                    $('.tooltipped').tooltip('remove');
                }
            });

            $scope.paginaAtual = 1;
            $scope.quantidadePaginas = 0;
            $scope.responsavelPM = true;
            $scope.outroResponsavel = false;
            $scope.estados = [];
            $scope.cidades = [];
            $scope.telefones = [];
            $scope.estadosCivis = [];
            $scope.particularidades = [];
            $scope.necessidadesEspeciais = [];
            $scope.particularidadesPessoa = [];
            $scope.estadoId = null;
            $scope.cidadeId = null;
            $scope.posicaoParti = 0;
            $scope.PessoaService = PessoaService;
            $scope.FuncionarioService = FuncionarioService;
            $scope.alunoService = MatriculaService;
            $scope.loader = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO CIRCULAR
            $scope.progresso = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO EM LINHAal
            $scope.cortina = false; // VARIAVEL QUE MOSTRA OU ESCONDE A DIV ESCURA TRANSLUCIDA PARA EVITAR ITERAÇÃO COM O USUARIO DURANTE AS CHAMADAS.
            $scope.mostraProgresso = function () {
                $scope.progresso = true;
                $scope.cortina = true;
            }; // CONTROLE DA BARRA DE PROGRESSO
            $scope.fechaProgresso = function () {
                $scope.progresso = false;
                $scope.cortina = false;
            }; // CONTROLE DA BARRA DE PROGRESSO
            $scope.mostraLoader = function (cortina) {
                $scope.loader = true;
                if (cortina) {
                    $scope.cortina = true;
                }
            }; // CONTROLE DO PROGRESSO CIRCULAR
            $scope.fechaLoader = function () {
                $scope.loader = false;
                $scope.cortina = false;
            }; // CONTROLE DO PROGRESSO CIRCULAR

            $scope.voltarMatriculas = function () {
                $('.tooltipped').tooltip('remove');
                $scope.alunoService.aluno = $scope.pessoa;
                PessoaService.abrirFormulario = false;
                $scope.alunoService.voltaMatricula = true;
                $scope.voltarMatricula = false;
                PessoaService.aluno = false;
            };

            $scope.voltarFuncionarios = function () {
                FuncionarioService.vinculo.funcionario = $scope.pessoa;
                PessoaService.abrirFormulario = false;
                FuncionarioService.voltarFuncionarios = true;
                $scope.voltarFuncionario = false;
                PessoaService.funcionario = false;
                $('.tooltipped').tooltip('remove');
            };

            /*Controle de etapas do cadastro*/
            $scope.dados = false;
            $scope.pessoaParti = false;
            $scope.pessoaDocs = false;
            $scope.pessoaEnd = false;
            $scope.pessoaContato = false;
            $scope.voltarMatricula = false;
            $scope.voltarFuncionario = false;

            /*Estrutura para busca Pessoa fisica*/
            $scope.pessoaBusca = {
                'nome': null, 'sobrenome': null,
                'cpf': null, 'dataNascimento': null,
                'nomeMae': null, 'codMatricula': null,
                'certidao': null, 'livro': null,
                'folha': null, 'termo': null,
                'certidaoFormatada': null
            };

            /*Estrutura Objeto Pessoa Fisica*/
            $scope.pessoa = {
                'nome': null, 'dataNascimento': null,
                'email': null, 'genero': null,
                'raca': null, 'estadoCivil': null,
                'particularidades': [],'necessidadesEspeciais':[], 'nomePai': null,
                'nomeMae': null, 'naturalidade': null,
                'nacionalidade': null, 'numeroRG': null,
                'dataExpedicaoRg': null, 'orgaoExpedidorRg': null,
                'cpfCnpj': null, 'certidaoNascimento': null,
                'dataExpedicaoCertidaoNascimento': null, 'codigoInep': null,
                'nis': null, 'pisPasep': null, 'endereco': null,
                'inep': null, 'cns': null
            };

            /* Estrutura de enredeco */
            $scope.endereco = {
                'logradouro': null, 'numero': null, 'bairro': null, 'complemento': null, 'cep': null,
                'cidade': {'id': null, 'nome': null, 'estado': {'id': null, 'nome': null, 'sigla': null},
                    'latitude': null, 'longitude': null
                }
            };

            /* Estrutura de Telefone */
            $scope.telefone = {
                'descricao': null, 'falarCom': null,
                'numero': null, 'pessoa': {id: null}
            };

            /* Atributos de controle da página */
            $scope.listaTelefone = false;
            $scope.editando = true;
            $scope.adicionaPessoa = false;
            $scope.mostraPessoas = false;
            $scope.cadastrando = false;
            $scope.naturalidadePessoa = 'none';
            $scope.cpfBusca = 'none';
            $scope.certidaoBusca = 'none';
            $scope.certidaoAntigaBusca = 'none';
            $scope.cpfPessoa = 'none';
            $scope.certidaoPessoa = 'none';
            $scope.certidaoAntigaPessoa = 'none';
            $scope.cadDocumento = 'certidao';
            $scope.opcaoDeEnvio = '';
            $scope.acao = '';
            $scope.documento = '';
            $scope.telefonesPessoa = 0;
            $scope.livroCad = null;
            $scope.folhaCad = null;
            $scope.termoCad = null;
            $scope.certidaoCad = null;
            $scope.dataCertidaoCad = null;
            $scope.cidades = [];
            $scope.cidadesDrop = [];
            $scope.buscaNaturalidade = [];
            $scope.pessoas = [];
            $scope.racas = [];

            /* Controle da barra de progresso/loader */
            $scope.mostraProgresso = function () {
                $scope.progresso = true;
            };
            $scope.fechaProgresso = function () {
                $scope.progresso = false;
            };
            $scope.mostraLoader = function () {
                $scope.loader = true;
            };
            $scope.fechaLoader = function () {
                $scope.loader = false;
            };

            /* Reinicia estrutura de busca*/
            $scope.reiniciarPessoaBusca = function () {
                $scope.adicionaPessoa = false;
                $timeout(function () {
                    $('select').material_select('destroy');
                    $('select').material_select();
                }, 100);
                $scope.documento = null;
                $scope.verificaDocumento();
                $scope.pessoaBusca = {
                    'nome': null, 'sobrenome': null,
                    'cpf': null, 'dataNascimento': null,
                    'dataNascimentoFormatada': null,
                    'nomeMae': null, 'certidao': null,
                    'livro': null, 'folha': null,
                    'termo': null, 'certidaoFormatada': null
                };
            };

            $scope.prepararVoltar = function (objeto) {
                if (objeto.nome && !objeto.id) {
                    $('#modal-certeza').openModal();
                } else {
                    $scope.fecharFormulario();
                }
            };

            $scope.resetaTelefone = function () {
                $scope.telefone = {
                    'descricao': null, 'falarCom': null,
                    'numero': null, 'pessoa': {'id': null}
                };
                $timeout(function () {
                    $('#descricao').material_select('destroy');
                    $('#descricao').material_select();
                }, 100);
            };

            /*Reinicis estrutura de Pessoa e Endereço*/
            $scope.reiniciarpessoa = function () {
                $scope.documento = "";
                $scope.livroCad = null;
                $scope.folhaCad = null;
                $scope.termoCad = null;
                $scope.certidaoCad = null;
                $scope.dataCertidaoCad = null;
                $scope.estadoId = null;
                $scope.cidadeId = null;
                $scope.pessoa = {
                    'nome': null, 'dataNascimento': null,
                    'email': null, 'genero': null,
                    'raca': null, 'estadoCivil': null,
                    'particularidades': [], 'nomePai': null,
                    'nomeMae': null, 'naturalidade': null,
                    'nacionalidade': null, 'numeroRG': null,
                    'dataExpedicaoRg': null, 'orgaoExpedidorRg': null,
                    'cpfCnpj': null, 'certidaoNascimento': null,
                    'dataExpedicaoCertidaoNascimento': null,
                    'codigoInep': null, 'nis': null, 'endereco': null,
                    'responsavelNome': null
                };
                $scope.endereco = {
                    'logradouro': '', 'numero': null, 'bairro': null, 'complemento': null, 'cep': null,
                    'cidade': {'id': null, 'nome': null, 'estado': {'id': null, 'nome': null, 'sigla': null},
                        'latitude': null, 'longitude': null
                    }
                };
            };
            $scope.telaNumero = 1;
            /* Responsavel ??*/

            /*Controle das telas do cadastro*/
            $scope.mudaTelaCadastro = function (tela, trocar) {
                $scope.telaNumero = tela;
                if (trocar) {
                    switch (tela) {
                        case 1:
                            $('ul.tabs').tabs('select_tab', 'tab-dados');
                        break;
                        case 2:
                            $('ul.tabs').tabs('select_tab', 'tab-contatos');
                            $("#telefone").mask("(00) 0000-00009");
                        break;
                        case 3:
                            $('ul.tabs').tabs('select_tab', 'tab-documentos');
                            $(".cpf").mask('000.000.000-00');
                            $timeout(function() {
                                $('.data').mask('00/00/0000');
                            }, 500);
                        break;
                        case 4:
                            $('ul.tabs').tabs('select_tab', 'tab-particularidades');
                        break;
                        case 5:
                            $('ul.tabs').tabs('select_tab', 'tab-necessidades');
                        break;
                    }
                }
            };

            /* Inicializando */
            $scope.inicializar = function (inicializaContador, primeiraVez) {
                $scope.inicializando = true;
                $("#telefone").mask("(00) 0000-00009");
                $scope.mostraLoader();
                $timeout(function () {
                    $("#telefone").mask("(00) 0000-00009");
                    $(".data").mask("00/00/0009");
                    $(".cpfBuscaMask").mask('000.000.000-00');
                    $(".cpf").mask('00000000000');
                    Servidor.inputNumero();
                    $('#documentoBuscar').material_select('destroy');
                    $('#documentoBuscar').material_select();
                    //$scope.buscaEstados();
                    $('.collapsible').collapsible();
                    $('.cep').mask("00000009");
                    $('.tooltipped').tooltip('remove');
                    $('.tooltipped').tooltip({delay: 50});
                    $('.dropdown').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true, // Does not change width of dropdown to that of the activator
                        hover: false, // Activate on hover
                        gutter: 45, // Spacing from edge
                        belowOrigin: true, // Displays dropdown below the button
                        alignment: 'left' // Displays dropdown with edge aligned to the left of button
                    });
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
                    $scope.buscaEstados();
                    $scope.fechaLoader();
                    if (primeiraVez) {
                        $timeout(function() {
                            Servidor.entradaPagina();
                            $('.counter').characterCounter();
                            $scope.inicializando = false;
                        }, 500);
                    }
                }, 500);
            };

            /*altera a pagina ativa*/
            $scope.alterarPagina = function (pagina) {
                for (var i = 0; i <= $scope.qtdPaginas; i++) {
                    $(".paginasLista" + parseInt(i)).removeClass('active');
                    if (pagina === i) {
                        $(".paginasLista" + parseInt(i)).addClass('active');
                    }
                }
            };

            /* Abre modal Informacoes de pessoa */
            $scope.infoPessoa = function (pessoa) {
                $('#modal-pessoa').openModal();
                var promise = Servidor.buscarUm('pessoas', pessoa.id);
                promise.then(function (response) {
                    $scope.pessoa = response.data;
                    var promise = Servidor.buscar('telefones', {'pessoa': $scope.pessoa.id});
                    promise.then(function (response) {
                        $scope.telefones = response.data;
                    });
                });
                $timeout(function () {
                    $timeout(function () {
                        //$scope.initMap(false, "info-map");
                    }, 500);
                }, 300);
            };

            /* Busca de Pessoa /paginação*/
            $scope.buscarUnidades = function (pagina) {
                if (pagina < 0) {
                    $scope.pagina = 0;
                } else {
                    $scope.pagina = pagina;
                }
                $scope.mostraProgresso();
                var promise = Servidor.buscar('pessoas', {page: $scope.pagina});
                promise.then(function (response) {
                    if (response.data.length > 0) {
                        var pessoas = response.data;
                        $scope.pessoas = pessoas;
                        if ($('#search').is(':disabled')) {
                            $('#search').attr('disabled', '');
                            $('#search').css('background', '');
                            $('#search').attr('placeholder', 'Digite aqui para buscar');
                        }

                        $timeout(function () {
                            $scope.fechaProgresso();
                        }, 200);
                    } else {
                        if ($scope.pessoas.length === 0) {
                            $('#search').attr('disabled', 'disabled');
                            $('#search').css('background', '#ccc');
                            $('#search').attr('placeholder', '');
                        }
                        $scope.pagina--;
                        $scope.fechaLoader();
                        $scope.fechaProgresso();
                    }
                });
            };

            /* Busca de Pessoa -Lista */
            $scope.buscarPessoas = function () {
                var pessoaBusca = $scope.pessoaBusca;
                if (pessoaBusca.dataNascimento && pessoaBusca.dataNascimento !== undefined) {
                    var dataNascimento = dateTime.converterDataServidor(pessoaBusca.dataNascimento, true);
                } else {
                    dataNascimento = null;
                }
                if(pessoaBusca.cpf){
                    pessoaBusca.cpf = pessoaBusca.cpf.split(".").join("");
                    pessoaBusca.cpf = pessoaBusca.cpf.split("-").join("");
                }
                $scope.mostraProgresso();
                $scope.mostraLoader();
                var promise = Servidor.buscar('pessoas', {
                    'nome': pessoaBusca.nome,
                    'sobrenome': pessoaBusca.sobrenome, 'cpf': pessoaBusca.cpf,
                    'dataNascimento': dataNascimento,
                    'nomeMae': pessoaBusca.nomeMae, 'certidaoNascimento': pessoaBusca.certidaoFormatada
                });
                promise.then(function (response) {
                    $('.tooltipped').tooltip('remove');
                    $timeout(function () {
                        $('.tooltipped').tooltip({delay: 50});
                        window.scrollTo(0, 600);
                    }, 100);
                    $scope.paginaAtual = 1;
                    $scope.fechaLoader();
                    $scope.pessoas = response.data;
                    if(!response.data.length) {
                        Servidor.customToast('Nenhuma pessoa encontrada.');
                    }
                    $scope.quantidadePaginas = Math.ceil(response.data.length/50);
                    $('.btn-add').show();
                    $scope.adicionaPessoa = true;
                    $scope.fechaProgresso();
                    $scope.fechaLoader();
                });
            };

            $scope.buscarEstadoCivil = function () {
                var promise = Servidor.buscar('estados-civis', null);
                promise.then(function (response) {
                    $scope.estadosCivis = response.data;
                    $timeout(function () {
                        $('#estadoCivil').material_select();
                    }, 150);
                });
            };

            $scope.buscaRaca = function () {
                var promise = Servidor.buscar('racas', null);
                promise.then(function (response) {
                    $scope.racas = response.data;
                    $timeout(function () {
                        $('#alunoRaca').material_select();
                    }, 150);
                });
            };

            $scope.buscarParticularidades = function () {
                var promise = Servidor.buscar('particularidades', null);
                promise.then(function (response) {
                    $scope.particularidades = response.data.plain();
                });
            };

            $scope.atualizarPagina = function(numero, substituir) {
                if (substituir) {
                    $scope.paginaAtual = numero;
                } else {
                    var soma = $scope.paginaAtual+numero;
                    if (soma > 0 && soma <= $scope.quantidadePaginas) {
                        $scope.paginaAtual = soma;
                    }
                }
            };

            /* Inicializar Mapa */
            $scope.initMap = function (comJanela, idMap) {
                var map;
                var latLng = new google.maps.LatLng($scope.pessoa.endereco.latitude, $scope.pessoa.endereco.longitude);
                var options = {zoom: 17, center: latLng};
                map = new google.maps.Map(document.getElementById(idMap), options);
                $scope.marker = new google.maps.Marker({position: latLng, title: $scope.pessoa.nome, map: map});
                var infowindow = new google.maps.InfoWindow(), marker;
                google.maps.event.addListener(map, 'click', (function (event) {
                    $scope.marker.setMap(null);
                    var newLatLng = event.latLng;
                    $scope.pessoa.endereco.latitude = newLatLng.lat();
                    $scope.pessoa.endereco.longitude = newLatLng.lng();
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    $scope.marker = new google.maps.Marker({position: newLatLng, map: map});
                    infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function (e) {
                        infowindow.open(null, null);
                    });
                    $('.gm-style').on('click', '.btn-address-close', function (e) {
                        infowindow.open(null, null);
                    });
                    return true;
                }));
                if (comJanela) {
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function (e) {
                        infowindow.open(null, null);
                    });
                    $('.gm-style').on('click', '.btn-address-close', function (e) {
                        infowindow.open(null, null);
                    });
                }
            };

            /* Buscar endereço pelas coordenadas */
            $scope.getEnderecoPorCoordenada = function () {
                $.ajax({
                    url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.pessoa.endereco.latitude + ',' + $scope.pessoa.endereco.longitude + '&sensor=false',
                    dataType: 'JSON', type: 'get',
                    success: function (data) {
                        if (data.results[0].address_components[7].long_name.length === 9) {
                            var cep = data.results[0].address_components[7].long_name.split('-');
                            $scope.pessoa.endereco.cep = cep[0] + cep[1];
                            $scope.buscaCEP($scope.pessoa.endereco.cep);
                        } else {
                            Servidor.customToast('CEP não identificado, digite o endereço manualmente.');
                        }
                    }
                });
            };

            /* Busca coordenada por endereço */
            $scope.buscaCoordenadasPorEndereco = function () {
//                if ($scope.pessoa.endereco.numero === null) {
//                    $scope.pessoa.endereco.numero = '';
//                }
//                $.ajax({
//                    url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.pessoa.endereco.logradouro + ','
//                            + $scope.pessoa.endereco.bairro + ',' + $scope.pessoa.endereco.cidade.nome + ','
//                            + $scope.pessoa.endereco.cidade.estado.nome + ',' + $scope.pessoa.endereco.numero,
//                    dataType: 'JSON', type: 'get',
//                    success: function (data) {
//                        if (data.status !== 'ZERO_RESULTS') {                            
//                            $scope.pessoa.endereco.latitude = data.results[0].geometry.location.lat;
//                            $scope.pessoa.endereco.longitude = data.results[0].geometry.location.lng;
//                            $scope.initMap(true, "mapa");
//                        } else {
//                            $scope.pessoa.endereco.latitude = -26.929647;
//                            $scope.pessoa.endereco.longitude = -48.683661;
//                            $scope.initMap(true, "mapa");
//                        }
//                    }
//                });
            };

            /* Busca coordenada por endereço digitado */
            $scope.buscaCoordenadasPorEnderecoCompleto = function () {
                if (($scope.pessoa.endereco.cep === null || $scope.pessoa.endereco.cep === '' || $scope.pessoa.endereco.cep === undefined) && ($scope.pessoa.endereco.logradouro !== null && $scope.pessoa.endereco.bairro !== null && $scope.pessoa.endereco.cidade.nome !== null && $scope.pessoa.endereco.cidade.estado.nome !== null)) {
                    $scope.buscaCoordenadasPorEndereco();
                }
            };

            /*Reverte as datas vindas dos servidor para o formatado dd/mm/aaaa*/
            $scope.reverteData = function (data) {
                if (data) {
                    var arrayData = data.split('-');
                    data = arrayData[2] + "/" + (arrayData[1]) + "/" + arrayData[0];
                    return data;
                }
                else {
                    return data;
                }
            };

            /* Busca Telefones */
            $scope.buscaTelefones = function () {
                var promise = Servidor.buscar('telefones', {'pessoa': $scope.pessoa.id});
                promise.then(function (response) {
                    $scope.telefones = response.data;
                    if ($scope.telefones.length > 0) {
                        $scope.listaTelefone = true;
                    }
                    else {
                        $scope.listaTelefone = false;
                    }
                });
            };

            $scope.carregarCidade = function (cidade) {
                $scope.pessoa.naturalidade = cidade;
            };

            /* Busca cidade naturalidade */
            $scope.buscarNaturalidade = function () {
                if ($scope.pessoa.naturalidade.nome.length >= 3) {
                    var promise = Servidor.buscar('cidades', {'nome': $scope.pessoa.naturalidade.nome});
                    promise.then(function (response) {
                        $scope.cidadesDrop = response.data;
                    });

                } else {
                    $scope.cidadesDrop = [];
                }
            };

            /* Busca estados  - SelectBox */
            $scope.buscaEstados = function () {
                Servidor.buscarEstados().getList().then(function (response) {
                    $scope.estados = response.plain();
                    $timeout(function () {
                        $('#estado').material_select('destroy');
                        $('#estado').material_select();
                    }, 500);
                });
            };

            /* Busca de Cidades - SelectBox*/
            $scope.buscaCidades = function () {
                $scope.mostraLoader();
                var promise = Servidor.buscar('cidades', {estado: $scope.pessoa.endereco.cidade.estado.id});
                promise.then(function (response) {
                    $scope.cidades = response.data;
                    $timeout(function () {
                        $('#cidade').material_select('destroy');
                        $('#cidade').material_select();
                        $scope.fechaLoader();
                    }, 500);
                });
            };

            /*Validar Busca de Pessoa*/
            $scope.validarBusca = function () {
                if ($scope.pessoaBusca.dataNascimento !== undefined && $scope.pessoaBusca.dataNascimento) {
                    contador++;
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
                var dadosBusca = [$scope.pessoaBusca.nome, $scope.pessoaBusca.sobrenome, $scope.pessoaBusca.verificaCpf,
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

            /*Verifica o documento para busca*/
            $scope.verificaDocumento = function (documento) {
                if (!documento) {
                    documento = $scope.documento;
                }
                switch (documento) {
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
                    default:
                        $scope.cpfBusca = 'none';
                        $scope.certidaoBusca = 'none';
                        $scope.certidaoAntigaBusca = 'none';
                        $scope.pessoaBusca.cpf = null;
                        $scope.pessoaBusca.certidao = null;
                        $scope.pessoaBusca.termo = null;
                        $scope.pessoaBusca.livro = null;
                        $scope.pessoaBusca.folha = null;
                        break;
                }
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
                    if ($scope.termoCad && $scope.livroCad && $scope.folhaCad) {
                        $scope.pessoa.certidaoNascimento = $scope.livroCad + $scope.folhaCad + $scope.termoCad;
                        $scope.pessoa.certidaoNascimento = $scope.completaDigitos($scope.pessoa.certidaoNascimento, '32');
                        return $scope.pessoa.certidaoNascimento;
                    } else {
                        $scope.cadDocumento = 'certidao';
                        $scope.verificaDocumentoCadastro();
                        return $scope.pessoa.certidaoNascimento = "";
                    }
                } else {
                    return $scope.pessoa.certidaoNascimento = $scope.certidaoCad;
                }
            };

            /*Separa a certiao em livro,folha e termo*/
            $scope.desmontaCertidao = function () {
                if ($scope.pessoa.certidaoNascimento) {
                    var temp = $scope.pessoa.certidaoNascimento;
                    if (temp.indexOf("00000000000000000") !== -1) {
                        temp = temp.replace("00000000000000000","");
                        $scope.cadDocumento = 'certidao-antiga'; $scope.certidaoAntigaPessoa = ''; $scope.certidaoPessoa = "none"; $scope.certidaoAntiga = true;
                        $scope.livroCad = temp.toString().slice(0, 5);
                        $scope.folhaCad = temp.toString().slice(5, 8);
                        $scope.termoCad = temp.toString().slice(8, 15);
                        $timeout(function(){ $('#documento').material_select('destroy'); $('#documento').material_select(); },500);
                    }
                } else {
                    return $scope.pessoa.certidaoNascimento;
                }
            };

            $scope.verificaCertidaoAntiga = function() {
                if($scope.cadDocumento === 'certidao-antiga') {
                    if($scope.livroCad !== undefined) {
                        if($scope.livroCad.length > 5 || $scope.livroCad.length === 0) { Servidor.customToast('Número do livro incorreto.'); return false; }
                        else {
                            var liv = 5-$scope.livroCad.length; var zeros = "";
                            if (liv !== 0) {
                                for (var i=0; i<liv; i++) { zeros += "0"; if (i === liv-1) { $scope.livroCad = zeros+$scope.livroCad; } }
                            }
                        }
                    }
                    if($scope.folhaCad !== undefined) {
                        if($scope.folhaCad.length > 3 || $scope.folhaCad.length === 0) { Servidor.customToast('Preencha o número da folha.'); return false; }
                        else {
                            var liv = 3-$scope.folhaCad.length; var zeros = "";
                            if (liv !== 0) {
                                for (var i=0; i<liv; i++) { zeros += "0"; if (i === liv-1) { $scope.folhaCad = zeros+$scope.folhaCad; } }
                            }
                        }
                        //$scope.folhaCad = $scope.completaDigitos($scope.folhaCad, 3);
                    }
                    if($scope.termoCad !== undefined) {
                        if($scope.termoCad.length > 7 || $scope.termoCad.length === 0) { Servidor.customToast('Número do termo incorreto.'); return false; }
                        else {
                            var liv = 7-$scope.termoCad.length; var zeros = "";
                            if (liv !== 0) {
                                for (var i=0; i<liv; i++) { zeros += "0"; if (i === liv-1) { $scope.termoCad = zeros+$scope.termoCad; } }
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            };
            
            /*Verifica tipo Nacionalidade*/
            $scope.tipoNacionalidade = function (nacionalidade) {
                if (nacionalidade === $scope.pessoa.nacionalidade) {
                    return true;
                }
            };

            /*Atribui ou exclui particularidade*/
            $scope.salvarParticularidades = function (particularidade) {
                if ($('#parti' + particularidade.id).is(':checked')) {
                    $scope.pessoa.particularidades.push(particularidade);
                } else {
                    for (var i = 0; i < $scope.pessoa.particularidades.length; i++) {
                        if ($scope.pessoa.particularidades[i].id === particularidade.id) {
                            $scope.pessoa.particularidades.splice(i, 1);
                        }
                    }
                }
            };
            /*Atribui ou exclui necessidade*/
            $scope.salvarNecessidades = function (necessidadeEspecial) {
                if ($('#necess' + necessidadeEspecial.id).is(':checked')) {
                    $scope.pessoa.necessidadesEspeciais.push(necessidadeEspecial);
                } else {
                    for (var i = 0; i < $scope.pessoa.necessidadesEspeciais.length; i++) {
                        if ($scope.pessoa.necessidadesEspeciais[i].id === necessidadeEspecial.id) {
                            $scope.pessoa.necessidadesEspeciais.splice(i, 1);
                        }
                    }
                }
            };

            /*Verifica as Particularidades da Pessoa*/
            $scope.verificaParti = function (id) {
                if ($scope.pessoa.particularidades.length) {
                    for (var i = 0; i < $scope.pessoa.particularidades.length; i++) {
                        if ($scope.pessoa.particularidades[i].id === id) {
                            return true;
                        }
                    }
                }
            };

            /*Verifica as Necessidades da Pessoa*/
             $scope.verificaNecessidades = function (id) {
                if ($scope.pessoa.necessidadesEspeciais) {
                    for (var i = 0; i < $scope.pessoa.necessidadesEspeciais.length; i++) {
                        if ($scope.pessoa.necessidadesEspeciais[i].id === id) {
                            return true;
                        }
                    }
                }
            };

            /*Verifca Raca*/
            $scope.verificaRaca = function (id) {
                if ($scope.pessoa.raca) {
                    if (id === $scope.pessoa.raca.id) {
                        return true;
                    }
                }
            };

            /*Verifca Estado Civil*/
            $scope.verificaEstadoCivil = function (id) {
                if ($scope.pessoa.estadoCivil) {
                    if (id === $scope.pessoa.estadoCivil.id) {
                        return true;
                    }
                }
            };

            /*Verifca Genero*/
            $scope.verificaGenero = function (genero) {
                if (genero === $scope.pessoa.genero) {
                    return true;
                }
            };

            $scope.verificaCpf = function (cpf) {
                if (cpf.length === 14) {
                    cpf = cpf.split(".").join("");
                    cpf = cpf.split("-").join("");
                    if (!Servidor.validarCpf(cpf)) {
                        Servidor.customToast('CPF inválido');
                        $scope.invalido = true;
                    }
                }
            };
            /*Buscar Necessidades da pessoa */
            $scope.buscarNecessidadesEspeciais = function(){
                 var promise = Servidor.buscar('necessidades-especiais', null);
                promise.then(function (response) {
                    $scope.necessidadesEspeciais = response.data;
                });

            };

            /*Carrega um cadastro para o formulario*/
            $scope.carregarFormulario = function (pessoa, salvo, novo) {
                Servidor.removeTooltipp(); 
//                $scope.inicializar(false);
                $scope.mostraLoader();
                var promise = Servidor.buscarUm('pessoas', pessoa.id);
                promise.then(function(response){
                    $timeout(function () {
                        var persona = response.data; $scope.pessoa = response.data; $scope.resetaTelefone();
                        $scope.pessoa.dataNascimento = dateTime.converterDataForm($scope.pessoa.dataNascimento);
                        $scope.buscaTelefones();
                        if (response.data.certidaoNascimento !== undefined) {
                            if (response.data.certidaoNascimento.indexOf("00000000000000000") !== -1) { $scope.desmontaCertidao(); }
                        }
                        $scope.buscarDadosPessoa(pessoa, salvo, novo);
                    }, 500);
                });
                //var promise = Servidor.buscar('enderecos',{pessoa: pessoa.id});
                //promise.then(function(response){ $timeout(function () { $scope.pessoa.endereco = response.data; }, 500); });
                var promise = Servidor.buscar('vinculos', {funcionario: pessoa.id});
                promise.then(function(response) {
                    if($scope.isAdmin) {
                        $scope.buscarDadosPessoa(pessoa, salvo, novo);
                    } else {
                        var vinculos = response.data;
                        if(vinculos.length) {
                            //var encontrou = false;
                            var requisicoes = vinculos.length;
                            vinculos.forEach(function(vinculo) {
                                var promise = Servidor.buscar('alocacoes', {vinculo: vinculo.id});
                                promise.then(function(response) {
                                    if(response.data.length > 0) {
                                        var alocacoes = response.data;
                                        requisicoes += alocacoes.length;
                                        alocacoes.forEach(function(alocacao) {
                                            var unidade = JSON.parse(sessionStorage.getItem('unidade'));
                                            if (alocacao.instituicao.id === unidade.id) {
                                                $scope.buscarDadosPessoa(pessoa, salvo, novo);
                                            } else {
                                                $scope.fechaLoader();
                                                Servidor.customToast('Não é possível ver os dados de pessoas de unidade diferente.');
                                            }
                                        });
                                    } else {
                                        $scope.fechaLoader();
                                        Servidor.customToast('Nenhum dado para ser exibido.');
                                    }
                                });
                            });
                        } else {
                            $scope.buscarDadosPessoa(pessoa, salvo, novo);
                        }
                    }
                });
            };

            $scope.buscarDadosPessoa = function (pessoa, salvo, novo) {
                var promise = Servidor.buscarUm('pessoas', pessoa.id);
                promise.then(function (response) {
                    $scope.pessoa = response.data;
                    if ($scope.pessoa.cpfCnpj) {
                        $scope.pessoa.cpfCnpj = $scope.pessoa.cpfCnpj.slice(0,3) + '.' + $scope.pessoa.cpfCnpj.slice(3,6) + '.' + $scope.pessoa.cpfCnpj.slice(6,9) + '-' + $scope.pessoa.cpfCnpj.slice(9,12);
                        //console.log($scope.pessoa.cpfCnpj);
                    }
                    $("#nomePessoa").focus(); $scope.buscaTelefones();
                    $scope.pessoa.dataNascimento = dateTime.converterDataForm($scope.pessoa.dataNascimento);
                    $scope.pessoa.dataExpedicaoRg = dateTime.converterDataForm($scope.pessoa.dataExpedicaoRg);
                    $scope.pessoa.dataExpedicaoCertidaoNascimento = dateTime.converterDataForm($scope.pessoa.dataExpedicaoCertidaoNascimento);
                    if ($scope.pessoa.certidaoNascimento !== undefined) {
                        if ($scope.pessoa.certidaoNascimento.indexOf("00000000000000000") !== -1) { $scope.desmontaCertidao(); }
                    }
                    if ($scope.pessoa.endereco !== undefined && $scope.pessoa.endereco.id !== undefined && !$scope.pessoa.endereco) {
                        if ($scope.pessoa.endereco.cidade !== undefined) {
                            if ($scope.pessoa.endereco.cidade.id !== undefined && $scope.pessoa.endereco.cidade.id) {
                                $scope.cidadeId = $scope.pessoa.endereco.cidade.id;
                            }
                            if ($scope.pessoa.endereco.cidade.estado.id !== undefined) {
                                $scope.estadoId = $scope.pessoa.endereco.cidade.estado.id;
                            }
                        }
                    }
                    if ($scope.pessoa.responsavelNome !== $scope.pessoa.nomeMae && $scope.pessoa.responsavelNome !== $scope.pessoa.nomePai) {
                        $scope.pessoaResponsavel = 'outro';
                    } else {
                        $scope.pessoaResponsavel = $scope.pessoa.responsavelNome;
                    }
                    
                    $scope.cadastrarPessoa(salvo, novo);
                });
                $scope.buscarParticularidades();
                $scope.buscarNecessidadesEspeciais();
            };

            /*Cadastro de Pessoa*/
            $scope.cadastrarPessoa = function (salvo, novo) {
                $scope.telefones = [];
                $scope.buscarEstadoCivil();
                $scope.buscaRaca();
                $scope.buscarParticularidades();
                $scope.buscarNecessidadesEspeciais();
                $scope.novo = novo;
                $scope.telaNumero = 1;
                $("#nomePessoa").focus();
                $(".btn-add").hide();
                $('.btn-voltar').show();
                $scope.telaNumero = 1;
                if (novo) {
                    $scope.reiniciarpessoa();
                    $scope.pessoaResponsavel = 'mae';
                };
                if ($scope.pessoa.id) {
                    $scope.acao = 'Editar';
                    $scope.mostraLoader();
                    if ($scope.pessoa.endereco === undefined || !$scope.pessoa.endereco) {
                        $scope.pessoa.endereco = {
                            latitude: null,
                            longitude: null
                        };
                    } else {
                        var promise = Servidor.buscarUm('enderecos', $scope.pessoa.endereco.id);
                        promise.then(function(response) {
                            $scope.pessoa.endereco = response.data;
                        });
                        if ($scope.pessoa.endereco !== undefined && $scope.pessoa.endereco.cidade !== undefined) {
                            $scope.estadoId = $scope.pessoa.endereco.cidade.estado.id;
                            $scope.cidadeId = $scope.pessoa.endereco.cidade.id;
                            $scope.buscaCidades();
                        }
                    }
                } else {
                    $scope.acao = 'Cadastrar';
//                    $scope.pessoa.endereco.latitude = -26.929647;
//                    $scope.pessoa.endereco.longitude = -48.683661;
                    $scope.pessoa.nome = $scope.pessoaBusca.nome;
                    $scope.pessoa.endereco = $scope.endereco;
                    $scope.pessoa.endereco.latitude = null;
                    $scope.pessoa.endereco.longitude = null;
                }
                $timeout(function () {
                    Servidor.verificaLabels();
                    $("#nomePessoa").focus();
                    $scope.adicionaPessoa = false;
                    $scope.editando = false;
                    $timeout(function () {
                        $('ul.tabs').tabs();
                        $timeout(function () {
                            $('ul.tabs').tabs('select_tab', 'tab-dados');
                        }, 50);
                    }, 50);
                    $timeout(function () {
                        //$scope.initMap(false, "mapa");
                    }, 500);
                    if (!salvo) {
                        $scope.mostraLoader();
                        window.scrollTo(0, 0);
                        $scope.dados = true;
                        $scope.pessoaEnd = true;
                        $scope.fechaLoader();
                    }
                    $('select').material_select('destroy');
                    $('select').material_select();
                }, 500);
            };

            /*Fecha Modal aberto*/
            $scope.fecharModal = function () {
                $('.lean-overlay').hide();
                $('#telefone-modal-pessoa').closeModal();
            };

            /* Adiciona Telefone */
            $scope.salvarTelefone = function (telefone) {
                if (!telefone.id) {
                    telefone.pessoa = $scope.pessoa;
                    delete telefone.pessoa.endereco;
                    telefone.pessoa.dataNascimento = dateTime.converterDataServidor($scope.pessoa.dataNascimento);
                    telefone.pessoa.dataExpedicaoCertidaoNascimento = dateTime.converterDataServidor($scope.pessoa.dataExpedicaoCertidaoNascimento);
                }
                if (telefone.numero && telefone.descricao) {
                    telefone.pessoa.tipo_pessoa = "PessoaFisica";
                    var pessoa = {id:$scope.telefone.pessoa.id, 'tipo_pessoa': 'PessoaFisica' }; $scope.telefone.pessoa = pessoa;
                    var promise = Servidor.finalizar(telefone, 'telefones', 'Telefone');
                    promise.then(function (response) {
                        $scope.telefones.unshift(response.data);
                        $scope.resetaTelefone();
                    });
                } else {
                    Materialize.toast('Preencha os campos obrigatórios.', 2000);
                }
            };

            /* Preparar remover */
            $scope.prepararRemover = function (pessoa) {
                $scope.pessoaRemover = pessoa;
                $('#remove-modal-pessoa').openModal();
            };

            /* Limpa pessoa */
            $scope.limpaPessoa = function () {
                $scope.pessoa = {
                    'nome': null, 'dataNascimento': null,
                    'email': null, 'genero': null,
                    'raca': null, 'estadoCivil': null,
                    'particularidades': [], 'nomePai': null,
                    'nomeMae': null, 'naturalidade': null,
                    'nacionalidade': null, 'numeroRG': null,
                    'dataExpedicaoRg': null, 'orgaoExpedidorRg': null,
                    'cpfCnpj': null, 'certidaoNascimento': null,
                    'dataExpedicaoCertidaoNascimento': null, 'codigoInep': null,
                    'nis': null, 'endereco': null, 'responsavelNome': null
                };
            };

            /* Removendo pessoa */
            $scope.remover = function () {
                $scope.pessoas.forEach(function (pessoa, i) {
                    if (pessoa.id === $scope.pessoaRemover.id) {
                        $scope.pessoas.splice(i, 1);
                    }
                });
                Servidor.remover($scope.pessoaRemover, 'pessoa');
            };

            /* Remove Telefone */
            $scope.removerTelefone = function (telefone) {
                if (telefone.id) {
                    $scope.mostraProgresso();
                    var promise = Servidor.buscarUm('telefones',telefone.id);
                    promise.then(function(response){
                        Servidor.remover(response.data, 'Telefone');
                        $timeout(function () {
                            $scope.buscaTelefones();
                            $scope.fechaProgresso();
                        }, 300);
                    });
                } else {
                    for (var i = 0; i < $scope.telefones.length; i++) {
                        if (telefone.numero === $scope.telefones[i].numero) {
                            $scope.telefones.splice(i, 1);
                            $scope.telefonesPessoa--;
                            break;
                        }
                    }
                }
            };

            /* Seleciona a descricao correta no select */
            $scope.verificaSelectDescricao = function (tipo) {
                var retorno = false;
                if ($scope.telefone.descricao) {
                    if ($scope.telefone.descricao.length === tipo.length) {
                        retorno = true;
                    }
                }
                return retorno;
            };

            /*Verifica se já existe cadastro com mesmo nome,dataNascimento e nomeMae*/
            $scope.verificaPessoa = function () {
                if (!$scope.pessoa.id) {
                    if($scope.pessoa.nome && $scope.pessoa.dataNascimento) {
                        var promise = Servidor.buscar('pessoas', {'nome': $scope.pessoa.nome,
                            'dataNascimento': dateTime.converterDataServidor($scope.pessoa.dataNascimento),
                            'nomeMae': $scope.pessoa.nomeMae});
                        promise.then(function (response) {
                            if (response.data.length) {
                                Servidor.customToast('Esta pessoa já está cadastrada.');
    //                            var promiseP = Servidor.buscarUm('pessoas', response.data[0].id);
    //                            promiseP.then(function (responseP) {
    //                                $scope.cadastroExistente = responseP.data;
    //                                var promiseT = Servidor.buscar('telefones', {'pessoa': $scope.cadastroExistente.id});
    //                                promiseT.then(function (responseT) {
    //                                    $scope.cadastroExistente.telefones = responseT.data;
    //                                    $('#pessoa-cadastrada').openModal();
    //                                });
    //                            });
                            } else {
                                $scope.verificaCadastroCpf();
                            }
                        });
                    } else {
                        Servidor.customToast('Preencha os campos obrigatórios.');
                    }
                } else {
                    $scope.verificaCadastroCpf();
                }
            };

            $scope.removerEndereco = function(endereco) {
                $scope.mostraLoader();
                var promise = Servidor.buscarUm('enderecos', endereco.id);
                promise.then(function(response) {
                    Servidor.remover(response.data, 'endereco');
                    Servidor.customToast('Endereço removido com sucesso!');
                    $scope.pessoa.endereco = {
                        'id': null, 'logradouro': null, 'numero': null, 'bairro': null, 'complemento': null, 'cep': null,
                        'cidade': {'id': null, 'nome': null, 'estado': {'id': null, 'nome': null, 'sigla': null},
                            'latitude': null, 'longitude': null
                        }
                    };
                    $scope.endereco = $scope.pessoa.endereco;
                    $scope.estadoId = null;
                    $scope.cidadeId = null;
                    $timeout(function() { $('#cidade, #estado').material_select(); $scope.fechaLoader(); }, 100);
                });
            };

            $scope.confirmarCadastro = function () {
//                $scope.carregarFormulario($scope.pessoa, true);
                $scope.buscarDadosPessoa($scope.pessoa, true);
                $scope.telaNumero = 4;
                $scope.mudaTelaCadastro($scope.telaNumero);
            };

            /*Verifica se já existe pessoa com o cpf*/
            $scope.verificaCadastroCpf = function () {
                if (!$scope.pessoa.cpfCnpj || $scope.pessoa.cpfCnpj === undefined) {
                    $scope.salvarPessoa();
                } else {
                    var cpf = $scope.pessoa.cpfCnpj.split(".").join("");
                    cpf = cpf.split("-").join("");
                    if (Servidor.validarCpf(cpf)) {
                        var promise = Servidor.buscar('pessoas', {'cpf': cpf});
                        promise.then(function (response) {
                            if (response.data.length && response.data[0].id !== $scope.pessoa.id) {
                                Servidor.customToast('CPF já cadastrado.');
                            } else {
                                $scope.salvarPessoa();
                            }
                        });
                    } else {
                        Servidor.customToast('CPF inválido.');
                    };
                }
            };

//            /*asdas*/
//            $scope.mostraOutroResponsavel =  function(){
//                console.log($scope.pessoa.nomeResponsavel);
//                if($scope.pessoa.nomeResponsavel === 'outro') {
//                    $scope.outroResponsavel = true;
//
//                    $timeout(function(){
//                        Servidor.verificaLabels();
//                    },100);
//                } else {
//                    $scope.pessoa.nomeResponsavel = false;
//                }
//            };

            $scope.verificarEnderecoPreenchido = function(endereco) {
                if(endereco === undefined || endereco === null) {
                    return false;
                } else if(endereco.bairro === undefined || !endereco.bairro) {
                    return false;
                } else if(endereco.logradouro === undefined || !endereco.logradouro) {
                    return false;
                } else if(endereco.cep === undefined || !endereco.cep) {
                    return false;
                } else if(endereco.cidade === undefined || !endereco.cidade.id) {
                    return false;
                } else if(endereco.cidade.estado === undefined || !endereco.cidade.estado.id) {
                    return false;
                }
                return true;
            };

            /*Salvar Cadastro de Pessoa*/
            $scope.salvarPessoa = function () {
                if ($scope.validarPessoa('validate-pessoa') || $scope.telaNumero > 2) {
                    $scope.mostraLoader();
                    if ($scope.pessoa.id !== null && $scope.pessoa.id !== undefined && $scope.telaNumero === 3) {
                        if($scope.verificaCertidaoAntiga()) { $scope.montaCertidao(); }
                        else { if ($scope.pessoa.certidaoNascimento !== undefined && $scope.pessoa.certidaoNascimento.length > 0 && $scope.pessoa.certidaoNascimento.length !== 32) { $scope.fechaLoader(); $scope.fechaProgresso(); return Servidor.customToast("Certidão não possui 32 dígitos."); } else { $scope.fechaLoader(); $scope.fechaProgresso(); } }
                    }
                    if ($scope.pessoa.dataNascimento && $scope.pessoa.dataNascimento !== undefined) {
                        $scope.pessoa.dataNascimento = dateTime.converterDataServidor($scope.pessoa.dataNascimento);
                    }
                    if ($scope.pessoa.certidaoNascimento === null) {
                        $scope.pessoa.certidaoNascimento = '';
                    }
                    if ($scope.pessoa.cpfCnpj) {
                        $scope.pessoa.cpfCnpj = $scope.pessoa.cpfCnpj.split(".").join("");
                        $scope.pessoa.cpfCnpj = $scope.pessoa.cpfCnpj.split("-").join("");
                    }
                    if ($scope.pessoa.dataExpedicaoRg) {
                        $scope.pessoa.dataExpedicaoRg = dateTime.converterDataServidor($scope.pessoa.dataExpedicaoRg);
                    }
                    if ($scope.pessoa.dataExpedicaoCertidaoNascimento) {
                        $scope.pessoa.dataExpedicaoCertidaoNascimento = dateTime.converterDataServidor($scope.pessoa.dataExpedicaoCertidaoNascimento);
                    }
                    if ($scope.telaNumero === 1) {
                        if ($scope.pessoa.id === undefined || $scope.pessoa.id === null) {
                            //if ($scope.verificarEnderecoPreenchido($scope.pessoa.endereco)) {
                                var endereco = Restangular.copy($scope.pessoa.endereco);
                                if (Servidor.validar('validate-endereco')) {
                                    endereco.route = 'enderecos';
                                    var promise = Servidor.finalizar(endereco, 'enderecos', 'Endereço');
                                    promise.then(function (response) {
                                        $scope.pessoa.endereco.id = response.data.id;
                                        $scope.endereco.id = response.data.id;
                                        $scope.finalizarPessoa();
                                    });
                                } else {
                                    $scope.fechaLoader();
                                    return Servidor.customToast("Os campos de endereço são obrigatórios.");
                                }
                            /*} else {
                                delete $scope.pessoa.endereco;
                                $scope.finalizarPessoa();
                            }*/
                        } else {
                            //if ($scope.pessoa.endereco.id === undefined || $scope.pessoa.endereco.id === null) {
                                //if ($scope.verificarEnderecoPreenchido($scope.pessoa.endereco)) {
                                    var endereco = Restangular.copy($scope.pessoa.endereco);
                                    if (Servidor.validar('validate-endereco')) {
                                        endereco.route = 'enderecos';
                                        var promise = Servidor.finalizar(endereco, 'enderecos', 'Endereço');
                                        promise.then(function (response) {
                                            $scope.pessoa.endereco.id = response.id;
                                            $scope.endereco.id = response.id;
                                            $scope.finalizarPessoa();
                                        });
                                    } else {
                                        $scope.fechaLoader();
                                        return Servidor.customToast("Os campos de endereço são obrigatórios.");
                                    }
                                /*} else {
                                    delete $scope.pessoa.endereco;
                                    $scope.finalizarPessoa();
                                }*/
                            /*} else {
                                $scope.finalizarPessoa();
                            }*/
                        }
                    } else {
                        if (!$scope.verificarEnderecoPreenchido($scope.pessoa.endereco)) {
                            $scope.fechaLoader();
                            return Servidor.customToast("Os campos de endereço são obrigatórios.");
                        } else {
                            delete $scope.pessoa.endereco;
                            $scope.finalizarPessoa();
                        }
                    }
                };
            };

            $scope.finalizarPessoa = function () {
                switch ($scope.pessoaResponsavel) {
                    case'mae':
                        $scope.pessoa.responsavelNome = $scope.pessoa.nomeMae;
                        break;
                    case'pai':
                        $scope.pessoa.responsavelNome = $scope.pessoa.nomePai;
                        break;
                };
                $scope.pessoa.tipo_pessoa = "PessoaFisica"; delete $scope.pessoa.usuario;
                var promise = Servidor.finalizar($scope.pessoa, 'pessoas', 'Pessoa');
                
                promise.then(function (response) {
                    $scope.pessoa = response.data;
                    
                    if ($scope.telefone.numero !== null && $scope.telefone.descricao !== null){
                        $scope.salvarTelefone($scope.telefone);
                    }
                    
                    $scope.pessoa.dataNascimento = dateTime.converterDataForm(response.data.dataNascimento);
                    if($scope.pessoa.dataExpedicaoCertidaoNascimento !== undefined || !$scope.pessoa.dataExpedicaoCertidaoNascimento) {
                        var dataExpedicaoCertidaoNascimento = dateTime.converterDataForm($scope.pessoa.dataExpedicaoCertidaoNascimento);
                        $scope.pessoa.dataExpedicaoCertidaoNascimento = dataExpedicaoCertidaoNascimento;
                        $('.data').mask('00/00/0000');
                    }
                    if($scope.pessoa.dataExpedicaoRg !== undefined || !$scope.pessoa.dataExpedicaoRg) {
                        var dataExpedicaoRg = dateTime.converterDataForm($scope.pessoa.dataExpedicaoRg);
                        $scope.pessoa.dataExpedicaoRg = dataExpedicaoRg;
                        $('.data').mask('00/00/0000');
                    }
                    if ($scope.telaNumero === 5) {
                        if ($scope.novo) {
                            $('#confirma-cadastro').openModal();
                            $scope.fechaLoader();
                            $scope.novo = false;
                        } else {
                            $scope.reiniciarPessoaBusca();
                            $scope.pessoaBusca.nome = response.data.nome;
                            $scope.buscarPessoas($scope.pessoaBusca, '', 'botao');
                            $scope.fecharFormulario();
                            $scope.fechaLoader();
                        }
                    } else {
                        $scope.fechaLoader();
                        $scope.mudaTelaCadastro($scope.telaNumero + 1, true);
                    }
                });
            };


            $scope.validarPessoa = function (id) {
                var retorno = false;
                if (Servidor.validar(id)) {
                    retorno = true;
                    if ($scope.pessoa.naturalidade.id === undefined) {
                        Servidor.customToast('Selecione uma cidade no campo naturalidade.');
                        retorno = false;
                    }
                    return retorno;
                }
            };

            /*verifica qual tipo de documento*/
            $scope.tipoDocumento = function () {
                if ($scope.pessoa.certidaoNascimento) {
                    $scope.cadDocumento = 'certidao';
                    $scope.certidaoPessoa = '';
                    $scope.dataCertidaoCad = '';
                    $scope.cpfPessoa = 'none';
                    $scope.certidaoAntigaPessoa = 'none';
                    $scope.certidaoAntiga = false;
                }
                if ($scope.livroCad && $scope.folhaCad && $scope.termoCad) {
                    $scope.cadDocumento = 'certidao-antiga';
                    $scope.certidaoAntigaPessoa = '';
                    $scope.cpfPessoa = 'none';
                    $scope.certidaoPessoa = 'none';
                    $scope.dataCertidaoCad = 'none';
                    $scope.certidaoAntiga = true;
                }
            };

            $scope.verificaDocumentoCadastro = function () {
                if ($scope.cadDocumento === 'certidao') {
                    $scope.certidaoPessoa = "";
                    $scope.dataCertidaoCad = "";
                    $scope.cpfPessoa = 'none';
                    $scope.certidaoAntigaPessoa = 'none';
                    $scope.termoCad = "";
                    $scope.livroCad = "";
                    $scope.folhaCad = "";
                    $scope.certidaoAntiga = false;
                } else if ($scope.cadDocumento === 'certidao-antiga') {
                    $scope.certidaoAntigaPessoa = "";
                    $scope.cpfPessoa = 'none';
                    $scope.certidaoPessoa = 'none';
                    $scope.dataCertidaoCad = 'none';
                    $scope.pessoa.certidaoNascimento = null;
                    $scope.certidaoAntiga = true;
                }
            };

            /* Verifica selects de Estado e Cidade */
            $scope.verificaSelectEstado = function (estadoId) {
                if (estadoId === $scope.estadoId) {
                    return true;
                }
            };

            $scope.verificaSelectCidade = function (cidadeId) {
                if (cidadeId === $scope.cidadeId) {
                    return true;
                }
            };

            $scope.selecionaEstado = function () {
                var estado;
                for (var i = 0; i < $scope.estados.length; i++) {
                    if ($scope.estados[i].id === parseInt($scope.estadoId)) {
                        estado = $scope.estados[i];
                    }
                }
                $scope.estadoId = estado.id;
                $scope.pessoa.endereco.cidade = {estado: estado};
                $scope.cidadeId = null;
                $scope.pessoa.endereco.cidade.id = null;
                $scope.pessoa.endereco.cidade.nome = null;
                $scope.buscaCidades();
                $timeout(function () {
                    $scope.buscaCoordenadasPorEnderecoCompleto();
                }, 500);
            };

            $scope.selecionaCidade = function () {
                var cidade = null;
                for (var i = 0; i < $scope.cidades.length; i++) {
                    if ($scope.cidades[i].id === parseInt($scope.cidadeId)) {
                        cidade = $scope.cidades[i];
                    }
                }
                $scope.pessoa.endereco.cidade = cidade;
                $timeout(function () {
                    $scope.buscaCoordenadasPorEnderecoCompleto();
                }, 500);
            };

            /* Busca de endereço pelo CEP */
            $scope.buscaCEP = function (query) {
                $timeout.cancel($scope.delayBusca);
                $scope.delayBusca = $timeout(function () {
                    if (query === undefined || query === null) {
                        query = '';
                    }
                    var tamanho = query.length;
                    if (tamanho === 8) {
                        $scope.mostraLoader();
                        Servidor.consultaCep(query);
                        $timeout(function () {
                            var endereco = Servidor.recuperaCep();
                            if (endereco[0]) {
                                $scope.pessoa.endereco.logradouro = endereco[0];
                            } else {
                                $scope.pessoa.endereco.logradouro = '';
                            }
                            if (endereco[1]) {
                                $scope.pessoa.endereco.bairro = endereco[1];
                            } else {
                                $scope.pessoa.endereco.bairro = '';
                            }
                            /* Buscando Estado */
                            if (endereco[3]) {
                                var promise = Servidor.buscar('estados', {'sigla': endereco[3]});
                                promise.then(function (response) {
                                    var estado = response.data;
                                    $scope.pessoa.endereco.cidade = {
                                        estado: estado[0].plain(),
                                        id: null,
                                        nome: null
                                    };
                                    $scope.estadoId = estado[0].id;
                                    $scope.cidadeId = null;
                                    $scope.buscaEstados();
                                    $scope.buscaCidades();
                                    if (endereco[2]) {
                                        $timeout(function () {
                                            /* Buscando Cidade */
                                            var promise = Servidor.buscar('cidades', {'nome': endereco[2], 'estado': $scope.pessoa.endereco.cidade.estado.id});
                                            promise.then(function (response) {
                                                var cidade = response.data;
                                                $scope.pessoa.endereco.cidade = cidade[0].plain();
                                                $scope.cidadeId = cidade[0].id;
                                                $timeout(function () {
                                                    $scope.buscaCoordenadasPorEndereco();
                                                    $('#cidade').material_select('destroy');
                                                    $('#cidade').material_select();
                                                    Servidor.verificaLabels();
                                                    $scope.fechaProgresso();
                                                    if (!$scope.pessoa.id) {
                                                        if (!$scope.pessoa.endereco.bairro) {
                                                            $("#logradouro").focus();
                                                        } else {
                                                            $("#numero").focus();
                                                        }
                                                    }
                                                }, 500);
                                            });
                                        }, 500);
                                    } else {
                                        $scope.fechaLoader();
                                        $scope.endereco.cidade.id = '';
                                        $('#cidade').material_select();
                                    }
                                });
                            } else {
                                $scope.fechaLoader();
                                $scope.endereco.cidade.estado.id = '';
                                $('#estado').material_select();
                            }
                        }, 500);
                    }
                }, 200);
            };

            $scope.temNaturalidade = function () {
                if ($scope.buscaNaturalidade.length > 0) {
                    return true;
                }
            };

            $scope.setaCidade = function (cidade) {
                $scope.pessoa.naturalidade.id = cidade.id;
                $scope.pessoa.naturalidade.nome = cidade.nome;
                $scope.buscaNaturalidade = [];
            };

            /* Checa os labels nao ativos dos inputs da busca */
            $scope.verificaLabelsBusca = function () {
                $('.input-field').each(function () {
                    $(this).find('label').removeClass('active');
                });
            };

            $scope.fecharFormulario = function () {
                $scope.mostraProgresso();
                $scope.reiniciarpessoa();
              $scope.reiniciarPessoaBusca();
//              $scope.pessoas = [];
                $scope.cidades = [];
                //$scope.pessoas = [];
                $scope.cidadesDrop = [];
                $scope.mostraPessoas = false;
                $scope.adicionaPessoa = true;
                $scope.dados = false;
                $scope.pessoaEnd = false;
                $scope.pessoaDocs = false;
                $scope.pessoaParti = false;
                $scope.pessoaContato = false;
                $scope.telaNumero = 1;
                Servidor.verificaLabels();
                $('.btn-add').show();
                $scope.editando = true;
                $("#mapa").html('');
                $('select').material_select('destroy');
                $('select').material_select();
                $timeout(function () {
                    $scope.cadDocumento = 'certidao'; $scope.certidaoAntigaPessoa = 'none'; $scope.certidaoPessoa = ""; $scope.certidaoAntiga = false;
                    $scope.fechaProgresso();
                    $("#telefone").mask("(00) 0000-00009");
                }, 500);
            };

            if(!$scope.inicializando) {                
                $scope.inicializar(true, true);
            }            
            $scope.verificaDocumentoCadastro();
    }]);
})();

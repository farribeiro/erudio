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

(function (){
    // DEFINIÇÃO DO MÓDULO
    var unidadeModule = angular.module('unidadeModule', ['servidorModule','unidadeDirectives']);

    //DEFINIÇÃO DO CONTROLADOR
    unidadeModule.controller('UnidadeController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('UNIDADE_ENSINO');
        $scope.unidades = [];
        $scope.estados = [];
        $scope.cidades = [];
        $scope.telefones = [];
        $scope.tipos = [];
        $scope.instituicoes = [];
        $scope.unidadeRemover = null;
        $scope.nomeEstado = null;
        $scope.nomeCidade = null;
        $scope.selectEstado = null;
        $scope.selectCidade = null;
        $scope.selectTipo = null;
        $scope.selectInstituicao = null;
        $scope.nomeUnidade = '';
        $scope.estadoId = null;
        $scope.cidadeId = null;
        $scope.editando = false;
        $scope.editandoMobile = false;
        $scope.loader = false;
        $scope.progresso = false;
        $scope.cortina = false;
        $scope.acoesMobile = false;
        $scope.pagina = 0;
        $scope.primeiroScroll = true;
        $scope.index = null;
        $scope.habilitaClique = true;
        $scope.icone = 'search';
        $scope.listaTelefone = false;
        $scope.opcaoDeEnvio = '';
        $scope.acao = "";
        $scope.curso = {'id': null};
        $scope.cursos = [];
        $scope.paginaAtual = 1;

        /* Estrutura de unidade */
        $scope.unidade = { nome:null, cpfCnpj:null, email:null, tipo: { id:null }, instituicaoPai: null, endereco: null, cursos: [] };

        /* Estrutura de enredeco */
        $scope.endereco = {
            logradouro: null, numero:null, bairro:null, complemento:null, cep:null,
            cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null },
            latitude: null, longitude: null
            }
        };

        /* Estrutura de Telefone */
        $scope.telefone = { descricao: null, falarCom: null, numero: null, pessoa: {id: null} };

        /* Controle da barra de progresso */
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } };
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; };

        /* Reinciando Estrutura de unidade*/
        $scope.reiniciar = function (){ $scope.unidade = { nome:null, cpfCnpj:null,  email:null, tipo: { id:null }, instituicaoPai: null, endereco: null, telefones:[], cursos: [] }; };

        /* Reiniciando Estrutura de Telefone */
        $scope.resetaEndereco = function() { $scope.endereco = { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null }, latitude: null, longitude: null } }; };

        /* Reiniciando Estrutura de endereço */
        $scope.resetaTelefone = function() {
            $scope.telefone = {
                descricao: null,
                falarCom: null,
                numero: null,
                pessoa: {id: null}
            };
            $('#telefone-modal-unidade').closeModal();
            $('.lean-overlay').hide();
        };

        $scope.inicializar = function (inicializaUmaVez) {
            $timeout(function (){
                if (inicializaUmaVez) {
                    $('.counter').each(function(){ $(this).characterCounter(); });
                    /*$(window).scroll(function() {
                        if($(this).scrollTop() + $(this).height() === $(document).height()) {
                            if (!$scope.editando) {
                                $scope.pagina++;
                                $scope.buscarInstituicoes(true);
                            }
                        }
                    });*/
                    $('#unidadeForm').keydown(function(event){
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) { //if enter is pressed
                                $timeout(function(){
                                    if ($scope.habilitaClique) { $('#salvarUnidade').trigger('click'); }
                                    else { $scope.habilitaClique = true; }
                                },300);
                            }
                        }
                    });
                    $('#descricao').material_select();
                    $scope.buscarCursos();
                    $('.cep').mask("00000009");
                }
                $('.tooltipped').tooltip('remove');
                $('.tooltipped').tooltip({delay: 30});
                $('#tipoUnidade, #instituicaoPai, #estado').material_select('destroy');
                $('#tipoUnidade, #instituicaoPai, #estado').material_select();
            },500);
        };

        /* Inicializar Mapa */
        $scope.initMap = function (comJanela, idMap) {
            if ($scope.unidade.endereco !== null) {
                var map;
                var latLng = new google.maps.LatLng($scope.unidade.endereco.latitude, $scope.unidade.endereco.longitude);
                var options = { zoom: 17, center: latLng };
                map = new google.maps.Map(document.getElementById(idMap), options);
                $scope.marker = new google.maps.Marker({ position: latLng, title: $scope.unidade.nome, map: map });
                var infowindow = new google.maps.InfoWindow(), marker;
                google.maps.event.addListener(map, 'click', (function(event) {
                    $scope.marker.setMap(null);
                    var newLatLng = event.latLng;
                    $scope.unidade.endereco.latitude = newLatLng.lat();
                    $scope.unidade.endereco.longitude = newLatLng.lng();
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    $scope.marker = new google.maps.Marker({position: newLatLng, map: map});
                    infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function(e){ infowindow.open( null, null ); });
                    $('.gm-style').on('click', '.btn-address-close', function(e){ infowindow.open( null, null ); });
                    return true;
                }));
                if (comJanela) {
                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
                    infowindow.open(map, $scope.marker);
                    $('.gm-style').on('click', '.btn-address', function(e){ infowindow.open( null, null ); });
                    $('.gm-style').on('click', '.btn-address-close', function(e){ infowindow.open( null, null ); });
                }
            }
        };

//        /* Inicializar Mapa */
//        $scope.initMap = function (comJanela) {
//            if ($scope.unidade.endereco !== null) {
//                var map;
//                var latLng = new google.maps.LatLng($scope.unidade.endereco.latitude, $scope.unidade.endereco.longitude);
//                var options = { zoom: 17, center: latLng };
//                map = new google.maps.Map(document.getElementById("mapa"), options);
//                $scope.marker = new google.maps.Marker({ position: latLng, title: $scope.unidade.nome, map: map });
//                var infowindow = new google.maps.InfoWindow(), marker;
//                google.maps.event.addListener(map, 'click', (function(event) {
//                    $scope.marker.setMap(null);
//                    var newLatLng = event.latLng;
//                    $scope.unidade.endereco.latitude = newLatLng.lat();
//                    $scope.unidade.endereco.longitude = newLatLng.lng();
//                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
//                    $scope.marker = new google.maps.Marker({position: newLatLng, map: map});
//                    infowindow.open(map, $scope.marker);
//                    $('.gm-style').on('click', '.btn-address', function(e){ infowindow.open( null, null ); });
//                    $('.gm-style').on('click', '.btn-address-close', function(e){ infowindow.open( null, null ); });
//                    return true;
//                }));
//                if (comJanela) {
//                    infowindow.setContent("<div style='width: 100%; text-align: center;'>Este é realmente o endereço indicado?<br /><a style='margin-right: 10px; margin-top: 10px;' class='waves-effect waves-light btn teal btn-address'>SIM</a><a style='margin-top: 10px;' class='waves-effect waves-light btn teal btn-address-close'>NÃO</a></div>");
//                    infowindow.open(map, $scope.marker);
//                    $('.gm-style').on('click', '.btn-address', function(e){ infowindow.open( null, null ); });
//                    $('.gm-style').on('click', '.btn-address-close', function(e){ infowindow.open( null, null ); });
//                }
//            }
//        };

        /* Buscar endereço pelas coordenadas */
        $scope.getEnderecoPorCoordenada = function() {
            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.unidade.endereco.latitude + ',' + $scope.unidade.endereco.longitude + '&sensor=false',
                dataType: 'JSON', type: 'get',
                success: function(data){
                    if (data.results[0].address_components[7].long_name.length === 9) {
                        var cep = data.results[0].address_components[7].long_name.split('-');
                        $scope.unidade.endereco.cep = cep[0]+cep[1];
                        $scope.buscaCEP($scope.unidade.endereco.cep);
                    } else {Servidor.customToast('CEP não identificado, digite o endereço manualmente.');}
                }
            });
        };

        /* Busca coordenada por endereço */
        $scope.buscaCoordenadasPorEndereco = function (){
            if ($scope.unidade.endereco.numero === null) { $scope.unidade.endereco.numero = ''; }
            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.unidade.endereco.logradouro + ','
                        + $scope.unidade.endereco.bairro + ',' + $scope.unidade.endereco.cidade.nome + ','
                        + $scope.unidade.endereco.cidade.estado.nome + ',' + $scope.unidade.endereco.numero,
                dataType: 'JSON', type: 'get',
                success: function(data){
                    if (data.status !== 'ZERO_RESULTS') {
                        $scope.unidade.endereco.latitude = data.results[0].geometry.location.lat;
                        $scope.unidade.endereco.longitude = data.results[0].geometry.location.lng;
                        //$scope.initMap(true, "mapa");
                    } else {
                        $scope.unidade.endereco.latitude = -26.929647;
                        $scope.unidade.endereco.longitude = -48.683661;
                        //$scope.initMap(true, "mapa");
                    }
                }
            });
        };

//        /* Busca coordenada por endereço */
//        $scope.buscaCoordenadasPorEndereco = function (){
//            if ($scope.unidade.endereco.latitude === null) {
//                if ($scope.unidade.endereco.numero === null) { $scope.unidade.endereco.numero = ''; }
//                $.ajax({
//                    url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.unidade.endereco.logradouro + ','
//                            + $scope.unidade.endereco.bairro + ',' + $scope.unidade.endereco.cidade.nome + ','
//                            + $scope.unidade.endereco.cidade.estado.nome + ',' + $scope.unidade.endereco.numero + '&sensor=false',
//                    dataType: 'JSON', type: 'get',
//                    success: function(data){
//                        if (data.status !== 'ZERO_RESULTS') {
//                            $scope.unidade.endereco.latitude = data.results[0].geometry.location.lat;
//                            $scope.unidade.endereco.longitude = data.results[0].geometry.location.lng;
//                            $scope.initMap(true);
//                        } else {
//                            $scope.unidade.endereco.latitude = -26.929647;
//                            $scope.unidade.endereco.longitude = -48.683661;
//                            $scope.initMap(true);
//                        }
//                    }
//                });
//            } else { $scope.initMap(false); }
//        };

        /* Busca coordenada por endereço digitado */
        $scope.buscaCoordenadasPorEnderecoCompleto = function (){
            if (($scope.unidade.endereco.cep === null || $scope.unidade.endereco.cep === '' || $scope.unidade.endereco.cep === undefined) && ($scope.unidade.endereco.logradouro !== null && $scope.unidade.endereco.bairro !== null && $scope.unidade.endereco.cidade.nome !== null && $scope.unidade.endereco.cidade.estado.nome !== null)) {
                $scope.buscaCoordenadasPorEndereco();
            }
        };

        /* Busca todos os cursos */
        $scope.buscarCursos = function() {
            var promise = Servidor.buscar('cursos', null);
            promise.then(function(response) {
                $scope.cursos = response.data;
            });
        };
        
        $scope.selecionarTipo = function(id) {
            id = parseInt(id);            
            $scope.tipos.forEach(function(t) {
                if(t.id === id) {
                    $scope.unidade.tipo = t;
                }
            });
        };

        $scope.selecionarCurso = function(curso) {
            if ($scope.unidade.cursos === undefined) { $scope.unidade.cursos = []; }
            var qtd = $scope.unidade.cursos.length;
            var total = 0;
            $('.cursoUnidade:checked').each(function(){ total++; });
            if (qtd > total) {
                var cursoIds = [];
                $('.cursoUnidade:checked').each(function(){ cursoIds.push($(this).val()); });
                $timeout(function() {
                    for (var i=0; i<$scope.unidade.cursos.length; i++) {
                        var index = cursoIds.indexOf($scope.unidade.cursos[i].id);
                        if (index === -1) { $scope.unidade.cursos.splice(index,1); }
                    }
                },50);
            } else {
                $scope.unidade.cursos.push(curso.plain());
            }
            console.log($scope.unidade);
            $timeout(function() {
                var unidade = angular.copy($scope.unidade);
                delete unidade.instituicaoPai;
                Servidor.finalizar(unidade, 'unidades-ensino', 'Curso'); 
                $timeout(function() { 
                    var promise = Servidor.buscarUm('unidades-ensino',$scope.unidade.id);
                    promise.then(function(response){
                        $scope.unidade = response.data;
                        $timeout(function() {
                            Servidor.verificaLabels();
                            $('#cidade').material_select('destroy');
                            $('#cidade').material_select();
                            $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select('destroy');
                            $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select();
                        }, 100);
                    });
                }, 300);
            }, 100);
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

        /* Buscando unidades - Lista */
        $scope.buscarUnidades = function() {
            $scope.paginaAtual = 1;
            var promise = Servidor.buscar('unidades-ensino');
            promise.then(function (response){
                if (response.data.length > 0) {
                    $scope.unidades = response.data;
                    $scope.quantidadePaginas = Math.ceil(response.data.length/50);
                    $('.tooltipped').tooltip('remove');
                    $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 500);
                    if ($('#search').is(':disabled')) {
                        $('#search').attr('disabled',''); $('#search').css('background',''); $('#search').attr('placeholder','Digite aqui para buscar');
                    }
                } else {
                    if ($scope.unidades.length === 0) {
                        $('#search').attr('disabled','disabled'); $('#search').css('background','#ccc'); $('#search').attr('placeholder','');
                    }
                }
                $timeout(function (){ Servidor.entradaPagina(); },200);
            });
        };

        /* Salvando Unidade */
        $scope.finalizar = function() {
            if ($scope.validar('validate-unidade') === true) {
                $scope.mostraLoader(true);
                var novo = false;
                $scope.promise = null;
                if (!$scope.unidade.id) { $scope.pagina = 0; novo = true; }
                if ($scope.unidade.endereco.id !== undefined) {
                    var promiseEndereco = Servidor.buscarUm('enderecos',$scope.unidade.endereco.id);
                    promiseEndereco.then(function(responseAddr) {
                        var endereco = responseAddr.data;
                        endereco.bairro = $scope.unidade.endereco.bairro;
                        endereco.cep = $scope.unidade.endereco.cep;
                        endereco.cidade.id = $scope.unidade.endereco.cidade.id;
                        endereco.id = $scope.unidade.endereco.id;
                        endereco.latitude = $scope.unidade.endereco.latitude;
                        endereco.longitude = $scope.unidade.endereco.longitude;
                        endereco.logradouro = $scope.unidade.endereco.logradouro;
                        endereco.numero = $scope.unidade.endereco.numero;
                        $scope.promise = Servidor.finalizar(endereco, 'enderecos', null);
                    });
                } else {
                    $scope.promise = Servidor.finalizar($scope.unidade.endereco, 'enderecos', null);
                }
                $timeout(function () {
                    $scope.promise.then(function(response) {
                        $scope.unidade.endereco.id = response.data.id;
                        var telefones = $scope.unidade.telefones;
                        delete $scope.unidade.telefones;
                        $scope.tipos.forEach(function(t) { if(t.id === parseInt($scope.unidade.tipo.id)) { $scope.unidade.tipo = t; } });
                        if ($scope.unidade.cpfCnpj !== undefined && $scope.unidade.cpfCnpj !== null){
                            $scope.unidade.cpfCnpj = $scope.unidade.cpfCnpj.replace(/[.]/g,"");
                            $scope.unidade.cpfCnpj = $scope.unidade.cpfCnpj.replace(/[//]/g,"");
                            $scope.unidade.cpfCnpj = $scope.unidade.cpfCnpj.replace(/[-]/g,"");
                        }
                        var result = Servidor.finalizar($scope.unidade, 'unidades-ensino', 'Unidade');
                        result.then(function (response) {
                            $scope.unidade.id = response.data.id;
                            $scope.unidades.forEach(function (u, index) {
                                if (u.id === $scope.unidade.id) {
                                    $scope.unidades[index] = response.data;
                                }
                            });
                            if ($scope.telefone.numero && $scope.telefone.descricao) {
                                $scope.telefone.numero = $scope.telefone.numero.replace(/\D/g, '');
                                telefones.push($scope.telefone);
                            }
                            if (telefones.length > 0) {
                                $timeout(function () {
                                    for (var i = 0; i < telefones.length; i++) {
                                        if (!telefones[i].id) {
                                            telefones[i].pessoa.id = $scope.unidade.id;
                                            Servidor.finalizar(telefones[i], 'telefones', 'Telefone');
                                        }
                                    }
                                    $scope.inicializar(false);
                                    $scope.fechaLoader();
                                    $scope.fecharFormulario();
                                    if (novo) {
                                        $scope.unidades = [];
                                        $scope.buscarUnidades(false);
                                    }
                                }, 300);
                            } else {
                                $timeout(function () {
                                    $scope.inicializar(false);
                                    $scope.fechaLoader();
                                    $scope.fecharFormulario();
                                    if (novo) {
                                        $scope.unidades = [];
                                        $scope.buscarUnidades(false);
                                    }
                                }, 300);
                            }
                        });
                    });
                }, 1000);
            }
        };

        /* Previne os dados de serem descartados */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) {
                $('#modal-certeza').modal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* Validando Formulário */
        $scope.validar = function (id) {
            var result = Servidor.validar(id);
            if ($scope.unidade.cpfCnpj && $scope.unidade.cpjCnpj !== undefined) {
                var res = Servidor.validarCnpj($scope.unidade.cpfCnpj);
            } else {
                $scope.unidade.cpjCnpj = null;
                res = true;
            }
            if (result && res) { return true; }
        };

        /*Fecha Modal aberto*/
        $scope.fecharModal = function (id) {
            $('.lean-overlay').hide();
            $('#'+id).closeModal();
        };

        /* Preparando carregamento da unidade */

        $scope.carregar = function (unidade, nova, mobile, index){
            Servidor.animacaoEntradaForm(false);
            Servidor.inputNumero();
            $("#nomeUnidadeEnsino").focus();
            $('div').find('.unidade-banner').removeClass('topo-pagina');
            $("#telefone").mask("(00) 0000-00009");
            $('.filled-in').prop('checked', false);
            $timeout(function(){
                $("#nomeUnidadeEnsino").focus();
            },300);
            if (!mobile) {
                $scope.mostraLoader(true);
                $scope.reiniciar();
                if (nova){
                    $scope.acao = "Cadastrar";
                    $scope.unidade.endereco = $scope.endereco;
                    $scope.unidade.endereco.latitude = -26.929647;
                    $scope.unidade.endereco.longitude = -48.683661;
                    $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select('destroy');
                    $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select();
                } else {
                    $scope.acao = "Editar";
                    $("#nomeUnidadeEnsino").focus();
                    var promise = Servidor.buscarUm('unidades-ensino', unidade.id);
                    promise.then(function (response) {
                        $scope.unidade = response.data;
                        $timeout(function(){
                            $scope.buscaTelefones();
                            $scope.estadoId = $scope.unidade.endereco.cidade.estado.id;
                            $scope.cidadeId = $scope.unidade.endereco.cidade.id;
                            if ($scope.unidade.cursos !== undefined && $scope.unidade.cursos.length) {
                                $scope.unidade.cursos.forEach(function(curso) {
                                    $('#c' + curso.id).prop('checked', true);
                                });
                            }
                            var promise = Servidor.buscarUm('enderecos', response.data.endereco.id);
                            promise.then(function(response) {
                                $scope.unidade.endereco = response.data;
                            });
                            $timeout(function(){
                                $scope.buscaCidades();
                                Servidor.verificaLabels();
                                $('#cidade').material_select('destroy');
                                $('#cidade').material_select();
                                $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select('destroy');
                                $('#tipoUnidade, #instituicaoPai, #estado, #descricao').material_select();
                            },1000);
                        },500);
                    });
                }
                $timeout(function(){
                    if (!nova) { $('.opcoesUnidade' + unidade.id).hide(); }
                    $scope.buscaEstados();
                    if ($scope.editandoMobile) { $(".unidade-banner, .busca").hide(); }
                    if (!nova) { $scope.index = index; }
                    $scope.fechaLoader();
                    $scope.editando = true;
                    $timeout(function(){ $('#nomeUnidadeEnsinoFocus').focus(); },300);
                }, 300);
            }else {
                if (!nova){
                    $scope.editandoMobile = true;
                    $('.opcoesUnidade' + unidade.id).show();
                } else {
                    $scope.editandoMobile = true;
                    $scope.carregar(null,true,false);
                }
            }
        };

        /* Carregar informação da unidade */
        $scope.carregarInfo = function (unidade) {
            $scope.mostraLoader(true);
            var promise = Servidor.buscarUm('unidades-ensino',unidade.id);
            promise.then(function (response) { $scope.unidade = response.data; });
            $timeout(function(){
                $('.opcoesUnidade' + unidade.id).hide();
                $scope.fechaLoader();
                $scope.buscaTelefones();
                $timeout(function(){ $('#info-modal-unidade').modal();  },500);
            }, 300);
        };

        /* Verifica selects de Estado e Cidade */
        $scope.verificaSelectEstado = function (estadoId) { if (estadoId === $scope.estadoId) { return 'selected'; } };
        $scope.verificaSelectCidade = function (cidadeId) { if (cidadeId === $scope.cidadeId) { return true; } };


        /* Adiciona Telefone */
        $scope.salvarTelefone = function(telefone) {
            if(telefone.numero){
                telefone.numero = telefone.numero.replace(/\D/g,'');
            }
            if ($scope.unidade.id) {
                if (telefone.numero.length > 7 && telefone.descricao) {
                    telefone.pessoa.id = $scope.unidade.id;
                    $scope.mostraLoader(true);
                    $scope.telefoneSalvar = telefone;
                    var result = Servidor.finalizar($scope.telefoneSalvar, 'telefones', 'Telefone');
                    result.then(function() {
                        $scope.buscaTelefones();
                        $scope.telefone = { "descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
                        $timeout(function() {
                            $scope.fechaLoader();
                            $('.lean-overlay').hide();
                            $('#telefone-modal-unidade').closeModal();
                        },500);
                    });
                } else {
                    Materialize.toast('Preencha todos os campos!', 1500);
                }
            } else if(telefone.numero){
                if (telefone.numero.length > 7) {
                    $scope.unidade.telefones.push(telefone);
                    $scope.telefone = {"descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
                    Materialize.toast('A nova unidade ainda não está salva! Conclua o cadastro para salvar as modificações.', 4000);
                } else {
                    Materialize.toast('Número Inválido!', 1500);
                }
            }else{ Materialize.toast('Digite um número de telefone.', 2000); }
            $timeout(function() {
                $scope.telefone.descricao = '';
                $('#descricao').material_select('destroy');
                $('#descricao').material_select();
            },50);
        };

        /* Busca Telefones */
        $scope.buscaTelefones = function() {
            var promise = Servidor.buscar('telefones',{'pessoa':$scope.unidade.id });
            promise.then(function(response) {
                $scope.unidade.telefones = response.data;
                if($scope.unidade.telefones.length>0) { $scope.listaTelefone = true;
                } else { $scope.listaTelefone = false; }
            });
        };

        /* Remove Telefone */
        $scope.removerTelefone = function(telefone) {
            if ($scope.acao === "Editar") {
                Servidor.remover(telefone, 'Telefone');
            }
            $scope.unidade.telefones.forEach(function(t, index){
                if(t.numero === telefone.numero){
                    $scope.unidade.telefones.splice(index, 1);
                }
            });
        };

        /* Carrega Telefone Para os Inputs */
        $scope.carregaTelefone = function(telefone) {
            if(telefone){
                var promise = Servidor.buscarUm('telefones', telefone.id);
                promise.then(function(response){
                   $scope.telefone = response.data;
                });
                telefone = $scope.telefone;
                $scope.opcaoDeEnvio = 'EDITAR';
            } else {
                $scope.resetaTelefone();
                $scope.opcaoDeEnvio = 'ADICIONAR';
            }
            $timeout(function() {
                $('#descricao').material_select('destroy');
                $('#descricao').material_select();
                $('#telefone-modal-unidade').modal();
                $('.lean-overlay').on('click', function() {
                    $('.lean-overlay').hide();
                });
                Servidor.verificaLabels();
            }, 500);
        };

        /* Seleciona a descricao correta no select */
        $scope.verificaSelectDescricao = function(tipo) {
            var retorno = false;
            if($scope.telefone.descricao) {
                if ($scope.telefone.descricao.length === tipo.length) {
                    retorno = true;
                }
            }
            return retorno;
        };



        /* Busca Tipos de Unidade */
        $scope.buscarTipos = function() {
            var promise = Servidor.buscar('tipos-unidade-ensino',null);
            promise.then(function (response){
                $scope.tipos = response.data;
            });
        };

        /* Busca Instituições */
        $scope.buscarInstituicoes = function() {
            var promise = Servidor.buscar('instituicoes',null);
            promise.then(function (response){
                $scope.instituicoes = response.data;
            });
        };

        /* Guarda a instituição para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (unidade, index){
            $('#remove-modal-unidade').modal();
            $scope.unidadeRemover = unidade;
            $('.opcoesunidade' + unidade.id).hide();
            $scope.index = index;
        };

        /* Remove a instituição */
        $scope.remover = function (){
            $scope.mostraProgresso();
            var id = $scope.unidadeRemover.id;
            Servidor.remover($scope.unidadeRemover, 'Unidade');
            $scope.unidades.forEach(function(u, index){
                if(u.id === id){
                    $scope.unidades.splice(index,1);
                    $scope.fechaProgresso();
                }
            });
        };

        /* Abre o formulário de edição/cadastro */
        $scope.carregarFormulario = function () {
            $scope.mostraLoader(); Servidor.cardSai(['.info-card','.lista-geral', '.add-btn'], true);
            $scope.unidade.endereco = $scope.endereco;
            if ($scope.unidade.nome === null){
                $scope.acao = "CADASTRAR";
                $scope.unidade.endereco = $scope.endereco;
                $scope.unidade.endereco.latitude = -26.929647;
                $scope.unidade.endereco.longitude = -48.683661;
            }
            $timeout(function(){
                $scope.buscaEstados();
                $scope.editando = true;
                Servidor.verificaLabels();
                $('.nav-wrapper').addClass('ajuste-nav-direita');
                $("#telefone").mask("(00) 0000-00009");
                $timeout(function(){
                    $("#nomeUnidadeEnsino").focus();
                    $timeout(function () {Servidor.cardEntra('.form-geral');}, 500);
                    //$timeout(function () { $scope.initMap(false);}, 500);
                    Servidor.cardEntra('.form-geral');
                    $('#tipo, #instituicao, #estado').material_select('destroy');
                    $('#tipo, #instituicao, #estado').material_select();
                    //$timeout(function(){ $scope.initMap(false, "mapa"); },1000);
                    $("#nomeUnidadeEnsino").focus();
                },500);
            },500);
        };

        /* Fecha o formulário de cadastro/edição */
        $scope.fecharFormulario = function () {
            if ($scope.editandoMobile) { $(".unidade-banner, .busca").show(); $scope.editandoMobile = false; }
            Servidor.animacaoEntradaLista(false);
            $timeout(function (){ $scope.editando = false; },300);
            $("#nomeUnidade").focus();
            $scope.resetaTelefone(); $scope.resetaEndereco();
            $scope.listaTelefone = false; $scope.telefones = [];
            $scope.reiniciar(); Servidor.resetarValidador('validate-unidade');
            $scope.estadoId = null; $scope.cidadeId = null;
            $scope.acao = "CADASTRAR";
            $('#tipoUnidade, #instituicaoPai, #estado, #cidade').material_select('destroy');
            $('.nav-wrapper').removeClass('ajuste-nav-direita'); $("#mapa").html('');
//            $('div').find('.unidade-banner').addClass('topo-pagina');
            //$scope.buscarUnidades();
            $timeout(function(){Servidor.verificaLabels();},1000);
        };


        $scope.selecionaEstado = function () {
            var estado;
            for (var i=0; i<$scope.estados.length;i++) {
                if ($scope.estados[i].id === parseInt($scope.estadoId)) { estado = $scope.estados[i]; }
            }
            $scope.estadoId = estado.id;
            $scope.unidade.endereco.cidade.estado = estado;
            $scope.unidade.endereco.cidade.id = null;
            $scope.cidadeId = null;
            $scope.unidade.endereco.cidade.nome = null;
            $scope.buscaCidades();
            $timeout(function(){ $scope.buscaCoordenadasPorEnderecoCompleto(); },500);
        };

        $scope.selecionaCidade = function () {
            var cidade = null;
            for (var i=0; i<$scope.cidades.length;i++) {
                if ($scope.cidades[i].id === parseInt($scope.cidadeId)) { cidade = $scope.cidades[i]; }
            }
            $scope.unidade.endereco.cidade = cidade;
//            $scope.unidade.endereco.bairro = null;
//            $scope.unidade.endereco.logradouro = null;
            $timeout(function(){ $scope.buscaCoordenadasPorEnderecoCompleto(); },500);
        };

        /* Busca estados  - SelectBox */
        $scope.buscaEstados = function () {
            $scope.mostraLoader();
            Servidor.buscarEstados().getList().then(function(response){
                $scope.estados = response.plain();
                $timeout(function (){
                    $('#estado').material_select('destroy'); $('#estado').material_select();
                    $scope.fechaLoader();
                },500);
            });
        };

        $scope.buscaCidades = function (id) {
            $scope.mostraLoader();
            var promise = Servidor.buscar('cidades',{estado: $scope.unidade.endereco.cidade.estado.id});
            promise.then(function(response){
                $scope.cidades = response.data;
                $timeout(function (){
                    $('#cidade').material_select('destroy');
                    $('#cidade').material_select();
                    $scope.fechaLoader();
                },1000);
            });
        };

        /* Busca de endereço pelo CEP */
        $scope.buscaCEP = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined || query === null) { query = ''; }
                var tamanho = query.length;
                if (tamanho === 8) {
                    $scope.mostraLoader();
                    Servidor.consultaCep(query);
                    $timeout(function (){
                        var endereco = Servidor.recuperaCep();
                        if (endereco[0]) { $scope.unidade.endereco.logradouro = endereco[0]; } else { $scope.unidade.endereco.logradouro = ''; }
                        if (endereco[1]) { $scope.unidade.endereco.bairro = endereco[1]; } else { $scope.unidade.endereco.bairro = ''; }
                        /* Buscando Estado */
                        if (endereco[3]) {
                            var promise = Servidor.buscar('estados',{'sigla': endereco[3]});
                            promise.then(function (response){
                                var estado = response.data;
                                $scope.unidade.endereco.cidade.estado = estado[0].plain();
                                $scope.estadoId = estado[0].id;
                                $scope.cidadeId = null;
                                $scope.unidade.endereco.cidade.id = null;
                                $scope.unidade.endereco.cidade.nome = null;
                                $scope.buscaEstados();
                                $scope.buscaCidades();
                                if (endereco[2]) {
                                    $timeout(function (){
                                        /* Buscando Cidade */
                                        var promise = Servidor.buscar('cidades',{'nome': endereco[2],'estado': $scope.unidade.endereco.cidade.estado.id });
                                        promise.then(function (response){
                                            var cidade = response.data;
                                            $scope.unidade.endereco.cidade = cidade[0].plain();
                                            $scope.cidadeId = cidade[0].id;
                                            $timeout(function(){
                                                $scope.buscaCoordenadasPorEndereco();
                                                $('#cidade').material_select('destroy');
                                                $('#cidade').material_select();
                                                Servidor.verificaLabels();
                                                $scope.fechaProgresso();
                                                if(!$scope.unidade.id){
                                                    if(!$scope.unidade.endereco.bairro){
                                                        $("#logradouro").focus();
                                                    }else{
                                                        $("#numero").focus();
                                                    }
                                                }
                                            },500);
                                        });
                                    },500);
                                } else { $scope.fechaLoader(); $scope.endereco.cidade.id = ''; $('#cidade').material_select(); }
                            });
                        } else { $scope.fechaLoader(); $scope.endereco.cidade.estado.id = ''; $('#estado').material_select(); }
                    }, 500);
                }
            }, 200);
        };

        $scope.selecionaTipo = function (tipo){
            $scope.unidade.tipo = tipo;
            $scope.fechaSelectTipo();
        };

        $scope.selecionaInstituicao = function (instituicao){
            $scope.unidade.instituicao = instituicao;
            $scope.fechaSelectInstituicao();
        };

        /* Verifica selects de Instituição e Tipo */
        $scope.verificaSelectInstituicao = function (id) {
            if ($scope.unidade.instituicaoPai !== undefined && $scope.unidade.instituicaoPai !== null) {
                if (id === $scope.unidade.instituicaoPai.id) {
                    return true;
                }
            }
        };

        $scope.verificaSelectTipo = function (id) {
            if ($scope.unidade.tipo !== undefined) {
                if (id === $scope.unidade.tipo.id) {
                    return true;
                }
            }
        };

        /* Verifica se o campo de busca foi preenchido  */

        $scope.$watch("nomeUnidade", function(query){
            if(!$scope.nomeUnidade || $scope.nomeUnidade === undefined){
                $scope.unidades = [];
                $scope.limparBusca();
                $scope.buscarUnidades();
                $scope.icone='search';
            }else{
                $scope.buscaUnidade(query);
                $scope.icone='clear';
            }
        });

        /* Função de Busca */
        $scope.buscaUnidade = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.paginaAtual = 1;
            $scope.delayBusca = $timeout(function(){
                if (query === undefined) { query = ''; }
                var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Servidor.buscar('unidades-ensino',{'nome':query});
                    res.then(function(response){
                        $scope.unidades = response.data;
                        $scope.quantidadePaginas = Math.ceil(response.data/50);
                        $timeout(function (){ $scope.inicializar(false); $('.collection li').css('opacity',1); $('.paginate').hide(); });
                    });
                } else {
                    if (tamanho === 0) {
                        $scope.inicializar(false);
                        $scope.buscarUnidades(false);
                        $('.collection li').css('opacity','');
                        $('.paginate').show();
                    }
                }
            }, 1000);
        };

        /* Resetar Busca */
        $scope.resetaBusca = function (){
            $scope.nomeUnidade = '';
            $('.paginate').show();
        };

        /*limpar busca*/
        $scope.limparBusca = function(){
            $scope.nomeUnidade = '';
            $('.paginate').show();
        };

        /* Inicializando Instituições */
        $scope.inicializar(true);
        $scope.buscarUnidades(0);
        $scope.buscarTipos();
        $scope.buscarInstituicoes();
    }]);
})();

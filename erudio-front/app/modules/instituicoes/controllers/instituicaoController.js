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
    var instituicaoModule = angular.module('instituicaoModule', ['servidorModule','instituicaoDirectives']);
    
    instituicaoModule.controller('InstituicaoController', ['$scope', 'Servidor', 'Restangular', '$timeout', '$templateCache', function($scope, Servidor, Restangular, $timeout, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('INSTITUICAO');
        $scope.instituicoes = []; $scope.estados = []; $scope.cidades = [];
        $scope.estadoId = null; $scope.cidadeId = null; $scope.instituicaoRemover = null; $scope.nomeInstituicao = '';
        
        /* Atributos de controle da página */
        $scope.acao = "";
        $scope.editando = false;
        $scope.editandoMobile = false;
        $scope.loader = false;
        $scope.progresso = false;
        $scope.cortina = false;
        $scope.listaTelefone = false;
        $scope.opcaoDeEnvio = '';
        $scope.pagina = 0;
        $scope.primeiroScroll = true;
        $scope.index = null;
        $scope.habilitaClique = true;
        
        /* Estrutura de Instituição */
        $scope.instituicao = { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] };
        
        /* Estrutura de enredeco */
        $scope.endereco = { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null }, latitude: null, longitude: null } };
        
        /* Estrutura de Telefone */
        $scope.telefone = {"descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
        
        /* Controle da barra de progresso e loader */
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } };
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; };
        
        /* Reinciando Estrutura de Instituição*/
        $scope.reiniciar = function (){ $scope.instituicao = { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] }; };
        
        /* Reiniciando Estrutura de Telefone */
        $scope.resetaTelefone = function() {
            $scope.telefone = {"descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
            $('#telefone-modal-instituicao').closeModal();
            $('.lean-overlay').hide();
        };
        
        $scope.resetaEndereco = function() { $scope.endereco = { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null }, latitude: null, longitude: null } }; };
        
        /* Inicializando */
        $scope.inicializar = function (inicializaUmaVez) {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){      
                if (inicializaUmaVez) {
                    $('.counter').each(function(){ $(this).characterCounter(); }); 
                    $('#cnpj').mask('00000000000000');
                    $('#instituicaoForm').keydown(function(event){ 
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) { //if enter is pressed
                                $timeout(function(){ if ($scope.habilitaClique) { $('#salvarInstituicao').trigger('click'); } else { $scope.habilitaClique = true; } },300);
                            }
                        }
                    });                                        
                    $('#descricaoInstituicao').material_select();     
                    $('.cep').mask("00000009");
                }                          
                $('.tooltipped').tooltip({delay: 30});
            },500);
        };
        
        /* Inicializar Mapa */
        $scope.initMap = function (comJanela, idMap) {
            if ($scope.instituicao.endereco !== null) {
                var map; var latLng = new google.maps.LatLng($scope.instituicao.endereco.latitude, $scope.instituicao.endereco.longitude);
                var options = { zoom: 17, center: latLng }; map = new google.maps.Map(document.getElementById(idMap), options);
                $scope.marker = new google.maps.Marker({ position: latLng, title: $scope.instituicao.nome, map: map });
                var infowindow = new google.maps.InfoWindow(), marker;
                google.maps.event.addListener(map, 'click', (function(event) {
                    $scope.marker.setMap(null); var newLatLng = event.latLng;
                    $scope.instituicao.endereco.latitude = newLatLng.lat();
                    $scope.instituicao.endereco.longitude = newLatLng.lng();
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
        
        /* Busca coordenada por endereço */
        $scope.buscaCoordenadasPorEndereco = function (){
            if ($scope.instituicao.endereco.numero === null) { $scope.instituicao.endereco.numero = ''; }
            $.ajax({
                url: 'http://maps.google.com/maps/api/geocode/json?address=' + $scope.instituicao.endereco.logradouro + ',' + $scope.instituicao.endereco.bairro + ',' + $scope.instituicao.endereco.cidade.nome + ',' + $scope.instituicao.endereco.cidade.estado.nome + ',' + $scope.instituicao.endereco.numero,
                dataType: 'JSON', type: 'get',
                success: function(data){
                    if (data.status !== 'ZERO_RESULTS') {
                        $scope.instituicao.endereco.latitude = data.results[0].geometry.location.lat;
                        $scope.instituicao.endereco.longitude = data.results[0].geometry.location.lng;
                        $scope.initMap(true, "mapa");
                    } else {
                        $scope.instituicao.endereco.latitude = -26.929647;
                        $scope.instituicao.endereco.longitude = -48.683661;
                        $scope.initMap(true, "mapa");
                    }
                }
            });
        };
        
        /* Busca coordenada por endereço digitado */
        $scope.buscaCoordenadasPorEnderecoCompleto = function (){
            if (($scope.instituicao.endereco.cep === null || $scope.instituicao.endereco.cep === '' || $scope.instituicao.endereco.cep === undefined) && ($scope.instituicao.endereco.logradouro !== null && $scope.instituicao.endereco.bairro !== null && $scope.instituicao.endereco.cidade.nome !== null && $scope.instituicao.endereco.cidade.estado.nome !== null)) {
                $scope.buscaCoordenadasPorEndereco();
            }
        };

        /* BUSCANDO INSTITUICOES */
        $scope.buscarInstituicoes = function() {
            var promise = Servidor.buscar('instituicoes',{page: $scope.pagina});
            $('.modal-trigger').leanModal();
            $('.tooltipped').tooltip('remove');
            promise.then(function (response){
                if (response.data.length > 0) {
                    var instituicoes = response.data; $scope.instituicoes = instituicoes;
                    $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                    $timeout(function (){ $('.modal-trigger').leanModal({ dismissible: true , in_duration: 100, out_duration: 100 }); },500);
                    Servidor.entradaPagina();
                } else {
                    if ($scope.pagina !== 0) { $scope.pagina--; }
                    Servidor.entradaPagina();
                }
            });
        };

        /* Salvando Instituição */
        $scope.finalizar = function() {            
            if ($scope.validar('validate') === true) {
                $scope.mostraLoader(true);
                var novo = false;
                if (!$scope.instituicao.id) { $scope.pagina = 0; novo = true; }
                var endereco = Restangular.copy($scope.instituicao.endereco);
                var resultadoEndereco = Servidor.finalizar(endereco,'enderecos','Instituição(Endereço)');
                if (resultadoEndereco) {
                    var novoEndereco = resultadoEndereco.$object;
                    console.log(novoEndereco);
                    $timeout(function(){
                        $scope.instituicao.endereco = { id:novoEndereco.id };
                        $timeout(function(){                            
                            var result = Servidor.finalizar($scope.instituicao,'instituicoes','Instituição');
                            if (result) { 
                                var instituicaoId = result.$object;
                                if ($scope.telefone.numero && $scope.telefone.descricao) { $scope.telefone.numero = $scope.telefone.numero.replace(/\D/g,''); $scope.instituicao.telefones.push($scope.telefone); }
                                if ($scope.instituicao.telefones.length > 0) {
                                    $timeout(function(){
                                        for (var i=0; i < $scope.instituicao.telefones.length; i++) {
                                            if(!$scope.instituicao.telefones[i].id) {
                                                $scope.instituicao.telefones[i].pessoa.id = instituicaoId.id;
                                                Servidor.finalizar($scope.instituicao.telefones[i], 'telefones', 'Telefone');
                                            }                                            
                                        }
                                        $timeout(function (){ 
                                            $scope.inicializar(false);
                                            $scope.fechaLoader();
                                            $scope.fecharFormulario();
                                            if (!novo) {
                                                $scope.instituicoes.forEach(function(i,index){
                                                    if(i.id === instituicaoId.id){
                                                        $scope.instituicoes[index] = instituicaoId;
                                                    }
                                                });
                                            } else {
                                                $scope.instituicoes = [];
                                                $scope.buscarInstituicoes(false);
                                            }
                                        },300);
                                    },300);
                                } else {
                                    $timeout(function (){ 
                                        $scope.inicializar(false);
                                        $scope.fechaLoader();
                                        $scope.fecharFormulario();
                                        if (!novo) {
                                            $scope.instituicoes.forEach(function(i,index){
                                                if(i.id === instituicaoId.id){
                                                    $scope.instituicoes[index] = instituicaoId;
                                                }
                                            });
                                        } else {
                                            $scope.instituicoes = [];
                                            $scope.buscarInstituicoes(false);
                                        }
                                    },300);
                                }
                            }
                        },500);
                    },1000);
                }
            }
        };

        /* Previne os dados de serem descartados */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) { $('#modal-certeza').openModal(); } else { $scope.fecharFormulario(); }
        };
        
        /* Validando Formulário */
        $scope.validar = function (id) {
            var result = Servidor.validar(id);
            if ($scope.instituicao.cpfCnpj !== null && $scope.instituicao.cpfCnpj !== '' && $scope.instituicao.cpfCnpj !== undefined) { var res = Servidor.validarCnpj($scope.instituicao.cpfCnpj); } else { $scope.instituicao.cpfCnpj = null; res = true; }
            if (result && res) { 
                //$scope.instituicao.cpfCnpj = cnpj;
                return true; 
            }
        }; 
        
        /* Carregar instituicao */
        $scope.carregar = function (instituicao, nova, mobile, index) {
            $('.tooltipped').tooltip('remove');
            Servidor.animacaoEntradaForm(false);
            $timeout(function(){
                $('.tooltipped').tooltip({delay: 30});
                if (!mobile) {
                    $("#telefone").mask("(00) 0000-00000");
                    //$scope.mostraLoader(true);
                    $scope.reiniciar();
                    if (nova) { 
                        $scope.acao = "Cadastrar";
                        $scope.instituicao.endereco = $scope.endereco;
                        $scope.instituicao.endereco.latitude = -26.929647;
                        $scope.instituicao.endereco.longitude = -48.683661;
                    } else {
                        $scope.acao = "Editar";
                        var promise = Servidor.buscarUm('instituicoes', instituicao.id);
                        promise.then(function (response) { 
                            $('.tooltipped').tooltip({delay: 30});
                            $scope.instituicao = response.data;
                            $scope.estadoId = $scope.instituicao.endereco.cidade.estado.id;
                            $scope.cidadeId = $scope.instituicao.endereco.cidade.id;
                            $scope.buscaCidades();
                        });
                        //$scope.instituicao = Restangular.copy(instituicao);
                    }
                    
                    $timeout(function(){
                        if (!nova) { $('.opcoesInstituicao' + instituicao.id).hide(); }
                        $scope.buscaEstados();
                        $('.tooltipped').tooltip({delay: 30});
                        if ($scope.editandoMobile) { $(".instituicao-banner, .busca").hide(); }
                        if (!nova) { $scope.buscaTelefones(); $scope.index = index; }
                        //$scope.fechaLoader();
                        Servidor.verificaLabels();                    
                        $('div').find('.instituicao-banner').removeClass('topo-pagina');
                        $scope.editando = true;
                        $timeout(function(){ 
                            $scope.initMap(false, "mapa"); $('#nomeInstituicaoFocus').focus(); 
                        }, 300);
                    }, 300);
                } else {
                    if (!nova){
                        $('div').find('.instituicao-banner').removeClass('topo-pagina');
                        $('.tooltipped').tooltip({delay: 30});
                        $scope.editandoMobile = true;
                        $('.opcoesInstituicao' + instituicao.id).show();
                        $timeout(function(){{$('.tooltipped').tooltip   ({delay: 30});}});
                    } else {
                        $scope.editandoMobile = true;
                        $scope.carregar(null,true,false);
                        $('.tooltipped').tooltip({delay: 30});
                    }
                }
                $('#descricaoInstituicao').material_select();
                $('.tooltipped').tooltip({delay: 30});
                $('.cnpj').mask('00.000.000/0000-09');
            }, 500);
            //Servidor.inputNumero();
        };
        
        /* Carregar informação da instituicao */
        $scope.carregarInfo = function (instituicao) {
            $scope.mostraLoader(true);
            var promise = Servidor.buscarUm('instituicoes',instituicao.id);
            promise.then(function (response) { $scope.instituicao = response.data; });
            $timeout(function(){
                $('.opcoesInstituicao' + instituicao.id).hide();
                $scope.fechaLoader(); $scope.buscaTelefones();
                $timeout(function(){ $scope.initMap(false, "info-map"); },500);
            }, 300);
        };
        
        /* Verifica selects de Estado e Cidade */
        $scope.verificaSelectEstado = function (estadoId) {  if (estadoId === $scope.estadoId) { return true; } };
        $scope.verificaSelectCidade = function (cidadeId) {  if (cidadeId === $scope.cidadeId) { return true; } };
        
        /* Guarda a instituição para futura remoção e abre o modal de confirmação */
        $scope.prepararRemover = function (instituicao){ 
            $scope.instituicaoRemover = instituicao; 
            $('.opcoesInstituicao' + instituicao.id).hide();
        };
        
        /* Remove a instituição */
        $scope.remover = function (){
            $scope.mostraProgresso();
            var id = $scope.instituicaoRemover.id;
            Servidor.remover($scope.instituicaoRemover, 'Instituição');
            $scope.instituicoes.forEach(function(i, index){ if(i.id === id){ $scope.instituicoes.splice(index, 1); } });
            $scope.fechaProgresso();
        };

        /* Fecha o formulário de cadastro/edição */
        $scope.fecharFormulario = function () {
            if ($scope.editandoMobile) { $(".instituicao-banner, .busca").show(); $scope.editandoMobile = false; }
            Servidor.animacaoEntradaLista(false);
            $timeout(function (){ $scope.editando = false; },300);
            $scope.listaTelefone = false;
            $scope.reiniciar(); $scope.resetaTelefone(); $scope.resetaEndereco();
            Servidor.resetarValidador('validate');
            $scope.estadoId = null; $scope.cidadeId = null;
            $('#cidade').material_select('destroy');
            $('.nav-wrapper').removeClass('ajuste-nav-direita'); $("#mapa").html('');
            $timeout(function (){ $scope.acao = "Cadastrar"; Servidor.verificaLabels(); },1000);
        };
        
        /* Selecionar Item SelectBox */
        $scope.selecionaEstado = function (){
            var estado;
            for (var i=0; i<$scope.estados.length;i++) { if ($scope.estados[i].id === parseInt($scope.estadoId)) { estado = $scope.estados[i]; } }
            $scope.estadoId = estado.id; $scope.instituicao.endereco.cidade.estado = estado;
            $scope.cidadeId = null; $scope.instituicao.endereco.cidade.id = null;
            $scope.instituicao.endereco.cidade.nome = null; $scope.buscaCidades();
            $timeout(function(){ $scope.buscaCoordenadasPorEnderecoCompleto(); },500);
        };
        
        /* Seleciona select de cidade */
        $scope.selecionaCidade = function (){
            var cidade = null;
            for (var i=0; i<$scope.cidades.length;i++) { if ($scope.cidades[i].id === parseInt($scope.cidadeId)) { cidade = $scope.cidades[i]; } }
            $scope.instituicao.endereco.cidade = cidade; $scope.instituicao.endereco.bairro = null;
            $scope.instituicao.endereco.logradouro = null;
            $timeout(function(){ $scope.buscaCoordenadasPorEnderecoCompleto(); },500);
        };
        
        /* Busca estados  - SelectBox */
        $scope.buscaEstados = function () {
            Servidor.buscarEstados().getList().then(function(response){ 
                $scope.estados = response.plain();
                $timeout(function (){ $('#estadoInstituicao').material_select('destroy'); $('#estadoInstituicao').material_select(); },500);
            });                            
        };
        
        /* Busca de Cidades - SelectBox*/
        $scope.buscaCidades = function () {
            var promise = Servidor.buscar('cidades',{'estado': $scope.instituicao.endereco.cidade.estado.id });
            promise.then(function(response){
                $scope.cidades = response.data;
                $timeout(function (){ $('#cidadeInstituicao').material_select('destroy'); $('#cidadeInstituicao').material_select(); },500);
            });
        };          
        
        /* Verifica se o campo de busca foi preenchido  */
        $scope.$watch("nomeInstituicao", function(query){
            $scope.buscaInstituicao(query);
            if(!query) {$scope.icone = 'search'; } else { $scope.icone = 'clear'; }
        });
        
        /* Função de Busca */
        $scope.buscaInstituicao = function (query) {
            $('.modal-trigger').leanModal(); $('.tooltipped').tooltip({delay: 30});
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined) { query = ''; }
                var tamanho = query.length;
                if (tamanho > 3) {
                    var res = Servidor.buscar('instituicoes',{'nome':query});
                    res.then(function(response){
                        $scope.instituicoes = response.data;
                        $timeout(function (){ $scope.inicializar(false); });
                    });
                } else {
                    if (tamanho === 0) {
                        $scope.inicializar(false); $scope.buscarInstituicoes(true);
                    }
                }
            }, 1000);
        };
        
        /* Resetar Busca */
        $scope.resetaBusca = function (){ $scope.nomeInstituicao = ''; };
        
        /* Busca de endereço pelo CEP */
        $scope.buscaCEP = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined || query === null) { query = ''; }
                if (query.length === 8) {
                    $scope.mostraLoader(); Servidor.consultaCep(query);                    
                    $timeout(function (){ 
                        var endereco = Servidor.recuperaCep();
                        if (endereco[0] && endereco[0] !== null || endereco[0] !== undefined) { $scope.instituicao.endereco.logradouro = endereco[0]; } else { $scope.instituicao.endereco.logradouro = ''; }
                        if (endereco[1] && endereco[1] !== null || endereco[1] !== undefined) { $scope.instituicao.endereco.bairro = endereco[1]; } else { $scope.instituicao.endereco.bairro = ''; }
                        /* Buscando Estado */
                        if (endereco[3]) {
                            var promise = Servidor.buscar('estados',{'sigla': endereco[3]});
                            promise.then(function (response){ 
                                var estado = response.data; $scope.instituicao.endereco.cidade.estado = estado[0].plain();
                                $scope.estadoId = estado[0].id; $scope.cidadeId = null;
                                $scope.instituicao.endereco.cidade.id = null; $scope.instituicao.endereco.cidade.nome = null;
                                $scope.buscaCidades();
                                $timeout(function() { $('#estado').material_select('destroy'); $('#estado').material_select(); }, 50);
                                if (endereco[2]) {
                                    /* Buscando Cidade */
                                    var promise = Servidor.buscar('cidades',{'nome': endereco[2],'estado': $scope.instituicao.endereco.cidade.estado.id });
                                    promise.then(function (response){
                                        var cidade = response.data; $scope.instituicao.endereco.cidade = cidade[0]; $scope.cidadeId = cidade[0].id;
                                        $timeout(function(){
                                            $scope.buscaCoordenadasPorEndereco();
                                            $('#cidade').material_select('destroy'); $('#cidade').material_select();
                                            Servidor.verificaLabels();
                                            if(!$scope.instituicao.id){ if(!$scope.instituicao.endereco.bairro){ $("#logradouro").focus(); }else{ $("#numero").focus(); } }
                                        },50);
                                    });
                                } else {
                                    $scope.cidadeId = null;                                    
                                    $timeout(function(){ $('#cidade').material_select('destroy'); $('#cidade').material_select(); }, 50);
                                }                              
                            });
                        } else {
                            $scope.estadoId = null; $scope.cidadeId = null;
                            $timeout(function() { $('#estado, #cidade').material_select('destroy'); $('#estado, #cidade').material_select(); }, 50);
                        }                      
                    },1000);
                    $scope.fechaLoader();
                }
            }, 200);
        };
        
         /* Limpar busca */
        $scope.limparBusca = function(){ 
            $scope.nomeInstituicao=''; 
            $('.tooltipped').tooltip({delay: 30});
          
        };
        
        /* Adiciona Telefone */
        $scope.salvarTelefone = function(telefone) {
            if(telefone.numero){ telefone.numero = telefone.numero.replace(/\D/g,''); }
            if ($scope.instituicao.id) {
                if (telefone.numero.length > 7 && telefone.descricao) {
                    telefone.pessoa.id = $scope.instituicao.id;
                    $scope.mostraLoader(true);
                    var result = Servidor.finalizar(telefone, 'telefones', 'Telefone');
                    result.then(function() {
                        $scope.buscaTelefones();
                        $scope.telefone = { "descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
                        $timeout(function() {
                            $scope.fechaLoader();
                            $('.lean-overlay').hide(); $('#telefone-modal-instituicao').closeModal(); $('.tooltipped').tooltip({delay: 30});
                            $timeout(function(){{$('.tooltipped').tooltip({delay: 30});}});
                        },500);
                    });
                } else {
                    Materialize.toast('Por favor, preencha todos os campos', 1500);
                }
            } else if (telefone.numero){
                if (telefone.numero.length > 7 && telefone.descricao) {
                    $scope.instituicao.telefones.push(telefone);
                    $scope.telefone = {"descricao": '', "falarCom": null, "numero": null, "pessoa": { 'id': null } };
                    Materialize.toast('A nova instituição ainda não está salva! Conclua o cadastro para salvar as modificações.', 4000);
                } else { Materialize.toast('Número Inválido!', 1500); }
            }else{ Materialize.toast('Digite um número de telefone.', 2000); }
            $timeout(function() {
                $scope.telefone.descricao = '';
                $('#descricaoInstituicao').material_select('destroy'); $('#descricaoInstituicao').material_select(); $('.tooltipped').tooltip({delay: 30});
            },50);            
        };
        
        /* Busca Telefones */
        $scope.buscaTelefones = function() {
            $('.tooltipped').tooltip({delay: 30});
            var promise = Servidor.buscar('telefones',{ pessoa: $scope.instituicao.id });
            promise.then(function(response) {
                $scope.instituicao.telefones = response.data;
                if($scope.instituicao.telefones.length>0) { $scope.listaTelefone = true; } else { $scope.listaTelefone = false; }
            });
        };
        
        /* Remove Telefone */
        $scope.removerTelefone = function(telefone){
            if ($scope.acao === "Editar"){ Servidor.remover(telefone, 'Telefone'); $('.tooltipped').tooltip({delay: 30}); }
            for (var i = 0; i < $scope.instituicao.telefones.length; i++) {
                if (telefone.numero === $scope.instituicao.telefones[i].numero) { $scope.instituicao.telefones.splice(i, 1); }
            }
        };         
        
        /* Inicializando Instituições */
        $scope.inicializar(true);
        $scope.buscarInstituicoes(false);
    }]);
})();

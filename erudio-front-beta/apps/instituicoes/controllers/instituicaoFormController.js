(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    var instituicoesForm = angular.module('instituicoesForm',['ngMaterial', 'util', 'rest', 'erudioConfig','instituicaoDirectives']);
    instituicoesForm.controller('InstituicaoFormController',['$scope', 'Util', 'REST', 'ErudioConfig', '$routeParams', '$timeout', function($scope, Util, REST, ErudioConfig, $routeParams, $timeout){
        /*
         * @attr permissao
         * @attrType boolean
         * @attrDescription Flag para verificar permissão do módulo.
         * @attrExample 
         */
        $scope.permissao = Util.verificaPermissao('INSTITUICOES');        
        if ($scope.permissao) {
            Util.comPermissao();
            //SETA O TITULO
            Util.setTitulo('Instituições');
            //ATRIBUTOS EXTRAS
            $scope.telefones = [];
            $scope.tiposTelefone = ['CELULAR','COMERCIAL','RESIDENCIAL'];
            $scope.link = '/#!/instituicoes/';
            $scope.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: ErudioConfig.dominio + $scope.link};
            /*
            * @attr escrita
            * @attrType boolean
            * @attrDescription Flag para verificar escrita no módulo.
            * @attrExample 
            */
            $scope.escrita = Util.verificaEscrita('INSTITUICOES');
            /*
            * @attr leitura
            * @attrType string
            * @attrDescription URL para partial template de somente leitura.
            * @attrExample 
            */
            $scope.leitura = Util.getTemplateLeitura();
            /*
            * @attr form
            * @attrType string
            * @attrDescription URL para partial template de formulário.
            * @attrExample 
            */
            $scope.form = Util.getTemplateForm();
            /*
            * @attr isAdmin
            * @attrType boolean
            * @attrDescription Flag para verificar se o usuário é administrador.
            * @attrExample 
            */
            $scope.isAdmin = Util.isAdmin();
            /*
            * @attr attr
            * @attrType Array
            * @attrDescription Array com as atribuições do usuário.
            * @attrExample 
            */
            $scope.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
            /*
            * @attr instituicao
            * @attrType Object
            * @attrDescription Estrutura de Instituição
            * @attrExample 
            * @attrCode { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] }
            */
            $scope.instituicao = Util.getEstrutura('instituicao');
            /*
            * @attr instituicao.endereco
            * @attrType Object
            * @attrDescription Estrutura de Endereço;
            * @attrExample 
            * @attrCode { logradouro:null, numero:null, bairro:null, complemento:null, cep:null, cidade: { id:null, nome:null, estado: { id:null, nome:null, sigla:null }, latitude: null, longitude: null } }
            */
            $scope.instituicao.endereco = Util.getEstrutura('endereco');
            /*
            * @attr telefone
            * @attrType Object
            * @attrDescription Estrutura de Telefone;
            * @attrExample 
            * @attrCode {descricao: '', falarCom: null, numero: null, pessoa: { id: null, tipo_pessoa: null } }
            */
            $scope.telefone = Util.getEstrutura('telefone');
            /*
            * @attr formCards
            * @attrType Array
            * @attrDescription Array com a estrutura do formulário.
            * @attrExample 
            */
            $scope.formCards =[
                {label: 'Informações Instituicionais', href: Util.getInputBlockCustom('instituicoes','informacoesPessoais')},
                {label: 'Contatos', href: Util.getInputBlockCustom('instituicoes','contatos')},
                {label: 'Endereço', href: Util.getInputBlockCustom('instituicoes','endereco')}
            ];
            /*
            * @attr forms
            * @attrType Array
            * @attrDescription Array com os formulários criados.
            * @attrExample 
            */
            $scope.forms = [{ nome: 'instituicoesForm', formCards: $scope.formCards }];
            /*
            * @attr leituraHref
            * @attrType string
            * @attrDescription URL da página do módulo para somente leitura.
            * @attrExample 
            */
            $scope.leituraHref = Util.getInputBlockCustom('instituicoes','leitura');
            /*
            * @method buscarInstituicao
            * @methodReturn void
            * @methodDescription Busca a instituição selecionada.
            */
            $scope.buscarInstituicao = function () {
                $scope.telefones = []; $scope.telefone = Util.getEstrutura('telefone');
                if (!Util.isNovo($routeParams.id)) {
                    var callback = function (response) {
                        $scope.instituicao = response.data; $scope.buscarEstados();
                        if ($scope.instituicao.telefones !== undefined) { $scope.getTelefones($scope.instituicao.telefones); }
                        if (!Util.isVazio($scope.instituicao.endereco)) { $scope.getEndereco($scope.instituicao.endereco.id); } Util.aplicarMascaras();
                    };
                    REST.um('instituicoes',$routeParams.id,callback);
                    /*
                    var promise = Util.um('instituicoes',$routeParams.id);
                    promise.then(function(response){
                        $scope.instituicao = response.data; $scope.buscarEstados();
                        if ($scope.instituicao.telefones !== undefined) { $scope.getTelefones($scope.instituicao.telefones); }
                        if (!Util.isVazio($scope.instituicao.endereco)) { $scope.getEndereco($scope.instituicao.endereco.id); } Util.aplicarMascaras();
                    }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.buscarInstituicao(); }); } });
                    */
                } else { $timeout(function(){ 
                        Util.aplicarMascaras(); 
                        $timeout(function(){ $scope.initMapa(); },500); },300); $scope.buscarEstados();
                }
            };
            /*
            * @method getEndereco
            * @methodReturn void
            * @methodParams id|int
            * @methodDescription Busca o endereço de uma instituição.
            */
            $scope.getEndereco = function (id) {
                var callback = function (response) {
                    $scope.instituicao.endereco = response.data; $scope.buscarCidades($scope.instituicao.endereco.cidade.estado.id);
                    $scope.$watch("instituicao.endereco.cidade.estado.id", function(query){ $scope.buscarCidades(query); });
                    $timeout(function(){ $scope.initMapa($scope.instituicao.endereco.latitude, $scope.instituicao.endereco.longitude); },500);
                };
                REST.um('enderecos',id,callback,true);
            };
            /*
            * @method getTelefones
            * @methodReturn void
            * @methodParams telefones|object
            * @methodDescription Busca os telefones de uma instituição.
            */
            $scope.getTelefones = function (telefones) {
                if (telefones.length > 0) {
                    $('md-divider.hide').show(); $('.md-subheader.hide').show();
                    for (var i=0; i<telefones.length; i++) { var promise = Util.um('telefones',telefones[i].id); promise.then(function (response) { $scope.telefones.push(response.data); }); }
                } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
                $timeout(function(){ 
                    $scope.$watch("telefone.descricao", function(query){ if (!Util.isVazio(query)) { $scope.adicionarTelefone(); } });
                    /*
                     * Por alguma razão, o campo Número de Telefone não está fazendo o bind corretamente.
                     * A correção é feita na linha abaixo.
                     */
                    $("#telefone_numero").change(function (){ $scope.telefone.numero = $(this).val(); });
                },500);
            };
            /*
            * @method validaCampo
            * @methodReturn void
            * @methodDescription Valida cada campo do form em caso de modificação.
            */
            $scope.validaCampo = function () { Util.validaCampo(); };
            /*
            * @method buscarEstados
            * @methodReturn void
            * @methodDescription Busca os estados brasileiros.
            */
            $scope.buscarEstados = function () { 
                var promiseEstados = Util.getEstados(); 
                promiseEstados.then(function (response){ $scope.estados = response.data; }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.buscarEstados(); }); } });
            };
            /*
            * @method buscarCidades
            * @methodReturn void
            * @methodParams estado|int
            * @methodDescription Busca as cidades de um estado.
            */
            $scope.buscarCidades = function (estado) { 
                var promiseCidade = Util.getCidades(estado);
                promiseCidade.then(function(response){ $scope.cidades = response.data; }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.buscarCidades(estado); }); } });
            };
            /*
            * @method salvar
            * @methodReturn void
            * @methodDescription Salva o objeto Instituição.
            */
            $scope.salvar = function () {
                if ($scope.validar('instituicoesForm')) {
                    var endereco = $scope.instituicao.endereco;
                    delete $scope.instituicao.endereco; delete $scope.instituicao.telefones;
                    var resultado = Util.salvar(endereco,'enderecos');
                    resultado.then(function (response){
                        $scope.instituicao.endereco = { id: response.data.id }; 
                        var resultadoPessoa = Util.salvar($scope.instituicao,'instituicoes','Instituição','F');
                        resultadoPessoa.then(function(response){
                            var telefones = $scope.telefones; var tipo_pessoa = response.data.tipo_pessoa;
                            if (telefones.length > 0) {
                                for (var i=0; i<telefones.length; i++) { 
                                    telefones[i].pessoa.id = response.data.id; telefones[i].pessoa.tipo_pessoa = tipo_pessoa; var resultadoTelefone = Util.salvar(telefones[i],'telefones');
                                    if (i === telefones.length-1) { 
                                        resultadoTelefone.then(function(){ $scope.buscarInstituicao(); Util.redirect($scope.fab.href); }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.salvar(); }); } });
                                    }
                                }
                            } else { $scope.buscarInstituicao(); Util.redirect($scope.fab.href); }
                        }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.salvar(); }); } });
                    }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.salvar(); }); } });
                }
            };
            /*
            * @method validar
            * @methodReturn void
            * @methodParams formId|string
            * @methodDescription Valida os campos do formulário.
            */
            $scope.validar = function (formId) { 
                var obrigatorios = Util.validar(formId); var cnpj = null;
                if (!Util.isVazio($scope.instituicao.cpfCnpj)) {
                    cnpj = Util.validarCNPJ($scope.instituicao.cpfCnpj);
                    if (obrigatorios && cnpj) { return true; } else { return false; }
                } else { if (obrigatorios) { return true; } else { return false; } }
            };
            /*
            * @method adicionarTelefone
            * @methodReturn void
            * @methodParams telefone|object,index|int
            * @methodDescription Desativa o telefone da instituição e remove da listagem.
            */
            $scope.adicionarTelefone = function () { 
                if (!Util.isVazio($scope.telefone.numero) && !Util.isVazio($scope.telefone.descricao)) {
                    $scope.telefones.push($scope.telefone); $('md-divider.hide').show(); $('.md-subheader.hide').show();
                    $scope.instituicao.telefones = $scope.telefones; Util.toast('Telefone adicionado, salve para garantir as modificações.');
                    $timeout(function(){ $scope.telefone = Util.getEstrutura('telefone'); $("#telefone_numero").val(''); },300);
                } else {
                    Util.toast('Ambos os campos devem ser preenchidos para adicionar um telefone.');
                }
            };
            /*
            * @method removerTelefone
            * @methodReturn void
            * @methodParams telefone|object,index|int
            * @methodDescription Desativa o telefone da instituição e remove da listagem.
            */
            $scope.removerTelefone = function (telefone, index) {
                if (Util.isNovoObjeto(telefone)) { $scope.telefones.splice(index,1);
                } else {
                    var promise = Util.remover(telefone, 'Telefone', 'm');
                    promise.then(function(){ 
                        $scope.telefones.splice(index,1);
                        if ($scope.telefones.length > 0) { $('md-divider.hide').show(); $('.md-subheader.hide').show(); } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
                    }, function(error){ if (Util.statusToken(error.data.code)) { var promiseToken = Util.renovarToken(); promiseToken.then(function(){ $scope.removerTelefone(telefone,index); }); } });
                }
            };
            /*
            * @method initMapa
            * @methodReturn void
            * @methodParams lat|string(int),lng|string(int)
            * @methodDescription Inicializa o mapa com as localizações de latitude e longitude. (Local padrão: S.M.E)
            */
            $scope.initMapa = function (lat,lng){
                $timeout(function(){
                    if (Util.isVazio(lat) || Util.isVazio(lng) || lat === '-9999.99999') { lat = -26.930232; lng = -48.684180; }
                    $scope.mapa = L.map('mapa').setView([lat,lng],16); $scope.mapa.scrollWheelZoom.disable();
                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                        attribution: 'Erudio Map by &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        maxZoom: 24, id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1IjoiY3Jpc3RpYW5vc2llYmVydCIsImEiOiJjajRlaXlseDQwNDNhMndydjNqYzlqOWtyIn0.MQqg-LSABfmy1v8-EIpBGg'
                    }).addTo($scope.mapa);
                    $scope.marker = L.marker([lat, lng]).addTo($scope.mapa);
                    $scope.mapa.on('click', $scope.adicionaMarcador);
                },500);
            };
            /*
            * @method adicionaMarcador
            * @methodReturn void
            * @methodDescription Atualiza o mapa, inserindo o marcador no local do clique no mesmo.
            */
            $scope.adicionaMarcador = function (event) {
                $scope.mapa.removeLayer($scope.marker);
                $scope.marker = L.marker([event.latlng.lat, event.latlng.lng]).addTo($scope.mapa);
                $scope.marker.bindPopup('<div class="map-popup-content"><strong>Este é o endereço desejado?</strong><br /><br /><button id="btnMapaSim" class="material-btn">Sim</md-button><button class="material-btn">Não</md-button></div>').openPopup();
                $("#btnMapaSim").on('click',function(ev){ ev.preventDefault(); $scope.confirmaLatLng(event); });
            };
            /*
            * @method confirmaLatLng
            * @methodReturn void
            * @methodDescription Atribui o ponto clicado no mapa aos atributos de latitude e longitude.
            */
            $scope.confirmaLatLng = function (event) {
                var lt = parseFloat(event.latlng.lat).toFixed(6); var ln = parseFloat(event.latlng.lng).toFixed(6);
                $scope.instituicao.endereco.latitude = parseFloat(lt); $scope.instituicao.endereco.longitude = parseFloat(ln); $scope.mapa.closePopup();
            };
            /*
            * @method preencheCEP
            * @methodReturn void
            * @methodDescription Listener que ativa o método de busca de CEP.
            */
            $scope.preencheCEP = function(){ $timeout(function(){ $("input[name=cep]").change(function(){ $scope.buscaCEP(); }); },500); };
            /*
            * @method buscaCEP
            * @methodReturn void
            * @methodDescription Busca um endereço no formato JSON via CEP.
            */
            $scope.buscaCEP = function () {
                /*if (!Util.isVazio($scope.instituicao.endereco.cep) && $scope.instituicao.endereco.cep.length === 8) {
                    $.getJSON("https://viacep.com.br/ws/"+$scope.instituicao.endereco.cep+"/json/?callback=?", function(dados) {
                        console.log(dados);
                        if (!Util.isVazio(dados.logradouro)) { $scope.instituicao.endereco.logradouro = dados.logradouro; }
                        if (!Util.isVazio(dados.bairro)) { $scope.instituicao.endereco.bairro = dados.bairro; }
                        if (!Util.isVazio(dados.uf)) { 
                            var promise = Util.buscar('estados',{sigla: dados.uf});
                            promise.then(function(response){ $scope.instituicao.endereco.cidade.estado = response.data;  $scope.buscarCidades(); });
                        }

                    });
                }*/
            };
            //INICIANDO
            Util.inicializar(); $scope.buscarInstituicao();
            Util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
        } else { Util.semPermissao(); }            
    }]);
})();
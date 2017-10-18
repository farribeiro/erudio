(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class InstituicaoFormController {
        constructor(service, util, erudioConfig, routeParams, $timeout, estadoService, cidadeService, enderecoService, telefoneService, $scope){
            this.service = service;
            this.scope = $scope;
            this.util = util;
            this.routeParams = routeParams;
            this.erudioConfig = erudioConfig;
            this.enderecoService = enderecoService;
            this.estadoService = estadoService;
            this.cidadeService = cidadeService;
            this.telefoneService = telefoneService;
            this.telefones = [];
            this.nomeCidade = '';
            this.cidade = null;
            this.estados =[];
            this.timeout = $timeout;
            this.scope.instituicao = null;
            this.permissaoLabel = "INSTITUICOES";
            this.titulo = "Instituições";
            this.linkModulo = "/#!/instituicoes/";
            this.nomeForm = "instituicoesForm";
            this.tiposTelefone = ['CELULAR','COMERCIAL','RESIDENCIAL'];
            this.mapa = {};
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.leitura = this.util.getTemplateLeitura();
            this.leituraHref = this.util.getInputBlockCustom('instituicoes','leitura');
            this.form = this.util.getTemplateForm();
            this.formCards =[
                {label: 'Informações Instituicionais', href: this.util.getInputBlockCustom('instituicoes','informacoesPessoais')},
                {label: 'Contatos', href: this.util.getInputBlockCustom('instituicoes','contatos')},
                {label: 'Endereço', href: this.util.getInputBlockCustom('instituicoes','endereco')}
            ];
            this.forms = [{ nome: this.nomeForm, formCards: this.formCards }];
        }
        
        verificarEstado(estadoId) {
            if (!this.util.isVazio(estadoId)) {
                var retorno = false;
                this.estados.forEach((estado) => {
                    if (estado.id === estadoId) {
                        retorno = true;
                    }
                });
                return retorno;
            }
        }
        
        buscarInstituicao() {
            var self = this;
            this.scope.instituicao = this.service.getEstrutura();
            this.scope.telefone = this.service.getEstruturaTelefone();
            if (!this.util.isNovo(this.routeParams.id)) {
                this.novo = false;
                this.service.get(this.routeParams.id).then((instituicao) => {
                    this.scope.instituicao = instituicao;
                    this.buscarEstados().then((estados) => this.estados = estados);
                    if (this.scope.instituicao.telefones.length > 0) { 
                        this.getTelefones(this.scope.instituicao.telefones); 
                    }
                    if (!this.util.isVazio(this.scope.instituicao.endereco)) { 
                        this.getEndereco(this.scope.instituicao.endereco.id); 
                    }
                    this.util.aplicarMascaras();
                });
            } else {
                this.novo = true;
                this.timeout(function(){ 
                    self.util.aplicarMascaras(); self.timeout(() => { self.initMapa(); },500);
                    self.buscarEstados().then((estados) => self.estados = estados);
                },300);
            }
        }
        
        buscarEstados () { return this.estadoService.getAll(null,true); }
        buscarCidades (query,instituicao) { 
            if (query.length > 2) {
                var self = this;
                if (instituicao.endereco.cidade.estado.id === null) {
                    return [{id: null, nome: 'É necessário selecionar um estado.'}];
                } else {
                    return this.cidadeService.getAll({estado: instituicao.endereco.cidade.estado.id, nome: query},true);
                }
            }
        }
        
        getEndereco(id) {
            var self = this;
            this.service.getEnderecoCompleto(id,true).then((endereco) => {
                this.scope.instituicao.endereco = endereco;
                if (this.escrita){
                    this.timeout(() => { this.initMapa(); },1000);
                    this.cidadeService.get(this.scope.instituicao.endereco.cidade.id).then((cidade) => {
                        this.cidade = cidade;
                        this.scope.instituicao.endereco.cidade = this.cidade;
                        this.buscarEstados().then((estados) => {
                            this.estados = estados;
                            this.timeout(()=>{this.util.validarCampos()},1000);
                        });
                    });
                }
            });
        }
        
        getTelefones(telefones) {
            var self = this;
            if (telefones.length > 0) {
                $('md-divider.hide').show(); $('.md-subheader.hide').show();
                for (var i=0; i<telefones.length; i++) { 
                    this.telefoneService.get(telefones[i].id,true).then((telefone) => { this.telefones.push(telefone); });
                }
            } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
        }
        
        validaCampo() { this.util.validaCampo(); }
        
        validar() {
            var obrigatorios = this.util.validar(this.nomeForm); var cnpj = null;
            if (this.cidade.id === null) { obrigatorios = false; }
            if (!this.util.isVazio(this.scope.instituicao.cpfCnpj)) {
                cnpj = this.util.validarCNPJ(this.scope.instituicao.cpfCnpj);
                if (obrigatorios && cnpj) { return true; } else { return false; }
            } else { if (obrigatorios) { return true; } else { return false; } }
        }
        
        salvar() {
            if (this.validar()) {    
                this.scope.instituicao.endereco.cidade.id = this.cidade.id; var resultado = null;
                if (this.util.isNovoObjeto(this.scope.instituicao)) {
                    resultado = this.service.salvar(this.scope.instituicao);
                } else {
                    resultado = this.service.atualizar(this.scope.instituicao);
                }
                resultado.then(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); });
            }
        }
        
        adicionarTelefone() {
            var self = this; var tamanhoTelefone = this.scope.telefone.numero.length;
            if (!this.util.isVazio(this.scope.telefone.numero) && !this.util.isVazio(this.scope.telefone.descricao)) {
                this.telefones.push(this.scope.telefone); $('md-divider.hide').show(); $('.md-subheader.hide').show();
                this.scope.instituicao.telefones = this.telefones; this.util.toast('Telefone adicionado, salve para garantir as modificações.');
                this.timeout(function(){ self.scope.telefone = self.service.getEstruturaTelefone(); $("#telefone_numero").val(''); },300);
            } else if (tamanhoTelefone > 11) {
                this.util.toast('Ambos os campos devem ser preenchidos para adicionar um telefone.');
            }
        }
        
        removerTelefone(telefone, index) {
            var self = this;
            if (this.util.isNovoObjeto(telefone)) {
                this.telefones.splice(index,1);
            } else {
                this.telefoneService.remover(telefone, true).then((result) => {
                    self.telefones.splice(index,1);
                    if (self.telefones.length > 0) { $('md-divider.hide').show(); $('.md-subheader.hide').show(); } else { $('md-divider.hide').hide(); $('.md-subheader.hide').hide(); }
                });
            }
        }
        
        initMapa() {
            var lat; var lng;
            if (this.util.isNovoObjeto(this.scope.instituicao)) { lat = -26.930232; lng = -48.684180; } else { 
                if (this.util.isVazio(this.scope.instituicao.endereco.latitude)) {
                    lat = -26.930232; lng = -48.684180;
                } else {
                    lat = parseFloat(this.scope.instituicao.endereco.latitude); lng = parseFloat(this.scope.instituicao.endereco.longitude);
                }
            }
            if (typeof this.mapa.remove === "function") { this.mapa.remove(); }
            this.mapa = L.map('mapa').setView([lat,lng],16); this.mapa.scrollWheelZoom.disable();
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Erudio Map by &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 24, id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiY3Jpc3RpYW5vc2llYmVydCIsImEiOiJjajRlaXlseDQwNDNhMndydjNqYzlqOWtyIn0.MQqg-LSABfmy1v8-EIpBGg'
            }).addTo(this.mapa);
            this.marker = L.marker([lat, lng]).addTo(this.mapa);
            if (this.escrita){ this.mapa.on('click', this.adicionarMarcador.bind(this)); }
        }
        
        adicionarMarcador(event) {
            this.mapa.removeLayer(this.marker); 
            var self = this;
            this.marker = L.marker([event.latlng.lat, event.latlng.lng]).addTo(this.mapa);
            this.marker.bindPopup('<div class="map-popup-content"><strong>Este é o endereço desejado?</strong><br /><br /><button id="btnMapaSim" class="material-btn">Sim</md-button><button class="material-btn">Não</md-button></div>').openPopup();
            $("#btnMapaSim").on('click',function(ev){ ev.preventDefault(); self.confirmaLatLng(event); });
        }
        
        confirmaLatLng(event) {
            var lt = parseFloat(event.latlng.lat).toFixed(6); var ln = parseFloat(event.latlng.lng).toFixed(6);
            this.scope.instituicao.endereco.latitude = parseFloat(lt); this.scope.instituicao.endereco.longitude = parseFloat(ln); this.mapa.closePopup();
        }
        
        preencheCEP() {
            var self = this;
            this.timeout(function(){ $("input[name=cep]").change(function(){ self.buscaCEP(); }); },500);
        }
        
        buscaCEP () {
            if (!this.util.isVazio(this.scope.instituicao.endereco.cep) && this.scope.instituicao.endereco.cep.length === 8) {
                this.util.buscaCEP(this.scope.instituicao.endereco.cep).then((addr) => {
                    if(!this.util.isVazio(addr.bairro)) { this.scope.instituicao.endereco.bairro = addr.bairro; this.util.validarCampos(); }
                    if(!this.util.isVazio(addr.logradouro)) { this.scope.instituicao.endereco.logradouro = addr.logradouro; this.util.validarCampos(); }
                    if(!this.util.isVazio(addr.uf)) {
                        this.estados.forEach((estado) => {
                            if(estado.sigla === addr.uf) { 
                                this.scope.instituicao.endereco.cidade.estado.id = estado.id;
                                this.util.validarCampos();
                                if (!this.util.isVazio(addr.localidade)) {
                                    this.cidadeService.getAll({nome: addr.localidade},true).then((cidade) => {
                                        this.scope.instituicao.endereco.cidade.id = cidade[0].id;
                                        this.cidade = cidade[0];
                                        this.nomeCidade = cidade[0].nome;
                                        this.util.validarCampos();
                                    });
                                }
                            }
                        });
                    }
                });
            }
        }
        
        iniciar(){
            let permissao = this.verificarPermissao(); var self = this;
            if (permissao) {
                this.util.comPermissao();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                this.util.mudarImagemToolbar('instituicoes/assets/images/instituicoes.jpg');
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.preparaForm();
                this.buscarInstituicao(true);
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    InstituicaoFormController.$inject = ["InstituicaoService","Util","ErudioConfig","$routeParams","$timeout","EstadoService","CidadeService","EnderecoService","TelefoneService","$scope"];
    angular.module('InstituicaoFormController',['ngMaterial', 'util', 'erudioConfig']).controller('InstituicaoFormController',InstituicaoFormController);
})();
(function (){
    /*
     * @ErudioDoc Instituição Form Controller
     * @Module instituicoesForm
     * @Controller InstituicaoFormController
     */
    class PerfilController {
        constructor(service, util, erudioConfig, routeParams, $timeout, estadoService, cidadeService, enderecoService, telefoneService, $scope, beneficioService, necessidadeEspecialService, usuarioService, $http){
            this.service = service; this.http = $http; this.scope = $scope; this.util = util;
            this.routeParams = routeParams; this.erudioConfig = erudioConfig; this.enderecoService = enderecoService;
            this.estadoService = estadoService; this.cidadeService = cidadeService; this.telefoneService = telefoneService;
            this.beneficioService = beneficioService; this.necessidadeEspecialService = necessidadeEspecialService;
            this.usuarioService = usuarioService; this.telefones = []; this.nomeCidade = ''; this.cidade = null;
            this.estados =[]; this.etnias = []; this.nacionalidades = ["BRASILEIRO","ESTRANGEIRO","ESTRANGEIRO_NATURALIZADO"];
            this.nomeNaturalidade = ""; this.estadosCivis = []; this.filiacoes = ["Não se aplica","Filiação 1","Filiação 2","Outro"];
            this.tipoCertidao = null; this.termo = null; this.livro = null; this.folha = null; this.responsavel = null;
            this.particularidades = []; this.cpfAtual = null; this.particularidadesSelecionadas = []; this.necessidades = [];
            this.necessidadesSelecionadas = []; this.beneficios = []; this.beneficiosSelecionados = []; this.senha = null;
            this.novaSenha = null; this.timeout = $timeout; this.scope.perfil = null; this.permissaoLabel = ""; this.titulo = ""; this.linkModulo = "/#!";
            this.nomeForm = "perfilForm"; this.tiposTelefone = ['CELULAR','COMERCIAL','RESIDENCIAL']; this.mapa = {}; this.abaAtiva = 0;
            this.temFoto = false; this.fotoSrc = null; this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaForm() {
            this.fab = {tooltip: 'Voltar à lista', icone: 'arrow_back', href: this.erudioConfig.dominio + this.linkModulo};
            this.form = this.util.getInputBlockCustom('perfil','form','professor');
            this.formCards =[
                {label: 'Foto', href: this.util.getInputBlockCustom('perfil','foto','professor')},
                {label: 'Informações Pessoais', href: this.util.getInputBlockCustom('perfil','informacoesPessoais','professor')},
                {label: 'Contatos', href: this.util.getInputBlockCustom('perfil','contatos','professor')},
                {label: 'Endereço', href: this.util.getInputBlockCustom('perfil','endereco','professor')}
            ];
            this.formCardsDocumentos =[
                {label: 'Documentos', href: this.util.getInputBlockCustom('perfil','documentos','professor')},
                {label: 'Particularidades', href: this.util.getInputBlockCustom('perfil','particularidades','professor')}
            ];
            this.formCardsUsuario =[
                {label: 'Usuário', href: this.util.getInputBlockCustom('perfil','usuario','professor')},
            ];
            this.forms = [
                { nome: this.nomeForm, formCards: this.formCards },
                { nome: this.nomeForm, formCards: this.formCardsDocumentos },
                { nome: this.nomeForm, formCards: this.formCardsUsuario }
            ];
        }

        abreArquivo(){ $("#foto-perfil").trigger('click'); }
        
        mostraCameraStream() {
            $(".cortina-foto").show();
            var video = document.querySelector('#camera-stream');
            navigator.getMedia = ( navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
            if (!navigator.getMedia) { this.util.toast("Seu navegador ainda não possui suporte para tirar fotos.");
            } else {
                navigator.getMedia({ video: true
                },(stream) => {
                    video.src = window.URL.createObjectURL(stream); $('.start-video').show(); video.play();
                },(error) => { console.log(error); });
            }            
        }

        capturar() {
            var imageW = $('#camera-stream').width(); var imageH = $('#camera-stream').height();
            var foto = this.tirarFoto(); var image = document.querySelector("#foto-perfil-preview");
            image.setAttribute('src',foto); image.classList.add('visivel');
            image.setAttribute('style','width:'+imageW+'px; height:'+imageH+'px;');
            this.temFoto = true;
        }

        tirarFoto() {
            var video = document.querySelector('#camera-stream');
            var hidden_canvas = document.querySelector('canvas');
            var context = hidden_canvas.getContext('2d');
            var width = video.videoWidth; var height = video.videoHeight;
            if (width && height) {
                hidden_canvas.width = width; hidden_canvas.height = height;
                context.drawImage(video, 0, 0, width, height);
                return hidden_canvas.toDataURL('image/jpeg');
            }
        }

        removerPreFoto() {
            var image = document.querySelector("#foto-perfil-preview"); image.setAttribute('src',"");
            image.classList.remove('visivel'); this.temFoto = false;
        }

        salvarFoto(){
            var src = $("#foto-perfil-preview").attr('src'); var image = document.querySelector("#foto-perfil-preview"); this.fotoSrc = src;
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+this.scope.pessoa.id+'/foto';
            if (!this.util.isVazio(src)) {
                var file = this.util.dataURLtoFile(src,'avatar_'+this.scope.pessoa.id+'.jpg');
                var fd = new FormData(); fd.append('foto',file);
                this.timeout(()=>{
                    this.http.post(fileUrl,fd,{
                        headers: {
                            "JWT-Authorization":token,
                            'Content-type':undefined
                        }
                    }).then(() => { $('.foto-professor img').attr('src',src); });
                },500);
            }
            this.cancelarFoto();
        }

        cancelarFoto() {
            var image = document.querySelector("#foto-perfil-preview");
            var video = document.querySelector('#camera-stream'); video.src = "";
            image.setAttribute('src',""); image.classList.remove('visivel');
            this.temFoto = false; $('.start-video').hide();
            $(".cortina-foto").hide();
        }

        salvarFotoUpload() {
            this.timeout(() => {
                var input = document.getElementById('foto-perfil');
                var atribuicoes = JSON.parse(sessionStorage.getItem('atribuicoes'));
                var token = "Bearer "+sessionStorage.getItem('token'); var file = input.files[0];
                var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+atribuicoes.pessoa.id+'/foto';
                var fd = new FormData(); fd.append('foto',file);
                this.http.post(fileUrl,fd,{
                    headers: { "JWT-Authorization":token, 'Content-type':undefined }
                }).then(() => { 
                    this.timeout(() => {
                        this.recarregarFoto().then((foto) => { $('.foto-professor img').attr('src',foto);  });
                    },500);
                });
            },500);
        }

        recarregarFoto() {
            var atribuicoes = JSON.parse(sessionStorage.getItem('atribuicoes'));
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+atribuicoes.pessoa.id+'/foto';
            return this.http.get(fileUrl,{headers: {"JWT-Authorization":token},responseType: 'arraybuffer'}).then((data) => {
                return new Promise((resolve) => {
                    var file = new Blob([data.data],{type: 'image/jpg'}); resolve(URL.createObjectURL(file));
                });
            }, (error) => { return new Promise((resolve) => { resolve(this.erudioConfig.dominio+"/apps/professor/avaliacoes/assets/images/avatar.png"); }) });
        };
        
        verificarEstado(estadoId) {
            if (!this.util.isVazio(estadoId)) {
                var retorno = false;
                this.estados.forEach((estado) => { if (estado.id === estadoId) { retorno = true; } });
                return retorno;
            }
        }

        getPasso(index) { this.abaAtiva = index; $('.step-tab').hide(); $('.step-tab-'+index).show(); }
        
        buscarPessoa() {
            var self = this; var pessoaId = JSON.parse(sessionStorage.getItem('atribuicoes')).pessoa.id;
            this.scope.pessoa = this.service.getEstrutura(); this.senha = null; this.novaSenha = null;
            this.scope.telefone = this.service.getEstruturaTelefone();
            this.tipoCertidao = null; this.termo = null; this.livro = null; this.folha = null;
            this.service.get(pessoaId).then((pessoa) => {
                this.scope.pessoa = pessoa; this.cpfAtual = this.scope.pessoa.cpfCnpj;
                if (this.scope.pessoa.naturalidade !== undefined) { this.nomeNaturalidade = this.scope.pessoa.naturalidade.nome + ' - ' + this.scope.pessoa.naturalidade.estado.sigla; }
                this.scope.pessoa.dataNascimento = this.util.formataData(this.scope.pessoa.dataNascimento);
                if (this.scope.pessoa.dataExpedicaoRg !== undefined) { this.scope.pessoa.dataExpedicaoRg = this.util.formataData(this.scope.pessoa.dataExpedicaoRg); }
                if (this.scope.pessoa.dataExpedicaoCertidaoNascimento !== undefined) { this.scope.pessoa.dataExpedicaoCertidaoNascimento = this.util.formataData(this.scope.pessoa.dataExpedicaoCertidaoNascimento); }
                this.formatarCertidao();
                if (this.scope.pessoa.responsavelNome === this.scope.pessoa.nomeMae)  { this.responsavel = "Filiação 1";
                } else if (this.scope.pessoa.responsavelNome === this.scope.pessoa.nomePai) { this.responsavel = "Filiação 2";
                } else if (this.scope.pessoa.responsavelNome === "nenhum") { this.responsavel = "Não se aplica"; }
                else { this.responsavel = "Outro"; }
                this.buscarEtnias(); this.buscarEstadosCivis(); this.buscarParticularidades(); this.carregarParticularidades();
                this.buscarNecessidades(); this.buscarBeneficios(); this.carregarNecessidades(); this.carregarBeneficios();
                this.buscarEstados().then((estados) => this.estados = estados);
                if (this.scope.pessoa.telefones.length > 0) { this.getTelefones(this.scope.pessoa.telefones); }
                if (!this.util.isVazio(this.scope.pessoa.endereco)) { this.getEndereco(this.scope.pessoa.endereco.id); }
                this.getPasso(0); $('#foto-perfil').change(() => { this.salvarFotoUpload() });
                this.util.aplicarMascaras();
            });
        }

        carregarParticularidades() {
            this.particularidadesSelecionadas = [];
            if (this.scope.pessoa.particularidades.length > 0) {
                this.scope.pessoa.particularidades.forEach((particularidade) => {
                    this.particularidadesSelecionadas.push(particularidade.id);
                });
            }
        }

        carregarNecessidades() {
            this.necessidadesSelecionadas = [];
            if (this.scope.pessoa.necessidadesEspeciais.length > 0) {
                this.scope.pessoa.necessidadesEspeciais.forEach((necessidade) => {
                    this.necessidadesSelecionadas.push(necessidade.id);
                });
            }
        }

        carregarBeneficios() {
            this.beneficiosSelecionados = [];
            if (this.scope.pessoa.beneficiosSociais.length > 0) {
                this.scope.pessoa.beneficiosSociais.forEach((beneficio) => {
                    this.beneficiosSelecionados.push(beneficio.id);
                });
            }
        }

        formatarCertidao() {
            if (this.scope.pessoa.certidaoNascimento) {
                if (this.scope.pessoa.certidaoNascimento.indexOf("00000000000000000") !== -1) {
                    this.tipoCertidao = "certidao-antiga";
                    var certidao = this.scope.pessoa.certidaoNascimento.replace("00000000000000000","");
                    this.livro = certidao.toString().slice(0,5);
                    this.folha = certidao.toString().slice(5,8);
                    this.termo = certidao.toString().slice(8,15);
                } else { this.tipoCertidao = "certidao"; }
            }
        }
        
        buscarEtnias() { this.service.getRacas(null,true).then((etnias) => { this.etnias = etnias; }); }
        buscarEstadosCivis() { this.service.getEstadosCivis(null,true).then((estadosCivis) => { this.estadosCivis = estadosCivis; }); }
        buscarParticularidades() { this.service.getParticularidades(null,true).then((particularidades) => { this.particularidades = particularidades; }); }
        buscarNecessidades() { this.necessidadeEspecialService.getAll(null,true).then((necessidades) => { this.necessidades = necessidades; }); }
        buscarBeneficios() { this.beneficioService.getAll(null,true).then((beneficios) => { this.beneficios = beneficios; }); }
        buscarEstados () { return this.estadoService.getAll(null,true); }
        buscarCidades (query,pessoa) { 
            if (query.length > 2) {
                var self = this;
                if (pessoa.endereco.cidade.estado.id === null) {
                    return [{id: null, nome: 'É necessário selecionar um estado.'}];
                } else {
                    return this.cidadeService.getAll({estado: pessoa.endereco.cidade.estado.id, nome: query},true);
                }
            }
        }

        buscarCidadesNaturalidade (query) { 
            if (query.length > 2) { return this.cidadeService.getAll({nome: query},true); }
        }
        
        getEndereco(id) {
            var self = this;
            this.service.getEnderecoCompleto(id,true).then((endereco) => {
                this.scope.pessoa.endereco = endereco;
                this.timeout(() => { this.initMapa(); },1000);
                this.cidadeService.get(this.scope.pessoa.endereco.cidade.id).then((cidade) => {
                    this.cidade = cidade;
                    this.scope.pessoa.endereco.cidade = this.cidade;
                    this.buscarEstados().then((estados) => {
                        this.estados = estados;
                        this.timeout(()=>{this.util.validarCampos()},1000);
                    });
                });
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
            var obrigatorios = this.util.validar(this.nomeForm);
            if (this.cidade.id === null) { obrigatorios = false; }
            if (this.scope.pessoa.certidaoNascimento.length > 0 && this.scope.pessoa.certidaoNascimento.length < 32) { this.util.toast("Certidão de Nascimento deve ter 32 dígitos."); return false; }
            if (obrigatorios) { return true; } else { return false; }
        }
        
        salvar() {
            /*if (this.abaAtiva === 2) {
                if (this.senha === this.novaSenha) {
                    this.usuarioService.get(this.scope.pessoa.usuario.id).then((usuario) => {
                        usuario.password = this.senha; this.usuarioService.atualizar(usuario,true).then(() => {
                            sessionStorage.removeItem('pass');
                            sessionStorage.setItem('pass',btoa(this.senha));
                            this.timeout(() => { this.senha = null; this.novaSenha = null; },500);
                        });
                    });
                } else { this.util.toast("As senhas devem ser iguais, favor verificar."); }
            } else {*/
                if (this.validar()) {
                    this.scope.pessoa.particularidades = []; this.scope.pessoa.necessidadesEspeciais = []; this.scope.pessoa.beneficiosSociais = [];
                    this.particularidadesSelecionadas.forEach((particularidade) => { this.scope.pessoa.particularidades.push({id: particularidade}); });
                    this.necessidadesSelecionadas.forEach((necessidade) => { this.scope.pessoa.necessidadesEspeciais.push({id: necessidade}); });
                    this.beneficiosSelecionados.forEach((beneficio) => { this.scope.pessoa.beneficiosSociais.push({id: beneficio}); });
                    this.scope.pessoa.endereco.cidade.id = this.cidade.id; var resultado = null;
                    this.scope.pessoa.dataNascimento = this.util.converteData(this.scope.pessoa.dataNascimento);
                    this.scope.pessoa.estadoCivil = {id:this.scope.pessoa.estadoCivil.id};
                    this.scope.pessoa.raca = {id:this.scope.pessoa.raca.id};
                    if (!this.util.isVazio(this.scope.pessoa.naturalidade)) { this.scope.pessoa.naturalidade = {id:this.scope.pessoa.naturalidade.id}; }                
                    if (!this.util.isVazio(this.scope.pessoa.dataExpedicaoRg)) { this.scope.pessoa.dataExpedicaoRg = this.util.converteData(this.scope.pessoa.dataExpedicaoRg); }
                    if (!this.util.isVazio(this.scope.pessoa.dataExpedicaoCertidaoNascimento)) { this.scope.pessoa.dataExpedicaoCertidaoNascimento = this.util.converteData(this.scope.pessoa.dataExpedicaoCertidaoNascimento); }
                    if (this.tipoCertidao === "certidao-antiga") { this.scope.pessoa.certidaoNascimento = this.livro + this.folha + this.termo + "00000000000000000"; }
                    resultado = this.service.atualizar(this.scope.pessoa,true);
                    resultado.then(() => { 
                        /*if (this.scope.pessoa.cpfCnpj !== this.cpfAtual) { this.atualizarUsuario();
                        } else { */
                            this.util.redirect(this.erudioConfig.dominio + this.linkModulo);
                        //}
                    },
                    (erro) => { this.util.toast(erro.data.error.message); });
                }
            //}
        }

        atualizarUsuario() {
            this.usuarioService.get(this.scope.pessoa.usuario.id).then((usuario) => {
                usuario.username = this.scope.pessoa.cpfCnpj;
                this.usuarioService.atualizar(usuario,true).then(() => {
                    sessionStorage.removeItem('username');
                    sessionStorage.setItem('username',this.scope.pessoa.cpfCnpj);
                    this.timeout(() => { this.util.redirect(this.erudioConfig.dominio + this.linkModulo); },500);
                });
            });
        }
        
        adicionarTelefone() {
            var self = this; var tamanhoTelefone = this.scope.telefone.numero.length;
            if (!this.util.isVazio(this.scope.telefone.numero) && !this.util.isVazio(this.scope.telefone.descricao)) {
                this.telefones.push(this.scope.telefone); $('md-divider.hide').show(); $('.md-subheader.hide').show();
                this.scope.pessoa.telefones = this.telefones; this.util.toast('Telefone adicionado, salve para garantir as modificações.');
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
            if (this.util.isVazio(this.scope.pessoa.endereco.latitude)) {
                lat = -26.930232; lng = -48.684180;
            } else {
                lat = parseFloat(this.scope.pessoa.endereco.latitude); lng = parseFloat(this.scope.pessoa.endereco.longitude);
            }
            if (typeof this.mapa.remove === "function") { this.mapa.remove(); }
            this.mapa = L.map('mapa').setView([lat,lng],16); this.mapa.scrollWheelZoom.disable();
            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: 'Erudio Map by &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                maxZoom: 24, id: 'mapbox.streets',
                accessToken: 'pk.eyJ1IjoiY3Jpc3RpYW5vc2llYmVydCIsImEiOiJjajRlaXlseDQwNDNhMndydjNqYzlqOWtyIn0.MQqg-LSABfmy1v8-EIpBGg'
            }).addTo(this.mapa);
            this.marker = L.marker([lat, lng]).addTo(this.mapa);
            this.mapa.on('click', this.adicionarMarcador.bind(this));
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
            this.scope.pessoa.endereco.latitude = parseFloat(lt); this.scope.pessoa.endereco.longitude = parseFloat(ln); this.mapa.closePopup();
        }
        
        preencheCEP() {
            var self = this;
            this.timeout(function(){ $("input[name=cep]").change(function(){ self.buscaCEP(); }); },500);
        }
        
        buscaCEP () {
            if (!this.util.isVazio(this.scope.pessoa.endereco.cep) && this.scope.pessoa.endereco.cep.length === 8) {
                this.util.buscaCEP(this.scope.pessoa.endereco.cep).then((addr) => {
                    if(!this.util.isVazio(addr.bairro)) { this.scope.pessoa.endereco.bairro = addr.bairro; this.util.validarCampos(); }
                    if(!this.util.isVazio(addr.logradouro)) { this.scope.pessoa.endereco.logradouro = addr.logradouro; this.util.validarCampos(); }
                    if(!this.util.isVazio(addr.uf)) {
                        this.estados.forEach((estado) => {
                            if(estado.sigla === addr.uf) { 
                                this.scope.pessoa.endereco.cidade.estado.id = estado.id;
                                this.util.validarCampos();
                                if (!this.util.isVazio(addr.localidade)) {
                                    this.cidadeService.getAll({nome: addr.localidade},true).then((cidade) => {
                                        this.scope.pessoa.endereco.cidade.id = cidade[0].id;
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
            //if (permissao) {
                this.util.comPermissao(); $('.btn-home').hide();
                this.attr = JSON.parse(sessionStorage.getItem('atribuicoes'));
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.isAdmin = this.util.isAdmin();
                $(".fit-screen").unbind('scroll');
                this.timeout(()=>{ this.validaCampo(); },500);
                this.preparaForm();
                this.buscarPessoa();
                this.timeout(() => { $("input").keypress(function(event){ var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { self.salvar(); } }); }, 1000);
                this.util.inicializar();
            //} else { this.util.semPermissao(); }
        }
    }
    
    PerfilController.$inject = ["PessoaService","Util","ErudioConfig","$routeParams","$timeout","EstadoService","CidadeService","EnderecoService","TelefoneService","$scope","BeneficioService","NecessidadeEspecialService","UsuarioService","$http"];
    angular.module('PerfilController',['ngMaterial', 'util', 'erudioConfig']).controller('PerfilController',PerfilController);
})();
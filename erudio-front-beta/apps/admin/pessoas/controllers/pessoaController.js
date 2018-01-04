(function (){
    /*
     * @ErudioDoc Instituição Controller
     * @Module instituicoes
     * @Controller InstituicaoController
     */
    class PessoaController {
        constructor (service, util, $mdDialog, erudioConfig, $timeout, $http) {
            this.service = service;
            this.util = util;
            this.mdDialog = $mdDialog;
            this.erudioConfig = erudioConfig;
            this.timeout = $timeout;
            this.http = $http;
            this.permissaoLabel = "PESSOA";
            this.titulo = "Pessoas";
            this.linkModulo = "/#!/pessoas/";
            this.pessoa = null;
            this.objetos = [];
            this.pagina = 0;
            this.finalLista = false;
            this.buscaIcone = 'search';
            this.certidaoAntiga = null;
            this.busca = {nome: null, dataNasc: null, nomeMae: null, tipoDocumento: null, cpf: null, certidaoNova: null, termo: null, livro: null, folha: null};
            this.iniciar();
        }
        
        verificarPermissao(){ return this.util.verificaPermissao(this.permissaoLabel); }
        verificaEscrita() { return this.util.verificaEscrita(this.permissaoLabel); }
        validarEscrita(opcao) { if (opcao.validarEscrita) { return this.util.validarEscrita(opcao.opcao, this.opcoes, this.escrita); } else { return true; } }
        
        preparaLista(){
            this.subheaders = [{ label: 'Nome da Pessoa' }];
            this.opcoes = [{tooltip: 'Remover', icone: 'delete', opcao: 'remover', validarEscrita: true}];
            this.link = this.erudioConfig.dominio + this.linkModulo;
            this.fab = { tooltip: 'Adicionar Pessoa', icone: 'add', href: this.link+'novo' };
            this.template = this.util.getTemplateListaComFoto();
            this.lista = this.util.getTemplateListaEspecifica('pessoas');
        }

        limparBusca () {
            this.busca = {nome: null, dataNasc: null, nomeMae: null, tipoDocumento: null, cpf: null, certidaoNova: null, termo: null, livro: null, folha: null};
            this.objetos = [];
        }
        
        preparaBusca(){
            this.buscaCustomTemplate = this.util.getTemplateBuscaCustom();
            this.buscaCustom = this.util.setBuscaCustom('/apps/admin/pessoas/partials');
            this.timeout(() => { this.util.aplicarMascaras(); },500);
        }

        validarBusca() {
            var objetoBusca = {}; var retorno = false;
            if (!this.util.isVazio(this.busca.nome)) { objetoBusca.nome = this.busca.nome; retorno = true; }
            if (!this.util.isVazio(this.busca.dataNasc)) { 
                var nasc = this.busca.dataNasc; retorno = true;
                objetoBusca.dataNascimento = this.util.converteData(nasc);
            }
            if (!this.util.isVazio(this.busca.nomeMae)) { objetoBusca.nomeMae = this.busca.nomeMae; retorno = true; }
            if (!this.util.isVazio(this.busca.tipoDocumento)) { 
                switch (this.busca.tipoDocumento) {
                    case 'CPF':
                        if (!this.util.isVazio(this.busca.cpf)) { objetoBusca.cpfCnpj = this.busca.cpf; retorno = true; }
                    break;
                    case 'certidaoNova':
                        if (!this.util.isVazio(this.busca.certidao)) { objetoBusca.certidaoNascimento = this.busca.certidao; retorno = true; }
                    break;
                    case 'certidaoAntiga':
                        var certidaoAntiga = '';
                        if (!this.util.isVazio(this.busca.livro) && !this.util.isVazio(this.busca.folha) && !this.util.isVazio(this.busca.termo)) { 
                            var certidaoA = this.busca.livro + this.busca.folha + this.busca.termo;
                            var diferenca = 32-certidaoA.length;
                            for (var i=0; i<diferenca; i++) { certidaoA += "0"; if (i === diferenca-1) { this.certidaoAntiga = certidaoA; retorno = true; } }
                        } else { this.util.toast("Favor preencher os todos os campos da documentação para usar este filtro."); }
                    break;
                }
            }
            if (retorno) { return objetoBusca; } else { return false; }
        }
        
        buscarPessoas(loader) {
            var objetoBusca = this.validarBusca();
            if (objetoBusca !== false) {
                objetoBusca.page = this.pagina;
                this.service.getAll(objetoBusca,loader).then((pessoas) => {
                    pessoas.forEach((pessoa) => { this.carregarFoto(pessoa.id).then((foto) => { pessoa.foto = foto; }); });
                    if (this.pagina === 0) { this.objetos = pessoas; } else { 
                        if (pessoas.length !== 0) { this.objetos = this.objetos.concat(pessoas); } else { this.finalLista = true; this.pagina--; }
                    }
                });
            } else { this.util.toast("Pelo menos um campo deve ser preenchido."); }
        }

        carregarFoto (pessoaId) {
            var token = "Bearer "+sessionStorage.getItem('token');
            var fileUrl = this.erudioConfig.urlServidor+'/pessoas/'+pessoaId+'/foto';
            return this.http.get(fileUrl,{headers: {"JWT-Authorization":token},responseType: 'arraybuffer'}).then((data) => {
                return new Promise((resolve) => {
                    var file = new Blob([data.data],{type: 'image/jpg'}); resolve(URL.createObjectURL(file));
                });
            }, (error) => { return new Promise((resolve) => { resolve(this.erudioConfig.dominio+"/apps/professor/avaliacoes/assets/images/avatar.png"); }) });
        }
        
        executarOpcao(event,opcao,objeto) {
            this.pessoa = objeto;
            switch (opcao.opcao) {
                case 'remover': this.modalExclusao(event); break;
                default: return false; break;
            }
        }
        
        modalExclusao(event) {
            var self = this;
            let confirm = this.util.modalExclusao(event, "Remover Pessoa", "Deseja remover esta pessoa?", 'remover', this.mdDialog);
            this.mdDialog.show(confirm).then(function(){
                let id = self.pessoa.id;
                var index = self.util.buscaIndice(id, self.objetos);
                if (index !== false) {
                    self.service.remover(self.pessoa, "Pessoa","f");
                    self.objetos.splice(index,1);
                }
            });
        }
        
        paginar(){ this.pagina++; this.buscarPessoas(true); }
        
        iniciar(){
            let permissao = this.verificarPermissao(); let self = this;
            if (permissao) {
                this.util.comPermissao();
                this.util.setTitulo(this.titulo);
                this.escrita = this.verificaEscrita();
                this.util.mudarImagemToolbar('pessoas/assets/images/pessoas.jpeg');
                this.preparaLista();
                this.preparaBusca();
                $(".fit-screen").scroll(function(){
                    let distancia = Math.floor(Number($(".conteudo").offset().top - $(document).height()));
                    let altura = Math.floor(Number($(".main-layout").height()));
                    let total = altura + distancia;
                    if (total === 0) { self.paginar(); }
                });
                this.util.inicializar();
            } else { this.util.semPermissao(); }
        }
    }
    
    PessoaController.$inject = ["PessoaService","Util","$mdDialog","ErudioConfig","$timeout","$http"];
    angular.module('PessoaController',['ngMaterial', 'util', 'erudioConfig']).controller('PessoaController',PessoaController);
})();
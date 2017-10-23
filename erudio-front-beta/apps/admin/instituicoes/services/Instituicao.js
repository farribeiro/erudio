(function (){
    'use strict';    
    class InstituicaoService {        
        constructor(rest,enderecoService,telefoneService){
            this.rest = rest;
            this.enderecoService = enderecoService;
            this.telefoneService = telefoneService;
            this.url = 'instituicoes';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:this.getEstruturaEndereco(), telefones:[] }; }
        getEnderecoCompleto(id,loader) { return this.enderecoService.get(id,loader); }
        getTelefones(id) { return this.telefoneService.getAllByPessoa({pessoa: id}); }
        getEstruturaEndereco() {return this.enderecoService.getEstrutura(); }
        getEstruturaTelefone() {return this.telefoneService.getEstrutura(); }
        salvarInstituicao(objeto) { return this.rest.salvar(this.url, objeto, "Instituição", "F"); }
        salvarEndereco(objeto) { return this.enderecoService.salvar(objeto); }
        atualizarInstituicao(objeto) { return this.rest.atualizar(objeto, "Instituição", "F"); }
        atualizarEndereco(objeto) { return this.enderecoService.atualizar(objeto); }
        
        salvar(objeto) {
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.salvarEndereco(endereco).then(function(response){
                objeto.endereco = { id: response.id };
                return self.salvarInstituicao(objeto).then(
                    (instituicao) => self.salvarTelefones(telefones, instituicao.id)
                );
            });
        }

        salvarTelefones(telefones,pessoaId) {
            var self = this; let telefonesSalvos = [];
            if (telefones.length > 0) {
                telefones.forEach(function(telefone){
                    if (telefone.id === undefined) {
                        telefone.pessoa.id = pessoaId;
                        telefonesSalvos.push(self.telefoneService.salvar(telefone));
                    }
                });
                return Promise.all(telefonesSalvos);
            }
        }
        
        atualizar(objeto) {
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.atualizarEndereco(endereco).then(function(response){
                objeto.endereco = { id: response.id };
                return self.atualizarInstituicao(objeto).then(
                    (instituicao) => self.salvarTelefones(telefones, instituicao.id)
                );
            });
        }
        
        remover(objeto) { this.rest.remover(objeto, "Instituição", "F"); }
    };
    
    angular.module('InstituicaoService',[]).service('InstituicaoService',InstituicaoService);
    InstituicaoService.$inject = ["BaseService","EnderecoService","TelefoneService"];
})();
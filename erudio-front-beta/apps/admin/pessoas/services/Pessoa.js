(function (){
    'use strict';    
    class PessoaService {

        //necessidades-especiais
        
        constructor(rest,enderecoService,telefoneService,estadoCivilService,racaService,nacionalidadeService,particularidadeService,necessidadeEspecialService){
            this.rest = rest;
            this.enderecoService = enderecoService;
            this.telefoneService = telefoneService;
            this.estadoCivilService = estadoCivilService;
            this.racaService = racaService;
            this.nacionalidadeService = nacionalidadeService;
            this.particularidadeService = particularidadeService;
            this.necessidadeEspecialService = necessidadeEspecialService;
            this.genero = ["Masculino","Feminino"];
            this.url = 'pessoas';
        }
        
        get(id,loader){ return this.rest.um(this.url,id,loader); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstadosCivis(opcoes,loader){ return this.estadoCivilService.getAll(opcoes,loader); }
        getRacas(opcoes,loader){ return this.racaService.getAll(opcoes,loader); }
        getNacionalidades(opcoes,loader){ return this.nacionalidadeService.getAll(opcoes,loader); }
        getParticularidades(opcoes,loader){ return this.particularidadeService.getAll(opcoes,loader); }
        getNecessidadesEspeciais(opcoes,loader){ return this.necessidadeEspecialService.getAll(opcoes,loader); }
        getEstrutura() { return { nome:null, cpfCnpj:null, email:null, endereco: this.getEstruturaEndereco(), telefones: [], naturalidade: null, responsavelNome: null }; }
        getEstruturaCursoUnidade() { return this.cursoOfertadoService.getEstrutura(); }
        getEnderecoCompleto(id,loader) { return this.enderecoService.get(id,loader); }
        getTelefones(id,loader) { return this.telefoneService.getAllByPessoa({pessoa: id},loader); }
        getEstruturaEndereco() {return this.enderecoService.getEstrutura(); }
        getEstruturaTelefone() {return this.telefoneService.getEstrutura(); }
        salvarInstituicao(objeto,loader) { return this.rest.salvar(this.url, objeto, "Pessoa", "F",loader); }
        salvarEndereco(objeto,loader) { return this.enderecoService.salvar(objeto,loader); }
        salvarCursoUnidade(objeto,loader) { return this.cursoOfertadoService.salvar(objeto,loader); }
        atualizarInstituicao(objeto,loader) { return this.rest.atualizar(objeto, "Pessoa", "F",loader); }
        atualizarEndereco(objeto,loader) { return this.enderecoService.atualizar(objeto,loader); }
        atualizarCursoUnidade(objeto,loader) { return this.cursoOfertadoService.atualizar(objeto,loader); }
        
        salvar(objeto,loader) {
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.salvarEndereco(endereco,loader).then(function(response){
                objeto.endereco = { id: response.id };
                return self.salvarInstituicao(objeto,loader).then(
                    (instituicao) => self.salvarTelefones(telefones, instituicao.id)
                );
            });
        }

        salvarTelefones(telefones,pessoaId,loader) {
            var self = this; let telefonesSalvos = [];
            if (telefones.length > 0) {
                telefones.forEach(function(telefone){
                    if (telefone.id === undefined) {
                        telefone.pessoa.id = pessoaId;
                        telefonesSalvos.push(self.telefoneService.salvar(telefone,loader));
                    }
                });
                return Promise.all(telefonesSalvos);
            }
        }
        
        atualizar(objeto,loader) {
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.atualizarEndereco(endereco,loader).then(function(response){
                objeto.endereco = { id: response.id };
                return self.atualizarInstituicao(objeto,loader).then(
                    (instituicao) => self.salvarTelefones(telefones, instituicao.id,loader)
                );
            });
        }
        
        remover(objeto,loader) { this.rest.remover(objeto, "Pessoa", "F",loader); }
    };
    
    angular.module('PessoaService',[]).service('PessoaService',PessoaService);
    PessoaService.$inject = ["BaseService","EnderecoService","TelefoneService","EstadoCivilService",'RacaService','NacionalidadeService','ParticularidadeService','NecessidadeEspecialService'];
})();
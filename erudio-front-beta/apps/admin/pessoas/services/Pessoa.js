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
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes){ return this.rest.buscar(this.url,opcoes); }
        getEstadosCivis(opcoes){ return this.estadoCivilService.getAll(opcoes); }
        getRacas(opcoes){ return this.racaService.getAll(opcoes); }
        getNacionalidades(opcoes){ return this.nacionalidadeService.getAll(opcoes); }
        getParticularidades(opcoes){ return this.particularidadeService.getAll(opcoes); }
        getNecessidadesEspeciais(opcoes){ return this.necessidadeEspecialService.getAll(opcoes); }
        getEstrutura() { return { nome:null, cpfCnpj:null, email:null, tipo: { id:null }, instituicaoPai: { id:null }, endereco: this.getEstruturaEndereco(), cursos: [], telefones: [] }; }
        getEstruturaCursoUnidade() { return this.cursoOfertadoService.getEstrutura(); }
        getEnderecoCompleto(id) { return this.enderecoService.get(id); }
        getTelefones(id) { return this.telefoneService.getAllByPessoa({pessoa: id}); }
        getEstruturaEndereco() {return this.enderecoService.getEstrutura(); }
        getEstruturaTelefone() {return this.telefoneService.getEstrutura(); }
        salvarInstituicao(objeto) { return this.rest.salvar(this.url, objeto, "Unidade de Ensino", "F"); }
        salvarEndereco(objeto) { return this.enderecoService.salvar(objeto); }
        salvarCursoUnidade(objeto) { return this.cursoOfertadoService.salvar(objeto); }
        atualizarInstituicao(objeto) { return this.rest.atualizar(objeto, "Unidade de Ensino", "F"); }
        atualizarEndereco(objeto) { return this.enderecoService.atualizar(objeto); }
        atualizarCursoUnidade(objeto) { return this.cursoOfertadoService.atualizar(objeto); }
        
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
        
        remover(objeto) { this.rest.remover(objeto, "Unidade de Ensino", "F"); }
    };
    
    angular.module('PessoaService',[]).service('PessoaService',PessoaService);
    PessoaService.$inject = ["BaseService","EnderecoService","TelefoneService","EstadoCivilService",'RacaService','NacionalidadeService','ParticularidadeService','NecessidadeEspecialService'];
})();
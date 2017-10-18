(function (){
    'use strict';    
    class UnidadeService {        
        constructor(rest,enderecoService,telefoneService,cursoOfertadoService){
            this.rest = rest;
            this.enderecoService = enderecoService;
            this.telefoneService = telefoneService;
            this.cursoOfertadoService = cursoOfertadoService;
            this.url = 'unidades-ensino';
        }
        
        get(id){ return this.rest.um(this.url,id); }
        getAll(opcoes,loader){ return this.rest.buscar(this.url,opcoes,loader); }
        getEstrutura() { return { nome:null, cpfCnpj:null, email:null, tipo: { id:null }, instituicaoPai: { id:null }, endereco: this.getEstruturaEndereco(), cursos: [], telefones: [] }; }
        getEstruturaCursoUnidade() { return this.cursoOfertadoService.getEstrutura(); }
        getEnderecoCompleto(id,loader) { return this.enderecoService.get(id,loader); }
        getTelefones(id) { return this.telefoneService.getAllByPessoa({pessoa: id}); }
        getEstruturaEndereco() {return this.enderecoService.getEstrutura(); }
        getEstruturaTelefone() {return this.telefoneService.getEstrutura(); }
        salvarInstituicao(objeto) { return this.rest.salvar(this.url, objeto, "Unidade de Ensino", "F"); }
        salvarEndereco(objeto) { return this.enderecoService.salvar(objeto); }
        atualizarInstituicao(objeto) { return this.rest.atualizar(objeto, "Unidade de Ensino", "F"); }
        atualizarEndereco(objeto) { return this.enderecoService.atualizar(objeto); }
        atualizarCursoUnidade(objeto) { return this.cursoOfertadoService.atualizar(objeto); }
        
        salvar(objeto) {
            let cursos = objeto.cursos; delete objeto.cursos; delete objeto.removerCurso;
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.salvarEndereco(endereco).then(function(response){
                objeto.endereco = { id: response.id };
                return self.salvarInstituicao(objeto).then(
                    (instituicao) => {
                        self.salvarTelefones(telefones, instituicao.id);
                        self.salvarCursoUnidade(cursos, instituicao.id);
                    }
                );
            });
        }

        salvarCursoUnidade(cursos, pessoaId) { 
            var self = this;
            if (cursos.length > 0) {
                var estrutura = this.cursoOfertadoService.getEstrutura();
                estrutura.unidadeEnsino.id = pessoaId;
                cursos.forEach(function(curso){
                    let obj = angular.copy(estrutura);
                    obj.curso.id = curso;
                    self.cursoOfertadoService.salvar(obj);
                });
            }
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
            let removerCurso = objeto.removerCurso; delete objeto.removerCurso;
            let cursos = objeto.cursos; delete objeto.cursos;
            let telefones = objeto.telefones; delete objeto.telefones;
            let endereco = objeto.endereco; delete objeto.endereco; var self = this;
            return this.atualizarEndereco(endereco).then(function(response){
                objeto.endereco = { id: response.id };
                return self.atualizarInstituicao(objeto).then(
                    (instituicao) => {
                        self.salvarTelefones(telefones, instituicao.id);
                        self.salvarCursoUnidade(cursos, instituicao.id, removerCurso);
                        self.removerCursosOfertados(removerCurso, instituicao.id);
                    }
                );
            });
        }
        
        remover(objeto) { this.rest.remover(objeto, "Unidade de Ensino", "F"); }
        removerCursoOfertado(curso) { this.rest.remover(curso,null); }

        removerCursosOfertados(cursos, pessoaId) {
            var self = this;
            this.cursoOfertadoService.getAll({ unidadeEnsino: pessoaId }).then((cursosOfertados) => {
                cursosOfertados.forEach((curso) => { 
                    var idx = cursos.indexOf(curso.curso.id);
                    if (idx > -1) { self.removerCursoOfertado(curso); }
                });
            });
        }
    };
    
    angular.module('UnidadeService',[]).service('UnidadeService',UnidadeService);
    UnidadeService.$inject = ["BaseService","EnderecoService","TelefoneService","CursoOfertadoService"];
})();
(function (){
    
    class TestController {
        constructor(instituicaoService, util){
            this.instituicaoService = instituicaoService;
            this.util = util;
            //this.pagina = 0;
            this.iniciar();
        }
        
        iniciar(){
            $('.sem-permissao-texto').hide(); $('.sem-permissao-cortina').hide(); $('.loader').hide();
            var self = this;
            //var novosTelefones = [];
            
            //GET
            //this.instituicaoService.get('101805').then((instituicao) => this.objeto = instituicao);
            
            //GET2
            //let i = this.instituicaoService.get('101805').then((instituicao) => new Promise((resolve) => {resolve(instituicao);}) );
            
            //DELETE
            /*i.then(function(instituicao){
                self.instituicaoService.remover(instituicao);
            });*/
            
            //EDIT
            /*i.then(function(ins){
                let e = self.instituicaoService.getEnderecoCompleto(ins.endereco.id).then((endereco) => new Promise((resolve) => {resolve(endereco);}) );
                return e.then(function(end){ ins.endereco = end; return new Promise((resolve) => resolve(ins)); });
            }).then(function(response){
                let i = response;
                
                i.nome = 'Teste Service 2';
                i.endereco.logradouro = 'Rua Teste 2';

                let tel = self.instituicaoService.getEstruturaTelefone();
                tel.descricao = "COMERCIAL";
                tel.numero = "(47)12345678";
                i.telefones.push(tel);

                let tel2 = self.instituicaoService.getEstruturaTelefone();
                tel2.descricao = "RESIDENCIAL";
                tel2.numero = "(47)98765432";
                i.telefones.push(tel2);

                var result = self.instituicaoService.atualizar(i).then(function(response){
                    return new Promise((resolve) => { resolve(response); });
                }).then(function(telefones){
                    return self.instituicaoService.get(telefones[0].pessoa.id).then((instituicao) => { 
                        i = instituicao; return new Promise((resolve) => resolve(i));
                    });
                });
                console.log(result); 
            });*/
            
            //GET LIST
            /*this.instituicaoService.getAll({pagina: this.pagina}).then((instituicoes) => {
                if (this.pagina === 0) { this.objetos = instituicoes; } else { this.objetos.concat(instituicoes); }
            });*/
            
            //SAVE
            /*var result = this.instituicaoService.salvar(i).then(function(response){
                return new Promise((resolve) => { resolve(response); });
            }).then(function(telefones){
                return self.instituicaoService.get(telefones[0].pessoa.id).then((instituicao) => { 
                    i = instituicao; i.telefones = telefones;
                    return new Promise((resolve) => resolve(i));
                });
            });*/
        }
    };
    
    TestController.$inject = ["InstituicaoService","Util"];
    angular.module('teste',[]).controller('TESTE',TestController);
})();
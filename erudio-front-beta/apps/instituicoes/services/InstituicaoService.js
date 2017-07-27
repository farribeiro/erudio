(function (){
    var instituicaoService = angular.module('instituicaoService',['ngMaterial','rest']);
    
    instituicaoService.service('INSTITUICAO', ['REST', function(REST) {
        
        this.url = 'instituicoes';
        this.label = 'Instituição';
        this.gen = 'F';
        
        this.getLista = function (callback){
            REST.buscar(this.url, null, callback);
        };
        
        this.getById = function (id,callback){
            REST.um(this.url, id, callback);
        };
        
        this.getByNome = function (nome,callback){
            REST.buscar(this.url, {nome: nome}, callback);
        };
        
        this.getEstrutura = function () {
            return { nome:null, sigla:null, cpfCnpj:null, email:null, endereco:null, telefones:[] };
        };
        
        this.post = function (objeto, callback) {
            REST.salvar(objeto, this.url, this.label, this.gen, callback);
        };
        
        this.put = function (objeto, callback) {
            REST.atualizar(objeto, this.url, this.label, this.gen, callback);
        };
        
        this.delete = function (objeto) {
            REST.remover(objeto, this.label, this.gen);
        };
    }]);
})();
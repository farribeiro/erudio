(function (){
    var rest = angular.module('rest',['structure', 'validator', 'angular-md5', 'angular-sha1', 'erudioConfig', 'ngMaterial', 'util']);
    
    rest.service('REST', ['Restangular', '$mdToast', '$http', function(Restangular, $mdToast, $http) {
        
        //CONTROLE DA TELA DE PROGRESSO
        this.abreProgresso = function () { $('.progresso').show(); $('.cortina').show(); };
        this.fechaProgresso = function () { $('.progresso').hide(); $('.cortina').hide(); };
        this.abreLoader = function () { $('.loader').show(); };
        this.fechaLoader = function () { $('.loader').hide(); };
        
        //PREPARA HEADER X-WSSE
        this.criarHeader = function () { var token = sessionStorage.getItem('token'); var header = "Bearer "+token; return header; };

        // BUSCAR PROMISE
        this.buscarPromise = function(endereco) {
            var rest = this.preparaRestangular();
            rest.setFullResponse(true);
            return rest.all(endereco);
        };

        //INSERE O HEADER NA CHAMADA REST
        this.preparaRestangular = function () {
            var header = this.criarHeader();
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "JWT-Authorization": header }); });
            return rest;
        };
        
        //RECUPERA TOKEN PARA OPERACOES DE BUSCAR POR ID
        this.retrieveAndRetry = function () {
            var rest = this.preparaRestangular();
            var promise = rest.all('tokens');
            var auth = {username: sessionStorage.getItem('username'), password: sessionStorage.getItem('pass')}; 
            return promise.post(auth);
        };
        
        //BUSCA POR ID
        this.um = function (endereco, id, callback, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            var promise = rest.one(endereco, id).get(); var self = this;
            promise.then(function(response){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                if(callback !== null && callback !== undefined){ callback(response); }
            }, function (){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.um(endereco,id,callback,loader);
                });
            });
        };
        
        //BUSCA POR LISTA
        this.buscar = function(endereco, opcoes, callback, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            var promise = rest.all(endereco).getList(opcoes); var self = this;
            promise.then(function(response){ 
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                if(callback !== null && callback !== undefined){ callback(response); }
            }, function (){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.buscar(endereco,opcoes,callback,loader);
                });
            });
        };
        
        //HELPER PARA ORIENTACAO DAS PALAVRAS
        this.orientarLabel = function (label, orientacao){ var retorno = ''; if (orientacao === "M") { retorno = label+"o"; } else if (orientacao === "F") { retorno = label+"a"; } else { retorno = label+"e"; } return retorno; };
        
        //REMOVER OBJETO
        this.remover = function (objeto, label, orientacao, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }
            var result = objeto.remove(); var self = this;
            var sufix = this.orientarLabel('removid', orientacao);
            result.then(function (response){ 
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                if (label !== null && label !== '') { $mdToast.show($mdToast.simple().textContent(label+' '+sufix)); } 
            },function (){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.remover(objeto, label, orientacao, loader);
                });
            });
        };
        
        //SALVAR
        this.salvar = function(objeto,endereco,label,gen, callback, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }
            var result = []; var artigo = 'e'; var self = this;
            if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
            var promise = this.buscarPromise(endereco);
            result = promise.post(objeto);
            result.then(function(data) {
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                if(callback !== null && callback !== undefined){ callback(data); }
                if (data.status >= 200 || data.status <= 204) {
                    if (label !== null && label !== '' && label !== undefined) { $mdToast.show($mdToast.simple().textContent(label+' salv'+artigo+' com sucesso.')); }
                }
            }, function() {
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.salvar(objeto,endereco,label,gen, callback, loader);
                });
            });
        };
        
        //UPDATE
        this.atualizar = function (objeto,endereco,label,gen, callback, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }
            var result = []; var artigo = 'e'; var self = this;
            if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
            if (objeto !== null && objeto.id) {
                if (objeto.route === undefined || objeto.route === null) { objeto.route = endereco; } result = objeto.put();
                result.then(function (data){
                    if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                    if(callback !== null && callback !== undefined){ callback(data); }
                    if (data.status >= 200 || data.status <= 204) {
                        if (label !== null && label !== '' && label !== undefined) { $mdToast.show($mdToast.simple().textContent(label+' modificad'+artigo+' com sucesso.')); }
                    }
                }, function() {
                    if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                    var res = self.retrieveAndRetry();
                    res.then(function(response){
                       sessionStorage.setItem('token',response.token);
                       self.atualizar(objeto,endereco,label,gen, callback, loader);
                    });
                });
            }
        };
        
        //SALVAR EM BATCH
        this.salvarLote = function(objeto, endereco, label, gen, callback, loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }  var self = this;
            var artigo = 'e'; if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
            var resultado = $http({
                method: "PUT",
                url: Restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'JWT-Authorization': this.criarHeader()}
            });
            resultado.then(function(data){
                if (data.status === 200 || data.status === 204) {
                    if(callback !== null && callback !== undefined){ callback(data); }
                    if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                    if (label !== null && label !== '' && label !== undefined) { $mdToast.show($mdToast.simple().textContent(label+' salv'+artigo+' com sucesso.')); }
                }
            }, function() { 
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.salvarLote(objeto, endereco, label, gen, callback, loader);
                });
            });
            return resultado;
        };

        //EXCLUIR EM BATCH
        this.excluirLote = function(objeto,endereco,callback,loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }  var self = this;
            var resultado = $http({
                method: "DELETE",
                url: Restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'Content-Type': 'application/json','JWT-Authorization': this.criarHeader()}
            });
            resultado.then(function(response){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                if(callback !== null && callback !== undefined){ callback(response); }
            },function(){
                if (loader === undefined) { self.fechaLoader(); } else { self.fechaProgresso(); }
                var res = self.retrieveAndRetry();
                res.then(function(response){
                   sessionStorage.setItem('token',response.token);
                   self.excluirLote(objeto,endereco,callback,loader);
                });
            });
        };
    }]);
})();
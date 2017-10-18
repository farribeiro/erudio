(function (){
    'use strict';
    
    class BaseService {
        constructor(Restangular, $mdToast, $http) {
            this.restangular = Restangular;
            this.mdToast = $mdToast;
            this.http = $http;
            this.loginPage = '/login.html!#/';
        }
        
        //CONTROLE DA TELA DE PROGRESSO
        abreProgresso() { $('.progresso').show(); $('.cortina').show(); }
        fechaProgresso() { $('.progresso').hide(); $('.cortina').hide(); }
        abreLoader() { $('.loader').show(); }
        fechaLoader() { $('.loader').hide(); }
        
        //PREPARA HEADER X-WSSE
        criarHeader() { var token = sessionStorage.getItem('token'); var header = "Bearer "+token; return header; }

        // BUSCAR PROMISE
        buscarPromise(endereco) { var rest = this.preparaRestangular(); rest.setFullResponse(true); return rest.all(endereco); }

        //INSERE O HEADER NA CHAMADA REST
        preparaRestangular() {
            var header = this.criarHeader();
            var rest = this.restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "JWT-Authorization": header }); });
            return rest;
        }
        
        //REAUTENTICAR
        reautenticar() {
            let rest = this.preparaRestangular();
            let auth = { username: sessionStorage.getItem('username'), password: sessionStorage.getItem('pass') };
            return new Promise((resolve, reject) => {
                rest.all('tokens').post(auth)
                .then((response) => { sessionStorage.setItem('token', response.token); resolve(); })
                .catch(() => { this.redirect(this.loginPage); });
            });
        }
        
        //REDIRECIONAR
        redirect(link) { window.location = link; };
        
        //ATIVAR PROGRESSO
        ativarLoader(loader) { if (loader === undefined) { this.abreProgresso(); } else { this.abreLoader(); } }

        //DESATIVAR PROGRESSO
        desativarLoader(loader) { if (loader === undefined) { this.fechaProgresso(); } else { this.fechaLoader(); } }
        
        //BUSCA POR ID
        um(id, endereco, loader) {
            var self = this; self.ativarLoader(loader);
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            return rest.one(id, endereco).get().then(function(response){
                return new Promise((resolve) => { self.desativarLoader(loader); resolve(response.data); });
            }, function (error){            
                return new Promise((resolve,reject) => {
                    if (error.data.code === 401) {
                        self.reautenticar().then(() => self.um(id, endereco, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                    } else { reject(error); self.desativarLoader(loader); }
                });
            });
        }
        
        //BUSCA POR LISTA
        buscar(endereco, opcoes, loader) {
            var self = this; self.ativarLoader(loader);
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            return rest.all(endereco).getList(opcoes).then(function(response){ 
                return new Promise((resolve) => { self.desativarLoader(loader); resolve(response.data); });
            }, function (error){
                return new Promise((resolve,reject) => {
                    if (error.data.code === 401) {
                        self.reautenticar().then(() => self.buscar(endereco, opcoes, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                    } else { reject(error); self.desativarLoader(loader); }
                });
            });
        }
        
        //HELPER PARA ORIENTACAO DAS PALAVRAS
        orientarLabel(label, orientacao){ var retorno = ''; if (orientacao === "M") { retorno = label+"o"; } else if (orientacao === "F") { retorno = label+"a"; } else { retorno = label+"e"; } return retorno; }
        
        //SALVAR
        salvar(endereco, objeto, label, gen, loader) {
            var self = this; self.ativarLoader(loader);
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            var promise = this.buscarPromise(endereco);
            return promise.post(objeto).then(function(response) {
                return new Promise((resolve) => { 
                    if (response.status >= 200 || response.status <= 204) {
                        var artigo = 'e'; if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
                        if (label !== undefined && label !== null) { self.mdToast.show(self.mdToast.simple().textContent(label+' salv'+artigo+' com sucesso.')); }
                    }
                    self.desativarLoader(loader); resolve(response.data);
                });
            }, function(error) {
                return new Promise((resolve,reject) => {
                    if (error.data.code === 401) {
                        self.reautenticar().then(() => self.salvar(endereco, objeto, label, gen, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                    } else { reject(error); self.desativarLoader(loader); }
                });
            });
        }
        
        //UPDATE
        atualizar(objeto, label, gen, loader) {
            var self = this; self.ativarLoader(loader);
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            if (objeto !== null && objeto.id) {
                if (objeto.route === undefined || objeto.route === null) { objeto.route = this.url; }
                return objeto.put().then(function (response){
                    return new Promise((resolve) => { 
                        if (response.status >= 200 || response.status <= 204) {
                            var artigo = 'e'; if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
                            if (label !== undefined  && label !== null) { self.mdToast.show(self.mdToast.simple().textContent(label+' atualizad'+artigo+' com sucesso.')); }
                        }
                        self.desativarLoader(loader); resolve(response.data);
                    });
                }, function(error) {
                    return new Promise((resolve,reject) => {
                        if (error.data.code === 401) {
                            self.reautenticar().then(() => self.atualizar(objeto, label, gen, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                        } else { reject(error); self.desativarLoader(loader); }
                    });
                });
            }
        }
        
        //REMOVER OBJETO
        remover(objeto, label, orientacao, loader) {
            var self = this; self.ativarLoader(loader);
            var rest = this.preparaRestangular(); rest.setFullResponse(true);
            objeto.remove();
            return new Promise((resolve) => {
                var sufix = self.orientarLabel('removid', orientacao);
                if (label !== null && label !== '' && label !== undefined) { self.mdToast.show(self.mdToast.simple().textContent(label+' '+sufix)); } 
                self.desativarLoader(loader); resolve('removido');
            });
            //TRATAR ERRO
            /*    
            }, function(error) {
                return new Promise((resolve,reject) => {
                    if (error.data.code === 401) {
                        self.reautenticar().then(() => self.remover(objeto, label, orientacao, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                    } else { reject(error); self.desativarLoader(loader); }
                });
            });*/
        }
        
        //SALVAR EM BATCH
        salvarLote(objeto, endereco, label, gen, loader) {
            var self = this; self.ativarLoader(loader);
            var artigo = 'e'; if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
            var resultado = this.http({
                method: "PUT",
                url: this.restangular.configuration.baseUrl + "/" + endereco,
                data: objeto,
                headers: {'JWT-Authorization': this.criarHeader()}
            });
            resultado.then(function(data){
                return new Promise((resolve) => {
                    if (data.status >= 200 || data.status <= 204) {
                        var artigo = 'e'; if (gen === 'M') { artigo = 'o'; } else if (gen === 'F') { artigo = 'a'; }
                        if (label !== undefined  && label !== null) { self.mdToast.show(self.mdToast.simple().textContent(label+' atualizad'+artigo+' com sucesso.')); }
                    }
                    self.desativarLoader(loader); resolve(data.data);
                });
            }, function(error) {
                return new Promise((resolve,reject) => {
                    if (error.data.code === 401) {
                        self.reautenticar().then(() => self.atualizar(objeto, label, gen, loader)).then((data) => { self.desativarLoader(loader); resolve(data); });
                    } else { reject(error); self.desativarLoader(loader); }
                });
            });
        }

        //EXCLUIR EM BATCH
        excluirLote(objeto,endereco,callback,loader) {
            if (loader === undefined) { this.abreLoader(); } else { this.abreProgresso(); }  var self = this;
            var resultado = $http({
                method: "DELETE",
                url: this.restangular.configuration.baseUrl + "/" + endereco,
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
        }
    }
    
    angular.module('BaseService',['ngMaterial']).service('BaseService',BaseService);
    BaseService.$inject = ["Restangular",'$mdToast','$http'];
}());
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

(function (){
    var mainModule = angular.module('mainModule', ['loginDirectives','angular-md5','angular-sha1', 'erudioConfig']);
    mainModule.controller('MainController',['$scope', '$timeout', 'md5', 'Restangular', 'sha1', '$templateCache', 'ErudioConfig', function($scope, $timeout, md5, Restangular, sha1, $templateCache, ErudioConfig){
        //LIMPA CACHE
        $templateCache.removeAll();
        $scope.usuario = null; $scope.senha = null; $scope.fonte = ''; $scope.foto = ''; $scope.btnText = 'ENTRAR';
        
        //GERADOR NONCE
        $scope.generateNonce = function () {
            var text = ""; var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i = 0; i < length; i++) { text += possible.charAt(Math.floor(Math.random() * possible.length)); }
            return text;
        };
        
        //GERADOR HEADER INTRANET
        $scope.criarHeaderIntranet = function (token) { return $scope.criarHeader(token); };
        
        //PREPARA HEADER X-WSSE
        $scope.criarHeader = function(token) { var header = "Bearer "+token; return header; };

        //INSERE O HEADER NA CHAMADA REST
        $scope.preparaRestangular = function(token) {
            var header = this.criarHeader(token);
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "JWT-Authorization": header }); });
            return rest;
        };
        
        //GERADOR GUID
        $scope.guid = function () { function s4() { return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1); } return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4(); };
        
        //CRIA SESSÃO NO SESSION STORAGE
        $scope.setaSessao = function (user, sessionId) {
            //seta avatar
            var profile = 'profile';
            if (user.pessoa !== undefined && user.pessoa.genero !== 'M') { profile = 'profileGirl'; }
            //busca pessoa para criar sessão
            if (user.pessoa.avatar !== undefined) {
                //busca avatar pessoal
                var promise = rest.one('assets',user.pessoa.avatar).get();
                promise.then(function(response){ 
                    sessionStorage.setItem('avatar',response.data.file); sessionStorage.setItem("username", $scope.usuario);
                    sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa)); sessionStorage.setItem("nome", user.nomeExibicao);
                    sessionStorage.setItem("pessoaId", user.id); sessionStorage.setItem("profile", profile);
                    sessionStorage.setItem("key", md5.createHash($scope.senha)); sessionStorage.setItem("sessionId", md5.createHash(sessionId));
                    sessionStorage.setItem("menu",0); sessionStorage.setItem("module","main");
                    sessionStorage.setItem("moduleOptions",""); sessionStorage.setItem("vinculo","");
                    sessionStorage.setItem("alocacao", ""); sessionStorage.setItem("disciplina", "");
                    $timeout(function (){ window.location.href = ErudioConfig.dominio+'/#/'; }, 1000);
                });
            } else {
                //sem avatar pessoal
                sessionStorage.setItem("username", $scope.usuario); sessionStorage.setItem("nome", user.nomeExibicao);
                sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa)); sessionStorage.setItem("pessoaId", user.id);
                sessionStorage.setItem("profile", profile); sessionStorage.setItem("key", md5.createHash($scope.senha));
                sessionStorage.setItem("sessionId", md5.createHash(sessionId)); sessionStorage.setItem("menu",0);
                sessionStorage.setItem("module","main"); sessionStorage.setItem("moduleOptions","");
                sessionStorage.setItem("vinculo",""); sessionStorage.setItem("alocacao", ""); sessionStorage.setItem("disciplina", "");
                $timeout(function (){ window.location.href = ErudioConfig.dominio+'/#/'; }, 1000);
            }
        };
        
        //EFETUAR LOGIN
        $scope.login = function () {
            
            //SÓ PROSSEGUE QUANDO USER E SENHA ESTÃO PREENCHIDOS
            if ($scope.usuario !== '' && $scope.usuario !== null && $scope.senha !== '' && $scope.senha !== null) {
                let auth = { username: $scope.usuario, password: btoa($scope.senha) };                
                //CRIA O HEADER
                var sessionId = $scope.guid(); var header = $scope.criarHeader($scope.usuario, $scope.senha);
                //var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); });
                var rest = Restangular;
                rest.setFullResponse(true); $scope.btnText = 'CARREGANDO...';
                rest.all('tokens').post(auth).then(function(response){
                    if (response.data.length === 0) {
                        //USUARIO NAO ENCONTRADO
                        $scope.btnText = 'ENTRAR'; Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                    } else {
                        //ANIMACAO INICIAL
                        $scope.loginAnimation(); 
                        sessionStorage.setItem('token',response.data.token);
                        var restangular = $scope.preparaRestangular(response.data.token);
                        var promise = restangular.all('users').getList({username: $scope.usuario});
                        promise.then(function(response){
                            if (response.status === 200) {
                                var user = response.data[0]; 
                                var promiseU = restangular.one('users',user.id).get();
                                promiseU.then(function(responseU){
                                    sessionStorage.setItem('user', JSON.stringify(responseU.data));
                                    var roles = responseU.data.atribuicoes; var atribuicoes = [];
                                    //PREPARA PERMISSOES
                                    var unidadesPermissoes = [];
                                    if (roles.length > 0) {
                                        for (var i=0; i<roles.length; i++)
                                        {
                                            unidadesPermissoes.push(roles[i].instituicao);
                                            var index = i;
                                            if (roles[i].grupo !== undefined) {
                                                var promise = restangular.all('permissoes-grupo').getList({'grupo':roles[i].grupo.id});
                                                promise.then(function(response){
                                                    for (var j=0; j<response.data.length; j++) { atribuicoes.push(response.data[j]); }
                                                    if (atribuicoes.length > 0) {
                                                        sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                                        if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                                    } else {
                                                        var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                                        sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                                        if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                                    }
                                                });
                                            } else {
                                                if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                            }
                                            if (index === roles.length-1) { sessionStorage.setItem('unidadesPermissoes',JSON.stringify(unidadesPermissoes)); }
                                        }
                                    } else {
                                        $scope.setaSessao(user, sessionId);
                                    }
                                });
                            }
                        });
                    }
                }, function(error){
                    //SENHA ERRADA
                    $scope.btnText = 'ENTRAR'; if (error.data.error.code === 401){ Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000); }
                });
            } else { $scope.btnText = 'ENTRAR'; Materialize.toast("Preencha os dois campos para efetuar login. :)", 5000); }
        };

        //ANIMAÇÃO INICIAL
        $scope.loginAnimation = function() {      
            $timeout(function() {
                $('.card-content, .card-action, nav').addClass('fade-out'); $('.loader').css('opacity',1);
                $timeout(function() { $('.card-expand').css('transform','scale(3)'); }, 500);
            }, 200);
        };
        
        //EVENTO DE LOGIN NA TECLA ENTER
        $timeout(function() {
            $('#senha').keypress(function(event) {
                var tecla = (window.event) ? event.keyCode : event.which; if (tecla === 13) { $scope.login(); return false; } else { return true; }
            });
        }, 500);
        
        //BUSCA PARAMETROS GET NO LOGIN VIA INTRANET
        $scope.getUrlParameter = function (sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)), sURLVariables = sPageURL.split('&'), sParameterName, i;
            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] === sParam) { return sParameterName[1] === undefined ? true : sParameterName[1]; }
            }
        };
        
        //EVENTO DE LOGIN VIA INTRANET
        $scope.intranetLogin = function () {
            $scope.usuario = $scope.getUrlParameter('username'); $scope.senha = $scope.getUrlParameter('key');
            var sessionId = $scope.guid(); var header = $scope.criarHeaderIntranet($scope.usuario, $scope.senha);
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); }); rest.setFullResponse(true);
            
            if ($scope.usuario !== '' && $scope.usuario !== null && $scope.senha !== '' && $scope.senha !== null) {
                
                //PREPARA O HEADER
                var promise = rest.all('users').getList({'username':$scope.usuario});
                promise.then(function(response){
                    if (response.status === 200) {
                        if (response.data.length === 0) {
                            
                            //USUARIO NAO ENCONTRADO
                            Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                        } else {                         
                            var user = response.data[0]; sessionStorage.setItem('user', JSON.stringify(user));
                            var promiseU = rest.one('users',user.id).get();
                            promiseU.then(function(responseU){
                                var roles = responseU.data.atribuicoes; var atribuicoes = [];
                                //PREPARA PERMISSOES
                                var unidadesPermissoes = [];
                                for (var i=0; i<roles.length; i++)
                                {
                                    unidadesPermissoes.push(roles[i].instituicao);
                                    var index = i;
                                    if (roles[i].grupo !== undefined) {
                                        var promise = rest.all('permissoes-grupo').getList({'grupo':roles[i].grupo.id});
                                        promise.then(function(response){
                                            for (var j=0; j<response.data.length; j++) { atribuicoes.push(response.data[j]); }
                                            if (atribuicoes.length > 0) {
                                                sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                                if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                            } else {
                                                var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                                sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                                if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                            }
                                        });
                                    } else {
                                        if (index === roles.length-1) { $scope.setaSessao(user, sessionId); }
                                    }
                                    if (index === roles.length-1) { sessionStorage.setItem('unidadesPermissoes',JSON.stringify(unidadesPermissoes)); }
                                }
                            });
                            
                            
                        }
                    }
                }, function(error){
                    //SENHA ERRADA
                    if (error.status === 403){ Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000); }
                });
            } else { Materialize.toast("Preencha os dois campos para efetuar login. :)", 5000); }
        };

        //ANIMAÇÃO INICIAL DA PÁGINA
        $(document).ready(function(){
            setTimeout(function(){ $('.brand-logo').css('opacity',1); }, 100); setTimeout(function(){ $('.user').css('opacity',1).css('margin-bottom','5rem'); }, 100);
            setTimeout(function(){ $('.pass').css('opacity',1);}, 300); setTimeout(function(){ $('.loginBtn').css('opacity',1).css('bottom',0);}, 900);
            setTimeout(function(){ $('.link1').css('opacity',1);}, 200); setTimeout(function(){ $('.link2').css('opacity',1);}, 300);
            setTimeout(function(){ $('.link3').css('opacity',1);}, 400); setTimeout(function(){ $('.foto-user').css('opacity',1); }, 700);
            setTimeout(function(){ $('.foto-user').css('transform','scale(1)'); }, 800); setTimeout(function(){ $('#usuario').focus(); }, 1);
            $('.login-form').css('opacity',1);            
        });
        
        //FUNÇÃO PARA ABRIR EM NOVA ABA(?)
        $scope.openInNewTab = function(url) { var win = window.open(url, '_blank'); win.focus(); };

        //LIMPA A SESSÃO QUANDO ACESSA A PÁGINA, FORÇANDO O LOGIN
        sessionStorage.clear();
    }]);
})();

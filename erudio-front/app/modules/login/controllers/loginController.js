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
    var mainModule = angular.module('mainModule', ['loginDirectives','angular-md5','angular-sha1']);

    mainModule.controller('MainController',['$scope', '$timeout', 'md5', 'Restangular', 'sha1', function($scope, $timeout, md5, Restangular, sha1){        
        $scope.usuario = null; $scope.senha = null;
        $scope.fonte = ''; $scope.foto = '';
        $scope.btnText = 'ENTRAR';
        
        //GERADOR NONCE
        $scope.generateNonce = function () {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for(var i = 0; i < length; i++) { text += possible.charAt(Math.floor(Math.random() * possible.length)); }
            return text;
        };
        
        //GERADOR HEADER
        $scope.criarHeader = function (username, senha) {
            var created = moment().format(); var nonce = $scope.guid();
            var nonceSend = btoa(nonce); var pass = md5.createHash(senha);
            var digest = btoa(sha1.hash(nonce + created + pass));
            var header = 'UsernameToken Username="' + username + '", PasswordDigest="' + digest + '", Nonce="' + nonceSend + '", Created="' + created + '"';
            return header;
        };
        
        $scope.criarHeaderIntranet = function (username, senha) {
            var created = moment().format(); var nonce = $scope.guid();
            var nonceSend = btoa(nonce); var pass = senha;
            var digest = btoa(sha1.hash(nonce + created + pass));
            var header = 'UsernameToken Username="' + username + '", PasswordDigest="' + digest + '", Nonce="' + nonceSend + '", Created="' + created + '"';
            return header;
        };
        
        //GERADOR GUID
        $scope.guid = function () {
            function s4() { return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1); }
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
        };
        
        //EFETUAR LOGIN
        $scope.login = function () {            
            //se usuario e senha estiver preenchido, prossegue.
            if ($scope.usuario !== '' && $scope.usuario !== null && $scope.senha !== '' && $scope.senha !== null) {
                //cria o header
                var sessionId = $scope.guid();
                var header = $scope.criarHeader($scope.usuario, $scope.senha);
                var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); });
                rest.setFullResponse(true);

                $scope.btnText = 'CARREGANDO...';

                //busca o usuario
                var promise = rest.all('users').getList({'username':$scope.usuario});
                promise.then(function(response){
                    if (response.status === 200) {
                        if (response.data.length === 0) {
                            //se tem resposta mas não encontra o usuario
                            $scope.btnText = 'ENTRAR';
                            Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                        } else {
                            
                            //faz animação inicial e prepara o login
                           $scope.loginAnimation();
                            
                            $timeout(function() {                                
                                var user = response.data[0];
                                sessionStorage.setItem('user', JSON.stringify(user));
                                var roles = user.rolesAtribuidas;
                                var atribuicoes = [];
                                for (var i=0; i<roles.length; i++)
                                {
                                    if (roles[i].grupo !== undefined) {
                                        var promise = rest.all('permissoes-grupo').getList({'grupo':roles[i].grupo.id});
                                        promise.then(function(response){
                                            for (var j=0; j<response.data.length; j++) { atribuicoes.push(response.data[j]); }
                                            if (atribuicoes.length > 0) {
                                                sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                            } else {
                                                var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                                sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                            }
                                        });
                                    } else {
                                        atribuicoes.push(roles[i]);
                                        if (atribuicoes.length > 0) {
                                            sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                        } else {
                                            var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                            sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                        }
                                    }
                                }
                                //seta avatar
                                var profile = 'profile';
                                if (user.pessoa !== undefined && user.pessoa.genero !== 'M') { profile = 'profileGirl'; }
                                //busca pessoa para criar sessão
                                if (user.pessoa.avatar !== undefined) {
                                    //busca avatar pessoal
                                    var promise = rest.one('assets',user.pessoa.avatar).get();
                                    promise.then(function(response){ 
                                        sessionStorage.setItem('avatar',response.data.file);
                                        sessionStorage.setItem("username", $scope.usuario);
                                        sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa));
                                        sessionStorage.setItem("nome", user.nomeExibicao);
                                        sessionStorage.setItem("pessoaId", user.id);
                                        sessionStorage.setItem("profile", profile);
                                        sessionStorage.setItem("key", md5.createHash($scope.senha));
                                        sessionStorage.setItem("sessionId", md5.createHash(sessionId));
                                        sessionStorage.setItem("menu",0);
                                        sessionStorage.setItem("module","main");
                                        sessionStorage.setItem("moduleOptions","");
                                        sessionStorage.setItem("vinculo","");
                                        sessionStorage.setItem("alocacao", "");
                                        sessionStorage.setItem("disciplina", "");
                                        $timeout(function (){ window.location = 'index.html'; }, 1000);
                                    });
                                } else {
                                    //sem avatar pessoal
                                    sessionStorage.setItem("username", $scope.usuario);
                                    sessionStorage.setItem("nome", user.nomeExibicao);
                                    sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa));
                                    sessionStorage.setItem("pessoaId", user.id);
                                    sessionStorage.setItem("profile", profile);
                                    sessionStorage.setItem("key", md5.createHash($scope.senha));
                                    sessionStorage.setItem("sessionId", md5.createHash(sessionId));
                                    sessionStorage.setItem("menu",0);
                                    sessionStorage.setItem("module","main");
                                    sessionStorage.setItem("moduleOptions","");
                                    sessionStorage.setItem("vinculo","");
                                    sessionStorage.setItem("alocacao", "");
                                    sessionStorage.setItem("disciplina", "");
                                    $timeout(function (){ window.location = 'index.html'; }, 1000);
                                }
                            }, 1000);
                        }
                    }
                }, function(error){
                    $scope.btnText = 'ENTRAR';
                    if (error.status === 403){
                        Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                    }
                });
            } else {
                $scope.btnText = 'ENTRAR';
                Materialize.toast("Preencha os dois campos para efetuar login. :)", 5000);
            }
        };

        //ANIMAÇÃO INICIAL
        $scope.loginAnimation = function() {      
            $timeout(function() {
                $('.card-content, .card-action, nav').addClass('fade-out');
                $('.loader').css('opacity',1);
                $timeout(function() {
                    $('.card-expand').css('transform','scale(3)');
                }, 500);
            }, 200);
        };
        
        //evento de login na tecla enter
        $timeout(function() {
            $('#senha').keypress(function(event) {
                var tecla = (window.event) ? event.keyCode : event.which;
                if (tecla === 13) {
                    $scope.login();                    
                    return false;
                } else {
                    return true;
                }
            });
        }, 500);
        
        $scope.getUrlParameter = function (sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        
        $scope.intranetLogin = function () {
            $scope.usuario = $scope.getUrlParameter('username');
            $scope.senha = $scope.getUrlParameter('key');
            
            var sessionId = $scope.guid();
            var header = $scope.criarHeaderIntranet($scope.usuario, $scope.senha);
            var rest = Restangular.withConfig(function(conf){ conf.setDefaultHeaders({ "X-WSSE": header }); });
            rest.setFullResponse(true);
            
            if ($scope.usuario !== '' && $scope.usuario !== null && $scope.senha !== '' && $scope.senha !== null) {
                //cria o header
                var promise = rest.all('users').getList({'username':$scope.usuario});
                promise.then(function(response){
                    if (response.status === 200) {
                        if (response.data.length === 0) {
                            Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                        } else {
                            $timeout(function() {                                
                                var user = response.data[0];
                                sessionStorage.setItem('user', JSON.stringify(user));
                                var roles = user.rolesAtribuidas;
                                var atribuicoes = [];
                                for (var i=0; i<roles.length; i++)
                                {
                                    if (roles[i].grupo !== undefined) {
                                        var promise = rest.all('permissoes-grupo').getList({'grupo':roles[i].grupo.id});
                                        promise.then(function(response){
                                            for (var j=0; j<response.data.length; j++) { atribuicoes.push(response.data[j]); }
                                            if (atribuicoes.length > 0) {
                                                sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                            } else {
                                                var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                                sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                            }
                                        });
                                    } else {
                                        atribuicoes.push(roles[i]);
                                        if (atribuicoes.length > 0) {
                                            sessionStorage.setItem("roles", JSON.stringify(atribuicoes));
                                        } else {
                                            var noRoles = [{"permissao":{"nomeIdentificacao":"ROLE_USUARIO"}}];
                                            sessionStorage.setItem("roles", JSON.stringify(noRoles));
                                        }
                                    }
                                }
                                //seta avatar
                                var profile = 'profile';
                                if (user.pessoa !== undefined && user.pessoa.genero !== 'M') { profile = 'profileGirl'; }
                                //busca pessoa para criar sessão
                                if (user.pessoa.avatar !== undefined) {
                                    //busca avatar pessoal
                                    var promise = rest.one('assets',user.pessoa.avatar).get();
                                    promise.then(function(response){ 
                                        sessionStorage.setItem('avatar',response.data.file);
                                        sessionStorage.setItem("username", $scope.usuario);
                                        sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa));
                                        sessionStorage.setItem("nome", user.nomeExibicao);
                                        sessionStorage.setItem("pessoaId", user.id);
                                        sessionStorage.setItem("profile", profile);
                                        sessionStorage.setItem("key", $scope.senha);
                                        sessionStorage.setItem("sessionId", md5.createHash(sessionId));
                                        sessionStorage.setItem("menu",0);
                                        sessionStorage.setItem("module","main");
                                        sessionStorage.setItem("moduleOptions","");
                                        sessionStorage.setItem("vinculo","");
                                        sessionStorage.setItem("alocacao", "");
                                        sessionStorage.setItem("disciplina", "");
                                        $timeout(function (){ window.location = 'index.html'; }, 2000);
                                    });
                                } else {
                                    //sem avatar pessoal
                                    sessionStorage.setItem("username", $scope.usuario);
                                    sessionStorage.setItem("nome", user.nomeExibicao);
                                    sessionStorage.setItem("pessoa", JSON.stringify(user.pessoa));
                                    sessionStorage.setItem("pessoaId", user.id);
                                    sessionStorage.setItem("profile", profile);
                                    sessionStorage.setItem("key", $scope.senha);
                                    sessionStorage.setItem("sessionId", md5.createHash(sessionId));
                                    sessionStorage.setItem("menu",0);
                                    sessionStorage.setItem("module","main");
                                    sessionStorage.setItem("moduleOptions","");
                                    sessionStorage.setItem("vinculo","");
                                    sessionStorage.setItem("alocacao", "");
                                    sessionStorage.setItem("disciplina", "");
                                    $timeout(function (){ window.location = 'index.html'; }, 2000);
                                }
                            }, 1000);
                        }
                    }
                }, function(error){
                    if (error.status === 403){
                        Materialize.toast("Verifique se o nome de usuário e senha estão corretos e tente novamente.", 5000);
                    }
                });
            } else {
                Materialize.toast("Preencha os dois campos para efetuar login. :)", 5000);
            }
        };

        //fade-in inicial da página
        $(document).ready(function(){
            setTimeout(function(){ $('.brand-logo').css('opacity',1); }, 100);
            setTimeout(function(){ $('.user').css('opacity',1).css('margin-bottom','5rem'); }, 100);
            setTimeout(function(){ $('.pass').css('opacity',1);}, 300);
            setTimeout(function(){ $('.loginBtn').css('opacity',1).css('bottom',0);}, 900);
            setTimeout(function(){ $('.link1').css('opacity',1);}, 200);
            setTimeout(function(){ $('.link2').css('opacity',1);}, 300);
            setTimeout(function(){ $('.link3').css('opacity',1);}, 400);
            setTimeout(function(){ $('.foto-user').css('opacity',1); }, 700);
            setTimeout(function(){ $('.foto-user').css('transform','scale(1)'); }, 800);
            setTimeout(function(){ $('#usuario').focus(); }, 1);
            $('.login-form').css('opacity',1);            
        });
        
        $scope.openInNewTab = function(url) {
            var win = window.open(url, '_blank');
            win.focus();
        };

        //limpa a sessão toda vez que entra, forçando o login
        sessionStorage.clear();
    }]);
})();

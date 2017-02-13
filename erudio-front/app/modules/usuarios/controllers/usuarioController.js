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
    // DEFINIÇÃO DO MÓDULO
    var usuarioModule = angular.module('usuarioModule', ['servidorModule','usuarioDirectives']);

    //DEFINIÇÃO DO CONTROLADOR
    usuarioModule.controller('UsuarioController', ['$scope', 'Servidor', 'Restangular', '$timeout', 'md5', 'sha1', '$templateCache', function($scope, Servidor, Restangular, $timeout, md5, sha1, $templateCache) {
        $templateCache.removeAll();
        
        $scope.usuarios = []; // ARRAY DE USUARIOS
        $scope.usuarioRemover = null; // VARIAVEL QUE MOSTRA OU ESCONDE O MODAL DE CONFIRMAÇÃO DE EXCLUSÃO
        $scope.strUsuario = ''; // VARIAVEL QUE GUARDA A STRING DE BUSCA.
        $scope.acao = ""; // LABEL DO BOTÃO DE SALVAR/EDITAR.
        $scope.editando = false; // VARIAVEL AUXILIAR PARA VERIFICAR SE PÁGINA É DE LISTA OU DE EDIÇÃO.
        $scope.editandoMobile = false; // IGUAL VARIAVEL EDITANDO
        $scope.loader = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO CIRCULAR
        $scope.progresso = false; // VARIAVEL QUE MOSTRA OU ESCONDE A ANIMAÇÃO DE CARREGAMENTO EM LINHA
        $scope.cortina = false; // VARIAVEL QUE MOSTRA OU ESCONDE A DIV ESCURA TRANSLUCIDA PARA EVITAR ITERAÇÃO COM O USUARIO DURANTE AS CHAMADAS.
        $scope.pagina = 0; // VARIAVEL QUE CONTROLA A PÁGINA ATUAL.
        $scope.habilitaClique = true; // VARIAVEL QUE HABILITA O BOTÃO ENTER PARA ENVIAR O FORM.
        $scope.senha = ''; // VARIAVEL DO FORM DE SENHA.
        $scope.repeteSenha = ''; // VARIÁVEL QUE SERVE PARA VALIDAR A NOVA SENHA.
        $scope.usuario = { nomeExibicao:null, username:null, password:null }; // ESTRUTURA DE USUARIO
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.reiniciar = function (){ $scope.usuario = { nomeExibicao:null, username:null, password:null }; $scope.senha = null; $scope.repeteSenha = null; }; // REINICIANDO ESTRUTURA
        $scope.pessoa = {
            'nome': null, 'dataNascimento': null, 'email': null, 'genero': null,
            'raca': null, 'estadoCivil': null, 'particularidades': [], 'nomePai': null, 'nomeMae': null, 'naturalidade': null,
            'nacionalidade': null, 'numeroRG': null,'dataExpedicaoRg': null, 'orgaoExpedidorRg': null, 'cpfCnpj': null, 'certidaoNascimento': null,
            'dataExpedicaoCertidaoNascimento': null, 'codigoInep': null, 'nis': null, 'pisPasep': null, 'endereco': null, 'inep':null, 'usuario': null
        }; // ESTRUTURA PESSOA
        $scope.grupos = [];
        $scope.isAdmin = Servidor.verificaAdmin();
        $scope.unidade = {id: null};

        /* INICIALIZAR */
        $scope.inicializar = function (inicializaUmaVez) {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){
                if (inicializaUmaVez) {
                    $scope.buscarGruposPermissoes();
                    $('.counter').each(function(){ $(this).characterCounter(); });
                    $('#usuarioForm').keydown(function(event){
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) {
                                $timeout(function(){
                                    if ($scope.habilitaClique) {
                                        $('#salvarUsuario').trigger('click');
                                    } else {
                                        $scope.habilitaClique = true;
                                    }
                                },300);
                            }
                        }
                    });
                    $('.auto-complete').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true,
                        hover: false,
                        gutter: 43,
                        belowOrigin: true,
                        alignment: 'left'
                    });
                }
                $('.tooltipped').tooltip({delay: 30});
            },500);
        };
        
        $scope.selecionarUnidade = function(unidade) {
            $scope.unidade = unidade;
            $scope.unidadeNome = unidade.nomeCompleto;
        };
        
        $scope.buscarGruposPermissoes = function() {
            var promise = Servidor.buscar('grupos', null);
            promise.then(function(response) {
                $scope.grupos = response.data;
            });
        };

        /* BUSCA DE USUARIOS */
        $scope.buscarUsuarios = function() {
            var promise = Servidor.buscar('users',{page: $scope.pagina});
            promise.then(function (response){
                if (response.data.length > 0) {
                    var usuarios = response.data;
                    $scope.usuarios = []; $scope.pessoas = [];
                    //var unidadeAlocacao = sessionStorage.getItem('unidade');
                    $scope.usuarios = usuarios;
                    /*usuarios.forEach(function(u) {
                        var compativel = false;
                        u.rolesAtribuidas.forEach(function(r) {
                            if(!compativel && ((unidadeAlocacao === r.idEntidade) || $scope.isAdmin)) {
                                compativel = true;
                                $scope.usuarios.push(u);
                            }
                        });                        
                    });    */                
                    $scope.usuarios.forEach(function(u) {
                        if (u.pessoa !== undefined) {
                            var promise = Servidor.buscarUm('pessoas', u.pessoa.id);
                            promise.then(function(response) {
                                $scope.pessoas.push(response.data);
                            });
                        }
                    });                        
                    if ($('#search').is(':disabled')) {
                        $('#search').attr('disabled','');
                        $('#search').css('background','');
                        $('#search').attr('placeholder','Digite aqui para buscar');
                    }
                    $('.tooltipped').tooltip('remove');
                    $timeout(function() {
                        $('.tooltipped').tooltip({delay: 50});
                        $('.modal-trigger').leanModal({ dismissible: true, in_duration: 100, out_duration: 100 }); $scope.fechaProgresso();
                        $scope.fechaLoader();
                        Servidor.entradaPagina();
                        $('.tooltipped').tooltip({delay: 30});
                    }, 300);
                } else {
                    if ($scope.usuarios.length === 0) {
                        $('#search').attr('disabled','disabled');
                        $('#search').css('background','#ccc');
                        $('#search').attr('placeholder','');
                    }
                    Materialize.toast('Nenhum usuário encontrado.', 1000);
                    $scope.pagina--; $scope.fechaLoader();
                    Servidor.entradaPagina();
                }
            });
        };

        /* SALVAR USUARIOS */
        $scope.finalizar = function() {
            var entidade = ($scope.isAdmin) ? $scope.unidade.id : sessionStorage.getItem('unidade');
            if ($scope.validar('validate') === true) {                
                $scope.mostraLoader(true);
                var senha = $('#senha').val();
                if (senha !== '') {
                    var pass = md5.createHash(senha);
                    $scope.usuario.password = pass;
                }
                var username = $scope.usuario.username.split('.').join('').split('-').join('');
                var promise = Servidor.buscar('users', {username: username});
                promise.then(function(response) {
                    if (response.data.length) {
                        //Servidor.customToast('Já existe um usuário com este cpf.');
                        var result = Servidor.finalizar($scope.usuario,'users','Usuário');
                        $scope.buscarUsuarios($scope.pagina);
                        $scope.fecharFormulario();
                    } else {
                        $scope.usuario.username = username;
                        var result = Servidor.finalizar($scope.usuario,'users','Usuário');
                        result.then(function(response) {
                            $scope.salvarGrupoPermissoes(response.data);
                            $scope.buscarUsuarios($scope.pagina);
                            $scope.fecharFormulario();
                        });
                    }
                }, function(response) {
                    $scope.fechaLoader();
                    Materialize.toast('Cpf inválido.');
                });
            } else {
                Servidor.customToast('Campos obrigatórios nao preenchidos.');
            }
        };
        
        $scope.salvarGrupoPermissoes = function(usuario) {
            var entidade = ($scope.isAdmin) ? sessionStorage.getItem('unidade') : $scope.unidade.id;
            var promise = Servidor.buscar('atribuicoes-removidas', {usuario: usuario.id, grupo: $scope.grupoId, entidade: entidade});
            promise.then(function(response) {
                var roles = response.data;
                var role;
                if(roles.length) {
                    role = response.data[0];
                    role.ativo = true;
                    Servidor.finalizar(role, 'atribuicoes-removidas', null);
                } else {
                    role = {
                        usuario: {id: usuario.id},
                        grupo: {id: $scope.grupoId},
                        idEntidade: entidade
                    };
                    Servidor.finalizar(role, 'atribuicoes', 'Permissão');                    
                }
            });
        };
        
        $scope.buscarUnidades = function(nome) {
            if(nome.length >= 4) {
                $scope.unidades = [];
                var promise = Servidor.buscar('unidades-ensino', {nome: nome});
                promise.then(function(response) {
                    $scope.unidades = response.data;
                });
            }                
        };

        /* MODAL DE RETORNO */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nomeExibicao || objeto.username && !objeto.id) {
                $('#modal-certeza').openModal();
            } else {
                $scope.fecharFormulario();
            }
        };

        /* VALIDAÇÃO DE FORM */
        $scope.validar = function (id) {
            var result = Servidor.validar(id);
            var senha = $('#senha').val();
            var repete = $('#repeteSenha').val();
            if ((senha !== '' || repete !== '') && (senha !== repete)) {
                Servidor.customToast('As senhas digitadas não coincidem, favor verificar.');
                return false;
            }
            /*if($scope.grupoId === undefined || $scope.grupoId === null) {
                return false;
            }*/
            return result;
        };

        /* CARREGAR USUARIOS */
        $scope.carregar = function (usuario, nova, mobile, index) {
            if (!$scope.isAdmin) {
                var promise = Servidor.buscarUm('unidades-ensino', sessionStorage.getItem('unidade'));
                promise.then(function(response) {
                    $scope.unidade = response.data;
                    $scope.unidadeNome = response.data.nomeCompleto;
                });
            }
            if (!mobile) {
                $scope.mostraLoader(true);
                $scope.reiniciar();              
                if (nova) {
                    $scope.acao = "Cadastrar";
                } else {
                    $scope.acao = "Editar";
                    var promise = Servidor.buscarUm('users', usuario.id);
                    promise.then(function (response) {
                        $scope.usuario = response.data;
                    });
                }
                $scope.grupoId = null;
                $timeout(function(){
                    if (!nova) { $('.opcoesUsuario' + usuario.id).hide(); }
                    if ($scope.editandoMobile) { $(".usuario-banner, .busca").hide(); }
                    if (!nova) { $scope.index = index; }
                    $scope.fechaLoader();
                    Servidor.verificaLabels();
                    $('div').find('.usuario-banner').removeClass('topo-pagina');
                    $scope.editando = true;
                    $('.cpf').mask('000.000.000-09');
                    $('#grupoPermissao').material_select();
                    //$timeout(function(){ $('#nomeInstituicaoFocus').focus(); }, 300);                    
                }, 300);
            } else {
                if (!nova){
                    $('div').find('.usuario-banner').removeClass('topo-pagina');
                    $scope.editandoMobile = true;
                    $('.opcoesUsuario' + usuario.id).show();
                } else {
                    $scope.editandoMobile = true;
                    $scope.carregar(null,true,false);
                }
            }
            $('.auto-complete').dropdown({
                inDuration: 300,
                outDuration: 225,
                constrain_width: true,
                hover: false,
                gutter: 43,
                belowOrigin: true,
                alignment: 'left'
            });
        };

        /* GUARDA USUARIO A SER REMOVIDO */
        $scope.prepararRemover = function (usuario){
            $scope.usuarioRemover = usuario;
            $('.opcoesUsuario' + usuario.id).hide();
        };

        /* REMOVER USUARIO */
        $scope.remover = function (){
            $scope.mostraProgresso();
            var id = $scope.usuarioRemover.id;
            Servidor.remover($scope.usuarioRemover, 'Usuário');
            $scope.usuarios.forEach(function(i, index){
                if(i.id === id){
                    $scope.usuarios.splice(index, 1);
                }
            });
            $scope.fechaProgresso();
        };

        /* FECHAR FORMULARIO */
        $scope.fecharFormulario = function () {
            if ($scope.editandoMobile) { $(".usuario-banner, .busca").show(); $scope.editandoMobile = false; }
            $scope.editando = false;
            $scope.reiniciar();
            Servidor.resetarValidador('validate');
            $scope.acao = "CADASTRAR";
            $('.nav-wrapper').removeClass('ajuste-nav-direita');
//            $('div').find('.usuario-banner').addClass('topo-pagina');
            $timeout(function (){ Servidor.verificaLabels(); },1000);
            $scope.unidadeNome = '';
            $scope.unidade = {id: null};
        };

        /* BUSCA - LISTENER  */
        $scope.$watch("strUsuario", function(query){
            $scope.buscaUsuario(query);
            if(!query) {$scope.icone = 'search'; }
            else { $scope.icone = 'clear'; }
        });

        /* BUSCA */
        $scope.buscaUsuario = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined) { query = ''; }
                var tamanho = query.length;
                if (tamanho > 3) {
                    var res = null;
                    if (!isNaN(query)) {
                        res = Servidor.buscar('users',{'username':query});
                    } else {
                        res = Servidor.buscar('users',{'nomeExibicao':query});
                    }
                    res.then(function(response){
                        $scope.usuarios = response.data;
                        $timeout(function (){ $scope.inicializar(false); $('.collection li').css('opacity',1); });
                    });
                } else {
                    if (tamanho === 0) {
                        $scope.inicializar(false); $scope.buscarUsuarios(true);
                        $('.collection li').css('opacity','');
                    }
                }
            }, 1000);
        };

        /* REINICIAR BUSCA */
        $scope.resetaBusca = function (){ $scope.strUsuario = ''; };

         /* LIMPAR BUSCA */
        $scope.limparBusca = function(){ $scope.strUsuario=''; };

        /* INICIALIZANDO E BUSCANDO */
        $scope.inicializar(true);
        $scope.buscarUsuarios(false);
    }]);
})();

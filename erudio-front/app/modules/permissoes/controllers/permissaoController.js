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
    var permissaoModule = angular.module('permissaoModule', ['servidorModule','permissaoDirectives']);

    //DEFINIÇÃO DO CONTROLADOR
    permissaoModule.controller('PermissaoController', ['$scope', 'Servidor', 'Restangular', '$timeout', 'md5', 'sha1', '$templateCache', function($scope, Servidor, Restangular, $timeout, md5, sha1, $templateCache) {
        $templateCache.removeAll();
        $scope.usuarios = []; // ARRAY DE USUARIOS
        $scope.permissaoRemover = null; // VARIAVEL QUE MOSTRA OU ESCONDE O MODAL DE CONFIRMAÇÃO DE EXCLUSÃO
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
        $scope.atribuicao = { usuario:{id:null}, tipoAcesso:null, permissao:{id:null}, ativo: true, dataExclusao: null, instituicao: {id:null} }; // ESTRUTURA DE ATRIBUIÇÃO
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.reiniciar = function (){ $scope.usuario = { nomeExibicao:null, username:null, password:null }; }; // REINICIANDO ESTRUTURA DE USUARIO
        $scope.reiniciarAtribuicao = function (){ $scope.atribuicao = { usuario:{id:null}, tipoAcesso:null, permissao:{id:null}, ativo: true, dataExclusao: null, instituicao: {id:null} }; }; // REINICIANDO ESTRUTURA DE ATRIBUIÇÃO
        $scope.showError = false; // VARIAVEL QUE MOSTRA A MENSAGEM DE BUSCA SEM RESULTADO
        $scope.roles = []; // ARRAY DE PERMISSOES
        $scope.entidades = []; // ARRAY DE ESCOLAS
        $scope.role = 'PERMISSOES'; //NOME DA PERMISSAO
        $scope.especifico = false; // MOSTRAR FORM DE ROLE ESPECIFICA
        $scope.escolherUnidade = false; // ESCOLHER UNIDADE DE ENSINO
        $scope.permissao = null; // ID DO GRUPO DE PERMISSAO
        $scope.grupoRoles = [];
        $scope.atribuicoes = [];
        $scope.attribs = []; // ATRIBUICOES DO USUARIO
        $scope.attribsIds = []; // ATRIBUICOES REMOVIDAS DO USUARIO
        $scope.entidade = null;
        $scope.btnRemocao = false;

        /* INICIALIZAR */
        $scope.inicializar = function (inicializaUmaVez) {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){
                if (inicializaUmaVez) {
                    $('#permissaoForm').keydown(function(event){
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) {
                                $timeout(function(){ if ($scope.habilitaClique) {  $('#salvarPermissao').trigger('click');  } else {  $scope.habilitaClique = true; } },300);
                            }
                        }
                    });
                    $('.dropdown-input').dropdown({
                        inDuration: 300,
                        outDuration: 225,
                        constrain_width: true,
                        hover: false,
                        gutter: 45,
                        belowOrigin: true,
                        alignment: 'left'
                    });
                }
                $('.tooltipped').tooltip({delay: 30});
                Servidor.entradaPagina();
            },500);
        };

        $scope.buscarGrupos = function () {
            var promise = Servidor.buscar('grupos',null);
            promise.then(function (response) {
                if (response.data.length > 0) {
                    $scope.grupoRoles = response.data;
                }
            });
        };

        $scope.verificaPermissaoPrevia = function () {
            var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: $scope.atribuicao.usuario.id, permissao:$scope.atribuicao.permissao.id, entidade: $scope.atribuicao.instituicao, tipoAcesso: $scope.atribuicao.tipoAcesso });
            promise.then(function (response) {
                if (response.data.length > 0) {
                    var result = response.data[0]; result.ativo = true;
                    Servidor.finalizar(result, 'atribuicoes-removidas', 'Permissão');
                    $timeout(function(){
                        var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                        promise.then(function (response) { $scope.carregar(response.data, false, false, false); $scope.fechaLoader(); });
                    },500);
                } else {
                    var result = Servidor.finalizar($scope.atribuicao,'atribuicoes','Permissão');
                    $timeout(function(){
                        if (result) {
                            var promise = Servidor.buscarUm('users', $scope.usuario.id);
                            promise.then(function (response) {
                                $scope.reiniciarAtribuicao();
                                $scope.carregar(response.data, false, false, false);
                                $scope.fechaLoader();
                            });
                        }
                    },500);
                }
            });
        };

        /* SALVAR PERMISSOES */
        $scope.finalizar = function() {
            $('#remove_all').prop('checked', false);
            $scope.mostraLoader(true);
            if ($scope.especifico) {
                if ($scope.atribuicao.tipoAcesso && $scope.atribuicao.instituicao && $scope.atribuicao.permissao.id) {
                    $scope.verificaPermissaoPrevia();
                } else {
                    $scope.fechaLoader();
                    Servidor.customToast("Campos obrigatórios não preenchidos.");
                }
            } else {
                if ($scope.permissao) {
                    $scope.salvarPermissoes();
                } else {
                    $scope.fechaLoader();
                    Servidor.customToast("Campos obrigatórios não preenchidos.");
                }
            }
        };

        /* PREPARA ATRIBUICAO PARA FILA DE ROLES */
        $scope.preparaAtribuicaoBatch = function (item, value) {
            switch (item) {
                case 'usuario': $scope.atribuicao.usuario.id = value; break;
                case 'acesso': $scope.atribuicao.tipoAcesso = value; break;
                case 'entidade': $scope.atribuicao.instituicao = value; break;
                case 'permissao': $scope.atribuicao.permissao.id = value; break;
                case 'reset': $scope.reiniciarAtribuicao(); break;
                default: return $scope.atribuicao;
            }
        };

        $scope.abrirModalRemoverTodasPermissoes = function() {
            $('#remove-modal-permissao-total').modal();
        };

        /* SALVAR GRUPO DE PERMISSOES */
        $scope.salvarPermissoes = function () {
            if ($scope.verificarPermissao()) { var entidade = $scope.atribuicao.instituicao; $scope.reativarRoles($scope.permissao,entidade); } else { $scope.reativarRoles($scope.permissao,null); }
        };

        /* PEGA LISTA DE PERMISSOES DO GRUPO DE PERMISSAO */
        /*$scope.getPermissoes = function (roleArray) {
            var retorno = { permissoes:null, escrita: null }; var permissoes = []; var escrita = [];
            for (var i=0; i < roleArray.length; i++) {
                var escreve = roleArray[i].escrita;
                var promise = Servidor.buscar('permissoes',{ nomeIdentificacao: "ROLE_" + roleArray[i].nome });
                promise.then(function (response) {
                    if (response.data.length > 0) {  escrita.push(escreve); permissoes.push(response.data[0]); }
                });
            }
            retorno.permissoes = permissoes; retorno.escrita = escrita;
            return retorno;
        };*/

        /* SETA ARRAY DE ATRIBUICOES REMOVIDAS */
        $scope.setAttribsIds = function (item) { $scope.attribsIds.push(item); };

        /* PEGA ATRIBUICOES REMOVIDAS */
        $scope.getRolesExistentesIds = function (permissoes,entidade) {
            var usuario = $scope.atribuicao.usuario.id;
            var roles = permissoes.permissoes;
            for (var i=0; i< roles.length; i++) {
                var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: usuario, permissao: roles[i].id, entidade: entidade });
                promise.then(function (response) { if (response.data.length > 0) { $scope.setAttribsIds( { 'id': response.data[0].id, 'permissao': response.data[0].permissao.id }); } });
            }
        };

        $scope.verificaGrupo = function (grupo){
            var retorno = false;
            if (grupo !== undefined) { retorno = true; }
            return retorno;
        };

        /* REATIVA ROLES E CRIA NOVAS NA FILA */
        $scope.reativarRoles = function (grupo,entity) {
            var usuario = $scope.atribuicao.usuario.id;
            if (entity === null) {
                var promise = Servidor.buscar('instituicoes',{instituicaoPai:'null'});
                promise.then(function(response){ if (response.data.length > 0) { $scope.entidade = response.data[0].id; } });
            } else { $scope.entidade = entity; }
            $timeout(function(){
                if (!$scope.especifico) {
                    var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: usuario, grupo:grupo, entidade: $scope.entidade });
                    promise.then(function (response) {
                        if (response.data.length > 0) {
                            var result = response.data[0]; result.ativo = true;
                            Servidor.finalizar(result, 'atribuicoes-removidas', 'Permissão');
                            $timeout(function(){
                                var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                                promise.then(function (response) { $scope.carregar(response.data, false, false, false); $scope.fechaLoader(); });
                            },500);
                        } else {
                            var atribuicao = { usuario:{id:null}, tipoAcesso:null, grupo:{id:null}, ativo: true, dataExclusao: null, instituicao: {id:null} };
                            atribuicao.usuario.id = usuario; atribuicao.grupo.id = $scope.permissao; atribuicao.instituicao.id = $scope.atribuicao.instituicao.id;
                            var result = Servidor.finalizar(atribuicao,'atribuicoes','Permissão');
                            result.then(function() {
                                $('#tipoAcesso').material_select(); $scope.nomeUnidade = '';
                                var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                                promise.then(function (response) { $scope.carregar(response.data, false, false, false); $scope.fechaLoader(); });
                            });
                        }
                    });
                } else {

                }
            },500);
        };

        /* MODAL DE RETORNO */
        $scope.prepararVoltar = function(objeto) {
            //if (objeto.nomeExibicao || objeto.username && !objeto.id) { $('#modal-certeza').modal(); } else { $scope.fecharFormulario(); }
            $scope.fecharFormulario();
        };

        /*$scope.buscarPermissoes = function (){
            var promise = Servidor.buscar('permissoes-grupo',{grupo: $scope.permissao});
            promise.then(function(response){
                $scope.atribuicoes = response.data;
            });
        };*/

        /* VER QUAL GRUPO DE PERMISSAO ESTA SELECIONADO */
        $scope.verificarPermissao = function () {
            if (parseInt($scope.permissao) === 8) { $scope.escolherUnidade = false; return false; } else { $scope.escolherUnidade = true; return true; }
        };

        /* VALIDAÇÃO DE FORM */
        $scope.validar = function (id) {
            if ($scope.especifico) {
                var result = Servidor.validar(id); return result;
            } else {
                var result = $scope.verificarPermissao();
                if (result) {
                    if ($scope.permissao !== null || $scope.atribuicao.instituicao !== null) { return true; } else { return false; }
                } else {
                    if ($scope.permissao !== null) { return true; } else { return false; }
                }
            }
        };

        // BUSCA DE PERMISSOES
        $scope.buscarRoles = function() {
            var promise = Servidor.buscar('permissoes',{});
            promise.then(function (response) { $scope.roles = response.data; });
        };

        /* BUSCA DE UNIDADES E INSTITUICOES */
        $scope.buscarEntidades = function (){
            var instituicoes = [];
            var unidades = [];
            var promise = Servidor.buscar('instituicoes',{});
            promise.then(function (response) { 
                instituicoes = response.data;
                for (var i=0; i<instituicoes.length; i++) { 
                    instituicoes[i].instituicaoPai = true; $scope.entidades.push(instituicoes[i]);
                    if (i === instituicoes.length-1) {
                        var promise = Servidor.buscar('unidades-ensino',{});
                        promise.then(function (response) { 
                            unidades = response.data;
                            for (var i=0; i<unidades.length; i++) { $scope.entidades.push(unidades[i]); }
                        });
                    }
                }
            });
        };

        /*CARREGAR TIPO DE ROLE*/
        $scope.carregarTipoRole = function (tipoRole) {
            if (tipoRole === "E") { return "Escrita"; } else { return "Leitura"; }
        };

        /*CARREGAR NOME DE ENTIDADE*/
        $scope.nomeEntidade = function (entidadeId, id) {
            var unidade = Servidor.buscarUm('unidades-ensino',entidadeId);
            $timeout(function(){
                unidade.then(function(response){
                    var result = response.data;
                    $scope.usuario.rolesAtribuidas[id].instituicao = result.nomeCompleto;
                },function (error){
                    var instituicao = Servidor.buscarUm('instituicoes',entidadeId);
                    $timeout(function(){
                        instituicao.then(function(response2){
                            var resultado = response2.data;
                            $scope.usuario.rolesAtribuidas[id].instituicao = resultado.nome;
                        }, function (err){
                            $scope.usuario.rolesAtribuidas[id].instituicao = 'Entidade não encontrada.';
                        });
                    }, 200);
                });
            }, 200);
        };

        /* CARREGAR USUARIOS */
        $scope.carregar = function (usuario, nova, mobile, carregar) {
            $scope.especifico = false;
            $scope.btnRemocao = false;
            if (!mobile) {
                $scope.mostraLoader(true); $scope.reiniciar();
                $scope.acao = "Adicionar"; $scope.usuario = usuario;
                $scope.atribuicao.usuario = usuario;
                $scope.buscarEntidades();
                $scope.buscarRoles();
                $timeout(function(){
                    //for (var i=0; i<$scope.usuario.rolesAtribuidas.length; i++) { $scope.nomeEntidade($scope.usuario.rolesAtribuidas[i].instituicao, i); }
                    if (!nova) { $('.opcoesUsuario' + usuario.id).hide(); }
                    if ($scope.editandoMobile) { $(".usuario-banner, .busca").hide(); }
                    $scope.fechaLoader();
                    Servidor.verificaLabels();
                    $('#tipoEntidade, #tipoAcesso, #tipoPermissao').material_select('destroy');
                    $('#tipoEntidade, #tipoAcesso, #tipoPermissao').material_select();
                    $('div').find('.usuario-banner').removeClass('topo-pagina');
                    $('.tooltipped').tooltip('remove');
                    $('.tooltipped').tooltip({delay: 50});
                    $scope.editando = true;
                }, 1000);
            } else {
                if (!nova){
                    $('div').find('.usuario-banner').removeClass('topo-pagina'); $scope.editandoMobile = true; $('.opcoesUsuario' + usuario.id).show();
                } else {
                    $scope.editandoMobile = true; $scope.carregar(null,true,false,false);
                }
            }
        };

        /* GUARDA PERMISSAO A SER REMOVIDO */
        $scope.prepararRemover = function (atribuicao){
            var attr = Servidor.buscarUm('atribuicoes',atribuicao.id);
            attr.then(function(response){ var result = response.data; $scope.preRemover(result); });
            $('.opcoesUsuario' + atribuicao.id).hide();
        };

        //GUARDA OBJETO PERMISSAO PARA SER REMOVIDO
        $scope.preRemover = function (permissao){ $scope.permissaoRemover = permissao; $("#remove-modal-permissao").modal(); };

        /* REMOVER PERMISSAO */
        $scope.remover = function (){
            $scope.mostraProgresso(); var id = $scope.permissaoRemover.id;
            Servidor.remover($scope.permissaoRemover, 'Permissão');
            $timeout(function(){
                var promise = Servidor.buscarUm('users', $scope.usuario.id);
                promise.then(function (response) {
                    $scope.reiniciarAtribuicao(); $scope.carregar(response.data, false, false, false); $scope.fechaLoader();
                });
            },500);
            $scope.fechaProgresso();
        };

        /* REMOVER PERMISSOES */
        $scope.removerPermissoes = function (){
            $scope.mostraProgresso();
            var promise = Servidor.buscar('atribuicoes', { 'usuario': $scope.atribuicao.usuario.id });
            promise.then(function (response) {
                var atribuicoes = response.data;
                if (atribuicoes.length > 0) {
                    for (var i=0; i<atribuicoes.length; i++) {
                        if (atribuicoes.length-1 === i) {
                            Servidor.remover(atribuicoes[i], 'Permissão');
                            $timeout(function(){
                                var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                                promise.then(function (response) {  $scope.reiniciarAtribuicao(); $scope.carregar(response.data, false, false, false); });
                            },500);
                        } else {
                            Servidor.remover(atribuicoes[i], null);
                        }
                    }
                }
            });
            $scope.fechaProgresso();
        };

        /* FECHAR FORMULARIO */
        $scope.fecharFormulario = function () {
            $('.tooltipped').tooltip('remove');
            $('.tooltipped').tooltip({delay: 50});
            if ($scope.editandoMobile) { $(".usuario-banner, .busca").show(); $scope.editandoMobile = false; }
            $scope.editando = false; $scope.reiniciar();
            Servidor.resetarValidador('validate'); $scope.acao = "CADASTRAR";
            $scope.reiniciarAtribuicao(); $('.nav-wrapper').removeClass('ajuste-nav-direita');
            $timeout(function (){ Servidor.verificaLabels(); },1000);
        };

        /* BUSCA - LISTENER  */
        $scope.$watch("strUsuario", function(query){
            $scope.buscaUsuario(query);
            if(!query) {$scope.icone = 'search'; }
            else { $scope.icone = 'clear'; }
        });

        $scope.selecionarUnidade = function(unidade) {
            $scope.atribuicao.instituicao.id = unidade.id;
            $scope.nomeUnidade = unidade.nomeCompleto;
        };

        $scope.buscarUnidades = function(nomeUnidade) {
            if (nomeUnidade === undefined || nomeUnidade.length < 4) {
                return null;
            }
            var promise = Servidor.buscar('unidades-ensino', {nome: nomeUnidade});
            promise.then(function(response) {
                $scope.unidades = response.data;
            });
        };

        /* BUSCA */
        $scope.buscaUsuario = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined) { query = ''; }
                var tamanho = query.length;
                if (tamanho > 3) {
                    var res = null;
                    if (!isNaN(query)) { res = Servidor.buscar('users',{'username':query}); } else { res = Servidor.buscar('users',{'nomeExibicao':query}); }
                    res.then(function(response){
                        $scope.usuarios = response.data; $scope.verificaResultado();
                        $timeout(function (){ $scope.inicializar(false); $('.collection li').css('opacity',1); });
                    });
                } else {
                    if (tamanho === 0) {
                        $scope.inicializar(false); $scope.usuarios = []; $scope.showError = false;
                        $('.collection li').css('opacity','');
                    }
                }
            }, 1000);
        };

        /* VERIFICA RESULTADO DA BUSCA */
        $scope.verificaResultado = function () {
            if ($scope.strUsuario.length > 0 && $scope.usuarios > 0) { $scope.showError = false; } else { $scope.showError = true; }
        };

        /* REINICIAR BUSCA */
        $scope.resetaBusca = function (){ $scope.strUsuario = ''; $scope.showError = false; };

         /* LIMPAR BUSCA */
        $scope.limparBusca = function(){ $scope.strUsuario=''; $scope.showError = false; };

        /* VERIFICANDO PERMISSAO DE ESCRITA */
        $scope.verificarEscrita = function () {
            var result = Servidor.verificaEscrita($scope.role);
            if (result) { return ''; } else { return 'disabled'; }
        };

        $scope.selecionarRemocao = function(id) {
            var todos = $('#remove_all');
            if(id) {
                var checados = $('.check-permissao').filter(':checked').length;
                $scope.btnRemocao = (checados > 0) ? true : false;
                if(checados === $scope.usuario.rolesAtribuidas.length) {
                    todos.prop('checked', true);
                } else {
                    todos.prop('checked', false);
                }
            } else {
                $('.filled-in').prop('checked', todos.prop('checked'));
                $scope.btnRemocao = todos.prop('checked');
            }
        };

        $scope.verificarRemocaoPermissoes = function() {
            if($('#remove_all').prop('checked')) {
                $('#remove-modal-permissao-total').modal();
            } else {
                $scope.removerPermissoes();
            }
        };

        $scope.removerPermissoes = function() {
            $scope.mostraProgresso();
            var survivors = [];
            var requests = 0;
            $('.check-permissao').each(function(index) {
                var role = $scope.usuario.rolesAtribuidas[index];
                if($(this).prop('checked')) {
                    requests++;
                    var promise = Servidor.buscarUm('atribuicoes', role.id);
                    promise.then(function(response) {
                        role = response.data;
                        Servidor.remover(role, 'Atribuição');
                        if(--requests === 0) {
                            if(survivors.length) {
                                $scope.btnRemocao = (survivors.length > 0) ? true : false;
                            }
                            $scope.usuario.rolesAtribuidas = survivors;
                            $('#remove_all').prop('checked', false);
                            $scope.fechaProgresso();
                        }
                    });
                } else {
                    survivors.push(role);
                }
            });
        };

        /* INICIALIZANDO E BUSCANDO */
        $scope.inicializar(true);
        $scope.buscarGrupos();
    }]);
})();
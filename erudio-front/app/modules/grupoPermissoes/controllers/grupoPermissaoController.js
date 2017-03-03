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
    var grupoPermissaoModule = angular.module('grupoPermissaoModule', ['servidorModule','grupoPermissaoDirectives']);
    
    //DEFINIÇÃO DO CONTROLADOR
    grupoPermissaoModule.controller('GrupoPermissaoController', ['$scope', 'Servidor', 'Restangular', '$timeout', 'md5', 'sha1', '$templateCache', function($scope, Servidor, Restangular, $timeout, md5, sha1, $templateCache) {
        $templateCache.removeAll();
        
        $scope.escrita = Servidor.verificaEscrita('GRUPO');
        $scope.isAdmin = Servidor.verificaAdmin();
        
        /* INICIALIZAR */
        $scope.inicializar = function (inicializaUmaVez) {
            $('.tooltipped').tooltip('remove');
            $timeout(function (){      
                /*if (inicializaUmaVez) {                     
                    $('#permissaoForm').keydown(function(event){ 
                        if ($scope.editando) {
                            var keyCode = (event.keyCode ? event.keyCode : event.which);
                            if (keyCode === 13) {
                                $timeout(function(){ if ($scope.habilitaClique) {  $('#salvarPermissao').trigger('click');  } else {  $scope.habilitaClique = true; } },300);
                            }
                        }
                    });
                }*/                      
                $('.tooltipped').tooltip({delay: 30});
                Servidor.entradaPagina();
            },500);
        };
        
        $scope.grupos = [];
        $scope.grupo = {nome:null};
        $scope.pagina = 0;
        $scope.grupoRemover = null;
        $scope.strGrupo = null;
        $scope.roles = [];
        $scope.entidades = [];
        $scope.atribuicao = { tipoAcesso:null, grupo:{id:null}, permissao:{id:null} };
        $scope.atribuicoes = [];
        
        $scope.reiniciar = function () { $scope.grupo = {nome:null}; };
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; };
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; };
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } };
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; };
        
        $scope.buscarGrupos = function () {
            var promise = Servidor.buscar('grupos', {page: $scope.pagina});
            promise.then(function (response) {
                if (response.data.length > 0) {
                    $scope.grupos = response.data;
                    $scope.grupos.forEach(function(g) {
                        g.peso = parseInt(g.peso);
                    });
                    $('.tooltipped').tooltip('remove');
                    $timeout(function() { $('.tooltipped').tooltip({delay: 50}); }, 250);
                } else {
                    if ($scope.grupos.length === 0) { $('.card-busca').hide(); Materialize.toast('Nenhum grupo encontrado.', 1000); }
                    $scope.pagina--;
                }
            });
        };

        $scope.carregar = function (grupo, nova) {
            // Se peso minimo for 0 então o usuário não possui nenhum grupo
            if($scope.pesoMinimo > 0) {
                $scope.mostraLoader(true);
                $('#tipo, #tipoPermissao').material_select('destroy');
                $scope.reiniciar();
                if (!nova) {
                    $scope.acao = "Editar";
                    $scope.grupo = grupo;
                    $scope.buscarRoles();
                    $scope.buscarAtributos();
                } else {
                    $scope.acao = 'Adicionar';
                }
                $timeout(function () {
                    $('#tipo, #tipoPermissao').material_select();
                    $scope.fechaLoader();
                    Servidor.verificaLabels();
                    $scope.editando = true;
                    $('.wrapper-padding').css('padding',0);
                    //$('.top-cards').css('margin-top','-8px');
                }, 100);
            } else {
                Servidor.customToast('Você deve possuir um grupo de permissões para poder criar outros.');
            }
        };
        
        /* FECHAR FORMULARIO */
        $scope.fecharFormulario = function () {
            $scope.editando = false; $scope.reiniciar();
            Servidor.resetarValidador('validate'); $scope.acao = "CADASTRAR";
            $('.nav-wrapper').removeClass('ajuste-nav-direita');
            $('.wrapper-padding').css('padding','');
            //$('.top-cards').css('margin-top','-64px');
            $timeout(function (){ Servidor.verificaLabels(); },1000);
        };
        
        /* GUARDA GRUPO A SER REMOVIDO */
        $scope.prepararRemover = function (grupo){ 
            var attr = Servidor.buscarUm('grupos',grupo.id);
            attr.then(function(response){ var result = response.data; $scope.preRemover(result); });
            $('.opcoesUsuario' + grupo.id).hide();            
        };
        
        //GUARDA OBJETO PARA SER REMOVIDO
        $scope.preRemover = function (grupo){ $scope.grupoRemover = grupo; $("#remove-modal-grupo").openModal(); };
        
        //GUARDA OBJETO PERMISSAO PARA SER REMOVIDO
        $scope.prepararRemoverPermissao = function (perm){ $scope.permRemover = perm; $("#remove-modal-permissao-grupo").openModal(); };
        
        /* REMOVER GRUPO */
        $scope.remover = function (){
            $scope.mostraProgresso(); var id = $scope.grupoRemover.id;
            Servidor.remover($scope.grupoRemover, 'Grupo');
            $timeout(function(){
                $scope.fecharFormulario();
                $scope.buscarGrupos();
            },500);
            $scope.fechaProgresso();
        };
        
        /* REMOVER PERMISSAO */
        $scope.removerPermissao = function (){
            $scope.mostraProgresso();
            Servidor.remover($scope.permRemover, 'Permissão');
            $timeout(function(){
                var promise = Servidor.buscar('permissoes-grupo', $scope.grupo.id);
                promise.then(function (response) { 
                    $scope.buscarAtributos();
                });
            },500);
            $scope.fechaProgresso();
        };

        /* MODAL DE RETORNO */
        $scope.prepararVoltar = function(objeto) {
            if (objeto.nome && !objeto.id) { $('#modal-certeza').openModal(); } else { $scope.fecharFormulario(); }
        };

        /* BUSCA - LISTENER  */
        $scope.$watch("strGrupo", function(query){
            $scope.buscaGrupo(query);
            if(!query) {$scope.icone = 'search'; } else { $scope.icone = 'clear'; }
        });
        
        /* BUSCA */
        $scope.buscaGrupo = function (query) {
            $timeout.cancel($scope.delayBusca);
            $scope.delayBusca = $timeout(function(){
                if (query === undefined || query === null) { query = ''; }
                var tamanho = query.length;
                if (tamanho > 3) {
                    var res = null;
                    res = Servidor.buscar('grupos',{'nome':query});
                    res.then(function(response){
                        $scope.grupos = response.data; $scope.verificaResultado();
                        $timeout(function (){ $scope.inicializar(false); $('.collection li').css('opacity',1); });
                    });
                } else {
                    if (tamanho === 0) {
                        $scope.inicializar(false);
                        $scope.buscarGrupos();
                    }
                }
            }, 1000);
        };
        
        /* VERIFICA RESULTADO DA BUSCA */
        $scope.verificaResultado = function () {
            if ($scope.strGrupo.length > 0 && $scope.grupos > 0) { $scope.showError = false; } else { $scope.showError = true; }
        };
        
        /* REINICIAR BUSCA */
        $scope.resetaBusca = function (){ $scope.strUsuario = ''; $scope.showError = false; };
        
         /* LIMPAR BUSCA */
        $scope.limparBusca = function(){ $scope.strUsuario=''; $scope.showError = false; };
        
        /* VALIDAÇÃO DE FORM */
        $scope.validar = function (id) {
            var result = Servidor.validar(id); return result;
        };
        
        /* SALVAR PERMISSOES */
        $scope.finalizar = function() {
            if($scope.grupo.peso === undefined) {                
                return Servidor.customToast('Peso inválido.');
            }
            var validador = $scope.validar('validate');
            if (validador) {
                $scope.mostraLoader(true);
                var result = Servidor.finalizar($scope.grupo,'grupos','Grupo');
                $timeout(function(){
                    if (result) {
                        $scope.buscarGrupos();
                        var promise = Servidor.buscarUm('grupos', result.$object.id);
                        promise.then(function (response) { 
                            $scope.carregar(response.data, false);
                            $scope.fechaLoader();
                        });
                    }
                },500);
            }            
        };
        
        $scope.incluirPermissao = function() {
            $scope.mostraLoader(true);  
            $scope.atribuicao.grupo.id = $scope.grupo.id;
            var result = Servidor.finalizar($scope.atribuicao,'permissoes-grupo','Permissão');
            $timeout(function(){
                if (result) { 
                    var promise = Servidor.buscar('permissoes-grupo',{'grupo': $scope.grupo.id});
                    promise.then(function (response) { 
                        $scope.atribuicoes = response.data;
                        $scope.fechaLoader();
                    });
                }
            },500);
        };
        
        // BUSCA DE PERMISSOES
        $scope.buscarRoles = function() {
            var promise = Servidor.buscar('permissoes',{});
            promise.then(function (response) { $scope.roles = response.data; });
        };
        
        // BUSCA DE PERMISSOES
        $scope.buscarAtributos = function() {
            var promise = Servidor.buscar('permissoes-grupo',{'grupo': $scope.grupo.id});
            promise.then(function (response) { $scope.atribuicoes = response.data; });
        };
        
        /*CARREGAR TIPO DE ROLE*/
        $scope.carregarTipoRole = function (tipoRole) {
            if (tipoRole === "E") { return "Escrita"; } else { return "Leitura"; }
        };
                
        $scope.buscarAtribuicoes = function() {
            var usuario = JSON.parse(sessionStorage.getItem('user'));
            var unidade = sessionStorage.getItem('unidade');
            var promise = Servidor.buscar('atribuicoes', {usuario: usuario.id, entidade: unidade});
            promise.then(function(response) {
                var atribuicoes = response.data;
                var maiorPeso = 0;
                atribuicoes.forEach(function(at) {
                    if(at.grupo !== undefined && parseInt(at.grupo.peso) > maiorPeso) {
                        maiorPeso = parseInt(at.grupo.peso);
                    }
                });
                $scope.pesoMinimo = maiorPeso;
            });
        };
        $scope.buscarAtribuicoes();
        
        /*$scope.usuarios = []; // ARRAY DE USUARIOS
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
        $scope.atribuicao = { usuario:{id:null}, tipoAcesso:null, idEntidade:null, permissao:{id:null}, ativo: true, dataExclusao: null }; // ESTRUTURA DE ATRIBUIÇÃO
        $scope.mostraProgresso = function () { $scope.progresso = true; $scope.cortina = true; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.fechaProgresso = function () { $scope.progresso = false; $scope.cortina = false; }; // CONTROLE DA BARRA DE PROGRESSO
        $scope.mostraLoader = function (cortina) { $scope.loader = true; if (cortina) { $scope.cortina = true; } }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.fechaLoader = function () { $scope.loader = false; $scope.cortina = false; }; // CONTROLE DO PROGRESSO CIRCULAR
        $scope.reiniciar = function (){ $scope.usuario = { nomeExibicao:null, username:null, password:null }; }; // REINICIANDO ESTRUTURA DE USUARIO
        $scope.reiniciarAtribuicao = function (){ $scope.atribuicao = { usuario:{id:null}, tipoAcesso:null, idEntidade:null, permissao:{id:null}, ativo: true, dataExclusao: null }; }; // REINICIANDO ESTRUTURA DE ATRIBUIÇÃO
        $scope.showError = false; // VARIAVEL QUE MOSTRA A MENSAGEM DE BUSCA SEM RESULTADO
        $scope.roles = []; // ARRAY DE PERMISSOES
        $scope.entidades = []; // ARRAY DE ESCOLAS
        $scope.role = 'PERMISSOES'; //NOME DA PERMISSAO
        $scope.especifico = false; // MOSTRAR FORM DE ROLE ESPECIFICA
        $scope.escolherUnidade = false; // ESCOLHER UNIDADE DE ENSINO
        $scope.permissao = null; // ID DO GRUPO DE PERMISSAO
        $scope.grupoRoles = ['Super Administrador', 'Administrador', 'Diretor', 'Administrador Escolar', 'Orientador', 'Supervisor', 'Professor', 'Secretário', 'Aluno']; // TIPOS DE GRUPO DE PERMISSAO
        $scope.superAdmin = [{nome:'SUPER_ADMIN',escrita:true}]; // ROLES DE SUPER ADMIN
        $scope.roleAdmin = [
            {nome:'INSTITUICAO',escrita:true},{nome:'TIPO_UNIDADE',escrita:true},{nome:'UNIDADE_ENSINO',escrita:true},{nome:'MODULO',escrita:true},
            {nome:'CURSO',escrita:true},{nome:'ETAPA',escrita:true},{nome:'DISCIPLINA',escrita:true},{nome:'REGIME_ENSINO',escrita:true},{nome:'TURNO',escrita:true},
            {nome:'CARGO',escrita:true},{nome:'HABILIDADES',escrita:true},{nome:'CALENDARIO',escrita:true},{nome:'MODELO_QUADRO',escrita:true},{nome:'EVENTOS',escrita:true},
            {nome:'TIPOS_AVALIACAO',escrita:true}]; // ROLES DE ADMIN
        $scope.roleDiretor = [{nome:'CALENDARIO',escrita:false},{nome:'QUADRO_HORARIO',escrita:false},{nome:'TURMA',escrita:false}]; // ROLES DE DIRETOR
        $scope.roleSecretaria = [
            {nome:'CALENDARIO',escrita:true}, {nome:'QUADRO_HORARIO',escrita:true}, {nome:'TURMA',escrita:true}, {nome:'MATRICULA',escrita:true}, {nome:'HISTORICO_MOVIMENTACAO', escrita: true}, {nome:'MOVIMENTACAO', escrita: true}, {nome:'PESSOA', escrita: true},
            {nome:'FUNCIONARIO', escrita: true}, {nome:'AVALIACAO', escrita: false}, {nome:'HABILIDADES', escrita: true}, {nome:'ALUNOS_DEFASADOS', escrita: true},
            {nome:'BOLETIM_ESCOLAR', escrita: true}, {nome:'DIARIO_FREQUENCIA', escrita: true}, {nome:'DIARIO_NOTAS', escrita: true}, {nome:'SOLICITAR_VAGA', escrita: true}, {nome:'REGISTRO_MATRICULA', escrita: true}, {nome:'ESPELHO_NOTA', escrita: true}
        ]; //ROLES DE SECRETARIA
        $scope.roleOrientador = [{nome:'CALENDARIO',escrita:false},{nome:'QUADRO_HORARIO',escrita:false},{nome:'TURMA',escrita:false}]; // ROLES DE ORIENTADOR
        $scope.roleSupervisor = [{nome:'CALENDARIO',escrita:false},{nome:'QUADRO_HORARIO',escrita:false},{nome:'TURMA',escrita:false}]; // ROLES DE SUPERVISOR
        $scope.roleProfessor = [{nome:'TURMA',escrita:false},{nome:'CALENDARIO',escrita:false},{nome:'AVALIACAO',escrita:false},{nome:'HOME_PROFESSOR',escrita:true}]; // ROLES DE PROFESSOR
        $scope.roleAdministrador = [{nome:'CALENDARIO',escrita:false},{nome:'QUADRO_HORARIO',escrita:false},{nome:'TURMA',escrita:false}]; // ROLES DE ADMINISTRADOR ESCOLAR
        $scope.roleAluno = []; // ROLES DE ALUNO
        $scope.attribs = []; // ATRIBUICOES DO USUARIO
        $scope.attribsIds = []; // ATRIBUICOES REMOVIDAS DO USUARIO

        
        
        /* PREPARA ATRIBUICAO PARA FILA DE ROLES */
        /*$scope.preparaAtribuicaoBatch = function (item, value) {
            switch (item) {
                case 'usuario': $scope.atribuicao.usuario.id = value; break;
                case 'acesso': $scope.atribuicao.tipoAcesso = value; break;
                case 'entidade': $scope.atribuicao.idEntidade = value; break;
                case 'permissao': $scope.atribuicao.permissao.id = value; break;
                case 'reset': $scope.reiniciarAtribuicao(); break;
                default: return $scope.atribuicao;
            }
        };
        
        $scope.abrirModalRemoverTodasPermissoes = function() {
            $('#remove-modal-permissao-total').openModal();
        };
        
        /* SALVAR GRUPO DE PERMISSOES */
        /*$scope.salvarPermissoes = function () {
            if ($scope.verificarPermissao()) {
                var entidade = $scope.atribuicao.idEntidade;
                switch (parseInt($scope.permissao)) {
                    case 2: $scope.reativarRoles($scope.roleDiretor,entidade); break;
                    case 3: $scope.reativarRoles($scope.roleAdministrador,entidade); break;
                    case 4: $scope.reativarRoles($scope.roleOrientador,entidade); break;
                    case 5: $scope.reativarRoles($scope.roleSupervisor,entidade); break;
                    case 6: $scope.reativarRoles($scope.roleProfessor,entidade); break;
                    case 7: $scope.reativarRoles($scope.roleSecretaria,entidade); break;
                    case 8: $scope.reativarRoles($scope.roleAluno,entidade); break;
                    default: break;
                }
            } else {
                if (parseInt($scope.permissao) === 0) { $scope.reativarRoles($scope.superAdmin,null); } else { $scope.reativarRoles($scope.roleAdmin,null); }
            }
        };
        
        /* PEGA LISTA DE PERMISSOES DO GRUPO DE PERMISSAO */
       /* $scope.getPermissoes = function (roleArray) {
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
        };
        
        /* SETA ARRAY DE ATRIBUICOES REMOVIDAS */
        /*$scope.setAttribsIds = function (item) { $scope.attribsIds.push(item); };
        
        /* PEGA ATRIBUICOES REMOVIDAS */
        /*$scope.getRolesExistentesIds = function (permissoes,entidade) {
            var usuario = $scope.atribuicao.usuario.id;
            var roles = permissoes.permissoes;
            for (var i=0; i< roles.length; i++) {
                var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: usuario, permissao: roles[i].id, entidade: entidade });
                promise.then(function (response) { if (response.data.length > 0) { $scope.setAttribsIds( { 'id': response.data[0].id, 'permissao': response.data[0].permissao.id }); } });
            }
        };
        
        /* REATIVA ROLES E CRIA NOVAS NA FILA */
        /*$scope.reativarRoles = function (roleArray,entity) {
            var array = $scope.getPermissoes(roleArray); var permissoes = array.permissoes;
            var escrita = array.escrita; var usuario = $scope.atribuicao.usuario.id; var entidade = null;
            if (entity === null) { 
                for (var k=0; k<$scope.entidades.length; k++) { 
                    if ($scope.entidades[k].instituicaoPai === undefined) { 
                        entidade = $scope.entidades[k].id; 
                    } 
                } 
            } else { entidade = entity; }
            $timeout(function(){
                $scope.getRolesExistentesIds(array,entidade);
                $timeout(function(){
                    if ($scope.attribsIds.length > 0) {
                        for (var i=0; i< permissoes.length; i++) {
                            var permissaoId = permissoes[i].id;
                            for (var k=0; k<$scope.attribsIds.length; k++) {
                                if (permissaoId === $scope.attribsIds[k].permissao) {
                                    if (permissoes.length-1 === i) {
                                        var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: usuario, permissao: permissaoId, entidade: entidade });
                                        promise.then(function (response) { if (response.data.length > 0) { 
                                            var result = response.data[0]; result.ativo = true;
                                            Servidor.finalizar(result, 'atribuicoes-removidas', 'Permissão');
                                            $timeout(function(){
                                                var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                                                promise.then(function (response) { $scope.carregar(response.data, false, false, false); $scope.fechaLoader(); });
                                            },500);
                                        } });
                                    } else {
                                        var promise = Servidor.buscar('atribuicoes-removidas',{ usuario: usuario, permissao: permissaoId, entidade: entidade });
                                        promise.then(function (response) { if (response.data.length > 0) { 
                                            var result = response.data[0]; result.ativo = true;
                                            Servidor.finalizar(result, 'atribuicoes-removidas', null);
                                        } });
                                    }                                  
                                }
                            }
                        }
                    } else {
                        for (var i=0; i<permissoes.length; i++) {
                            var tipoAcesso = 'L';
                            var atribuicao = { usuario:{id:null}, tipoAcesso:null, idEntidade:null, permissao:{id:null}, ativo: true, dataExclusao: null };
                            if (escrita[i]) { tipoAcesso = 'E'; }
                            atribuicao.usuario.id = usuario; atribuicao.idEntidade = entidade; atribuicao.tipoAcesso = tipoAcesso;
                            atribuicao.permissao.id = permissoes[i].id;
                            if (permissoes.length-1 === i) {
                                var result = Servidor.finalizar(atribuicao,'atribuicoes','Permissão');
                                $timeout(function(){
                                    if (result) { 
                                        var promise = Servidor.buscarUm('users', $scope.atribuicao.usuario.id);
                                        promise.then(function (response) { $scope.carregar(response.data, false, false, false); $scope.fechaLoader(); });
                                    }
                                },500);
                            } else {
                                Servidor.finalizar(atribuicao,'atribuicoes',null);
                            }
                        }
                    }
                }, 500);
            }, 500);
        };
        
        /* VER QUAL GRUPO DE PERMISSAO ESTA SELECIONADO */
        /*$scope.verificarPermissao = function () {
            if ($scope.permissao >= 2) { $scope.escolherUnidade = true; return true; } else { $scope.escolherUnidade = false; return false; }
        };
        
        /* VALIDAÇÃO DE FORM */
        /*$scope.validar = function (id) {
            if ($scope.especifico) {
                var result = Servidor.validar(id); return result;
            } else {
                var result = $scope.verificarPermissao();
                if (result) {
                    if ($scope.permissao !== null || $scope.atribuicao.idEntidade !== null) { return true; } else { return false; }
                } else {
                    if ($scope.permissao !== null) { return true; } else { return false; }
                }
            }
        }; 
        
        
        
        /* CARREGAR USUARIOS */
        /*$scope.carregar = function (usuario, nova, mobile, carregar) { 
            $scope.especifico = false;
            if (!mobile) {
                $scope.mostraLoader(true); $scope.reiniciar();
                $scope.acao = "Adicionar"; $scope.usuario = usuario;
                $scope.atribuicao.usuario.id = usuario.id;
                $scope.buscarEntidades();
                $timeout(function(){
                    for (var i=0; i<$scope.usuario.rolesAtribuidas.length; i++) { $scope.nomeEntidade($scope.usuario.rolesAtribuidas[i].idEntidade, i); }
                    if (!nova) { $('.opcoesUsuario' + usuario.id).hide(); }
                    if ($scope.editandoMobile) { $(".usuario-banner, .busca").hide(); }
                    $scope.fechaLoader();
                    Servidor.verificaLabels();
                    $('#tipoEntidade, #tipoAcesso, #tipoPermissao').material_select('destroy');
                    $('#tipoEntidade, #tipoAcesso, #tipoPermissao').material_select();
                    $('div').find('.usuario-banner').removeClass('topo-pagina');
                    $('.tooltipped').tooltip('remove');
                    if (carregar) {
                        $('.tooltipped').tooltip({delay: 30});
                    }
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
        
        
        
        
        
        /* REMOVER PERMISSOES */
        /*$scope.removerPermissoes = function (){
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
        
        /* VERIFICANDO PERMISSAO DE ESCRITA */
        /*$scope.verificarEscrita = function () { 
            var result = Servidor.verificaEscrita($scope.role);
            if (result) { return ''; } else { return 'disabled'; }
        };
        
        /* INICIALIZANDO E BUSCANDO */
        $scope.inicializar(true);
        $scope.buscarGrupos();
    }]);
})();
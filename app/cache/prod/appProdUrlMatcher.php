<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        if (0 === strpos($pathinfo, '/log')) {
            if (0 === strpos($pathinfo, '/login')) {
                // login
                if ($pathinfo === '/login') {
                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::loginAction',  '_route' => 'login',);
                }

                // login_check
                if ($pathinfo === '/login_check') {
                    return array('_route' => 'login_check');
                }

            }

            // logout
            if ($pathinfo === '/logout') {
                return array('_route' => 'logout');
            }

        }

        // intranet_index
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'intranet_index');
            }

            return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::indexAction',  '_route' => 'intranet_index',);
        }

        if (0 === strpos($pathinfo, '/cadastro')) {
            // intranet_formCadastro
            if ($pathinfo === '/cadastro') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_intranet_formCadastro;
                }

                return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\CadastroController::formCadastroAction',  '_route' => 'intranet_formCadastro',);
            }
            not_intranet_formCadastro:

            // intranet_criarCadastro
            if ($pathinfo === '/cadastro') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_intranet_criarCadastro;
                }

                return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\CadastroController::criarCadastroAction',  '_route' => 'intranet_criarCadastro',);
            }
            not_intranet_criarCadastro:

        }

        if (0 === strpos($pathinfo, '/perfil')) {
            // intranet_consultarCadastro
            if ($pathinfo === '/perfil') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_intranet_consultarCadastro;
                }

                return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\CadastroController::consultarCadastroAction',  '_route' => 'intranet_consultarCadastro',);
            }
            not_intranet_consultarCadastro:

            // intranet_atualizarCadastro
            if ($pathinfo === '/perfil') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_intranet_atualizarCadastro;
                }

                return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\CadastroController::atualizarCadastroAction',  '_route' => 'intranet_atualizarCadastro',);
            }
            not_intranet_atualizarCadastro:

        }

        // intranet_buscar_notificacoes
        if (0 === strpos($pathinfo, '/notificacoes') && preg_match('#^/notificacoes/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_buscar_notificacoes')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::buscarNotificacoesAction',));
        }

        // intranet_excluir_notificacao
        if ($pathinfo === '/excluir-notificacao') {
            return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::deletarNotificacaoAction',  '_route' => 'intranet_excluir_notificacao',);
        }

        // intranet_total_notificacoes
        if ($pathinfo === '/total-notificacoes') {
            return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::totalNotificacoesAction',  '_route' => 'intranet_total_notificacoes',);
        }

        // intranet_excluir_notificacoes
        if ($pathinfo === '/excluir-notificacoes') {
            return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::deletarNotificacoesAction',  '_route' => 'intranet_excluir_notificacoes',);
        }

        if (0 === strpos($pathinfo, '/u')) {
            if (0 === strpos($pathinfo, '/user/public')) {
                // intranet_recover_email
                if ($pathinfo === '/user/public/password-recovery') {
                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::sendRecoveryEmailAction',  '_route' => 'intranet_recover_email',);
                }

                if (0 === strpos($pathinfo, '/user/public/s')) {
                    // intranet_set_new_password
                    if ($pathinfo === '/user/public/set-new-password') {
                        return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::setNewPasswordAction',  '_route' => 'intranet_set_new_password',);
                    }

                    // intranet_save_new_password
                    if (0 === strpos($pathinfo, '/user/public/save-new-password') && preg_match('#^/user/public/save\\-new\\-password/(?P<token>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_save_new_password')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::saveNewPasswordAction',));
                    }

                }

                // intranet_first_access
                if ($pathinfo === '/user/public/first-access') {
                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::firstAccessAction',  '_route' => 'intranet_first_access',);
                }

                // intranet_create_user
                if ($pathinfo === '/user/public/create-user') {
                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::createUserAction',  '_route' => 'intranet_create_user',);
                }

                // intranet_get_email
                if ($pathinfo === '/user/public/intranet-get-email') {
                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::getEmailAction',  '_route' => 'intranet_get_email',);
                }

            }

            if (0 === strpos($pathinfo, '/unidade')) {
                if (0 === strpos($pathinfo, '/unidade/selecionar')) {
                    // intranet_listarUnidadesEscolares
                    if ($pathinfo === '/unidade/selecionar') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_intranet_listarUnidadesEscolares;
                        }

                        return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UnidadeEscolarController::listarUnidadesEscolaresAction',  '_route' => 'intranet_listarUnidadesEscolares',);
                    }
                    not_intranet_listarUnidadesEscolares:

                    // intranet_selecionarUnidadeEscolar
                    if (preg_match('#^/unidade/selecionar/(?P<unidade>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_intranet_selecionarUnidadeEscolar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_selecionarUnidadeEscolar')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UnidadeEscolarController::selecionarUnidadeEscolarAction',));
                    }
                    not_intranet_selecionarUnidadeEscolar:

                }

                if (0 === strpos($pathinfo, '/unidade/users')) {
                    // intranet_unidade_usuario_gerenciar
                    if (preg_match('#^/unidade/users/(?P<usuario>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_unidade_usuario_gerenciar')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UnidadeEscolarController::gerenciarUsuarioAction',));
                    }

                    // intranet_unidade_usuario_atribuirRole
                    if (preg_match('#^/unidade/users/(?P<usuario>[^/]++)/roles$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_unidade_usuario_atribuirRole')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UnidadeEscolarController::atribuirRoleAction',));
                    }

                    // intranet_unidade_usuario_retirarRole
                    if (preg_match('#^/unidade/users/(?P<usuario>[^/]++)/roles/(?P<role>[^/]++)/remove$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_unidade_usuario_retirarRole')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UnidadeEscolarController::retirarRoleAction',));
                    }

                }

            }

        }

        if (0 === strpos($pathinfo, '/admin')) {
            // intranet_admin_index
            if ($pathinfo === '/admin') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_intranet_admin_index;
                }

                return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\AcessoController::indexAction',  '_route' => 'intranet_admin_index',);
            }
            not_intranet_admin_index:

            if (0 === strpos($pathinfo, '/admin/users')) {
                // intranet_admin_formPesquisaUsuario
                if ($pathinfo === '/admin/users') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_intranet_admin_formPesquisaUsuario;
                    }

                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::formPesquisaUsuarioAction',  '_route' => 'intranet_admin_formPesquisaUsuario',);
                }
                not_intranet_admin_formPesquisaUsuario:

                // intranet_admin_pesquisarUsuario
                if ($pathinfo === '/admin/users') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_intranet_admin_pesquisarUsuario;
                    }

                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::pesquisarUsuarioAction',  '_route' => 'intranet_admin_pesquisarUsuario',);
                }
                not_intranet_admin_pesquisarUsuario:

                // intranet_admin_listarRoles
                if (preg_match('#^/admin/users/(?P<usuario>[^/]++)/roles$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_intranet_admin_listarRoles;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_listarRoles')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::listarRolesAction',));
                }
                not_intranet_admin_listarRoles:

                // intranet_admin_atribuirRole
                if (preg_match('#^/admin/users/(?P<usuario>[^/]++)/roles/add$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_intranet_admin_atribuirRole;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_atribuirRole')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::atribuirRoleAction',));
                }
                not_intranet_admin_atribuirRole:

                // intranet_admin_retirarRole
                if (preg_match('#^/admin/users/(?P<usuario>[^/]++)/roles/remove/(?P<role>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_retirarRole')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::retirarRoleAction',));
                }

            }

            if (0 === strpos($pathinfo, '/admin/roles')) {
                // intranet_admin_formPesquisaRole
                if ($pathinfo === '/admin/roles') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_intranet_admin_formPesquisaRole;
                    }

                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\RoleController::formPesquisaAction',  '_route' => 'intranet_admin_formPesquisaRole',);
                }
                not_intranet_admin_formPesquisaRole:

                // intranet_admin_pesquisarRole
                if ($pathinfo === '/admin/roles') {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_intranet_admin_pesquisarRole;
                    }

                    return array (  '_controller' => 'SME\\IntranetBundle\\Controller\\RoleController::pesquisarAction',  '_route' => 'intranet_admin_pesquisarRole',);
                }
                not_intranet_admin_pesquisarRole:

                // intranet_admin_listarRolesHerdadas
                if (preg_match('#^/admin/roles/(?P<role>[^/]++)/hierarchy$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_intranet_admin_listarRolesHerdadas;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_listarRolesHerdadas')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\RoleController::listarRolesHerdadasAction',));
                }
                not_intranet_admin_listarRolesHerdadas:

                // intranet_admin_adicionarRoleHerdada
                if (preg_match('#^/admin/roles/(?P<role>[^/]++)/hierarchy/add$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_intranet_admin_adicionarRoleHerdada;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_adicionarRoleHerdada')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\RoleController::adicionarRoleHerdadaAction',));
                }
                not_intranet_admin_adicionarRoleHerdada:

                // intranet_admin_removerRoleHerdada
                if (preg_match('#^/admin/roles/(?P<role>[^/]++)/hierarchy/remove/(?P<roleHerdada>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_removerRoleHerdada')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\RoleController::removerRoleHerdadaAction',));
                }

            }

            // intranet_admin_buscaFoto
            if (0 === strpos($pathinfo, '/admin/users') && preg_match('#^/admin/users/(?P<id>[^/]++)/foto$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'intranet_admin_buscaFoto')), array (  '_controller' => 'SME\\IntranetBundle\\Controller\\UsuarioController::buscarFotoAction',));
            }

        }

        if (0 === strpos($pathinfo, '/dgp')) {
            if (0 === strpos($pathinfo, '/dgp/admin')) {
                if (0 === strpos($pathinfo, '/dgp/admin/admissao')) {
                    if (0 === strpos($pathinfo, '/dgp/admin/admissao/processo')) {
                        // dgp_processoAdmissao_cadastrar
                        if ($pathinfo === '/dgp/admin/admissao/processo/cadastrar') {
                            return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\ProcessoController::cadastrarAction',  '_route' => 'dgp_processoAdmissao_cadastrar',);
                        }

                        // dgp_processoAdmissao_pesquisar
                        if ($pathinfo === '/dgp/admin/admissao/processo/pesquisar') {
                            return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\ProcessoController::pesquisarAction',  '_route' => 'dgp_processoAdmissao_pesquisar',);
                        }

                        // dgp_processoAdmissao_pesquisarInscricoes
                        if (preg_match('#^/dgp/admin/admissao/processo/(?P<processo>[^/]++)/inscricoes$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_processoAdmissao_pesquisarInscricoes')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\InscricaoController::pesquisarAction',));
                        }

                        // dgp_processoAdmissao_cadastrarInscricao
                        if (preg_match('#^/dgp/admin/admissao/processo/(?P<processo>[^/]++)/inscricoes/add/(?P<cargo>[^/]++)$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_dgp_processoAdmissao_cadastrarInscricao;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_processoAdmissao_cadastrarInscricao')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\InscricaoController::cadastrarAction',));
                        }
                        not_dgp_processoAdmissao_cadastrarInscricao:

                        // dgp_processoAdmissao_formDesistencia
                        if (preg_match('#^/dgp/admin/admissao/processo/(?P<processo>[^/]++)/inscricoes/(?P<inscricao>[^/]++)/desistencia$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_processoAdmissao_formDesistencia;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_processoAdmissao_formDesistencia')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\InscricaoController::formDesistenciaAction',));
                        }
                        not_dgp_processoAdmissao_formDesistencia:

                        // dgp_processoAdmissao_imprimirTermoDesistencia
                        if (preg_match('#^/dgp/admin/admissao/processo/(?P<processo>[^/]++)/inscricoes/(?P<inscricao>[^/]++)/desistencia$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_dgp_processoAdmissao_imprimirTermoDesistencia;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_processoAdmissao_imprimirTermoDesistencia')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\InscricaoController::imprimirTermoDesistenciaAction',));
                        }
                        not_dgp_processoAdmissao_imprimirTermoDesistencia:

                        // dgp_convocacao_pesquisar
                        if (preg_match('#^/dgp/admin/admissao/processo/(?P<processo>[^/]++)/convocacoes$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_convocacao_pesquisar')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\ConvocacaoController::pesquisarAction',));
                        }

                    }

                    if (0 === strpos($pathinfo, '/dgp/admin/admissao/c')) {
                        // dgp_convocacao_getJson
                        if ($pathinfo === '/dgp/admin/admissao/convocacoes/json') {
                            return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\ConvocacaoController::getJsonAction',  '_route' => 'dgp_convocacao_getJson',);
                        }

                        if (0 === strpos($pathinfo, '/dgp/admin/admissao/ci-geral')) {
                            if (0 === strpos($pathinfo, '/dgp/admin/admissao/ci-geral/cadastrar')) {
                                // dgp_ciGeral_formCadastro
                                if ($pathinfo === '/dgp/admin/admissao/ci-geral/cadastrar') {
                                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                        $allow = array_merge($allow, array('GET', 'HEAD'));
                                        goto not_dgp_ciGeral_formCadastro;
                                    }

                                    return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::formCadastroAction',  '_route' => 'dgp_ciGeral_formCadastro',);
                                }
                                not_dgp_ciGeral_formCadastro:

                                // dgp_ciGeral_cadastrar
                                if ($pathinfo === '/dgp/admin/admissao/ci-geral/cadastrar') {
                                    if ($this->context->getMethod() != 'POST') {
                                        $allow[] = 'POST';
                                        goto not_dgp_ciGeral_cadastrar;
                                    }

                                    return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::cadastrarAction',  '_route' => 'dgp_ciGeral_cadastrar',);
                                }
                                not_dgp_ciGeral_cadastrar:

                            }

                            if (0 === strpos($pathinfo, '/dgp/admin/admissao/ci-geral/pesquisar')) {
                                // dgp_ciGeral_formPesquisa
                                if ($pathinfo === '/dgp/admin/admissao/ci-geral/pesquisar') {
                                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                        $allow = array_merge($allow, array('GET', 'HEAD'));
                                        goto not_dgp_ciGeral_formPesquisa;
                                    }

                                    return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::formPesquisaAction',  '_route' => 'dgp_ciGeral_formPesquisa',);
                                }
                                not_dgp_ciGeral_formPesquisa:

                                // dgp_ciGeral_pesquisar
                                if ($pathinfo === '/dgp/admin/admissao/ci-geral/pesquisar') {
                                    if ($this->context->getMethod() != 'POST') {
                                        $allow[] = 'POST';
                                        goto not_dgp_ciGeral_pesquisar;
                                    }

                                    return array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::pesquisarAction',  '_route' => 'dgp_ciGeral_pesquisar',);
                                }
                                not_dgp_ciGeral_pesquisar:

                            }

                            // dgp_ciGeral_formAlteracao
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_ciGeral_formAlteracao;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_formAlteracao')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::formAlteracaoAction',));
                            }
                            not_dgp_ciGeral_formAlteracao:

                            // dgp_ciGeral_atualizar
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/atualizar$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_ciGeral_atualizar;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_atualizar')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::atualizarAction',));
                            }
                            not_dgp_ciGeral_atualizar:

                            // dgp_ciGeral_excluir
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_ciGeral_excluir;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_excluir')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::excluirAction',));
                            }
                            not_dgp_ciGeral_excluir:

                            // dgp_ciGeral_formPesquisaVinculo
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/vinculos/pesquisar$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_ciGeral_formPesquisaVinculo;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_formPesquisaVinculo')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::formPesquisaVinculoAction',));
                            }
                            not_dgp_ciGeral_formPesquisaVinculo:

                            // dgp_ciGeral_pesquisarVinculos
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/vinculos/pesquisar$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_ciGeral_pesquisarVinculos;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_pesquisarVinculos')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::pesquisarVinculosAction',));
                            }
                            not_dgp_ciGeral_pesquisarVinculos:

                            // dgp_ciGeral_incluirVinculos
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/vinculos/add$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_ciGeral_incluirVinculos;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_incluirVinculos')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::incluirVinculosAction',));
                            }
                            not_dgp_ciGeral_incluirVinculos:

                            // dgp_ciGeral_excluirVinculos
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/vinculos/excluir$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_ciGeral_excluirVinculos;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_excluirVinculos')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::excluirVinculosAction',));
                            }
                            not_dgp_ciGeral_excluirVinculos:

                            // dgp_ciGeral_imprimir
                            if (preg_match('#^/dgp/admin/admissao/ci\\-geral/(?P<ci>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_ciGeral_imprimir;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_ciGeral_imprimir')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\CIGeralController::imprimirAction',));
                            }
                            not_dgp_ciGeral_imprimir:

                        }

                    }

                    if (0 === strpos($pathinfo, '/dgp/admin/admissao/impressoes')) {
                        // dgp_vinculo_imprimirFichaCadastral
                        if (preg_match('#^/dgp/admin/admissao/impressoes/(?P<vinculo>[^/]++)/ficha\\-cadastral$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_vinculo_imprimirFichaCadastral;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirFichaCadastral')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\DocumentacaoController::imprimirFichaCadastralAction',));
                        }
                        not_dgp_vinculo_imprimirFichaCadastral:

                        // dgp_vinculo_imprimirEncaminhamento
                        if (preg_match('#^/dgp/admin/admissao/impressoes/(?P<vinculo>[^/]++)/encaminhamento$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_vinculo_imprimirEncaminhamento;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirEncaminhamento')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\DocumentacaoController::imprimirEncaminhamentoAction',));
                        }
                        not_dgp_vinculo_imprimirEncaminhamento:

                        // dgp_vinculo_imprimirChecklist
                        if (preg_match('#^/dgp/admin/admissao/impressoes/(?P<vinculo>[^/]++)/checklist$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_vinculo_imprimirChecklist;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirChecklist')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\DocumentacaoController::imprimirChecklistAction',));
                        }
                        not_dgp_vinculo_imprimirChecklist:

                        // dgp_vinculo_imprimirParecerRegularidade
                        if (preg_match('#^/dgp/admin/admissao/impressoes/(?P<vinculo>[^/]++)/parecer\\-regularidade$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_vinculo_imprimirParecerRegularidade;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirParecerRegularidade')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\DocumentacaoController::imprimirParecerRegularidadeAction',));
                        }
                        not_dgp_vinculo_imprimirParecerRegularidade:

                        // dgp_vinculo_imprimirTermoPosse
                        if (preg_match('#^/dgp/admin/admissao/impressoes/(?P<vinculo>[^/]++)/termo\\-posse$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_vinculo_imprimirTermoPosse;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirTermoPosse')), array (  '_controller' => 'SME\\DGPContratacaoBundle\\Controller\\DocumentacaoController::imprimirTermoPosseAction',));
                        }
                        not_dgp_vinculo_imprimirTermoPosse:

                    }

                }

                if (0 === strpos($pathinfo, '/dgp/admin/promocao')) {
                    if (0 === strpos($pathinfo, '/dgp/admin/promocao/ci-geral')) {
                        if (0 === strpos($pathinfo, '/dgp/admin/promocao/ci-geral/pesquisar')) {
                            // dgp_promocao_ciGeral_formPesquisa
                            if ($pathinfo === '/dgp/admin/promocao/ci-geral/pesquisar') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_promocao_ciGeral_formPesquisa;
                                }

                                return array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::formPesquisaAction',  '_route' => 'dgp_promocao_ciGeral_formPesquisa',);
                            }
                            not_dgp_promocao_ciGeral_formPesquisa:

                            // dgp_promocao_ciGeral_pesquisar
                            if ($pathinfo === '/dgp/admin/promocao/ci-geral/pesquisar') {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_promocao_ciGeral_pesquisar;
                                }

                                return array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::pesquisarAction',  '_route' => 'dgp_promocao_ciGeral_pesquisar',);
                            }
                            not_dgp_promocao_ciGeral_pesquisar:

                        }

                        // dgp_promocao_ciGeral_cadastrar
                        if ($pathinfo === '/dgp/admin/promocao/ci-geral/cadastrar') {
                            return array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::cadastrarAction',  '_route' => 'dgp_promocao_ciGeral_cadastrar',);
                        }

                        // dgp_promocao_ciGeral_alterar
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_alterar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::alterarAction',));
                        }

                        // dgp_promocao_ciGeral_excluir
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_excluir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::excluirAction',));
                        }

                        // dgp_promocao_ciGeral_formPesquisaPromocoes
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/promocoes/pesquisar$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_promocao_ciGeral_formPesquisaPromocoes;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_formPesquisaPromocoes')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::formPesquisaPromocoesAction',));
                        }
                        not_dgp_promocao_ciGeral_formPesquisaPromocoes:

                        // dgp_promocao_ciGeral_pesquisarPromocoes
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/promocoes/pesquisar$#s', $pathinfo, $matches)) {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_dgp_promocao_ciGeral_pesquisarPromocoes;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_pesquisarPromocoes')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::pesquisarPromocoesAction',));
                        }
                        not_dgp_promocao_ciGeral_pesquisarPromocoes:

                        // dgp_promocao_ciGeral_adicionarPromocoes
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/promocoes/adicionar$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_adicionarPromocoes')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::adicionarPromocoesAction',));
                        }

                        // dgp_promocao_ciGeral_removerPromocao
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/promocoes/(?P<promocao>[^/]++)/remover$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_removerPromocao')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::removerPromocaoAction',));
                        }

                        // dgp_promocao_ciGeral_imprimir
                        if (preg_match('#^/dgp/admin/promocao/ci\\-geral/(?P<ci>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocao_ciGeral_imprimir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\CIGeralController::imprimirAction',));
                        }

                    }

                    // dgp_promocaoHorizontal_listar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/horizontal$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_listar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::listarAction',));
                    }

                    // dgp_promocaoHorizontal_cadastrar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/horizontal/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_cadastrar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::cadastrarAction',));
                    }

                    // dgp_promocaoHorizontal_alterar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/horizontal/(?P<promocao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_alterar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::alterarAction',));
                    }

                    // dgp_promocaoHorizontal_imprimir
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/horizontal/(?P<promocao>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_imprimir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::imprimirAction',));
                    }

                    if (0 === strpos($pathinfo, '/dgp/admin/promocao/horizontal')) {
                        // dgp_promocaoHorizontal_formacaoInterna_incluir
                        if (preg_match('#^/dgp/admin/promocao/horizontal/(?P<promocao>[^/]++)/formacoes\\-internas/incluir$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_formacaoInterna_incluir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::incluirFormacaoAction',));
                        }

                        // dgp_promocaoHorizontal_formacaoInterna_excluir
                        if (preg_match('#^/dgp/admin/promocao/horizontal/(?P<promocao>[^/]++)/formacoes\\-internas/(?P<formacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_formacaoInterna_excluir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::excluirFormacaoAction',));
                        }

                        // dgp_promocaoHorizontal_formacaoExterna_cadastrar
                        if (preg_match('#^/dgp/admin/promocao/horizontal/(?P<promocao>[^/]++)/formacoes\\-externas/cadastrar$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_formacaoExterna_cadastrar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\FormacaoExternaController::cadastrarAction',));
                        }

                        // dgp_promocaoHorizontal_formacaoExterna_excluir
                        if (preg_match('#^/dgp/admin/promocao/horizontal/(?P<promocao>[^/]++)/formacoes\\-externas/(?P<formacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoHorizontal_formacaoExterna_excluir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\FormacaoExternaController::excluirAction',));
                        }

                    }

                    // dgp_promocaoVertical_listar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/vertical$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoVertical_listar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::listarAction',));
                    }

                    // dgp_promocaoVertical_cadastrar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/vertical/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoVertical_cadastrar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::cadastrarAction',));
                    }

                    // dgp_promocaoVertical_alterar
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/vertical/(?P<promocao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoVertical_alterar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::alterarAction',));
                    }

                    // dgp_promocaoVertical_imprimir
                    if (preg_match('#^/dgp/admin/promocao/(?P<vinculo>[^/]++)/vertical/(?P<promocao>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_promocaoVertical_imprimir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::imprimirAction',));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/dgp/servidor/promocao')) {
                // dgp_servidor_promocao_listar
                if (preg_match('#^/dgp/servidor/promocao/(?P<vinculo>[^/]++)/listar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocao_listar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\ServidorController::listarAction',));
                }

                // dgp_servidor_promocaoHorizontal_cadastrar
                if (preg_match('#^/dgp/servidor/promocao/(?P<vinculo>[^/]++)/horizontal/cadastrar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoHorizontal_cadastrar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::solicitarAction',));
                }

                if (0 === strpos($pathinfo, '/dgp/servidor/promocao/horizontal')) {
                    // dgp_servidor_promocaoHorizontal_visualizar
                    if (preg_match('#^/dgp/servidor/promocao/horizontal/(?P<promocao>[^/]++)/visualizar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoHorizontal_visualizar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::visualizarAction',));
                    }

                    // dgp_servidor_promocaoHorizontal_cancelar
                    if (preg_match('#^/dgp/servidor/promocao/horizontal/(?P<promocao>[^/]++)/cancelar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoHorizontal_cancelar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::cancelarAction',));
                    }

                    // dgp_servidor_promocaoHorizontal_imprimir
                    if (preg_match('#^/dgp/servidor/promocao/horizontal/(?P<promocao>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoHorizontal_imprimir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoHorizontalController::imprimirAction',));
                    }

                }

                // dgp_servidor_promocaoVertical_cadastrar
                if (preg_match('#^/dgp/servidor/promocao/(?P<vinculo>[^/]++)/vertical/cadastrar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoVertical_cadastrar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::solicitarAction',));
                }

                if (0 === strpos($pathinfo, '/dgp/servidor/promocao/vertical')) {
                    // dgp_servidor_promocaoVertical_visualizar
                    if (preg_match('#^/dgp/servidor/promocao/vertical/(?P<promocao>[^/]++)/visualizar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoVertical_visualizar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::visualizarAction',));
                    }

                    // dgp_servidor_promocaoVertical_cancelar
                    if (preg_match('#^/dgp/servidor/promocao/vertical/(?P<promocao>[^/]++)/cancelar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoVertical_cancelar')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::cancelarAction',));
                    }

                    // dgp_servidor_promocaoVertical_imprimir
                    if (preg_match('#^/dgp/servidor/promocao/vertical/(?P<promocao>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_promocaoVertical_imprimir')), array (  '_controller' => 'SME\\DGPPromocaoBundle\\Controller\\PromocaoVerticalController::imprimirAction',));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/dgp/admin/formacao')) {
                // dgp_formacao_listar
                if ($pathinfo === '/dgp/admin/formacao') {
                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::listarAction',  '_route' => 'dgp_formacao_listar',);
                }

                // dgp_formacao_cadastrar
                if ($pathinfo === '/dgp/admin/formacao/cadastrar') {
                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::cadastrarAction',  '_route' => 'dgp_formacao_cadastrar',);
                }

                // dgp_formacao_atualizar
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_atualizar')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::atualizarAction',));
                }

                // dgp_formacao_excluir
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_excluir')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::excluirAction',));
                }

                // dgp_formacao_cadastrarMatriculaPorCpf
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/matriculas/adicionar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_cadastrarMatriculaPorCpf')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::cadastrarPorCpfAction',));
                }

                // dgp_formacao_atualizarMatriculas
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/matriculas/atualizar/(?P<page>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_atualizarMatriculas')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::atualizarTodasAction',));
                }

                // dgp_formacao_excluirMatriculas
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/matriculas/excluir/(?P<page>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_excluirMatriculas')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::excluirTodasAction',));
                }

                // dgp_formacao_listarMatriculas
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/matriculas(?:/(?P<page>[^/]++))?$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_listarMatriculas')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::listarPorFormacaoAction',  'page' => 1,));
                }

                // dgp_formacao_listarEncontros
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/encontros$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_listarEncontros')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\EncontroController::listarAction',));
                }

                // dgp_formacao_excluirEncontro
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/encontros/(?P<encontro>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_excluirEncontro')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\EncontroController::excluirAction',));
                }

                // dgp_formacao_imprimirChamada
                if (preg_match('#^/dgp/admin/formacao/(?P<formacao>[^/]++)/imprimir\\-chamada$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_imprimirChamada')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::imprimirChamadaAction',));
                }

            }

            if (0 === strpos($pathinfo, '/dgp/servidor/formacoes')) {
                // dgp_servidor_listarFormacoes
                if ($pathinfo === '/dgp/servidor/formacoes') {
                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::listarPorServidorAction',  '_route' => 'dgp_servidor_listarFormacoes',);
                }

                // dgp_servidor_imprimirCertificado
                if (preg_match('#^/dgp/servidor/formacoes/(?P<matricula>[^/]++)/certificado$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_imprimirCertificado')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::imprimirCertificadoAction',));
                }

            }

            if (0 === strpos($pathinfo, '/dgp/formacao')) {
                // dgp_formacao_adicionarMatricula
                if (preg_match('#^/dgp/formacao/(?P<formacao>[^/]++)/matriculas/add/(?P<pessoa>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_adicionarMatricula')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::cadastrarAction',));
                }

                // dgp_formacao_cancelarMatricula
                if (preg_match('#^/dgp/formacao/(?P<formacao>[^/]++)/matriculas/remove/(?P<matricula>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_cancelarMatricula')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\MatriculaController::cancelarAction',));
                }

            }

            if (0 === strpos($pathinfo, '/dgp/public/formac')) {
                // dgp_publico_getFormacoes
                if ($pathinfo === '/dgp/public/formacoes') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dgp_publico_getFormacoes;
                    }

                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\PublicController::getFormacoesAction',  '_route' => 'dgp_publico_getFormacoes',);
                }
                not_dgp_publico_getFormacoes:

                // dgp_publico_getFormacao
                if (0 === strpos($pathinfo, '/dgp/public/formacao') && preg_match('#^/dgp/public/formacao/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dgp_publico_getFormacao;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_publico_getFormacao')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\PublicController::getFormacaoAction',));
                }
                not_dgp_publico_getFormacao:

                // dgp_publico_postMatricula
                if (0 === strpos($pathinfo, '/dgp/public/formacoes') && preg_match('#^/dgp/public/formacoes/(?P<formacao>[^/]++)/matriculas$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_dgp_publico_postMatricula;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_publico_postMatricula')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\PublicController::postMatriculaAction',));
                }
                not_dgp_publico_postMatricula:

            }

            if (0 === strpos($pathinfo, '/dgp/admin/formacao')) {
                // dgp_formacao_listar_no_local
                if ($pathinfo === '/dgp/admin/formacao/local') {
                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::listarNoLocalAction',  '_route' => 'dgp_formacao_listar_no_local',);
                }

                // dgp_formacao_inscritos
                if (preg_match('#^/dgp/admin/formacao/(?P<id>[^/]++)/inscritos$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_inscritos')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::listarInscritosAction',));
                }

                // dgp_formacao_buscar_nome
                if ($pathinfo === '/dgp/admin/formacao/buscar-nome') {
                    return array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::buscarNomeAction',  '_route' => 'dgp_formacao_buscar_nome',);
                }

                // dgp_formacao_inscrever
                if (preg_match('#^/dgp/admin/formacao/(?P<id>[^/]++)/inscrever$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_formacao_inscrever')), array (  '_controller' => 'SME\\DGPFormacaoBundle\\Controller\\FormacaoController::inscreverNoLocalAction',));
                }

            }

            if (0 === strpos($pathinfo, '/dgp/servidor/p')) {
                if (0 === strpos($pathinfo, '/dgp/servidor/permuta/intencoes')) {
                    // dgp_servidor_permuta_listarIntencoes
                    if ($pathinfo === '/dgp/servidor/permuta/intencoes') {
                        return array (  '_controller' => 'SME\\DGPPermutaBundle\\Controller\\IntencaoController::listarAction',  '_route' => 'dgp_servidor_permuta_listarIntencoes',);
                    }

                    // dgp_servidor_permuta_cadastrarIntencao
                    if ($pathinfo === '/dgp/servidor/permuta/intencoes/cadastrar') {
                        return array (  '_controller' => 'SME\\DGPPermutaBundle\\Controller\\IntencaoController::cadastrarAction',  '_route' => 'dgp_servidor_permuta_cadastrarIntencao',);
                    }

                    // dgp_servidor_permuta_excluirIntencao
                    if (preg_match('#^/dgp/servidor/permuta/intencoes/(?P<intencao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_permuta_excluirIntencao')), array (  '_controller' => 'SME\\DGPPermutaBundle\\Controller\\IntencaoController::excluirAction',));
                    }

                }

                if (0 === strpos($pathinfo, '/dgp/servidor/processos-anuais')) {
                    // dgp_servidor_processoAnual_listarDisponiveis
                    if ($pathinfo === '/dgp/servidor/processos-anuais') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_dgp_servidor_processoAnual_listarDisponiveis;
                        }

                        return array (  '_controller' => 'SME\\DGPProcessoAnualBundle\\Controller\\ProcessoAnualController::listarDisponiveisAction',  '_route' => 'dgp_servidor_processoAnual_listarDisponiveis',);
                    }
                    not_dgp_servidor_processoAnual_listarDisponiveis:

                    // dgp_servidor_processoAnual_cadastrarInscricao
                    if (0 === strpos($pathinfo, '/dgp/servidor/processos-anuais/cadastrar') && preg_match('#^/dgp/servidor/processos\\-anuais/cadastrar/(?P<processo>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_dgp_servidor_processoAnual_cadastrarInscricao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_processoAnual_cadastrarInscricao')), array (  '_controller' => 'SME\\DGPProcessoAnualBundle\\Controller\\ProcessoAnualController::cadastrarInscricaoAction',));
                    }
                    not_dgp_servidor_processoAnual_cadastrarInscricao:

                }

            }

            // dgp_index
            if (rtrim($pathinfo, '/') === '/dgp') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_dgp_index;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'dgp_index');
                }

                return array (  '_controller' => 'SME\\DGPBundle\\Controller\\ServidorController::indexAction',  '_route' => 'dgp_index',);
            }
            not_dgp_index:

            if (0 === strpos($pathinfo, '/dgp/admin')) {
                if (0 === strpos($pathinfo, '/dgp/admin/pessoas')) {
                    // dgp_pessoa_pesquisar
                    if ($pathinfo === '/dgp/admin/pessoas/pesquisar') {
                        return array (  '_controller' => 'SME\\DGPBundle\\Controller\\PessoaController::pesquisarAction',  '_route' => 'dgp_pessoa_pesquisar',);
                    }

                    // dgp_pessoa_cadastrar
                    if ($pathinfo === '/dgp/admin/pessoas/cadastrar') {
                        return array (  '_controller' => 'SME\\DGPBundle\\Controller\\PessoaController::cadastrarAction',  '_route' => 'dgp_pessoa_cadastrar',);
                    }

                    // dgp_pessoa_alterar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\PessoaController::alterarAction',));
                    }

                    // dgp_pessoa_endereco_alterar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/endereco/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_endereco_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\PessoaController::alterarEnderecoAction',));
                    }

                    // dgp_pessoa_telefone_cadastrar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/telefones/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_telefone_cadastrar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\TelefoneController::cadastrarAction',));
                    }

                    // dgp_pessoa_telefone_excluir
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/telefones/(?P<telefone>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_telefone_excluir')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\TelefoneController::excluirAction',));
                    }

                    // dgp_pessoa_usuario_alterarSenha
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/usuario/alterar\\-senha$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_usuario_alterarSenha')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\UsuarioController::alterarSenhaAction',));
                    }

                    // dgp_pessoa_formacao_listar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/titulos$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_formacao_listar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\FormacaoController::listarAction',));
                    }

                    // dgp_pessoa_formacao_cadastrar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/titulos/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_formacao_cadastrar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\FormacaoController::cadastrarAction',));
                    }

                    // dgp_pessoa_formacao_alterar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/titulos/(?P<formacao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_formacao_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\FormacaoController::alterarAction',));
                    }

                    // dgp_pessoa_formacao_excluir
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/titulos/(?P<formacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_formacao_excluir')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\FormacaoController::excluirAction',));
                    }

                    // dgp_pessoa_vinculo_listar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/vinculos$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_vinculo_listar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::listarPorPessoaAction',));
                    }

                    // dgp_pessoa_vinculo_cadastrar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/vinculos/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_vinculo_cadastrar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::cadastrarAction',));
                    }

                    // dgp_pessoa_vinculo_alterar
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/vinculos/(?P<vinculo>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_vinculo_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::alterarAction',));
                    }

                    // dgp_pessoa_vinculo_excluir
                    if (preg_match('#^/dgp/admin/pessoas/(?P<pessoa>[^/]++)/vinculos/(?P<vinculo>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_pessoa_vinculo_excluir')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::excluirAction',));
                    }

                }

                if (0 === strpos($pathinfo, '/dgp/admin/vinculos')) {
                    // dgp_vinculo_imprimirDocumentos
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/imprimir\\-documentos$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_imprimirDocumentos')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::imprimirDocumentosAction',));
                    }

                    // dgp_vinculo_formAlteracaoRapida
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alterar\\-rapido$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_dgp_vinculo_formAlteracaoRapida;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_formAlteracaoRapida')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::formAlteracaoRapidaAction',));
                    }
                    not_dgp_vinculo_formAlteracaoRapida:

                    // dgp_vinculo_alterarRapido
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alterar\\-rapido$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_dgp_vinculo_alterarRapido;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_alterarRapido')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\VinculoController::alterarRapidoAction',));
                    }
                    not_dgp_vinculo_alterarRapido:

                    // dgp_vinculo_alocacao_listar
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alocacoes$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_alocacao_listar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\AlocacaoController::listarPorVinculoAction',));
                    }

                    // dgp_vinculo_alocacao_cadastrar
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alocacoes/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_alocacao_cadastrar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\AlocacaoController::cadastrarAction',));
                    }

                    // dgp_vinculo_alocacao_alterar
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alocacoes/(?P<alocacao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_alocacao_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\AlocacaoController::alterarAction',));
                    }

                    // dgp_vinculo_alocacao_excluir
                    if (preg_match('#^/dgp/admin/vinculos/(?P<vinculo>[^/]++)/alocacoes/(?P<alocacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_vinculo_alocacao_excluir')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\AlocacaoController::excluirAction',));
                    }

                }

            }

            if (0 === strpos($pathinfo, '/dgp/servidor/vinculos')) {
                // dgp_servidor_listarVinculos
                if ($pathinfo === '/dgp/servidor/vinculos') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dgp_servidor_listarVinculos;
                    }

                    return array (  '_controller' => 'SME\\DGPBundle\\Controller\\ServidorController::listarVinculosAction',  '_route' => 'dgp_servidor_listarVinculos',);
                }
                not_dgp_servidor_listarVinculos:

                // dgp_servidor_consultarVinculo
                if (preg_match('#^/dgp/servidor/vinculos/(?P<vinculo>[^/]++)/consultar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_servidor_consultarVinculo')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\ServidorController::consultarVinculoAction',));
                }

            }

            if (0 === strpos($pathinfo, '/dgp/unidade')) {
                // dgp_unidade_criarRoles
                if ($pathinfo === '/dgp/unidade/roles/gerar') {
                    return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::criarRolesAction',  '_route' => 'dgp_unidade_criarRoles',);
                }

                if (0 === strpos($pathinfo, '/dgp/unidade/servidor')) {
                    // dgp_unidade_listarAlocacoes
                    if ($pathinfo === '/dgp/unidade/servidores') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_dgp_unidade_listarAlocacoes;
                        }

                        return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::listarAlocacoesAction',  '_route' => 'dgp_unidade_listarAlocacoes',);
                    }
                    not_dgp_unidade_listarAlocacoes:

                    // dgp_unidade_buscarEmailByUsuario
                    if ($pathinfo === '/dgp/unidade/servidor/email') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_dgp_unidade_buscarEmailByUsuario;
                        }

                        return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::buscarEmailByUsuarioAction',  '_route' => 'dgp_unidade_buscarEmailByUsuario',);
                    }
                    not_dgp_unidade_buscarEmailByUsuario:

                    // dgp_unidade_salvarEmailServidor
                    if ($pathinfo === '/dgp/unidade/servidor/salvar/email') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_dgp_unidade_salvarEmailServidor;
                        }

                        return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::salvarEmailServidorAction',  '_route' => 'dgp_unidade_salvarEmailServidor',);
                    }
                    not_dgp_unidade_salvarEmailServidor:

                    if (0 === strpos($pathinfo, '/dgp/unidade/servidores')) {
                        if (0 === strpos($pathinfo, '/dgp/unidade/servidores/pesquisar')) {
                            // dgp_unidade_formPesquisaVinculo
                            if ($pathinfo === '/dgp/unidade/servidores/pesquisar') {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_unidade_formPesquisaVinculo;
                                }

                                return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::formPesquisaVinculoAction',  '_route' => 'dgp_unidade_formPesquisaVinculo',);
                            }
                            not_dgp_unidade_formPesquisaVinculo:

                            // dgp_unidade_pesquisarVinculo
                            if ($pathinfo === '/dgp/unidade/servidores/pesquisar') {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_unidade_pesquisarVinculo;
                                }

                                return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::pesquisarVinculoAction',  '_route' => 'dgp_unidade_pesquisarVinculo',);
                            }
                            not_dgp_unidade_pesquisarVinculo:

                        }

                        if (0 === strpos($pathinfo, '/dgp/unidade/servidores/incluir')) {
                            // dgp_unidade_formAlocacao
                            if (preg_match('#^/dgp/unidade/servidores/incluir/(?P<vinculo>[^/]++)$#s', $pathinfo, $matches)) {
                                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                    $allow = array_merge($allow, array('GET', 'HEAD'));
                                    goto not_dgp_unidade_formAlocacao;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_unidade_formAlocacao')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::formAlocacaoAction',));
                            }
                            not_dgp_unidade_formAlocacao:

                            // dgp_unidade_alocarVinculo
                            if (preg_match('#^/dgp/unidade/servidores/incluir/(?P<vinculo>[^/]++)$#s', $pathinfo, $matches)) {
                                if ($this->context->getMethod() != 'POST') {
                                    $allow[] = 'POST';
                                    goto not_dgp_unidade_alocarVinculo;
                                }

                                return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_unidade_alocarVinculo')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::alocarVinculoAction',));
                            }
                            not_dgp_unidade_alocarVinculo:

                        }

                        // dgp_unidade_desalocarVinculo
                        if (0 === strpos($pathinfo, '/dgp/unidade/servidores/excluir') && preg_match('#^/dgp/unidade/servidores/excluir/(?P<alocacao>[^/]++)$#s', $pathinfo, $matches)) {
                            if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                                goto not_dgp_unidade_desalocarVinculo;
                            }

                            return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_unidade_desalocarVinculo')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::desalocarVinculoAction',));
                        }
                        not_dgp_unidade_desalocarVinculo:

                        // dgp_unidade_formPonto
                        if ($pathinfo === '/dgp/unidade/servidores/registro-ponto') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_dgp_unidade_formPonto;
                            }

                            return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::formPontoAction',  '_route' => 'dgp_unidade_formPonto',);
                        }
                        not_dgp_unidade_formPonto:

                    }

                }

                // dgp_unidade_imprimirPonto
                if ($pathinfo === '/dgp/unidade/alocacao/registro-ponto/imprimir') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_dgp_unidade_imprimirPonto;
                    }

                    return array (  '_controller' => 'SME\\DGPBundle\\Controller\\UnidadeEscolarController::imprimirPontoAction',  '_route' => 'dgp_unidade_imprimirPonto',);
                }
                not_dgp_unidade_imprimirPonto:

            }

            if (0 === strpos($pathinfo, '/dgp/admin/entidades')) {
                // dgp_entidade_listar
                if ($pathinfo === '/dgp/admin/entidades') {
                    return array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::listarAction',  '_route' => 'dgp_entidade_listar',);
                }

                // dgp_entidade_cadastrar
                if ($pathinfo === '/dgp/admin/entidades/cadastrar') {
                    return array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::cadastrarAction',  '_route' => 'dgp_entidade_cadastrar',);
                }

                // dgp_entidade_alterar
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::alterarAction',));
                }

                // dgp_entidade_endereco_alterar
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/endereco/alterar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_endereco_alterar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::alterarEnderecoAction',));
                }

                // dgp_entidade_telefone_cadastrar
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/telefones/cadastrar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_telefone_cadastrar')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::adicionarTelefoneAction',));
                }

                // dgp_entidade_telefone_excluir
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/telefones/(?P<telefone>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_telefone_excluir')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::removerTelefoneAction',));
                }

                // dgp_entidade_listar_alocados
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/alocacoes$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_listar_alocados')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::listarAlocadosEntidadeAction',));
                }

                // dgp_entidade_excluir_alocacao
                if (preg_match('#^/dgp/admin/entidades/(?P<entidade>[^/]++)/alocacoes/excluir/(?P<alocacao>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'dgp_entidade_excluir_alocacao')), array (  '_controller' => 'SME\\DGPBundle\\Controller\\EntidadeController::excluirAlocacaoAction',));
                }

            }

        }

        if (0 === strpos($pathinfo, '/fu')) {
            // fu_index
            if ($pathinfo === '/fu/admin') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_fu_index;
                }

                return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\IndexController::indexAction',  '_route' => 'fu_index',);
            }
            not_fu_index:

            if (0 === strpos($pathinfo, '/fu/p')) {
                if (0 === strpos($pathinfo, '/fu/public/c')) {
                    // fu_publico_consultarInscricao
                    if (0 === strpos($pathinfo, '/fu/public/consulta') && preg_match('#^/fu/public/consulta/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_publico_consultarInscricao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_publico_consultarInscricao')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PublicController::consultarInscricaoAction',));
                    }
                    not_fu_publico_consultarInscricao:

                    // fu_publico_consultarChamadas
                    if (0 === strpos($pathinfo, '/fu/public/chamadas') && preg_match('#^/fu/public/chamadas/(?P<zoneamento>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_publico_consultarChamadas;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_publico_consultarChamadas')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PublicController::consultarChamadasAction',));
                    }
                    not_fu_publico_consultarChamadas:

                }

                if (0 === strpos($pathinfo, '/fu/promotoria')) {
                    if (0 === strpos($pathinfo, '/fu/promotoria/fila')) {
                        // fu_promotoria_formFila
                        if ($pathinfo === '/fu/promotoria/fila') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_promotoria_formFila;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::formFilaAction',  '_route' => 'fu_promotoria_formFila',);
                        }
                        not_fu_promotoria_formFila:

                        // fu_promotoria_exibirFila
                        if ($pathinfo === '/fu/promotoria/fila') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_promotoria_exibirFila;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::exibirFilaAction',  '_route' => 'fu_promotoria_exibirFila',);
                        }
                        not_fu_promotoria_exibirFila:

                    }

                    if (0 === strpos($pathinfo, '/fu/promotoria/pesquisar')) {
                        // fu_promotoria_formPesquisa
                        if ($pathinfo === '/fu/promotoria/pesquisar') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_promotoria_formPesquisa;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::formPesquisaAction',  '_route' => 'fu_promotoria_formPesquisa',);
                        }
                        not_fu_promotoria_formPesquisa:

                        // fu_promotoria_pesquisar
                        if ($pathinfo === '/fu/promotoria/pesquisar') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_promotoria_pesquisar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::pesquisarAction',  '_route' => 'fu_promotoria_pesquisar',);
                        }
                        not_fu_promotoria_pesquisar:

                    }

                    // fu_promotoria_imprimir
                    if (0 === strpos($pathinfo, '/fu/promotoria/imprimir') && preg_match('#^/fu/promotoria/imprimir/(?P<inscricao>[^/]++)$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_promotoria_imprimir')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::imprimirAction',));
                    }

                    // fu_promotoria_glossario
                    if ($pathinfo === '/fu/promotoria/glossario') {
                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\PromotoriaController::glossarioAction',  '_route' => 'fu_promotoria_glossario',);
                    }

                }

            }

            if (0 === strpos($pathinfo, '/fu/admin')) {
                if (0 === strpos($pathinfo, '/fu/admin/fila')) {
                    // fu_inscricao_formFila
                    if ($pathinfo === '/fu/admin/fila') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_formFila;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::formFilaAction',  '_route' => 'fu_inscricao_formFila',);
                    }
                    not_fu_inscricao_formFila:

                    // fu_inscricao_exibirFila
                    if ($pathinfo === '/fu/admin/fila') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_exibirFila;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::exibirFilaAction',  '_route' => 'fu_inscricao_exibirFila',);
                    }
                    not_fu_inscricao_exibirFila:

                }

                if (0 === strpos($pathinfo, '/fu/admin/inscricao')) {
                    if (0 === strpos($pathinfo, '/fu/admin/inscricao/pesquisa')) {
                        // fu_inscricao_formPesquisa
                        if ($pathinfo === '/fu/admin/inscricao/pesquisa') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_inscricao_formPesquisa;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::formPesquisaAction',  '_route' => 'fu_inscricao_formPesquisa',);
                        }
                        not_fu_inscricao_formPesquisa:

                        // fu_inscricao_pesquisar
                        if ($pathinfo === '/fu/admin/inscricao/pesquisa') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_inscricao_pesquisar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::pesquisarAction',  '_route' => 'fu_inscricao_pesquisar',);
                        }
                        not_fu_inscricao_pesquisar:

                    }

                    // fu_inscricao_formInscricao
                    if (0 === strpos($pathinfo, '/fu/admin/inscricao/cadastrar') && preg_match('#^/fu/admin/inscricao/cadastrar(?:/(?P<tipoInscricao>[^/]++))?$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_formInscricao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_formInscricao')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::formCadastroAction',  'tipoInscricao' => 1,));
                    }
                    not_fu_inscricao_formInscricao:

                    // fu_inscricao_validar
                    if ($pathinfo === '/fu/admin/inscricao/validar') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_validar;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::validarAction',  '_route' => 'fu_inscricao_validar',);
                    }
                    not_fu_inscricao_validar:

                    // fu_inscricao_cadastrar
                    if (0 === strpos($pathinfo, '/fu/admin/inscricao/cadastrar') && preg_match('#^/fu/admin/inscricao/cadastrar/(?P<tipoInscricao>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_cadastrar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_cadastrar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::cadastrarAction',));
                    }
                    not_fu_inscricao_cadastrar:

                    // fu_inscricao_consultar
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_consultar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_consultar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::consultarAction',));
                    }
                    not_fu_inscricao_consultar:

                    // fu_inscricao_imprimirComprovante
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/imprimir/comprovante$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_imprimirComprovante;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_imprimirComprovante')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::imprimirComprovanteAction',));
                    }
                    not_fu_inscricao_imprimirComprovante:

                    // fu_inscricao_alterar
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_alterar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_alterar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::alterarAction',));
                    }
                    not_fu_inscricao_alterar:

                    // fu_inscricao_imprimir
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/imprimir/cadastro$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_imprimir;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_imprimir')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::imprimirAction',));
                    }
                    not_fu_inscricao_imprimir:

                    // fu_inscricao_cancelar
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/cancelar$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_cancelar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_cancelar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::cancelarAction',));
                    }
                    not_fu_inscricao_cancelar:

                    // fu_inscricao_imprimirTermoDesistencia
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/imprimir/termo\\-desistencia$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_imprimirTermoDesistencia;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_imprimirTermoDesistencia')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::imprimirTermoDesistenciaAction',));
                    }
                    not_fu_inscricao_imprimirTermoDesistencia:

                    // fu_inscricao_incluirTelefone
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/telefones/incluir$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_incluirTelefone;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_incluirTelefone')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::incluirTelefoneAction',));
                    }
                    not_fu_inscricao_incluirTelefone:

                    // fu_inscricao_excluirTelefone
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/telefones/excluir/(?P<telefone>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_excluirTelefone;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_excluirTelefone')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::excluirTelefoneAction',));
                    }
                    not_fu_inscricao_excluirTelefone:

                    // fu_inscricao_exibirHistorico
                    if (preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/historico$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_exibirHistorico;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_exibirHistorico')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::exibirHistoricoAction',));
                    }
                    not_fu_inscricao_exibirHistorico:

                }

                if (0 === strpos($pathinfo, '/fu/admin/ordem-judicial')) {
                    // fu_inscricao_formOrdemJudicial
                    if ($pathinfo === '/fu/admin/ordem-judicial') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_inscricao_formOrdemJudicial;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::formOrdemJudicialAction',  '_route' => 'fu_inscricao_formOrdemJudicial',);
                    }
                    not_fu_inscricao_formOrdemJudicial:

                    // fu_inscricao_cadastrarOrdemJudicial
                    if ($pathinfo === '/fu/admin/ordem-judicial') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_inscricao_cadastrarOrdemJudicial;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::cadastrarOrdemJudicialAction',  '_route' => 'fu_inscricao_cadastrarOrdemJudicial',);
                    }
                    not_fu_inscricao_cadastrarOrdemJudicial:

                }

                // fu_inscricao_incluirEvento
                if (0 === strpos($pathinfo, '/fu/admin/inscricao') && preg_match('#^/fu/admin/inscricao/(?P<inscricao>[^/]++)/eventos/incluir$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fu_inscricao_incluirEvento;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_incluirEvento')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::incluirEventoAction',));
                }
                not_fu_inscricao_incluirEvento:

                // fu_inscricao_excluirEvento
                if (0 === strpos($pathinfo, '/fu/admin/eventos/excluir') && preg_match('#^/fu/admin/eventos/excluir/(?P<evento>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_fu_inscricao_excluirEvento;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_inscricao_excluirEvento')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::excluirEventoAction',));
                }
                not_fu_inscricao_excluirEvento:

                if (0 === strpos($pathinfo, '/fu/admin/fila/re')) {
                    // fu_inscricao_reordenarGeral
                    if ($pathinfo === '/fu/admin/fila/reordenar') {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_fu_inscricao_reordenarGeral;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::reordenarGeralAction',  '_route' => 'fu_inscricao_reordenarGeral',);
                    }
                    not_fu_inscricao_reordenarGeral:

                    // fu_inscricao_redefinirAnosEscolares
                    if ($pathinfo === '/fu/admin/fila/redefinir-turmas') {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_fu_inscricao_redefinirAnosEscolares;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\InscricaoController::redefinirAnosEscolaresAction',  '_route' => 'fu_inscricao_redefinirAnosEscolares',);
                    }
                    not_fu_inscricao_redefinirAnosEscolares:

                }

                if (0 === strpos($pathinfo, '/fu/admin/vaga')) {
                    if (0 === strpos($pathinfo, '/fu/admin/vaga/cadastrar')) {
                        // fu_vaga_formCadastro
                        if ($pathinfo === '/fu/admin/vaga/cadastrar') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_vaga_formCadastro;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::formCadastroAction',  '_route' => 'fu_vaga_formCadastro',);
                        }
                        not_fu_vaga_formCadastro:

                        // fu_vaga_cadastrar
                        if ($pathinfo === '/fu/admin/vaga/cadastrar') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_vaga_cadastrar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::cadastrarAction',  '_route' => 'fu_vaga_cadastrar',);
                        }
                        not_fu_vaga_cadastrar:

                    }

                    if (0 === strpos($pathinfo, '/fu/admin/vaga/pesquisa')) {
                        // fu_vaga_formPesquisa
                        if ($pathinfo === '/fu/admin/vaga/pesquisa') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_vaga_formPesquisa;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::formPesquisaAction',  '_route' => 'fu_vaga_formPesquisa',);
                        }
                        not_fu_vaga_formPesquisa:

                        // fu_vaga_pesquisar
                        if ($pathinfo === '/fu/admin/vaga/pesquisa') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_vaga_pesquisar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::pesquisarAction',  '_route' => 'fu_vaga_pesquisar',);
                        }
                        not_fu_vaga_pesquisar:

                    }

                    // fu_vaga_preencher
                    if (preg_match('#^/fu/admin/vaga/(?P<vaga>[^/]++)/preencher$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_vaga_preencher;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_vaga_preencher')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::preencherAction',));
                    }
                    not_fu_vaga_preencher:

                    // fu_vaga_formGerencia
                    if (preg_match('#^/fu/admin/vaga/(?P<vaga>[^/]++)/atualizar$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_vaga_formGerencia;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_vaga_formGerencia')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::formGerenciaAction',));
                    }
                    not_fu_vaga_formGerencia:

                    // fu_vaga_atualizar
                    if (preg_match('#^/fu/admin/vaga/(?P<vaga>[^/]++)/atualizar/(?P<status>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_vaga_atualizar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_vaga_atualizar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::atualizarAction',));
                    }
                    not_fu_vaga_atualizar:

                    // fu_vaga_cancelar
                    if (preg_match('#^/fu/admin/vaga/(?P<vaga>[^/]++)/cancelar$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_vaga_cancelar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_vaga_cancelar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::cancelarAction',));
                    }
                    not_fu_vaga_cancelar:

                    // fu_vaga_reverterChamada
                    if (preg_match('#^/fu/admin/vaga/(?P<vaga>[^/]++)/reverter\\-chamada$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_vaga_reverterChamada;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_vaga_reverterChamada')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\VagaController::reverterChamadaAction',));
                    }
                    not_fu_vaga_reverterChamada:

                }

                if (0 === strpos($pathinfo, '/fu/admin/movimentacao-interna')) {
                    if (0 === strpos($pathinfo, '/fu/admin/movimentacao-interna/pesquisa')) {
                        // fu_movimentacaoInterna_formPesquisa
                        if ($pathinfo === '/fu/admin/movimentacao-interna/pesquisa') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_movimentacaoInterna_formPesquisa;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::formPesquisaAction',  '_route' => 'fu_movimentacaoInterna_formPesquisa',);
                        }
                        not_fu_movimentacaoInterna_formPesquisa:

                        // fu_movimentacaoInterna_pesquisar
                        if ($pathinfo === '/fu/admin/movimentacao-interna/pesquisa') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_movimentacaoInterna_pesquisar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::pesquisarAction',  '_route' => 'fu_movimentacaoInterna_pesquisar',);
                        }
                        not_fu_movimentacaoInterna_pesquisar:

                    }

                    // fu_movimentacaoInterna_visualizar
                    if (preg_match('#^/fu/admin/movimentacao\\-interna/(?P<movimentacao>[^/]++)/detalhes$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_movimentacaoInterna_visualizar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_movimentacaoInterna_visualizar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::visualizarAction',));
                    }
                    not_fu_movimentacaoInterna_visualizar:

                    // fu_movimentacaoInterna_imprimirPeriodo
                    if ($pathinfo === '/fu/admin/movimentacao-interna/imprimir-periodo') {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_movimentacaoInterna_imprimirPeriodo;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::imprimirPeriodoAction',  '_route' => 'fu_movimentacaoInterna_imprimirPeriodo',);
                    }
                    not_fu_movimentacaoInterna_imprimirPeriodo:

                    if (0 === strpos($pathinfo, '/fu/admin/movimentacao-interna/mover-fila')) {
                        // fu_movimentacaoInterna_formCadastro
                        if ($pathinfo === '/fu/admin/movimentacao-interna/mover-fila') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_movimentacaoInterna_formCadastro;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::formMovimentacaoFilaAction',  '_route' => 'fu_movimentacaoInterna_formCadastro',);
                        }
                        not_fu_movimentacaoInterna_formCadastro:

                        // fu_movimentacaoInterna_cadastrar
                        if ($pathinfo === '/fu/admin/movimentacao-interna/mover-fila') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_movimentacaoInterna_cadastrar;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::moverFilaAction',  '_route' => 'fu_movimentacaoInterna_cadastrar',);
                        }
                        not_fu_movimentacaoInterna_cadastrar:

                    }

                    // fu_movimentacaoInterna_formMovimentacaoVaga
                    if (preg_match('#^/fu/admin/movimentacao\\-interna/(?P<vaga>[^/]++)/mover\\-vaga$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_movimentacaoInterna_formMovimentacaoVaga;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_movimentacaoInterna_formMovimentacaoVaga')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::formMovimentacaoVagaAction',));
                    }
                    not_fu_movimentacaoInterna_formMovimentacaoVaga:

                    // fu_movimentacaoInterna_moverVaga
                    if (preg_match('#^/fu/admin/movimentacao\\-interna/(?P<vaga>[^/]++)/mover\\-vaga$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_movimentacaoInterna_moverVaga;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_movimentacaoInterna_moverVaga')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\MovimentacaoInternaController::moverVagaAction',));
                    }
                    not_fu_movimentacaoInterna_moverVaga:

                }

                if (0 === strpos($pathinfo, '/fu/admin/unidade-escolar')) {
                    // fu_unidade_escolar_listar
                    if ($pathinfo === '/fu/admin/unidade-escolar/listar') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_unidade_escolar_listar;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\UnidadeEscolarController::listarAction',  '_route' => 'fu_unidade_escolar_listar',);
                    }
                    not_fu_unidade_escolar_listar:

                    // fu_unidade_escolar_formAlteracao
                    if (preg_match('#^/fu/admin/unidade\\-escolar/(?P<unidade>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_unidade_escolar_formAlteracao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_unidade_escolar_formAlteracao')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\UnidadeEscolarController::formAlteracaoAction',));
                    }
                    not_fu_unidade_escolar_formAlteracao:

                    // fu_unidade_escolar_alterar
                    if (preg_match('#^/fu/admin/unidade\\-escolar/(?P<unidade>[^/]++)/alterar$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_fu_unidade_escolar_alterar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'fu_unidade_escolar_alterar')), array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\UnidadeEscolarController::alterarAction',));
                    }
                    not_fu_unidade_escolar_alterar:

                }

                if (0 === strpos($pathinfo, '/fu/admin/relatorio')) {
                    if (0 === strpos($pathinfo, '/fu/admin/relatorio/inscricoes')) {
                        // fu_relatorio_formRelatorioInscricoes
                        if ($pathinfo === '/fu/admin/relatorio/inscricoes') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_relatorio_formRelatorioInscricoes;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\RelatorioController::formRelatorioInscricoesAction',  '_route' => 'fu_relatorio_formRelatorioInscricoes',);
                        }
                        not_fu_relatorio_formRelatorioInscricoes:

                        // fu_relatorio_gerarRelatorioInscricoes
                        if ($pathinfo === '/fu/admin/relatorio/inscricoes') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_relatorio_gerarRelatorioInscricoes;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\RelatorioController::gerarRelatorioInscricoesAction',  '_route' => 'fu_relatorio_gerarRelatorioInscricoes',);
                        }
                        not_fu_relatorio_gerarRelatorioInscricoes:

                    }

                    if (0 === strpos($pathinfo, '/fu/admin/relatorio/vagas')) {
                        // fu_relatorio_formRelatorioVagas
                        if ($pathinfo === '/fu/admin/relatorio/vagas') {
                            if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                                $allow = array_merge($allow, array('GET', 'HEAD'));
                                goto not_fu_relatorio_formRelatorioVagas;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\RelatorioController::formRelatorioVagasAction',  '_route' => 'fu_relatorio_formRelatorioVagas',);
                        }
                        not_fu_relatorio_formRelatorioVagas:

                        // fu_relatorio_gerarRelatorioVagas
                        if ($pathinfo === '/fu/admin/relatorio/vagas') {
                            if ($this->context->getMethod() != 'POST') {
                                $allow[] = 'POST';
                                goto not_fu_relatorio_gerarRelatorioVagas;
                            }

                            return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\RelatorioController::gerarRelatorioVagasAction',  '_route' => 'fu_relatorio_gerarRelatorioVagas',);
                        }
                        not_fu_relatorio_gerarRelatorioVagas:

                    }

                    // fu_relatorio_gerarPrevisaoFila
                    if ($pathinfo === '/fu/admin/relatorio/previsao') {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_fu_relatorio_gerarPrevisaoFila;
                        }

                        return array (  '_controller' => 'SME\\FilaUnicaBundle\\Controller\\RelatorioController::gerarPrevisaoFilaAction',  '_route' => 'fu_relatorio_gerarPrevisaoFila',);
                    }
                    not_fu_relatorio_gerarPrevisaoFila:

                }

            }

        }

        if (0 === strpos($pathinfo, '/protocolo')) {
            // protocolo_servidor_listar
            if (0 === strpos($pathinfo, '/protocolo/meus-protocolos') && preg_match('#^/protocolo/meus\\-protocolos/(?P<categoria>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_protocolo_servidor_listar;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_servidor_listar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ServidorController::listarAction',));
            }
            not_protocolo_servidor_listar:

            // protocolo_servidor_visualizar
            if (0 === strpos($pathinfo, '/protocolo/consultar') && preg_match('#^/protocolo/consultar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_protocolo_servidor_visualizar;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_servidor_visualizar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ServidorController::visualizarAction',));
            }
            not_protocolo_servidor_visualizar:

            if (0 === strpos($pathinfo, '/protocolo/i')) {
                if (0 === strpos($pathinfo, '/protocolo/incluir')) {
                    // protocolo_servidor_incluirSolicitacao
                    if (preg_match('#^/protocolo/incluir/(?P<solicitacao>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_protocolo_servidor_incluirSolicitacao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_servidor_incluirSolicitacao')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ServidorController::incluirSolicitacaoAction',));
                    }
                    not_protocolo_servidor_incluirSolicitacao:

                    // protocolo_servidor_formSolicitacao
                    if ($pathinfo === '/protocolo/incluir') {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_protocolo_servidor_formSolicitacao;
                        }

                        return array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ServidorController::formSolicitacaoAction',  '_route' => 'protocolo_servidor_formSolicitacao',);
                    }
                    not_protocolo_servidor_formSolicitacao:

                }

                // protocolo_servidor_imprimir
                if (0 === strpos($pathinfo, '/protocolo/imprimir') && preg_match('#^/protocolo/imprimir/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_protocolo_servidor_imprimir;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_servidor_imprimir')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ServidorController::imprimirAction',));
                }
                not_protocolo_servidor_imprimir:

            }

            if (0 === strpos($pathinfo, '/protocolo/public')) {
                // protocolo_api_getSolicitacoes
                if ($pathinfo === '/protocolo/public/solicitacao') {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_protocolo_api_getSolicitacoes;
                    }

                    return array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\PublicController::getRequerimentosAction',  '_route' => 'protocolo_api_getSolicitacoes',);
                }
                not_protocolo_api_getSolicitacoes:

                if (0 === strpos($pathinfo, '/protocolo/public/protocolo')) {
                    // protocolo_api_postProtocolo
                    if (preg_match('#^/protocolo/public/protocolo/(?P<solicitacao>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_protocolo_api_postProtocolo;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_api_postProtocolo')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\PublicController::postProtocoloAction',));
                    }
                    not_protocolo_api_postProtocolo:

                    // protocolo_api_getDocumento
                    if (preg_match('#^/protocolo/public/protocolo/(?P<protocolo>[^/]++)/pdf$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_protocolo_api_getDocumento;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_api_getDocumento')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\PublicController::getDocumentoAction',));
                    }
                    not_protocolo_api_getDocumento:

                }

            }

            if (0 === strpos($pathinfo, '/protocolo/admin')) {
                if (0 === strpos($pathinfo, '/protocolo/admin/pesquisar')) {
                    // protocolo_admin_formPesquisa
                    if (preg_match('#^/protocolo/admin/pesquisar/(?P<categoria>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_protocolo_admin_formPesquisa;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_formPesquisa')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::formPesquisaAction',));
                    }
                    not_protocolo_admin_formPesquisa:

                    // protocolo_admin_pesquisar
                    if (preg_match('#^/protocolo/admin/pesquisar/(?P<categoria>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_protocolo_admin_pesquisar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_pesquisar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::pesquisarAction',));
                    }
                    not_protocolo_admin_pesquisar:

                }

                // protocolo_admin_consultarRequerente
                if (0 === strpos($pathinfo, '/protocolo/admin/consultar-requerente') && preg_match('#^/protocolo/admin/consultar\\-requerente/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_protocolo_admin_consultarRequerente;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_consultarRequerente')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::consultarRequerenteAction',));
                }
                not_protocolo_admin_consultarRequerente:

                // protocolo_admin_visualizar
                if (0 === strpos($pathinfo, '/protocolo/admin/visualizar') && preg_match('#^/protocolo/admin/visualizar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_protocolo_admin_visualizar;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_visualizar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::VisualizarAction',));
                }
                not_protocolo_admin_visualizar:

                // protocolo_admin_receber
                if (0 === strpos($pathinfo, '/protocolo/admin/receber') && preg_match('#^/protocolo/admin/receber/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_protocolo_admin_receber;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_receber')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::receberAction',));
                }
                not_protocolo_admin_receber:

                // protocolo_admin_tomarPosse
                if (0 === strpos($pathinfo, '/protocolo/admin/tomar-posse') && preg_match('#^/protocolo/admin/tomar\\-posse/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_protocolo_admin_tomarPosse;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_tomarPosse')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::tomarPosseAction',));
                }
                not_protocolo_admin_tomarPosse:

                if (0 === strpos($pathinfo, '/protocolo/admin/atualizar')) {
                    // protocolo_admin_formAtualizacao
                    if (preg_match('#^/protocolo/admin/atualizar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_protocolo_admin_formAtualizacao;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_formAtualizacao')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::formAtualizacaoAction',));
                    }
                    not_protocolo_admin_formAtualizacao:

                    // protocolo_admin_atualizar
                    if (preg_match('#^/protocolo/admin/atualizar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_protocolo_admin_atualizar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_atualizar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::atualizarAction',));
                    }
                    not_protocolo_admin_atualizar:

                }

                if (0 === strpos($pathinfo, '/protocolo/admin/encaminhar')) {
                    // protocolo_admin_formEncaminhamento
                    if (preg_match('#^/protocolo/admin/encaminhar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_protocolo_admin_formEncaminhamento;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_formEncaminhamento')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::formEncaminhamentoAction',));
                    }
                    not_protocolo_admin_formEncaminhamento:

                    // protocolo_admin_encaminhar
                    if (preg_match('#^/protocolo/admin/encaminhar/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                        if ($this->context->getMethod() != 'POST') {
                            $allow[] = 'POST';
                            goto not_protocolo_admin_encaminhar;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_encaminhar')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::encaminharAction',));
                    }
                    not_protocolo_admin_encaminhar:

                }

                // protocolo_admin_cancelarEncaminhamento
                if (0 === strpos($pathinfo, '/protocolo/admin/cancelar-encaminhamento') && preg_match('#^/protocolo/admin/cancelar\\-encaminhamento/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if ($this->context->getMethod() != 'POST') {
                        $allow[] = 'POST';
                        goto not_protocolo_admin_cancelarEncaminhamento;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_cancelarEncaminhamento')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::cancelarEncaminhamentoAction',));
                }
                not_protocolo_admin_cancelarEncaminhamento:

                // protocolo_admin_imprimir
                if (0 === strpos($pathinfo, '/protocolo/admin/imprimir') && preg_match('#^/protocolo/admin/imprimir/(?P<protocolo>[^/]++)$#s', $pathinfo, $matches)) {
                    if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                        $allow = array_merge($allow, array('GET', 'HEAD'));
                        goto not_protocolo_admin_imprimir;
                    }

                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_imprimir')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\ProtocoloController::imprimirAction',));
                }
                not_protocolo_admin_imprimir:

                if (0 === strpos($pathinfo, '/protocolo/admin/encaminhamentos')) {
                    // protocolo_admin_listarEncaminhamentos
                    if (preg_match('#^/protocolo/admin/encaminhamentos/(?P<categoria>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'HEAD'));
                            goto not_protocolo_admin_listarEncaminhamentos;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_listarEncaminhamentos')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\EncaminhamentoController::listarAction',));
                    }
                    not_protocolo_admin_listarEncaminhamentos:

                    // protocolo_admin_aceitarEncaminhamento
                    if (0 === strpos($pathinfo, '/protocolo/admin/encaminhamentos/aceitar') && preg_match('#^/protocolo/admin/encaminhamentos/aceitar/(?P<encaminhamento>[^/]++)$#s', $pathinfo, $matches)) {
                        if (!in_array($this->context->getMethod(), array('GET', 'POST', 'HEAD'))) {
                            $allow = array_merge($allow, array('GET', 'POST', 'HEAD'));
                            goto not_protocolo_admin_aceitarEncaminhamento;
                        }

                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'protocolo_admin_aceitarEncaminhamento')), array (  '_controller' => 'SME\\ProtocoloBundle\\Controller\\EncaminhamentoController::aceitarAction',));
                    }
                    not_protocolo_admin_aceitarEncaminhamento:

                }

            }

        }

        if (0 === strpos($pathinfo, '/suporte')) {
            // suporte_index
            if (rtrim($pathinfo, '/') === '/suporte') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'suporte_index');
                }

                return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\IndexController::indexAction',  '_route' => 'suporte_index',);
            }

            if (0 === strpos($pathinfo, '/suporte/chamado')) {
                // suporte_chamado_pesquisar
                if ($pathinfo === '/suporte/chamado/pesquisar') {
                    return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\ChamadoController::pesquisarAction',  '_route' => 'suporte_chamado_pesquisar',);
                }

                // suporte_chamado_cadastrar
                if ($pathinfo === '/suporte/chamado/cadastrar') {
                    return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\ChamadoController::cadastrarAction',  '_route' => 'suporte_chamado_cadastrar',);
                }

                // suporte_chamado_gerenciar
                if (preg_match('#^/suporte/chamado/(?P<chamado>[^/]++)/gerenciar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_chamado_gerenciar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\ChamadoController::gerenciarAction',));
                }

                // suporte_chamado_imprimir
                if (preg_match('#^/suporte/chamado/(?P<chamado>[^/]++)/imprimir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_chamado_imprimir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\ChamadoController::imprimirAction',));
                }

            }

            if (0 === strpos($pathinfo, '/suporte/admin/chamado')) {
                // suporte_atividade_cadastrar
                if (preg_match('#^/suporte/admin/chamado/(?P<chamado>[^/]++)/atividades/cadastrar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_atividade_cadastrar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\AtividadeController::cadastrarAction',));
                }

                // suporte_atividade_excluir
                if (preg_match('#^/suporte/admin/chamado/(?P<chamado>[^/]++)/atividades/(?P<atividade>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_atividade_excluir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\AtividadeController::excluirAction',));
                }

            }

            if (0 === strpos($pathinfo, '/suporte/chamado')) {
                // suporte_anotacao_cadastrar
                if (preg_match('#^/suporte/chamado/(?P<chamado>[^/]++)/anotacoes/cadastrar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_anotacao_cadastrar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\AnotacaoController::cadastrarAction',));
                }

                // suporte_anotacao_excluir
                if (preg_match('#^/suporte/chamado/(?P<chamado>[^/]++)/anotacoes/(?P<anotacao>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_anotacao_excluir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\AnotacaoController::excluirAction',));
                }

            }

            if (0 === strpos($pathinfo, '/suporte/admin/categoria')) {
                // suporte_categoria_pesquisar
                if ($pathinfo === '/suporte/admin/categoria/pesquisar') {
                    return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::pesquisarAction',  '_route' => 'suporte_categoria_pesquisar',);
                }

                // suporte_categoria_cadastrar
                if ($pathinfo === '/suporte/admin/categoria/cadastrar') {
                    return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::cadastrarAction',  '_route' => 'suporte_categoria_cadastrar',);
                }

                // suporte_categoria_atualizar
                if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/atualizar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_atualizar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::atualizarAction',));
                }

                // suporte_categoria_editar
                if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/editar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_editar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::editarAction',));
                }

                // suporte_categoria_excluir
                if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_excluir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::excluirAction',));
                }

            }

            // suporte_categoria_gerarCombo
            if ($pathinfo === '/suporte/categoria/combobox') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_suporte_categoria_gerarCombo;
                }

                return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::gerarComboAction',  '_route' => 'suporte_categoria_gerarCombo',);
            }
            not_suporte_categoria_gerarCombo:

            if (0 === strpos($pathinfo, '/suporte/admin')) {
                if (0 === strpos($pathinfo, '/suporte/admin/categoria')) {
                    // suporte_api_categoria_getSubcategorias
                    if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/listar\\-filhas$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_api_categoria_getSubcategorias')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\CategoriaController::getSubcategoriasAction',));
                    }

                    // suporte_categoria_tag_listar
                    if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/tags$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_tag_listar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\TagController::listarAction',));
                    }

                    // suporte_categoria_tag_cadastrar
                    if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/tags/cadastrar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_tag_cadastrar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\TagController::cadastrarAction',));
                    }

                    // suporte_categoria_tag_excluir
                    if (preg_match('#^/suporte/admin/categoria/(?P<categoria>[^/]++)/tags/(?P<tag>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_categoria_tag_excluir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\TagController::excluirAction',));
                    }

                }

                if (0 === strpos($pathinfo, '/suporte/admin/equipe')) {
                    // suporte_equipe_pesquisar
                    if ($pathinfo === '/suporte/admin/equipe/pesquisar') {
                        return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::pesquisarAction',  '_route' => 'suporte_equipe_pesquisar',);
                    }

                    // suporte_equipe_cadastrar
                    if ($pathinfo === '/suporte/admin/equipe/cadastrar') {
                        return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::cadastrarAction',  '_route' => 'suporte_equipe_cadastrar',);
                    }

                    // suporte_equipe_atualizar
                    if (preg_match('#^/suporte/admin/equipe/(?P<equipe>[^/]++)/atualizar$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_equipe_atualizar')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::atualizarAction',));
                    }

                    // suporte_equipe_atualizarAjax
                    if (preg_match('#^/suporte/admin/equipe/(?P<equipe>[^/]++)/atualizar/ajax$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_equipe_atualizarAjax')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::atualizarAjaxAction',));
                    }

                    // suporte_equipe_adicionarIntegrante
                    if (preg_match('#^/suporte/admin/equipe/(?P<equipe>[^/]++)/integrantes/add$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_equipe_adicionarIntegrante')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::adicionarIntegranteAction',));
                    }

                    // suporte_equipe_buscarIntegrante
                    if ($pathinfo === '/suporte/admin/equipe/integrantes/buscar') {
                        return array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::buscarPessoaAction',  '_route' => 'suporte_equipe_buscarIntegrante',);
                    }

                    // suporte_equipe_excluirIntegrante
                    if (preg_match('#^/suporte/admin/equipe/(?P<equipe>[^/]++)/integrantes/(?P<integrante>[^/]++)/remove$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_equipe_excluirIntegrante')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::excluirIntegranteAction',));
                    }

                    // suporte_equipe_excluir
                    if (preg_match('#^/suporte/admin/equipe/(?P<equipe>[^/]++)/excluir$#s', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, array('_route' => 'suporte_equipe_excluir')), array (  '_controller' => 'SME\\SuporteTecnicoBundle\\Controller\\EquipeController::excluirAction',));
                    }

                }

            }

        }

        if (0 === strpos($pathinfo, '/user/public')) {
            // servicos_publicos
            if ($pathinfo === '/user/public/servicos-publicos') {
                return array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::servicosPublicosAction',  '_route' => 'servicos_publicos',);
            }

            // consulta_fila_unica
            if ($pathinfo === '/user/public/consulta-fila-unica') {
                return array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::consultaFilaUnicaAction',  '_route' => 'consulta_fila_unica',);
            }

            // requerimento_externo
            if ($pathinfo === '/user/public/requerimento-externo') {
                return array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::requerimentoExternoAction',  '_route' => 'requerimento_externo',);
            }

            if (0 === strpos($pathinfo, '/user/public/formacao-externa')) {
                // formacao_externa
                if ($pathinfo === '/user/public/formacao-externa') {
                    return array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::formacaoExternaAction',  '_route' => 'formacao_externa',);
                }

                // formacao_externa_matricula
                if (preg_match('#^/user/public/formacao\\-externa/(?P<formacao>[^/]++)/matricula$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'formacao_externa_matricula')), array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::formacaoExternaMatriculaAction',));
                }

                // formacao_externa_consulta
                if ($pathinfo === '/user/public/formacao-externa/consulta') {
                    return array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::buscaCertificadosAction',  '_route' => 'formacao_externa_consulta',);
                }

                // formacao_externa_imprimir_certificado
                if (preg_match('#^/user/public/formacao\\-externa/(?P<matricula>[^/]++)/imprimir\\-certificado$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'formacao_externa_imprimir_certificado')), array (  '_controller' => 'SME\\PublicBundle\\Controller\\PublicController::imprimirCertificadoAction',));
                }

            }

        }

        if (0 === strpos($pathinfo, '/presenca')) {
            // presenca_index
            if ($pathinfo === '/presenca/controlepresenca') {
                return array (  '_controller' => 'SME\\PresencaBundle\\Controller\\PresencaController::indexAction',  '_route' => 'presenca_index',);
            }

            // presenca_remover
            if (0 === strpos($pathinfo, '/presenca/presenca') && preg_match('#^/presenca/presenca/(?P<id>[^/]++)/remover$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'presenca_remover')), array (  '_controller' => 'SME\\PresencaBundle\\Controller\\PresencaController::removerAction',));
            }

            // presenca_busca
            if ($pathinfo === '/presenca/buscapresenca') {
                return array (  '_controller' => 'SME\\PresencaBundle\\Controller\\PresencaController::buscaAction',  '_route' => 'presenca_busca',);
            }

        }

        if (0 === strpos($pathinfo, '/questionario')) {
            // questionario_index
            if (rtrim($pathinfo, '/') === '/questionario') {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'questionario_index');
                }

                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::indexAction',  '_route' => 'questionario_index',);
            }

            // questionario_editar
            if (preg_match('#^/questionario/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'questionario_editar')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::editarQuestionariosAction',));
            }

            // questionario_adicionar_perguntas
            if ($pathinfo === '/questionario/add/perguntas') {
                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::adicionarPerguntasQuestionarioAction',  '_route' => 'questionario_adicionar_perguntas',);
            }

            // questionario_adicionar_perguntas_quick
            if ($pathinfo === '/questionario/questionario/quickadd/perguntas') {
                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::quickAddPerguntasQuestionarioAction',  '_route' => 'questionario_adicionar_perguntas_quick',);
            }

            // questionario_remover_perguntas
            if ($pathinfo === '/questionario/remover/perguntas') {
                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::removerPerguntasQuestionarioAction',  '_route' => 'questionario_remover_perguntas',);
            }

            // questionario_remover
            if (preg_match('#^/questionario/(?P<id>[^/]++)/remover$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'questionario_remover')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::removerQuestionarioAction',));
            }

            // perguntas_index
            if (0 === strpos($pathinfo, '/questionario/categoria') && preg_match('#^/questionario/categoria/(?P<id>[^/]++)/perguntas/lista/?$#s', $pathinfo, $matches)) {
                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'perguntas_index');
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'perguntas_index')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::perguntasAction',));
            }

            if (0 === strpos($pathinfo, '/questionario/perguntas')) {
                // perguntas_cadastro
                if ($pathinfo === '/questionario/perguntas/cadastrar') {
                    return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::cadastrarPerguntasAction',  '_route' => 'perguntas_cadastro',);
                }

                // perguntas_editar
                if (0 === strpos($pathinfo, '/questionario/perguntas/editar') && preg_match('#^/questionario/perguntas/editar/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'perguntas_editar')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::editarPerguntasAction',));
                }

                // perguntas_remover
                if (0 === strpos($pathinfo, '/questionario/perguntas/remover') && preg_match('#^/questionario/perguntas/remover/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'perguntas_remover')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::removerPerguntasAction',));
                }

            }

            // perguntas_busca
            if ($pathinfo === '/questionario/busca/perguntas') {
                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::buscaPerguntasAction',  '_route' => 'perguntas_busca',);
            }

            // questionarios_listados
            if ($pathinfo === '/questionario/user/listados') {
                return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::questionariosListadosAction',  '_route' => 'questionarios_listados',);
            }

            // questionarios_responder
            if (preg_match('#^/questionario/(?P<id>[^/]++)/responder$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'questionarios_responder')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::questionarioResponderAction',));
            }

            // questionarios_salvar_respostas
            if (preg_match('#^/questionario/(?P<id>[^/]++)/salvar/respostas$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'questionarios_salvar_respostas')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::questionarioSalvarRespostasAction',));
            }

            if (0 === strpos($pathinfo, '/questionario/respostas')) {
                // questionarios_ver_respostas
                if ($pathinfo === '/questionario/respostas/ver') {
                    return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::respostasPorQuestionarioAction',  '_route' => 'questionarios_ver_respostas',);
                }

                // questionarios_busca_respostas
                if ($pathinfo === '/questionario/respostas/busca') {
                    return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::buscarRespostasAction',  '_route' => 'questionarios_busca_respostas',);
                }

            }

            // questionarios_mostrar_respostas
            if (preg_match('#^/questionario/(?P<id>[^/]++)/respostas/mostrar/(?P<userId>[^/]++)$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'questionarios_mostrar_respostas')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::mostrarRespostasAction',));
            }

            if (0 === strpos($pathinfo, '/questionario/categorias')) {
                // categorias_index
                if ($pathinfo === '/questionario/categorias/lista') {
                    return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::categoriasAction',  '_route' => 'categorias_index',);
                }

                // categorias_adicionar
                if ($pathinfo === '/questionario/categorias/adicionar') {
                    return array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::categoriasAdicionarAction',  '_route' => 'categorias_adicionar',);
                }

                // categorias_editar
                if (preg_match('#^/questionario/categorias/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'categorias_editar')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::editarCategoriasAction',));
                }

                // categorias_remover
                if (preg_match('#^/questionario/categorias/(?P<id>[^/]++)/remover$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'categorias_remover')), array (  '_controller' => 'SME\\QuestionarioBundle\\Controller\\QuestionarioController::removerCategoriasAction',));
                }

            }

        }

        if (0 === strpos($pathinfo, '/estagio')) {
            if (0 === strpos($pathinfo, '/estagio/public')) {
                // public_consulta_estagio
                if ($pathinfo === '/estagio/public/consulta-estagio') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::consultaEstagioAction',  '_route' => 'public_consulta_estagio',);
                }

                // public_vaga_estagio
                if ($pathinfo === '/estagio/public/vaga-estagio') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::solicitarEstagioAction',  '_route' => 'public_vaga_estagio',);
                }

                // public_solicitar_usuario
                if ($pathinfo === '/estagio/public/solicitar-usuario') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::solicitarUsuarioAction',  '_route' => 'public_solicitar_usuario',);
                }

            }

            if (0 === strpos($pathinfo, '/estagio/vagas')) {
                // vagas_estagio
                if ($pathinfo === '/estagio/vagas-estagio') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::listarVagasAction',  '_route' => 'vagas_estagio',);
                }

                // adicionar_vaga
                if ($pathinfo === '/estagio/vagas/add') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::addVagasAction',  '_route' => 'adicionar_vaga',);
                }

                // editar_vaga
                if (0 === strpos($pathinfo, '/estagio/vagas/edit') && preg_match('#^/estagio/vagas/edit/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'editar_vaga')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::editarVagasAction',));
                }

                // remover_vaga
                if (0 === strpos($pathinfo, '/estagio/vagas/remover') && preg_match('#^/estagio/vagas/remover/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'remover_vaga')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::removerVagasAction',));
                }

            }

            if (0 === strpos($pathinfo, '/estagio/orientadores')) {
                // listar_orientadores
                if ($pathinfo === '/estagio/orientadores') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::orientadoresAction',  '_route' => 'listar_orientadores',);
                }

                // indeferir_orientadores
                if (preg_match('#^/estagio/orientadores/(?P<id>[^/]++)/indeferir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'indeferir_orientadores')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::indeferirUsuarioAction',));
                }

                // desativar_orientadores
                if (preg_match('#^/estagio/orientadores/(?P<id>[^/]++)/desativar$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'desativar_orientadores')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::desativarUsuarioAction',));
                }

                // deferir_orientadores
                if (preg_match('#^/estagio/orientadores/(?P<id>[^/]++)/deferir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'deferir_orientadores')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::deferirUsuarioAction',));
                }

            }

            // cadastrar_estagiario
            if ($pathinfo === '/estagio/inscrever-estagiario') {
                return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::inscreverEstagiarioAction',  '_route' => 'cadastrar_estagiario',);
            }

            // listar_inscritos
            if (0 === strpos($pathinfo, '/estagio/vagas') && preg_match('#^/estagio/vagas/(?P<id>[^/]++)/inscritos$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'listar_inscritos')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::listarInscritosAction',));
            }

            if (0 === strpos($pathinfo, '/estagio/inscricao')) {
                // indeferir_estagio
                if (preg_match('#^/estagio/inscricao/(?P<id>[^/]++)/indeferir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'indeferir_estagio')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::indeferirEstagioAction',));
                }

                // deferir_estagio
                if (preg_match('#^/estagio/inscricao/(?P<id>[^/]++)/deferir$#s', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, array('_route' => 'deferir_estagio')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::deferirEstagioAction',));
                }

            }

            // buscar_vagas
            if ($pathinfo === '/estagio/buscar-vagas') {
                return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::buscarVagasAction',  '_route' => 'buscar_vagas',);
            }

            // buscar_vagas_geral
            if ($pathinfo === '/estagio/vagas-gerais') {
                return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::buscarVagasGeraisAction',  '_route' => 'buscar_vagas_geral',);
            }

            if (0 === strpos($pathinfo, '/estagio/inscricoes')) {
                // inscricoes_pendentes
                if ($pathinfo === '/estagio/inscricoes/pendentes') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::listarPendentesAction',  '_route' => 'inscricoes_pendentes',);
                }

                // inscritos_escola
                if ($pathinfo === '/estagio/inscricoes/escola') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::deferidosPorEscolaAction',  '_route' => 'inscritos_escola',);
                }

                // inscritos_orientador
                if ($pathinfo === '/estagio/inscricoes/orientador') {
                    return array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::inscritosPorOrientadorAction',  '_route' => 'inscritos_orientador',);
                }

            }

            // remover_estagio
            if (0 === strpos($pathinfo, '/estagio/estagio') && preg_match('#^/estagio/estagio/(?P<id>[^/]++)/remover$#s', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, array('_route' => 'remover_estagio')), array (  '_controller' => 'SME\\EstagioBundle\\Controller\\EstagioController::removerEstagioAction',));
            }

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}

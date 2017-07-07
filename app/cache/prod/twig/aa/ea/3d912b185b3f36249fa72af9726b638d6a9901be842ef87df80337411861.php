<?php

/* menubar.html.twig */
class __TwigTemplate_aaea3d912b185b3f36249fa72af9726b638d6a9901be842ef87df80337411861 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div id=\"menuWrapper\">
    <div id=\"closeMenu\">
        <span class=\"glyphicon glyphicon-remove\"></span>
    </div>
    <ul class=\"menuList\">
        <li class=\"menuItem centerText\"><a label=\"menu1\" class=\"topLink plus\" href=\"#\">Usuário</a>
            <ul class=\"subMenu\" id=\"subMenu1\">
                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
        // line 8
        echo $this->env->getExtension('routing')->getPath("dgp_servidor_listarVinculos");
        echo "\">Página do Servidor</a></li>
               <!-- <li class=\"subMenuPadding\">
                    <a class=\"paddingMenu\" href=\"http://chat.educacao.itajai.sc.gov.br/client.php?locale=pt-br\" target=\"_blank\" 
                       onclick=\"if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 &amp;&amp; window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open(&#039;http://chat.educacao.itajai.sc.gov.br/client.php?locale=pt-br&amp;url=&#039;+&#039;&amp;name=";
        // line 11
        echo twig_escape_filter($this->env, strtr($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "nome"), array(" " => "+")), "html", null, true);
        echo "&amp;email=";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "email"), "html", null, true);
        echo "&#039;, 'mibew', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;\">
                       Atendimento Online
                    </a>
                       
                </li>-->
                ";
        // line 16
        if ($this->env->getExtension('security')->isGranted("ROLE_SUPORTE_USER")) {
            // line 17
            echo "                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            echo $this->env->getExtension('routing')->getPath("suporte_index");
            echo "\">Suporte Técnico</a></li>
                ";
        }
        // line 19
        echo "                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" target=\"_blank\" href=\"http://ead.educacao.itajai.sc.gov.br\">Educline</a></li>
                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" target=\"_blank\" href=\"http://erudio.itajai.sc.gov.br/login_intranet.html?username=";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "username"), "html", null, true);
        echo "&key=";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "password"), "html", null, true);
        echo "\">Erudio</a></li>
                ";
        // line 21
        if ($this->env->getExtension('security')->isGranted("ROLE_QUESTIONARIO")) {
            // line 22
            echo "                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            echo $this->env->getExtension('routing')->getPath("questionarios_ver_respostas");
            echo "\">Questionários (Respostas)</a></li>
                ";
        }
        // line 24
        echo "                ";
        if (($this->env->getExtension('security')->isGranted("ROLE_ORIENTADOR_ESTAGIO") || $this->env->getExtension('security')->isGranted("ROLE_ESTAGIO_ADMIN"))) {
            // line 25
            echo "                    <li class=\"subMenuPadding hasSubmenu\"><a label=\"submenu1\" class=\"topSubLink plus plusSub paddingMenu\" href=\"#\">Estágio</a>
                        <ul class=\"levelsubMenu\" id=\"levelSubMenu1\">
                            ";
            // line 27
            if (($this->env->getExtension('security')->isGranted("ROLE_ORIENTADOR_ESTAGIO") || $this->env->getExtension('security')->isGranted("ROLE_ESTAGIO_ADMIN"))) {
                // line 28
                echo "                                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                echo $this->env->getExtension('routing')->getPath("cadastrar_estagiario");
                echo "\">Inscrever Estagiários</a></li>
                                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 29
                echo $this->env->getExtension('routing')->getPath("inscritos_orientador");
                echo "\">Estagiários Cadastrados</a></li>
                            ";
            }
            // line 31
            echo "                            ";
            if ($this->env->getExtension('security')->isGranted("ROLE_ESTAGIO_ADMIN")) {
                // line 32
                echo "                                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                echo $this->env->getExtension('routing')->getPath("inscricoes_pendentes");
                echo "\">Inscrições Pendentes</a></li>
                                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 33
                echo $this->env->getExtension('routing')->getPath("vagas_estagio");
                echo "\">Vagas</a></li>
                                <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 34
                echo $this->env->getExtension('routing')->getPath("listar_orientadores");
                echo "\">Orientadores</a></li>
                            ";
            }
            // line 36
            echo "                        </ul>
                    </li>
                ";
        }
        // line 39
        echo "            </ul>
        </li>
        ";
        // line 41
        if (($this->env->getExtension('security')->isGranted("ROLE_UNIDADE_ENSINO") || $this->env->getExtension('security')->isGranted("ROLE_UNIDADE_SECRETARIA"))) {
            // line 42
            echo "            <li class=\"menuItem centerText\"><a label=\"menu2\" class=\"topLink plus\" href=\"#\">Unidade</a>
                <ul id=\"subMenu2\" class=\"subMenu\">
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 44
            echo $this->env->getExtension('routing')->getPath("intranet_listarUnidadesEscolares");
            echo "\">Selecionar Unidade</a></li>
                    ";
            // line 45
            if ($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "has", array(0 => "unidade"), "method")) {
                // line 46
                echo "                        ";
                if ($this->env->getExtension('security')->isGranted("ROLE_CEI")) {
                    // line 47
                    echo "                            <li class=\"subMenuPaddingM\"><a class=\"paddingMenu\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("fu_index");
                    echo "\">Fila Única</a></li>
                        ";
                }
                // line 49
                echo "                        <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                echo $this->env->getExtension('routing')->getPath("inscritos_escola");
                echo "\">Estágios Encaminhados</a></li>
                        <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 50
                echo $this->env->getExtension('routing')->getPath("dgp_unidade_listarAlocacoes");
                echo "\">Servidores</a></li>
                        <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 51
                echo $this->env->getExtension('routing')->getPath("suporte_index");
                echo "\">Suporte Técnico</a></li>
                        <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                // line 52
                echo $this->env->getExtension('routing')->getPath("presenca_index");
                echo "\">Controle de Presença</a></li>
                        ";
                // line 53
                if (($this->env->getExtension('security')->isGranted("ROLE_UNIDADE_ENSINO") || $this->env->getExtension('security')->isGranted("ROLE_UNIDADE_SECRETARIA"))) {
                    // line 54
                    echo "                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("questionarios_listados");
                    echo "\">Questionários</a></li>
                        ";
                }
                // line 56
                echo "                    ";
            }
            // line 57
            echo "                </ul>
            </li>
        ";
        }
        // line 60
        echo "        ";
        if ($this->env->getExtension('security')->isGranted("ROLE_DGP_MEMBRO")) {
            // line 61
            echo "            <li class=\"menuItem centerText\"><a label=\"menu3\" class=\"topLink plus\" href=\"#\">DGP</a>
                <ul id=\"subMenu3\" class=\"subMenu\">
                    <li class=\"subMenuPadding hasSubmenu\"><a label=\"submenu1\" class=\"topSubLink plus plusSub paddingMenu\" href=\"#\">Pessoas</a>
                        <ul class=\"levelsubMenu\" id=\"levelSubMenu1\">
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 65
            echo $this->env->getExtension('routing')->getPath("dgp_pessoa_cadastrar");
            echo "\">Cadastro</a></li>
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 66
            echo $this->env->getExtension('routing')->getPath("dgp_pessoa_pesquisar");
            echo "\">Pesquisa</a></li>
                        </ul>
                    </li>
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 69
            echo $this->env->getExtension('routing')->getPath("dgp_entidade_listar");
            echo "\">Entidades</a></li>
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 70
            echo $this->env->getExtension('routing')->getPath("protocolo_admin_pesquisar", array("categoria" => 1));
            echo "\">Requerimentos</a></li>
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 71
            echo $this->env->getExtension('routing')->getPath("dgp_formacao_listar");
            echo "\">Formação Continuada</a></li>
                    <li class=\"subMenuPadding\"> <a class=\"paddingMenu\" href=\"";
            // line 72
            echo $this->env->getExtension('routing')->getPath("dgp_processoAdmissao_pesquisar");
            echo "\">Processo Admissional</a> </li>
                    <li class=\"subMenuPadding\" class=\"hasSubmenu\"><a label=\"submenu2\" class=\"topSubLink plus plusSub paddingMenu\" href=\"#\">CI Geral</a>
                        <ul class=\"levelsubMenu\" id=\"levelSubMenu1\">
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 75
            echo $this->env->getExtension('routing')->getPath("dgp_ciGeral_formPesquisa");
            echo "\">Admissões</a></li>
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 76
            echo $this->env->getExtension('routing')->getPath("dgp_promocao_ciGeral_formPesquisa");
            echo "\">Promoções</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        ";
        }
        // line 82
        echo "        ";
        if (($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO") || $this->env->getExtension('security')->isGranted("ROLE_UNIDADE_ESCOLAR"))) {
            // line 83
            echo "            <li class=\"menuItem centerText \"><a label=\"menu4\" class=\"topLink plus\" href=\"#\">Infantil</a>
                <ul id=\"subMenu4\" class=\"subMenu\">
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 85
            echo $this->env->getExtension('routing')->getPath("fu_index");
            echo "\">Fila Única</a></li>
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 86
            echo $this->env->getExtension('routing')->getPath("presenca_busca");
            echo "\">Controle de Presença</a></li>
                </ul>
            </li>
        ";
        }
        // line 90
        echo "        ";
        if ($this->env->getExtension('security')->isGranted("ROLE_SUPER_ADMIN")) {
            // line 91
            echo "            <li class=\"menuItem centerText endMenu\"><a label=\"menu5\" class=\"topLink plus\" href=\"#\">Administrador</a>
                <ul id=\"subMenu5\" class=\"subMenu\">
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 93
            echo $this->env->getExtension('routing')->getPath("intranet_admin_formPesquisaUsuario");
            echo "\">Usuários</a></li>
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 94
            echo $this->env->getExtension('routing')->getPath("intranet_admin_formPesquisaRole");
            echo "\">Permissões</a></li>
                    <li class=\"subMenuPadding\" class=\"hasSubmenu\"><a label=\"submenu5\" class=\"topSubLink plus plusSub paddingMenu\" href=\"#\">Questionários</a>
                        <ul class=\"levelsubMenu\" id=\"levelSubMenu1\">
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 97
            echo $this->env->getExtension('routing')->getPath("questionario_index");
            echo "\">Criar Questionário</a></li>
                            <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 98
            echo $this->env->getExtension('routing')->getPath("categorias_index");
            echo "\">Categorias de Perguntas</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        ";
        }
        // line 104
        echo "        ";
        if ($this->env->getExtension('security')->isGranted("ROLE_PROMOTORIA")) {
            // line 105
            echo "            <li class=\"menuItem centerText\"><a label=\"menu2\" class=\"topLink plus\" href=\"#\">Promotoria</a>
                <ul id=\"subMenu2\" class=\"subMenu\">
                    <li class=\"subMenuPadding\"><a class=\"paddingMenu\" href=\"";
            // line 107
            echo $this->env->getExtension('routing')->getPath("fu_promotoria_formFila");
            echo "\">Fila Única</a></li>
                </ul>
            </li>
        ";
        }
        // line 111
        echo "        <li class=\"menuSystem centerText\">
            <span class=\"openMenu\"><span id=\"nameLogged\">";
        // line 112
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "nome"), "html", null, true);
        echo "</span> <span style=\" color: #fff;\" class=\"glyphicon glyphicon-off notifyIcon\"></span></span>
            <span class=\"glyphicon glyphicon-comment notification_switch icon_closed\" style=\"cursor: pointer; margin-left: 10px;\"><small class=\"notification_total\"></small></span>
        </li>
    </ul>
</div>

<div class=\"subMenuSystem\">
    <div class=\"userSysMenu\">
        <div class=\"photo\">
            <span class=\"glyphicon glyphicon-user glyphicon-lg\"></span>
        </div>
        <div class=\"fullname\">
            ";
        // line 124
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "nome"), "html", null, true);
        echo "
        </div>
    </div>
    <div class=\"systemOptions\">
        <a href=\"";
        // line 128
        echo $this->env->getExtension('routing')->getPath("intranet_consultarCadastro");
        echo "\">Acessar meu perfil</a>
    </div>
    <div class=\"systemOptions\">
        <a href=\"";
        // line 131
        echo $this->env->getExtension('routing')->getPath("logout");
        echo "\">Sair da Intranet</a>
    </div>
</div>
    
<script type=\"text/javascript\">
    var LHCChatOptionsPage = {};
    LHCChatOptionsPage.opt = {};
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = '//chat.educacao.itajai.sc.gov.br/index.php/chat/getstatusembed';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>";
    }

    public function getTemplateName()
    {
        return "menubar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  311 => 131,  305 => 128,  298 => 124,  283 => 112,  280 => 111,  273 => 107,  269 => 105,  266 => 104,  257 => 98,  253 => 97,  247 => 94,  243 => 93,  239 => 91,  236 => 90,  229 => 86,  225 => 85,  221 => 83,  218 => 82,  209 => 76,  205 => 75,  187 => 69,  181 => 66,  177 => 65,  168 => 60,  163 => 57,  160 => 56,  154 => 54,  152 => 53,  148 => 52,  144 => 51,  140 => 50,  129 => 47,  126 => 46,  124 => 45,  120 => 44,  116 => 42,  114 => 41,  110 => 39,  105 => 36,  100 => 34,  96 => 33,  91 => 32,  83 => 29,  78 => 28,  76 => 27,  72 => 25,  69 => 24,  61 => 21,  52 => 19,  44 => 16,  34 => 11,  28 => 8,  19 => 1,  284 => 105,  274 => 97,  264 => 94,  259 => 93,  250 => 91,  246 => 90,  232 => 79,  228 => 78,  224 => 77,  219 => 75,  215 => 74,  211 => 73,  207 => 72,  203 => 71,  199 => 72,  195 => 71,  191 => 70,  186 => 67,  183 => 66,  178 => 45,  174 => 39,  171 => 61,  165 => 32,  159 => 16,  155 => 15,  151 => 14,  147 => 13,  143 => 12,  139 => 11,  135 => 49,  130 => 9,  127 => 8,  121 => 4,  115 => 118,  113 => 66,  106 => 62,  88 => 31,  86 => 45,  79 => 40,  77 => 38,  68 => 34,  63 => 22,  57 => 28,  55 => 20,  46 => 17,  39 => 18,  37 => 8,  30 => 4,  25 => 1,  29 => 4,  26 => 3,);
    }
}

<?php

/* IntranetBundle:Acesso:login.html.twig */
class __TwigTemplate_b03c5e13e425ac678a62996714dfd3c8cec65ac9937225e6cb4c7d91191a5b92 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::paginaExterna.html.twig");

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::paginaExterna.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_css($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/less/bootstrap.less"), "html", null, true);
        echo "\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/signinMobile.css"), "html", null, true);
        echo "\" media=\"screen and (max-width: 480px)\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/signin.css"), "html", null, true);
        echo "\" media=\"screen and (min-width: 481px)\" />
    <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/fancybox/jquery.fancybox.css"), "html", null, true);
        echo "\" />
";
    }

    // line 11
    public function block_body($context, array $blocks = array())
    {
        // line 12
        echo "    <div id=\"top_menu_access\">
        <img src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/images/login_header_lg.gif"), "html", null, true);
        echo "\" class=\"img-responsive logo\" alt=\"Logo Intranet\" />
    </div>
    <div id=\"login_area\">
        <form id=\"form_login\" action=\"";
        // line 16
        echo $this->env->getExtension('routing')->getPath("login_check");
        echo "\" method=\"post\" class=\"\" role=\"form\">
            <input type=\"text\" id=\"username\" name=\"_username\" value=\"";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["last_username"]) ? $context["last_username"] : $this->getContext($context, "last_username")), "html", null, true);
        echo "\" class=\"form-control cpf loginInput\" placeholder=\"Insira seu CPF\" autofocus />
            <input type=\"password\" id=\"password\" name=\"_password\" class=\"form-control loginInput\" placeholder=\"Digite sua senha\" />
            <button class=\"btn btn-primary enter loginButton\" type=\"submit\">Entrar</button>
            <br />
            Digite seu CPF e Senha para entrar ou <a id=\"link_recovery\" style=\"cursor: pointer;\">solicite uma nova senha.</a>
        </form>
        <form id=\"form_recovery\" style=\"display: none;\" id=\"form_recovery\" action=\"";
        // line 23
        echo $this->env->getExtension('routing')->getPath("intranet_recover_email");
        echo "\" method=\"post\" class=\"\" role=\"form\">
            <input type=\"text\" id=\"username\" name=\"username\" class=\"form-control cpf getEmail loginInput\" placeholder=\"Insira seu CPF\" autofocus />
            <input type=\"text\" label=\"";
        // line 25
        echo $this->env->getExtension('routing')->getPath("intranet_get_email");
        echo "\" id=\"email\" name=\"email\" class=\"form-control setEmail loginInput\" placeholder=\"Informe seu e-mail\" />
            <button class=\"btn btn-primary loginButton\" type=\"submit\">Recuperar</button>
            <br />
            Já sei a minha senha, <a style=\"display: none; cursor: pointer;\" id=\"link_login\">quero entrar no sistema.</a>
        </form>
    </div>
    <div class=\"container\">
        <div id=\"title\" style=\"\">&nbsp;</div>
        <div id=\"tabs\" style=\"margin-top: 1%;\">
            <ul class=\"nav nav-tabs\">
                <li class=\"active menuSigninItem\"><a href=\"#welcome\" data-toggle=\"tab\">Bem-Vindo!</a></li>
                <li class=\"menuSigninItem\"><a href=\"#novousuario\" data-toggle=\"tab\">Novo por aqui?</a></li>
                <li class=\"menuSigninItem\"><a href=\"#filaunica\" data-toggle=\"tab\">Fila Única</a></li>
                <li class=\"menuSigninItem\"><a href=\"#formacao\" data-toggle=\"tab\">Formação Continuada</a></li>
                <li class=\"menuSigninItem\"><a href=\"#certificados\" data-toggle=\"tab\">Certificados</a></li>
                <li class=\"menuSigninItem\"><a href=\"#requerimentos\" data-toggle=\"tab\">Requerimentos</a></li>
                <!-- <li class=\"menuSigninItem\"><a href=\"#publicacoes\" data-toggle=\"tab\">Publicações</a></li> -->
                <li class=\"menuSigninItem\"><a href=\"#estagio\" data-toggle=\"tab\">Estágio</a></li>
            </ul>
            <div class=\"tab-content no-padding\">
                <div class=\"tab-pane active\" id=\"welcome\">
                    <div class=\"container paddingTop\">
                        <div class=\"row\">
                            <div class=\"col-lg-6\" style=\"text-align: justify;\">
                               Bem vindo à <strong>Intranet da Secretaria Municipal de Educação</strong>. Aqui você tem acesso aos serviços públicos, como consultar uma inscrição no Fila Única ou validar um certificado de formação. Caso realize acesso como servidor na parte superior da página, poderá também fazer requerimentos, acessar o ambiente virtual Educline ou inscrever-se em formações continuadas, além de outros recursos.
                            </div>
                            <div class=\"col-lg-6\" style=\"text-align: justify;\">
                                Caso seu usuário <strong>já exista</strong> e você <strong>não lembre ou não tenha recebido</strong> sua senha, não se preocupe!
                                Você pode criar uma senha nova em poucos passos:
                                <br /><br />
                                <ol>
                                    <li>Clique em \"solicite uma nova senha\" abaixo do botão \"Entrar\"</li>
                                    <li>Forneça seu número de CPF e e-mail cadastrado no sistema</li>
                                    <li>Abra a caixa de entrada do seu e-mail e acesse o link indicado</li>
                                    <li>Na página que abrir, insira sua nova senha e salve. Sua senha foi modificada e você está pronto para acessar os recursos do servidor!</li>
                                </ol>
                                <br /><br />
                            </div>
                        </div>
                    </div>
                </div>
                <div class=\"tab-pane\" id=\"novousuario\">
                    ";
        // line 67
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("IntranetBundle:Acesso:firstAccess"));
        echo "
                </div>
                <div class=\"tab-pane\" id=\"filaunica\">
                    ";
        // line 70
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("PublicBundle:Public:consultaFilaUnica"));
        echo "
                </div>
                <div class=\"tab-pane\" id=\"formacao\">
                    ";
        // line 73
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("PublicBundle:Public:formacaoExterna"));
        echo "
                </div>
                <div class=\"tab-pane\" id=\"certificados\">
                    ";
        // line 76
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("PublicBundle:Public:buscaCertificados"));
        echo "
                </div>
                <div class=\"tab-pane\" id=\"requerimentos\">
                    ";
        // line 79
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("PublicBundle:Public:requerimentosExternos"));
        echo "
                </div>
                <div class=\"tab-pane\" id=\"estagio\">
                    ";
        // line 82
        echo $this->env->getExtension('http_kernel')->renderFragment($this->env->getExtension('http_kernel')->controller("EstagioBundle:Estagio:solicitarEstagio"));
        echo "
                </div>
            </div>
        </div>
    </div>
    <div id=\"modalSenha\" class=\"modal fade\" role=\"dialog\" aria-labelledby=\"myModalLabel\" style=\"width: 70%; height: 55%; left: 50%; margin-left: -35%; top: 50%; margin-top: -20%; overflow: hidden; background: #fafafa;\">
        <div class=\"modal-header\">
            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
            <h3 id=\"myModalLabel\">Senha Atual Expirada.</h3>
        </div>
        <div class=\"modal-body\">
            <p>
               Olá, <strong>";
        // line 94
        echo twig_escape_filter($this->env, (isset($context["nomeUsuario"]) ? $context["nomeUsuario"] : $this->getContext($context, "nomeUsuario")), "html", null, true);
        echo "</strong>.<br /><br />
               De acordo com as políticas de segurança da Intranet, sua senha de acesso expirou e precisa ser redefinida.<br />
               Verifique seu e-mail e clique no botão Recuperar e enviaremos um e-mail para a redefinição de senha.<br /><br />
               
               <form style=\"margin-bottom: 15px; margin-top: -15px;\" id=\"form_recovery2\" id=\"form_recovery2\" action=\"";
        // line 98
        echo $this->env->getExtension('routing')->getPath("intranet_recover_email");
        echo "\" method=\"post\" class=\"\" role=\"form\">
                    <input style=\"width: 40%; float: left;\" type=\"hidden\" id=\"username\" name=\"username\" class=\"form-control\" placeholder=\"Insira seu CPF\" value=\"";
        // line 99
        echo twig_escape_filter($this->env, (isset($context["username"]) ? $context["username"] : $this->getContext($context, "username")), "html", null, true);
        echo "\" />
                    <input style=\"width: 40%; float: left;\" type=\"text\" value=\"";
        // line 100
        echo twig_escape_filter($this->env, (isset($context["email"]) ? $context["email"] : $this->getContext($context, "email")), "html", null, true);
        echo "\" id=\"email\" readonly=\"readonly\" name=\"email\" class=\"form-control\" placeholder=\"Informe seu e-mail\" />
                    <button style=\"width: 20%;\" class=\"btn btn-primary loginButton\" type=\"submit\">Recuperar</button>
                </form>

               Se este não for o seu e-mail, entre em contato o gestor da sua unidade ou fale conosco pelo atendimento online.<br /><br />
               Agradecemos pela compreensão.
            </p>
        </div>
        <div class=\"modal-footer\">
            <button class=\"btn\" data-dismiss=\"modal\" aria-hidden=\"true\">SAIR</button>
        </div>
    </div>
    <footer class=\"paddingTop online-chat\" style=\"min-height: 12em;\">
    <!--    <a style=\"position: fixed; bottom: 0em; right: 0em;\" href=\"http://chat.educacao.itajai.sc.gov.br/client.php?locale=pt-br\" target=\"_blank\" onclick=\"if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 &amp;&amp; window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open(&#039;http://chat.educacao.itajai.sc.gov.br/client.php?locale=pt-br&amp;url=&#039;+&#039;&amp;referrer=&#039;, 'mibew', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;\">
            <img alt=\"Atendimento Online\" src=\"";
        // line 114
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/images/atendimento-online.png"), "html", null, true);
        echo "\" />
        </a>-->
    </footer>
";
    }

    // line 119
    public function block_javascript($context, array $blocks = array())
    {
        // line 120
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"";
        // line 121
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/assets/js/less.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 122
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 123
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-growl.min.js"), "html", null, true);
        echo "\"></script>
    <!-- <script type=\"text/javascript\" src=\"";
        // line 124
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-alert.js"), "html", null, true);
        echo "\"></script> -->
    <script type=\"text/javascript\" src=\"";
        // line 125
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.mousewheel-3.0.6.pack.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 126
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/fancybox/jquery.fancybox.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 127
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/login.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 128
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/formacoes.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 129
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/filaUnica.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 130
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/certificados.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 131
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/requerimentoExterno.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 132
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/validatePassword.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\">
        ";
        // line 134
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "success"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["success"]) {
            // line 135
            echo "            \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["success"]) ? $context["success"] : $this->getContext($context, "success")), "html", null, true);
            echo "\", { type: 'success', delay: 5000 });
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['success'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 137
        echo "        
        function bindClickFolder () {
            \$('.folder_link').on('click', function(ev){
                ev.preventDefault();
                \$.ajax({
                    url: \$(this).attr('href'),
                    type: 'GET',
                    success: function (data){
                        \$('.ajaxPages').html(data);
                    }
                });
            });
        }  
        
        \$(document).ready(function(){
            var width = \$(window).width();
            if (width < 600) {
                \$('.online-chat').hide();
            }
            
            ";
        // line 157
        if ((isset($context["senhaExpirada"]) ? $context["senhaExpirada"] : $this->getContext($context, "senhaExpirada"))) {
            // line 158
            echo "                \$('#modalSenha').modal();
            ";
        }
        // line 160
        echo "                
            \$('.folder_link').on('click', function(ev){
                ev.preventDefault();
                \$.ajax({
                    url: \$(this).attr('href'),
                    type: 'GET',
                    success: function (data){
                        \$('.ajaxPages').html(data);
                        bindClickFolder();
                    }
                });
            });
        });
        
        var LHCChatOptions = {};
            LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
            (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
            var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
            po.src = '//chat.educacao.itajai.sc.gov.br/index.php/por/chat/getstatus/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true/(theme)/1?r='+referrer+'&l='+location;
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
        
    /*    
        var LHCFAQOptions = {status_text:'FAQ',url:'replace_me_with_dynamic_url',identifier:''};
        (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = '//chat.educacao.itajai.sc.gov.br/index.php/por/faq/getstatus/(position)/bottom_left/(top)/450/(units)/pixels/(theme)/1';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
        */
    </script>
    ";
        // line 193
        if ((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error"))) {
            // line 194
            echo "        <script type=\"text/javascript\" >    
            \$.bootstrapGrowl(\"";
            // line 195
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "message"), "html", null, true);
            echo "\", { type: 'danger', delay: 5000 }); 
        </script> 
    ";
        }
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Acesso:login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 157,  286 => 137,  277 => 135,  273 => 134,  268 => 132,  264 => 131,  260 => 130,  256 => 129,  252 => 128,  248 => 127,  244 => 126,  240 => 125,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 100,  187 => 99,  183 => 98,  176 => 94,  161 => 82,  155 => 79,  149 => 76,  143 => 73,  137 => 70,  131 => 67,  86 => 25,  81 => 23,  72 => 17,  68 => 16,  62 => 13,  59 => 12,  56 => 11,  50 => 8,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

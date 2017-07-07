<?php

/* ::template.html.twig */
class __TwigTemplate_c67de047a173b7771217fa71938d3773ae437acb856544336603ad30b1e9b5ac extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'pageTitle' => array($this, 'block_pageTitle'),
            'css' => array($this, 'block_css'),
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'footer' => array($this, 'block_footer'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt-br\">
    <head>
        <title>";
        // line 4
        $this->displayBlock('pageTitle', $context, $blocks);
        echo "</title>
        <meta charset=\"UTF-8\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\" />
        <meta name=\"description\" content=\"Intranet SME\" />
        ";
        // line 8
        $this->displayBlock('css', $context, $blocks);
        // line 18
        echo "        <link rel=\"icon\" type=\"image/x-icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    
    <body class=\"fundo\" label=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"), "html", null, true);
        echo "\">  
        <div id=\"contentWrapper\">
            <div id=\"menuSwitcher\">
                <span class=\"glyphicon glyphicon-th-list\"></span>
            </div>
            <div id=\"menu\">
                ";
        // line 27
        $this->env->loadTemplate("menubar.html.twig")->display($context);
        // line 28
        echo "            </div>

            <div id=\"viewContainer\" class=\"viewContainer\">
                <div class=\"container\">
                    <h2 class=\"page-header\">";
        // line 32
        $this->displayBlock('headerTitle', $context, $blocks);
        echo "</h2>
                    <div id=\"notificationsDiv\">
                        <div id=\"notification_area\" style=\"display: none;\" label=\"";
        // line 34
        echo $this->env->getExtension('routing')->getPath("intranet_total_notificacoes");
        echo "\" alt=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("intranet_buscar_notificacoes", array("id" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"))), "html", null, true);
        echo "\" class=\"rounded\">
                                <div id=\"notifications_title\">Notificações</div>
                        </div>
                    </div>
                    ";
        // line 38
        $this->displayBlock('body', $context, $blocks);
        // line 40
        echo "                </div>
            </div>

            <!--
                        <div class=\"footer\">
                            ";
        // line 45
        $this->displayBlock('footer', $context, $blocks);
        // line 46
        echo "                        </div>
                        -->

            <div id=\"modalError\" class=\"modal fade\" role=\"dialog\">
                <div class=\"modal-dialog\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header alert-danger\">
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                            <h4 class=\"modal-title\">Um erro ocorreu</h4>
                        </div>
                        <div id=\"error\" class=\"modal-body text-center\"></div>
                    </div>
                </div>
            </div>

            <div id=\"divAjax\" class=\"divAjax\">
                <img src=\"";
        // line 62
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/images/ajaxLoading.gif"), "html", null, true);
        echo "\" />
            </div>
        </div>
        
        ";
        // line 66
        $this->displayBlock('javascript', $context, $blocks);
        // line 118
        echo "    </body>
</html>
";
    }

    // line 4
    public function block_pageTitle($context, array $blocks = array())
    {
        echo "SME - Intranet";
    }

    // line 8
    public function block_css($context, array $blocks = array())
    {
        // line 9
        echo "            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/css/jquery-datetimepicker.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap-theme.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap-datepicker.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/css/bootstrap-submenu.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/style.css"), "html", null, true);
        echo "\"  media=\"screen and (min-width: 600px)\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/styleMobile.css"), "html", null, true);
        echo "\" media=\"screen and (max-width: 599px)\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 16
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/css/jquery.jscrollpane.css"), "html", null, true);
        echo "\" />
        ";
    }

    // line 32
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Intranet SME";
    }

    // line 38
    public function block_body($context, array $blocks = array())
    {
        // line 39
        echo "                    ";
    }

    // line 45
    public function block_footer($context, array $blocks = array())
    {
    }

    // line 66
    public function block_javascript($context, array $blocks = array())
    {
        // line 67
        echo "            <script type=\"text/javascript\" src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery-1.9.0.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 68
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.mousewheel.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 69
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.jscrollpane.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery-datetimepicker.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 71
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.maskedinput.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 72
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/handlebars/handlebars-1.3.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 73
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 74
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-growl.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 75
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootbox.min.js"), "html", null, true);
        echo "\"></script>
\t\t\t<script type=\"text/javascript\" src=\"http://chat.educacao.itajai.sc.gov.br/new/js/compiled/chat_popup.js\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 77
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/menu.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 78
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/notification.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 79
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/js/javascript.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\">
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-66608178-1', 'auto');
                ga('send', 'pageview');
            </script>
            <script type=\"text/javascript\">
                ";
        // line 90
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "message"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 91
            echo "                    \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
            echo "\", { type: 'info', delay: 5000 });
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 93
        echo "                ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "error"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            // line 94
            echo "                    \$(\"#error\").html(\"";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "\");
                    \$(\"#modalError\").modal(\"show\");
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 97
        echo "                \$(function() {
                    \$( \".datepickerSME\" ).datetimepicker({
                        'timepicker': false, 
                        'format': 'd/m/Y', 
                        'scrollInput': false
                    });
                    \$(\".datetimepickerSME\").datetimepicker({'scrollInput': false});
                    \$.ajax({
                        url: '";
        // line 105
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("intranet_admin_buscaFoto", array("id" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"))), "html", null, true);
        echo "',
                        type: 'GET',
                        success: function (data){
                            if (data !== 'error') {
                                \$('.photo').html('');
                                \$('.photo').html(\"<img src='\" + data + \"' class='img-circle' style='width: 100%;' />\");
                            }
                        }
                    });
                    \$(\".datepickerSME\").mask(\"99/99/9999\");
                });
            </script>
        ";
    }

    public function getTemplateName()
    {
        return "::template.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  284 => 105,  274 => 97,  264 => 94,  259 => 93,  250 => 91,  246 => 90,  232 => 79,  228 => 78,  224 => 77,  219 => 75,  215 => 74,  211 => 73,  207 => 72,  203 => 71,  199 => 70,  195 => 69,  191 => 68,  186 => 67,  183 => 66,  178 => 45,  174 => 39,  171 => 38,  165 => 32,  159 => 16,  155 => 15,  151 => 14,  147 => 13,  143 => 12,  139 => 11,  135 => 10,  130 => 9,  127 => 8,  121 => 4,  115 => 118,  113 => 66,  106 => 62,  88 => 46,  86 => 45,  79 => 40,  77 => 38,  68 => 34,  63 => 32,  57 => 28,  55 => 27,  46 => 21,  39 => 18,  37 => 8,  30 => 4,  25 => 1,  29 => 4,  26 => 3,);
    }
}

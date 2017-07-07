<?php

/* IntranetBundle:Acesso:new_password.html.twig */
class __TwigTemplate_0595b061004d611c4115285af47a177747f4e484bc1460646fff5912afd2728b extends Twig_Template
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
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/signin.css"), "html", null, true);
        echo "\" />
";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-12\">
                <img src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/images/login_header_lg.gif"), "html", null, true);
        echo "\" class=\"img-responsive centralizar hidden-xs\" alt=\"Logo Intranet\" />
                <img src=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/images/login_header_sm.gif"), "html", null, true);
        echo "\" class=\"img-responsive centralizar visible-xs\" alt=\"Logo Intranet\" />
            </div>
        </div>

        <div class=\"row\">
            <div class=\"span12\">             
                <form id=\"form_new_password\" action=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("intranet_save_new_password", array("token" => $this->getAttribute((isset($context["token"]) ? $context["token"] : $this->getContext($context, "token")), "id"))), "html", null, true);
        echo "\" method=\"post\" class=\"form-signin\" role=\"form\">
                    <div class=\"form-group\">
                        <label for=\"username\">Nova senha</label>
                        <input type=\"password\" id=\"password\" name=\"password\" class=\"form-control\" placeholder=\"Nova Senha\" autofocus />
                    </div>
                    <div class=\"form-group\">
                        <label for=\"password\">Repita a nova senha</label>
                        <input type=\"password\" id=\"repeat_password\" name=\"repeat_password\" class=\"form-control\" placeholder=\"Repita a Senha\" />
                    </div>
                    <div class=\"form-group\">
                        
                        <button id=\"btnSendNewPassword\" class=\"btn btn-lg btn-primary btn-block\" type=\"submit\">Mudar Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
";
    }

    // line 39
    public function block_javascript($context, array $blocks = array())
    {
        // line 40
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"";
        // line 41
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/assets/js/less.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 43
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-growl.min.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-alert.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/validatePassword.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\">
    \t";
        // line 47
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "message"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 48
            echo "            \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
            echo "\", { type: 'info', delay: 3000 });
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 50
        echo "    </script>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Acesso:new_password.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 50,  124 => 48,  120 => 47,  115 => 45,  111 => 44,  107 => 43,  103 => 42,  99 => 41,  94 => 40,  91 => 39,  69 => 20,  60 => 14,  56 => 13,  51 => 10,  48 => 9,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

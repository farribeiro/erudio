<?php

/* ::paginaExterna.html.twig */
class __TwigTemplate_3deacf0dfd7128ec04da0ac8cc0cabd98579d5b153b363a582fb5016759134ff extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'pageTitle' => array($this, 'block_pageTitle'),
            'css' => array($this, 'block_css'),
            'body' => array($this, 'block_body'),
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
        // line 14
        echo "        <link rel=\"icon\" type=\"image/x-icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    
    <body>
        ";
        // line 18
        $this->displayBlock('body', $context, $blocks);
        // line 19
        echo "        
        ";
        // line 20
        $this->displayBlock('javascript', $context, $blocks);
        // line 43
        echo "    </body>
</html>";
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
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap-theme.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/css/bootstrap-datepicker.css"), "html", null, true);
        echo "\" />
            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/intranet/css/style.css"), "html", null, true);
        echo "\" />
        ";
    }

    // line 18
    public function block_body($context, array $blocks = array())
    {
    }

    // line 20
    public function block_javascript($context, array $blocks = array())
    {
        // line 21
        echo "            <script type=\"text/javascript\" src=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery-1.9.0.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/dist/js/bootstrap-growl.min.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" src=\"";
        // line 24
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery-datetimepicker.js"), "html", null, true);
        echo "\"></script>
            <script type=\"text/javascript\" >    
                ";
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "message"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 27
            echo "                    \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
            echo "\", { type: 'info', delay: 8000 });
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 29
        echo "                ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "error"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            // line 30
            echo "                    \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "\", { type: 'danger', delay: 8000 });
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "            </script>
            <script type=\"text/javascript\">
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-66608178-1', 'auto');
                ga('send', 'pageview');
            </script>
        ";
    }

    public function getTemplateName()
    {
        return "::paginaExterna.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 32,  129 => 30,  124 => 29,  115 => 27,  111 => 26,  106 => 24,  102 => 23,  98 => 22,  93 => 21,  90 => 20,  85 => 18,  79 => 12,  75 => 11,  71 => 10,  66 => 9,  63 => 8,  57 => 4,  52 => 43,  47 => 19,  45 => 18,  37 => 14,  35 => 8,  28 => 4,  23 => 1,  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 157,  286 => 137,  277 => 135,  273 => 134,  268 => 132,  264 => 131,  260 => 130,  256 => 129,  252 => 128,  248 => 127,  244 => 126,  240 => 125,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 100,  187 => 99,  183 => 98,  176 => 94,  161 => 82,  155 => 79,  149 => 76,  143 => 73,  137 => 70,  131 => 67,  86 => 25,  81 => 23,  72 => 17,  68 => 16,  62 => 13,  59 => 12,  56 => 11,  50 => 20,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

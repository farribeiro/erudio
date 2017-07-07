<?php

/* ::templateAjax.html.twig */
class __TwigTemplate_7064ccc90dee88fc8eae9caa5dc9223623977dbeb729323d65331e9220cec3bd extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('css', $context, $blocks);
        // line 2
        echo "
";
        // line 3
        $this->displayBlock('body', $context, $blocks);
        // line 4
        echo "
<script type=\"text/javascript\">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-66608178-1', 'auto');
    ga('send', 'pageview');
</script>

<script type=\"text/javascript\" >    
    ";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "message"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 17
            echo "        \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
            echo "\", { type: 'info', delay: 5000 });
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        echo "    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "error"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            // line 20
            echo "        \$(\"#error\").html(\"";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "\");
        \$(\"#modalError\").modal(\"show\");
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "    \$(function() {
        \$( \".datepickerSME\" ).datetimepicker({
            'timepicker': false, 
            'format': 'd/m/Y', 
            'scrollInput': false
        });
        \$(\".datetimepickerSME\").datetimepicker({'scrollInput': false});
    }); 
</script>

";
        // line 33
        $this->displayBlock('javascript', $context, $blocks);
    }

    // line 1
    public function block_css($context, array $blocks = array())
    {
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
    }

    // line 33
    public function block_javascript($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::templateAjax.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 33,  92 => 3,  87 => 1,  83 => 33,  71 => 23,  61 => 20,  56 => 19,  47 => 17,  43 => 16,  29 => 4,  27 => 3,  24 => 2,  22 => 1,  194 => 74,  191 => 73,  181 => 65,  174 => 63,  167 => 60,  162 => 58,  159 => 57,  156 => 56,  150 => 54,  144 => 52,  138 => 50,  133 => 48,  128 => 47,  122 => 45,  120 => 44,  116 => 42,  110 => 41,  105 => 39,  101 => 38,  94 => 36,  89 => 34,  85 => 33,  82 => 32,  78 => 30,  74 => 28,  70 => 26,  66 => 24,  64 => 23,  60 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}

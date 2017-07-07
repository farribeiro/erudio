<?php

/* ::templateModal.html.twig */
class __TwigTemplate_f3cacb5121025ab68bfb77915b38a5a07adc0c4b5970f6bd67a00c1a66214ca3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'modalTitle' => array($this, 'block_modalTitle'),
            'css' => array($this, 'block_css'),
            'modalBody' => array($this, 'block_modalBody'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"modal-content\">
    <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        <h3 class=\"modal-title\">";
        // line 4
        $this->displayBlock('modalTitle', $context, $blocks);
        echo "</h3>
    </div>
    <div class=\"modal-body\">
        ";
        // line 7
        $this->displayBlock('css', $context, $blocks);
        // line 8
        echo "        ";
        $this->displayBlock('modalBody', $context, $blocks);
        // line 9
        echo "        <script type=\"text/javascript\" >
            ";
        // line 10
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "message"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 11
            echo "                \$.bootstrapGrowl(\"";
            echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : $this->getContext($context, "message")), "html", null, true);
            echo "\", { type: 'info', delay: 5000 });
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['message'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 13
        echo "            ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "flashbag"), "get", array(0 => "error"), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["error"]) {
            // line 14
            echo "                \$(\"#error\").html(\"";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "\");
                \$(\"#modalError\").modal(\"show\");
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['error'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "            \$(function() {
                \$( \".datepickerSME\" ).datetimepicker({
                    'timepicker': false, 
                    'format': 'd/m/Y', 
                    'scrollInput': false
                });
                \$(\".datetimepickerSME\").datetimepicker({'scrollInput': false});
            });
        </script>
        ";
        // line 26
        $this->displayBlock('javascript', $context, $blocks);
        // line 27
        echo "    </div>
</div>
";
    }

    // line 4
    public function block_modalTitle($context, array $blocks = array())
    {
        echo "Info";
    }

    // line 7
    public function block_css($context, array $blocks = array())
    {
    }

    // line 8
    public function block_modalBody($context, array $blocks = array())
    {
    }

    // line 26
    public function block_javascript($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "::templateModal.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 26,  100 => 8,  95 => 7,  89 => 4,  83 => 27,  81 => 26,  70 => 17,  60 => 14,  55 => 13,  46 => 11,  42 => 10,  39 => 9,  36 => 8,  34 => 7,  28 => 4,  23 => 1,  79 => 31,  68 => 29,  64 => 28,  38 => 6,  35 => 5,  29 => 3,);
    }
}

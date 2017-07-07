<?php

/* IntranetBundle:Index:servidor.html.twig */
class __TwigTemplate_3e75089600b9f57374989f636a83552162e89e3a5bf569fccb3e3bc35f8ede83 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest")) ? ("::templateAjax.html.twig") : ("::template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Intranet - Servidor";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Index:servidor.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 3,  119 => 56,  110 => 51,  107 => 50,  74 => 19,  63 => 17,  59 => 16,  48 => 8,  44 => 7,  41 => 6,  38 => 5,  30 => 3,);
    }
}

<?php

/* IntranetBundle:Index:administrador.html.twig */
class __TwigTemplate_a22b9343a4c1d0bee6d4e0a204f5e3fc726308806d692d605599b3457ff1b69e extends Twig_Template
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
        echo "Intranet - Administração";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Index:administrador.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 3,  96 => 41,  87 => 36,  84 => 35,  70 => 23,  59 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}

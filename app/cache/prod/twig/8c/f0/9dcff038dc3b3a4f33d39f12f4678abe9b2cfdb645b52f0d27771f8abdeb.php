<?php

/* IntranetBundle:Index:unidadeEscolar.html.twig */
class __TwigTemplate_8cf09dcff038dc3b3a4f33d39f12f4678abe9b2cfdb645b52f0d27771f8abdeb extends Twig_Template
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
        echo "Intranet - Gestão Escolar";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Index:unidadeEscolar.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 3,  84 => 28,  75 => 24,  66 => 20,  61 => 18,  57 => 17,  54 => 16,  49 => 15,  38 => 6,  35 => 5,  29 => 3,);
    }
}

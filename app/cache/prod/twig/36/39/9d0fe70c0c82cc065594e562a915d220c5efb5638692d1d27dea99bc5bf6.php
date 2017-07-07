<?php

/* SuporteTecnicoBundle:Anotacao:formCadastro.html.twig */
class __TwigTemplate_36399d0fe70c0c82cc065594e562a915d220c5efb5638692d1d27dea99bc5bf6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SuporteTecnicoBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Suporte Técnico > Anotações > Nova";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
        <div class=\"row\" id=\"formErrors\"> ";
        // line 7
        echo (isset($context["errors"]) ? $context["errors"] : $this->getContext($context, "errors"));
        echo " </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'label');
        echo "
                ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 16
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'widget', array("attr" => array("class" => "btn btn-primary")));
        echo "
            </div>
        </div>
    ";
        // line 19
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Anotacao:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  67 => 19,  61 => 16,  53 => 11,  49 => 10,  43 => 7,  38 => 6,  35 => 5,  29 => 3,);
    }
}

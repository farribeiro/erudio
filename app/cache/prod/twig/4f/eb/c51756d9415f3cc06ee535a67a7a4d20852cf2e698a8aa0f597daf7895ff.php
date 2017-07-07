<?php

/* SuporteTecnicoBundle:Chamado:confirmacaoCadastro.html.twig */
class __TwigTemplate_4febc51756d9415f3cc06ee535a67a7a4d20852cf2e698a8aa0f597daf7895ff extends Twig_Template
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
        echo "Suporte Técnico > Chamados > Cadastro";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "
    <div class=\"text-center\">
        <div class=\"alert alert-success\">
            <p> Seu chamado foi criado. Número de acompanhamento: <strong>";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"), "html", null, true);
        echo "</strong></p>
            <a class=\"alert-link\" href=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("suporte_chamado_gerenciar", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
        echo "\">[ Abrir página do chamado ]</a>
        </div>
    </div>

";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:confirmacaoCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 10,  43 => 9,  38 => 6,  35 => 5,  29 => 3,);
    }
}

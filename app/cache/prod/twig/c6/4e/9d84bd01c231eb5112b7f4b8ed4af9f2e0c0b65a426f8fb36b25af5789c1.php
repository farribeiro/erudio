<?php

/* FilaUnicaBundle:Inscricao:confirmacaoCadastro.html.twig */
class __TwigTemplate_c64e9d84bd01c231eb5112b7f4b8ed4af9f2e0c0b65a426f8fb36b25af5789c1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FilaUnicaBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FilaUnicaBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo " Fila Única > Inscrição > Impressão do Comprovante";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"text-center\">
        <div class=\"alert alert-success\">
            <p> Inscrição realizada com sucesso. Protocolo: ";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"), "html", null, true);
        echo "</p>
            <a target=\"_blank\" class=\"alert-link\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_imprimirComprovante", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
        echo "\">[ Imprimir comprovante ]</a>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:confirmacaoCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 9,  42 => 8,  38 => 6,  35 => 5,  29 => 3,);
    }
}

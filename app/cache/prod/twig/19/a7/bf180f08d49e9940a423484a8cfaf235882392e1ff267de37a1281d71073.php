<?php

/* DGPBundle:Vinculo:modalDocumentos.html.twig */
class __TwigTemplate_19a7bf180f08d49e9940a423484a8cfaf235882392e1ff267de37a1281d71073 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::templateModal.html.twig");

        $this->blocks = array(
            'modalTitle' => array($this, 'block_modalTitle'),
            'modalBody' => array($this, 'block_modalBody'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::templateModal.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_modalTitle($context, array $blocks = array())
    {
        echo " <h2>Impressão de Documentos</h2> ";
    }

    // line 5
    public function block_modalBody($context, array $blocks = array())
    {
        // line 6
        echo "    <ul>
        <li> <a href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirFichaCadastral", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\" target=\"_blank\" >Ficha Cadastral</a> </li>
        <li> <a href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirChecklist", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\" target=\"_blank\" >Checklist</a> </li>
        <li> <a href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirParecerRegularidade", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\" target=\"_blank\" >Parecer de Regularidade</a> </li>
        ";
        // line 10
        if (($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "id") != twig_constant("SME\\DGPBundle\\Entity\\TipoVinculo::ACT"))) {
            // line 11
            echo "            <li> <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirTermoPosse", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\" target=\"_blank\" >Termo de Posse</a> </li>
        ";
        }
        // line 13
        echo "        ";
        if (($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "id") != twig_constant("SME\\DGPBundle\\Entity\\TipoVinculo::COMISSIONADO"))) {
            // line 14
            echo "            <li> <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirEncaminhamento", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\" target=\"_blank\" >Encaminhamento</a> </li>
        ";
        }
        // line 16
        echo "    </ul>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Vinculo:modalDocumentos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 16,  64 => 14,  61 => 13,  55 => 11,  53 => 10,  49 => 9,  45 => 8,  41 => 7,  38 => 6,  35 => 5,  29 => 3,);
    }
}

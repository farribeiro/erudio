<?php

/* PublicBundle:Public:modalFormacao.html.twig */
class __TwigTemplate_9247d13bce9d744624568857404ca5da5209a36a1f6144defc8a523f2f81a478 extends Twig_Template
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
        echo " <h2>";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "nome"), "html", null, true);
        echo "</h2> ";
    }

    // line 5
    public function block_modalBody($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"container\">
        <div class=\"row\">
            <strong>Público Alvo:</strong> ";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "publicoAlvo"), "html", null, true);
        echo " <br />
            <strong>Carga Horária:</strong> ";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "cargaHoraria"), "html", null, true);
        echo " Horas <br />
            <strong>Data de Início:</strong> ";
        // line 10
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "dataInicioFormacao"), "d/m/Y"), "html", null, true);
        echo " <br />
            <strong>Data de Término:</strong> ";
        // line 11
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "dataTerminoFormacao"), "d/m/Y"), "html", null, true);
        echo " <br />
            <strong>Vagas Disponíveis:</strong> ";
        // line 12
        if (($this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "limiteVagas") > 0)) {
            echo " ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "vagasDisponiveis"), "html", null, true);
            echo " ";
        } else {
            echo " Sem limite ";
        }
        echo " 
            ";
        // line 13
        if ($this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "descricao")) {
            // line 14
            echo "                <hr />
                <strong>Descrição:</strong> <br /> ";
            // line 15
            echo $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "descricao");
            echo "
            ";
        }
        // line 17
        echo "        </div>
    </div>
            
";
    }

    public function getTemplateName()
    {
        return "PublicBundle:Public:modalFormacao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  80 => 17,  75 => 15,  72 => 14,  70 => 13,  60 => 12,  56 => 11,  52 => 10,  48 => 9,  44 => 8,  40 => 6,  37 => 5,  29 => 3,);
    }
}

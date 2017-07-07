<?php

/* SuporteTecnicoBundle:Chamado:chamado.html.twig */
class __TwigTemplate_cb7f1cf168688cc392b6caf5bff495a4ec404437a5438b0fa90238a8f920e175 extends Twig_Template
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
        echo "Suporte Técnico > Chamados > Nº ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"), "html", null, true);
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "
<div id=\"informacoes\" class=\"panel panel-default\">
    <div class=\"panel-heading\">
        <strong>Informações</strong>
    </div>
    <div class=\"panel-body\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <strong>Categoria:</strong> ";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "categoria"), "nomeHierarquico"), "html", null, true);
        echo " 
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <strong>Solicitante:</strong> ";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "pessoaCadastrou"), "nome"), "html", null, true);
        echo "
            </div>
            <div class=\"col-lg-4\">
                <strong>Data de Criação:</strong> ";
        // line 22
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "dataCadastro"), "d/m/Y H:i"), "html", null, true);
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <strong>Local:</strong> ";
        // line 27
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "local"), "nome"), "html", null, true);
        echo "
            </div>
            ";
        // line 29
        if ($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "encerrado")) {
            // line 30
            echo "                <div class=\"col-lg-4\">
                    <strong>Data de Encerramento:</strong> ";
            // line 31
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "dataEncerramento"), "d/m/Y H:i"), "html", null, true);
            echo "
                </div>
            ";
        }
        // line 34
        echo "        </div>
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <strong>Prioridade:</strong> ";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "nome"), "html", null, true);
        echo "
            </div>
            <div class=\"col-lg-4\">
                <strong>Status:</strong> ";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "status"), "nome"), "html", null, true);
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <strong>Descrição:</strong> 
                <p>";
        // line 46
        echo twig_escape_filter($this->env, trim($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "descricao")), "html", null, true);
        echo "</p>
            </div>
        </div>
        ";
        // line 49
        if ($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "encerrado")) {
            // line 50
            echo "            <div class=\"row\">
                <div class=\"col-lg-12\">
                    <strong>Solução:</strong> 
                    <p>";
            // line 53
            echo twig_escape_filter($this->env, trim($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "solucao")), "html", null, true);
            echo "</p>
                </div>
            </div>
        ";
        }
        // line 57
        echo "        <div class=\"col-lg-12\">
            <a href=\"";
        // line 58
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("suporte_chamado_imprimir", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
        echo "\" target=\"_blank\" class=\"btn btn-primary pull-right\">
                <span class=\"glyphicon glyphicon-print\"></span> Imprimir
            </a>
        </div>
    </div>
</div>

";
        // line 65
        $this->env->loadTemplate("SuporteTecnicoBundle:Atividade:atividades.html.twig")->display(array_merge($context, array("chamado" => (isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")))));
        // line 66
        echo "
";
        // line 67
        $this->env->loadTemplate("SuporteTecnicoBundle:Anotacao:anotacoes.html.twig")->display(array_merge($context, array("chamado" => (isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")))));
        // line 68
        echo "
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:chamado.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 68,  145 => 67,  142 => 66,  140 => 65,  130 => 58,  127 => 57,  120 => 53,  115 => 50,  113 => 49,  107 => 46,  98 => 40,  92 => 37,  87 => 34,  81 => 31,  78 => 30,  76 => 29,  71 => 27,  63 => 22,  57 => 19,  49 => 14,  39 => 6,  36 => 5,  29 => 3,);
    }
}

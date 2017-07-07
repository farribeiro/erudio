<?php

/* DGPBundle:UnidadeEscolar:modalFormPonto.html.twig */
class __TwigTemplate_824ec819941cf66aed3d5cb48e8fa438e0c05d17427266ace295f7dde77d531a extends Twig_Template
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
        echo " Folha Individual de Registro de Comparecimento";
    }

    // line 5
    public function block_modalBody($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formPonto\" target=\"_blank\" method=\"get\" action=\"";
        echo $this->env->getExtension('routing')->getUrl("dgp_unidade_imprimirPonto");
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"mes\">Mês:</label>
                <select id=\"mes\" name=\"mes\" class=\"form-control\">
                    <option value=\"1\">Janeiro</option>
                    <option value=\"2\">Fevereiro</option>
                    <option value=\"3\">Março</option>
                    <option value=\"4\">Abril</option>
                    <option value=\"5\">Maio</option>
                    <option value=\"6\">Junho</option>
                    <option value=\"7\">Julho</option>
                    <option value=\"8\">Agosto</option>
                    <option value=\"9\">Setembro</option>
                    <option value=\"10\">Outubro</option>
                    <option value=\"11\">Novembro</option>
                    <option value=\"12\">Dezembro</option>
                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"ano\">Ano:</label>
                <select id=\"ano\" name=\"ano\" class=\"form-control\">
                    ";
        // line 28
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(2014, 2020));
        foreach ($context['_seq'] as $context["_key"] => $context["ano"]) {
            // line 29
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, (isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ano'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 31
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <button type=\"submit\" class=\"btn btn-primary btn-block\">Gerar folhas</button>
            </div>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:UnidadeEscolar:modalFormPonto.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 31,  68 => 29,  64 => 28,  38 => 6,  35 => 5,  29 => 3,);
    }
}

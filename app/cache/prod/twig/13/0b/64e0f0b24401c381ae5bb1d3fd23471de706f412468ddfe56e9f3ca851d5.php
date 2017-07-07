<?php

/* DGPPromocaoBundle:PromocaoHorizontal:detalhes.html.twig */
class __TwigTemplate_130b64e0f0b24401c381ae5bb1d3fd23471de706f412468ddfe56e9f3ca851d5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"row\">
    <div class=\"col-lg-6\">
        <label>Status:</label>
        <span class=\"form-control\">";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
        echo "</span>
    </div>
    <div class=\"col-lg-6\">
        <label>Data do pedido:</label>
        <span class=\"form-control\">";
        // line 8
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataCadastro"), "d/m/Y"), "html", null, true);
        echo "</span>
    </div>
</div>
<div class=\"row\">
    <div class=\"col-lg-12\">
        <label>Resposta:</label>
        <span class=\"form-control\">";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "resposta"), "html", null, true);
        echo "</span>
    </div>
</div>
<div class=\"row\">
    <div class=\"col-lg-6\">
        <label>Observações:</label>
        <span class=\"form-control\">";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "observacao"), "html", null, true);
        echo "</span>
    </div>
    <div class=\"col-lg-6\">
        <label>A contar de:</label>
        <span class=\"form-control\">";
        // line 24
        echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataInicio")) ? (twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataInicio"), "d/m/Y")) : ("")), "html", null, true);
        echo "</span>
    </div>
</div>
    
<div class=\"panel panel-default\" style=\"margin-top: 1em;\">
    <div class=\"panel-heading\">Formações</div>
    <div class=\"panel-body\">
        <table class=\"table table-hover\">
            <thead>
                <tr>
                    <th class=\"text-center\">Formação</th>
                    <th class=\"text-center\">Instituição</th>
                    <th class=\"text-center\">Data de Conclusão</th>
                    <th class=\"text-center\">Carga Horária</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 41
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "formacoesInternas"));
        foreach ($context['_seq'] as $context["_key"] => $context["f"]) {
            // line 42
            echo "                    <tr>
                        <td>";
            // line 43
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "matricula"), "formacao"), "nomeCertificado"), "html", null, true);
            echo "</td>
                        <td class=\"text-center\">SME</td>
                        <td class=\"text-center\">";
            // line 45
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "matricula"), "formacao"), "dataTerminoFormacao"), "d/m/Y"), "html", null, true);
            echo "</td>
                        <td class=\"text-center\">";
            // line 46
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "matricula"), "formacao"), "cargaHoraria"), "html", null, true);
            echo "</td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['f'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "                ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "formacoesExternas"));
        foreach ($context['_seq'] as $context["_key"] => $context["f"]) {
            // line 50
            echo "                    <tr>
                        <td>";
            // line 51
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "nome"), "html", null, true);
            echo "</td>
                        <td class=\"text-center\">";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "instituicao"), "html", null, true);
            echo "</td>
                        <td class=\"text-center\">";
            // line 53
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "dataConclusao"), "d/m/Y"), "html", null, true);
            echo "</td>
                        <td class=\"text-center\">";
            // line 54
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["f"]) ? $context["f"] : $this->getContext($context, "f")), "cargaHoraria"), "html", null, true);
            echo "</td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['f'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 57
        echo "            </tbody>
        </table>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "DGPPromocaoBundle:PromocaoHorizontal:detalhes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  130 => 57,  121 => 54,  117 => 53,  113 => 52,  109 => 51,  106 => 50,  101 => 49,  92 => 46,  88 => 45,  83 => 43,  80 => 42,  76 => 41,  56 => 24,  49 => 20,  40 => 14,  31 => 8,  24 => 4,  19 => 1,);
    }
}

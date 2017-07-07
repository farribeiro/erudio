<?php

/* DGPPromocaoBundle:PromocaoVertical:detalhes.html.twig */
class __TwigTemplate_3d29e41b4a1cd4b7aa5e841f28fa8232d96a8870777ab019fa1f588881a5812b extends Twig_Template
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
    <div class=\"panel-heading\">Dados do Curso</div>
    <div class=\"panel-body\">
        <table class=\"table table-hover\">
            <thead>
                <tr>
                    <th class=\"text-center\">Nome</th>
                    <th class=\"text-center\">Instituição</th>
                    <th class=\"text-center\">Data de Conclusão</th>
                    <th class=\"text-center\">Carga Horária</th>
                    <th class=\"text-center\">Tipo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>";
        // line 43
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "nomeCurso"), "html", null, true);
        echo "</td>
                    <td class=\"text-center\">";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "instituicaoCurso"), "html", null, true);
        echo "</td>
                    <td class=\"text-center\">";
        // line 45
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataConclusaoCurso"), "d/m/Y"), "html", null, true);
        echo "</td>
                    <td class=\"text-center\">";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "cargaHorariaCurso"), "html", null, true);
        echo "</td>
                    <td class=\"text-center\">";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "grauCurso"), "nome"), "html", null, true);
        echo "</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "DGPPromocaoBundle:PromocaoVertical:detalhes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  94 => 47,  90 => 46,  86 => 45,  82 => 44,  78 => 43,  56 => 24,  49 => 20,  40 => 14,  31 => 8,  24 => 4,  19 => 1,);
    }
}

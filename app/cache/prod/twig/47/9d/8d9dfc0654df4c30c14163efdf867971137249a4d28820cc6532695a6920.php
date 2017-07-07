<?php

/* SuporteTecnicoBundle:Anotacao:anotacoes.html.twig */
class __TwigTemplate_479d8d9dfc0654df4c30c14163efdf867971137249a4d28820cc6532695a6920 extends Twig_Template
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
        echo "<div id=\"anotacoes\" class=\"panel panel-default\">
    <div class=\"panel-heading\">
        <strong>Anotações</strong>
    </div>
    <div class=\"panel-body\">
        <table class=\"table table-striped table-hover\">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Autor</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 15
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "anotacoes"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["anotacao"]) {
            // line 16
            echo "                    <tr>
                        <td>";
            // line 17
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["anotacao"]) ? $context["anotacao"] : $this->getContext($context, "anotacao")), "dataCadastro"), "d/m/Y H:i"), "html", null, true);
            echo "</td>
                        <td>";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["anotacao"]) ? $context["anotacao"] : $this->getContext($context, "anotacao")), "pessoaCadastrou"), "nome"), "html", null, true);
            echo "</td>
                        <td style=\"width: 50%;\">";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["anotacao"]) ? $context["anotacao"] : $this->getContext($context, "anotacao")), "descricao"), "html", null, true);
            echo "</td>
                    </tr>
                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 22
            echo "                    <tr>
                        <td colspan=\"3\" class=\"text-center\">Nenhuma anotação cadastrada</td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['anotacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 26
        echo "            </tbody>
        </table>
        ";
        // line 28
        if ((!$this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "encerrado"))) {
            // line 29
            echo "            <a  class=\"ajaxLink btn btn-primary\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("suporte_anotacao_cadastrar", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
            echo "\">Adicionar</a>
        ";
        }
        // line 31
        echo "    </div>
</div>

";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Anotacao:anotacoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 29,  72 => 28,  68 => 26,  59 => 22,  51 => 19,  47 => 18,  43 => 17,  35 => 15,  100 => 39,  94 => 37,  79 => 30,  71 => 27,  67 => 25,  58 => 23,  54 => 22,  48 => 19,  44 => 18,  41 => 17,  36 => 16,  19 => 1,  205 => 88,  202 => 87,  197 => 96,  195 => 87,  192 => 86,  190 => 85,  187 => 84,  185 => 83,  179 => 80,  173 => 77,  165 => 72,  157 => 67,  153 => 66,  145 => 61,  141 => 60,  133 => 55,  129 => 54,  121 => 49,  117 => 48,  111 => 45,  107 => 44,  101 => 41,  92 => 36,  88 => 34,  80 => 31,  73 => 25,  64 => 19,  57 => 15,  50 => 11,  45 => 9,  40 => 16,  37 => 5,  30 => 3,);
    }
}

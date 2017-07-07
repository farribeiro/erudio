<?php

/* SuporteTecnicoBundle:Atividade:atividades.html.twig */
class __TwigTemplate_5fc63ac9da9cf2a9cb7bc386fccb69bce36d08529de45dea1b1ffe2d941b952f extends Twig_Template
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
        echo "<div id=\"atividades\" class=\"panel panel-default\">
    <div class=\"panel-heading\">
        <strong>Atividades</strong>
    </div>
    <div class=\"panel-body\">
        <table class=\"table table-striped table-hover\">
            <thead>
                <tr>
                    <th>Início</th>
                    <th>Término</th>
                    <th>Técnicos</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "atividades"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["atividade"]) {
            // line 17
            echo "                    <tr>
                        <td>";
            // line 18
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["atividade"]) ? $context["atividade"] : $this->getContext($context, "atividade")), "inicio"), "d/m/Y H:i"), "html", null, true);
            echo "</td>
                        <td>";
            // line 19
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["atividade"]) ? $context["atividade"] : $this->getContext($context, "atividade")), "termino"), "d/m/Y H:i"), "html", null, true);
            echo "</td>
                        <td>
                            <ul>
                            ";
            // line 22
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["atividade"]) ? $context["atividade"] : $this->getContext($context, "atividade")), "tecnicos"));
            foreach ($context['_seq'] as $context["_key"] => $context["tecnico"]) {
                // line 23
                echo "                                <li>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tecnico"]) ? $context["tecnico"] : $this->getContext($context, "tecnico")), "nome"), "html", null, true);
                echo "</li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tecnico'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 25
            echo "                            </ul>
                        </td>
                        <td style=\"width: 40%;\">";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["atividade"]) ? $context["atividade"] : $this->getContext($context, "atividade")), "descricao"), "html", null, true);
            echo "</td>
                    </tr>
                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 30
            echo "                    <tr>
                        <td colspan=\"4\" class=\"text-center\">Nenhuma atividade cadastrada</td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['atividade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "            </tbody>
        </table>
        ";
        // line 36
        if (($this->env->getExtension('security')->isGranted("ROLE_SUPORTE_ADMIN") && (!$this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "encerrado")))) {
            // line 37
            echo "            <a  class=\"ajaxLink btn btn-primary\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("suporte_atividade_cadastrar", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
            echo "\">Adicionar</a>
        ";
        }
        // line 39
        echo "    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Atividade:atividades.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  100 => 39,  94 => 37,  79 => 30,  71 => 27,  67 => 25,  58 => 23,  54 => 22,  48 => 19,  44 => 18,  41 => 17,  36 => 16,  19 => 1,  205 => 88,  202 => 87,  197 => 96,  195 => 87,  192 => 86,  190 => 85,  187 => 84,  185 => 83,  179 => 80,  173 => 77,  165 => 72,  157 => 67,  153 => 66,  145 => 61,  141 => 60,  133 => 55,  129 => 54,  121 => 49,  117 => 48,  111 => 45,  107 => 44,  101 => 41,  92 => 36,  88 => 34,  80 => 29,  73 => 25,  64 => 19,  57 => 15,  50 => 11,  45 => 9,  40 => 6,  37 => 5,  30 => 3,);
    }
}

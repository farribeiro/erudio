<?php

/* EstagioBundle:Vaga:listarVagasTable.html.twig */
class __TwigTemplate_96cded968b44c0f9f4bc7f103fdd1595ba7277ff511a2c7ebb0298c70d177c86 extends Twig_Template
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
        echo "<table class=\"table table-striped table-hover tableDGP\">
    <thead>
        <tr>
            <th>Etapa</td>
            <th>Modalidade</td>
            <th>Unidade</td>
            <th>Turno</td>
            <th>Vagas</td>
            <th>Vagas Restantes(ativas)</td>
            <th>Opções</td>
        </tr>
    </thead>
    <tbody>
        ";
        // line 14
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vagas"]) ? $context["vagas"] : $this->getContext($context, "vagas")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["key"] => $context["vaga"]) {
            // line 15
            echo "            <tr>
                <td><a href=\"";
            // line 16
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("editar_vaga", array("id" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "titulo"), "html", null, true);
            echo "</a></td>
                <td>";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "disciplina"), "html", null, true);
            echo "</td>
                <td>";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidade"), "nome"), "html", null, true);
            echo "</td>
                <td>";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "turno"), "nome"), "html", null, true);
            echo "</td>
                <td>";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "totalVagas"), "html", null, true);
            echo "</td>
                ";
            // line 21
            if (twig_test_empty((isset($context["vagasRestantes"]) ? $context["vagasRestantes"] : $this->getContext($context, "vagasRestantes")))) {
                // line 22
                echo "                    <td>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "totalVagas"), "html", null, true);
                echo "</td>
                ";
            } else {
                // line 24
                echo "                    <td>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vagasRestantes"]) ? $context["vagasRestantes"] : $this->getContext($context, "vagasRestantes")), (isset($context["key"]) ? $context["key"] : $this->getContext($context, "key")), array(), "array"), "html", null, true);
                echo "</td>
                ";
            }
            // line 26
            echo "                <td>
                    <a title=\"Ver inscrições\" href=\"";
            // line 27
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("listar_inscritos", array("id" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
            echo "\"><span style=\"color: blue; cursor: pointer;\" class=\"glyphicon glyphicon-list-alt remove-questionario\" aria-hidden=\"true\"></span></a>
                    <a title=\"Remover\" href=\"";
            // line 28
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("remover_vaga", array("id" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
            echo "\"><span style=\"color: red; cursor: pointer;\" class=\"glyphicon glyphicon-remove-circle remove-questionario\" aria-hidden=\"true\"></span></a>
                </td>
            </tr>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 32
            echo "            <tr>
                <td colspan=\"7\" class=\"text-center\">Nenhuma vaga cadastrada</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['key'], $context['vaga'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "    </tbody>
</table>";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:listarVagasTable.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  103 => 36,  94 => 32,  85 => 28,  81 => 27,  78 => 26,  72 => 24,  66 => 22,  64 => 21,  60 => 20,  56 => 19,  52 => 18,  48 => 17,  42 => 16,  39 => 15,  34 => 14,  19 => 1,);
    }
}

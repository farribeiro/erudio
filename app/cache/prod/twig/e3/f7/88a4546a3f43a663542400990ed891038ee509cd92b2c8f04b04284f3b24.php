<?php

/* EstagioBundle:Public:vagas.html.twig */
class __TwigTemplate_e3f788a4546a3f43a663542400990ed891038ee509cd92b2c8f04b04284f3b24 extends Twig_Template
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
            <th>Unidade</td>
            <th>Etapa</td>
            <th>Modalidade</td>
            <th>Turno</td>
            <th>Qtde.</td>
        </tr>
    </thead>
    <tbody>
        ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vagas"]) ? $context["vagas"] : $this->getContext($context, "vagas")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["vaga"]) {
            // line 13
            echo "            <tr>
                <td>";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidade"), "nome"), "html", null, true);
            echo "</td>
                <td>";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "titulo"), "html", null, true);
            echo "</td>
                <td>";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "disciplina"), "html", null, true);
            echo "</td>
                <td>";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "turno"), "nome"), "html", null, true);
            echo "</td>                
                <td>";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "totalVagas"), "html", null, true);
            echo "</td>
            </tr>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 21
            echo "            <tr>
                <td colspan=\"5\" class=\"text-center\">Nenhuma vaga cadastrada</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vaga'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "    </tbody>
</table>";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Public:vagas.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 25,  64 => 21,  56 => 18,  52 => 17,  48 => 16,  44 => 15,  40 => 14,  37 => 13,  32 => 12,  19 => 1,);
    }
}

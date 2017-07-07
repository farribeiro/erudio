<?php

/* EstagioBundle:Vaga:listarVagas.html.twig */
class __TwigTemplate_807505cb03c0ec651ef908c257022505024fc7940338fc0ee0de23d4219733b9 extends Twig_Template
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
        echo "<option></option>
";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vagas"]) ? $context["vagas"] : $this->getContext($context, "vagas")));
        foreach ($context['_seq'] as $context["_key"] => $context["vaga"]) {
            // line 3
            echo "    <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "titulo"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "disciplina"), "html", null, true);
            echo "</option>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vaga'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:listarVagas.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 3,  22 => 2,  19 => 1,);
    }
}

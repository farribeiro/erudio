<?php

/* IntranetBundle:UnidadeEscolar:listaUnidadesEscolares.html.twig */
class __TwigTemplate_2ee221d534d48148b30da6ea0b47ff64e46f719b99776f80f60c5f0ce3b2b5b5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:unidadeEscolar.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:unidadeEscolar.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Gestão Escolar";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <table class=\"table table-striped table-hover\">
        <thead>
            <tr>
                <th>Unidade</th>
                <th>Tipo</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 15
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 16
            echo "                <tr>
                    <td>";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "pessoaJuridica"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "classe"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        <a href=\"";
            // line 20
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("intranet_selecionarUnidadeEscolar", array("unidade" => $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"))), "html", null, true);
            echo "\">Acessar</a>
                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 24
            echo "                <tr> 
                    <td class=\"text-center\" colspan=\"3\">Você não está cadastrado como gestor em nenhuma unidade.</td> 
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "        </tbody>
    </table>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:UnidadeEscolar:listaUnidadesEscolares.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  84 => 28,  75 => 24,  66 => 20,  61 => 18,  57 => 17,  54 => 16,  49 => 15,  38 => 6,  35 => 5,  29 => 3,);
    }
}

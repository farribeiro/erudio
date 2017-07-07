<?php

/* ProtocoloBundle:Encaminhamento:listaEncaminhamentos.html.twig */
class __TwigTemplate_54f0f0881a5a1cd173819514e8bdeaf1a22c71c718ed165b98e638d3f1b80dde extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:servidor.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Protocolo > Gerência > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "nome"), "html", null, true);
        echo " > Encaminhados";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <ul class=\"nav nav-tabs\">
        ";
        // line 7
        if ($this->env->getExtension('security')->isGranted("ROLE_PROTOCOLO_1")) {
            // line 8
            echo "            <li><a href=\"";
            echo $this->env->getExtension('routing')->getUrl("protocolo_admin_formPesquisa", array("categoria" => 1));
            echo "\">Requerimentos</a></li>
        ";
        }
        // line 10
        echo "        <li class=\"active\"><a href=\"#\">Encaminhamentos</a></li>
    </ul>
    <br />
    <table class=\"table table-striped table-hover\">
        <thead>
            <tr>
                <th>Data</th>
                <th>Protocolo</th>
                <th>Encaminhado por</th>
                <th>Motivo / Observação</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 24
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["encaminhamentos"]) ? $context["encaminhamentos"] : $this->getContext($context, "encaminhamentos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["encaminhamento"]) {
            // line 25
            echo "                <tr>
                    <td>";
            // line 26
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td>";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "protocolo"), "protocolo"), "html", null, true);
            echo "</td>
                    <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "pessoaEncaminha"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        ";
            // line 30
            if ($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "motivo")) {
                echo " 
                            ";
                // line 31
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "motivo"), "descricao"), "html", null, true);
                echo ". ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "observacao"), "html", null, true);
                echo "
                        ";
            } else {
                // line 32
                echo " 
                            ";
                // line 33
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "observacao"), "html", null, true);
                echo " 
                        ";
            }
            // line 35
            echo "                    </td>
                    <td> <a class=\"postLink\" href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_aceitarEncaminhamento", array("encaminhamento" => $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "id"))), "html", null, true);
            echo "\">Aceitar</a> </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 39
            echo "                <tr>
                    <td class=\"text-center\" colspan=\"5\">Nenhum encaminhamento pendente para você</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['encaminhamento'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "        </tbody>
    </table>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Encaminhamento:listaEncaminhamentos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 43,  118 => 39,  110 => 36,  107 => 35,  102 => 33,  99 => 32,  92 => 31,  88 => 30,  83 => 28,  79 => 27,  75 => 26,  72 => 25,  67 => 24,  51 => 10,  45 => 8,  43 => 7,  40 => 6,  37 => 5,  29 => 3,);
    }
}

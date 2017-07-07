<?php

/* SuporteTecnicoBundle:Chamado:chamados.html.twig */
class __TwigTemplate_06e5ec58f979221613dce24426d1d80657e31b8f7c6f25f9ce6851e785ea45ef extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'page' => array($this, 'block_page'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SuporteTecnicoBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page($context, array $blocks = array())
    {
        // line 4
        echo "    <div style=\"margin-top: 0.8em;\">
        <em>";
        // line 5
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["chamados"]) ? $context["chamados"] : $this->getContext($context, "chamados"))), "html", null, true);
        echo " chamado(s) nesta página</em>
    </div>
    <table class=\"table table-striped table-hover\">
        <thead>
            <tr>
                <th class=\"text-center\">Cod.</th>
                <th class=\"text-center\">Categoria</th>
                <th class=\"text-center\">Local</th>
                <th class=\"text-center\">Criação</th>
                <th class=\"text-center\">Status</th>
                <th class=\"text-center\">Prioridade</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["chamados"]) ? $context["chamados"] : $this->getContext($context, "chamados")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["chamado"]) {
            // line 20
            echo "                <tr ";
            if (($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "id") == 5)) {
                echo "class=\"danger text-danger\"";
            }
            echo " >
                    <td class=\"text-center\">
                        <a class=\"";
            // line 22
            if (($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "id") == 5)) {
                echo "text-danger";
            }
            echo "\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("suporte_chamado_gerenciar", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
            echo "\">
                            ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"), "html", null, true);
            echo "
                        </a>
                    </td>
                    <td>
                        ";
            // line 27
            if ($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "categoria"), "categoriaPai")) {
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "categoria"), "categoriaPai"), "nome"), "html", null, true);
                echo " <br />";
            }
            // line 28
            echo "                        ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "categoria"), "nome"), "html", null, true);
            echo "
                    </td>
                    <td title=\"Aberto por ";
            // line 30
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "pessoaCadastrou"), "nome"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "local"), "nome"), "html", null, true);
            echo "</td>
                    <td class=\"text-center\">";
            // line 31
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td class=\"text-center\">
                        <span ";
            // line 33
            if ($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "status"), "terminal")) {
                echo "class=\"text-danger\"";
            }
            echo ">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "status"), "nome"), "html", null, true);
            echo "</span>
                    </td>
                    <td class=\"text-center\">
                        ";
            // line 36
            if (($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "id") == 4)) {
                // line 37
                echo "                            <span class=\"glyphicon glyphicon-exclamation-sign text-danger\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "nome"), "html", null, true);
                echo "\"></span>
                        ";
            } elseif (($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "id") == 3)) {
                // line 39
                echo "                            <span class=\"glyphicon glyphicon-flag text-warning\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "nome"), "html", null, true);
                echo "\"></span>
                        ";
            } elseif (($this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "id") == 2)) {
                // line 41
                echo "                            <span class=\"glyphicon glyphicon-flag text-info\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "nome"), "html", null, true);
                echo "\"></span>
                        ";
            } else {
                // line 43
                echo "                            <span class=\"glyphicon glyphicon-flag text-success\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "prioridade"), "nome"), "html", null, true);
                echo "\"></span>
                        ";
            }
            // line 45
            echo "                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 48
            echo "                <tr> 
                    <td colspan=\"6\" class=\"text-center\">Nenhum chamado encontrado</td> 
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['chamado'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        echo "        </tbody>
    </table>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:chamados.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 52,  145 => 48,  138 => 45,  132 => 43,  126 => 41,  120 => 39,  114 => 37,  112 => 36,  102 => 33,  97 => 31,  91 => 30,  85 => 28,  79 => 27,  72 => 23,  64 => 22,  56 => 20,  51 => 19,  34 => 5,  31 => 4,  28 => 3,);
    }
}

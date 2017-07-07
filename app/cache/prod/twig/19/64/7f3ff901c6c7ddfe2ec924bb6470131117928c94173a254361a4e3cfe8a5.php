<?php

/* DGPBundle:Formacao:formacoes.html.twig */
class __TwigTemplate_19647f3ff901c6c7ddfe2ec924bb6470131117928c94173a254361a4e3cfe8a5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Pessoa:cadastroPessoa.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'tabContent' => array($this, 'block_tabContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Pessoa:cadastroPessoa.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["activeTab"] = "4";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " > Titulação";
    }

    // line 7
    public function block_tabContent($context, array $blocks = array())
    {
        // line 8
        echo "    <div class=\"row\" style=\"margin-top: 10px; cursor: pointer;\">
        <a href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_formacao_cadastrar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\" class=\"btn btn-primary\">
            <span class=\"glyphicon glyphicon-plus\"></span> Cadastrar
        </a>
    </div>
    <div class=\"row\">
        <table class=\"table table-striped table-hover\">
            <thead>
                <tr>
                    <th class=\"text-center\">Curso</th>
                    <th>C.H.</th>
                    <th>Instituição</th>
                    <th>Conclusão</th>
                    <th>Nível</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["formacoes"]) ? $context["formacoes"] : $this->getContext($context, "formacoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["formacao"]) {
            // line 27
            echo "                    <tr>
                        <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "nome"), "html", null, true);
            echo "</td>
                        <td>";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "cargaHoraria"), "html", null, true);
            echo "</td>
                        <td>";
            // line 30
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "instituicao"), "html", null, true);
            echo "</td>
                        <td>";
            // line 31
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "dataConclusao"), "d/m/Y"), "html", null, true);
            echo "</td>
                        <td>";
            // line 32
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "grauFormacao"), "nome"), "html", null, true);
            echo "</td>
                        <td>
                            <a class=\"lnkAlterar\" href=\"";
            // line 34
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_formacao_alterar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"), "formacao" => $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "id"))), "html", null, true);
            echo "\">
                                <span title=\"Alterar\" class=\"glyphicon glyphicon-edit\"></span>
                            </a> |
                            <a class=\"lnkExcluir\" href=\"";
            // line 37
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_formacao_excluir", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"), "formacao" => $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "id"))), "html", null, true);
            echo "\">
                                <span title=\"Excluir\" class=\"glyphicon glyphicon-remove\"></span>
                            </a>
                        </td>
                    </tr>
                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 43
            echo "                    <tr>
                        <td class=\"text-center\" colspan=\"6\">
                            <em>A pessoa não possui nenhum título cadastrado</em>
                        </td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['formacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "            </tbody>
        </table>
    </div>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Formacao:formacoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 49,  111 => 43,  100 => 37,  94 => 34,  89 => 32,  85 => 31,  81 => 30,  77 => 29,  73 => 28,  70 => 27,  65 => 26,  45 => 9,  42 => 8,  39 => 7,  31 => 5,  26 => 3,);
    }
}

<?php

/* EstagioBundle:Vaga:listarPendentes.html.twig */
class __TwigTemplate_04b602941e75209117b1c0eb8e75b7cb00f0355a4e04fb45dc90c5240449229b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest")) ? ("::templateAjax.html.twig") : ("::template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_css($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Todos os estagiários pendentes";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row\" style=\"margin: 1rem;\">
        <div class=\"col-md-12\">
            <table class=\"table table-striped table-hover tableDGP\">
                <thead>
                    <tr>
                        <th>Nome</td>
                        <th>Etapa</td>
                        <th>Modalidade</td>
                        <th>Turno</th>
                        <th>E-mail</td>
                        <th>Telefone</td>
                        <th>Início Observação</td>
                        <th>Final Observação</td>
                        <th>Início Intervenção</td>
                        <th>Final Intervenção</td>
                        <th>Opções</td>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 29
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["inscricao"]) {
            // line 30
            echo "                        <tr>
                            <td>";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "estagiario"), "nome"), "html", null, true);
            echo "</td>
                            <td>";
            // line 32
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "titulo"), "html", null, true);
            echo "</td>
                            <td>";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "disciplina"), "html", null, true);
            echo "</td>
                            <td>";
            // line 34
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turnos"]) ? $context["turnos"] : $this->getContext($context, "turnos")), $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "turno"), array(), "array"), "html", null, true);
            echo "</td>
                            <td>";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "estagiario"), "email"), "html", null, true);
            echo "</td>
                            <td>";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "telefone"), "html", null, true);
            echo "</td>
                            <td>";
            // line 37
            if (($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicio") != "0000-00-00")) {
                echo " ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicio"), "d/m/Y"), "html", null, true);
                echo " ";
            } else {
                echo " Não cadastrado. ";
            }
            echo "</td>
                            <td>";
            // line 38
            if (($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fim") != "0000-00-00")) {
                echo " ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fim"), "d/m/Y"), "html", null, true);
                echo " ";
            } else {
                echo " Não cadastrado. ";
            }
            echo "</td>
                            <td>";
            // line 39
            if (($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicioObservacao") != "0000-00-00")) {
                echo " ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicioObservacao"), "d/m/Y"), "html", null, true);
                echo " ";
            } else {
                echo " Não cadastrado. ";
            }
            echo "</td>
                            <td>";
            // line 40
            if (($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fimObservacao") != "0000-00-00")) {
                echo " ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fimObservacao"), "d/m/Y"), "html", null, true);
                echo " ";
            } else {
                echo " Não cadastrado. ";
            }
            echo "</td>
                            <td>
                                <a title=\"Deferir\" href=\"";
            // line 42
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("deferir_estagio", array("id" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\"><span style=\"color: green; cursor: pointer;\" class=\"glyphicon glyphicon-ok remove-questionario\" aria-hidden=\"true\"></span></a>
                                <a title=\"Indeferir\" href=\"";
            // line 43
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("indeferir_estagio", array("id" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\"><span style=\"color: red; cursor: pointer;\" class=\"glyphicon glyphicon-remove remove-questionario\" aria-hidden=\"true\"></span></a>
                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 47
            echo "                        <tr>
                            <td colspan=\"7\" class=\"text-center\">Nenhuma inscrição cadastrada</td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['inscricao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        echo "                </tbody>
            </table>
        </div>
    </div>
";
    }

    // line 57
    public function block_javascript($context, array $blocks = array())
    {
        // line 58
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(document).ready(function(){
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:listarPendentes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  175 => 58,  172 => 57,  164 => 51,  155 => 47,  146 => 43,  142 => 42,  131 => 40,  121 => 39,  111 => 38,  101 => 37,  97 => 36,  93 => 35,  89 => 34,  85 => 33,  81 => 32,  77 => 31,  74 => 30,  69 => 29,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

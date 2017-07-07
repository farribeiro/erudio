<?php

/* FilaUnicaBundle:Inscricao:fila.html.twig */
class __TwigTemplate_d888e510bfe7c637191aff84be473eec26f4d04c6e40f9e7f03a50adbd6493d5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FilaUnicaBundle:Index:index.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FilaUnicaBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "    <p class=\"text-info\" style=\"padding-top: 5px;\">
        <em>Existem ";
        // line 5
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes"))), "html", null, true);
        echo " inscrições nesta fila</em>
        ";
        // line 6
        if ((isset($context["aviso"]) ? $context["aviso"] : $this->getContext($context, "aviso"))) {
            echo " - ";
            echo twig_escape_filter($this->env, (isset($context["aviso"]) ? $context["aviso"] : $this->getContext($context, "aviso")), "html", null, true);
        }
        // line 7
        echo "    </p>
    <table class=\"table table-hover table-striped\">
        <thead>
            <tr>
                <th></th>
                <th>Categoria</th>
                <th>Protocolo</th>
                <th>Criança</th>
                <th>Pontuação</th>
                <th>Zoneamento</th>
                <th>Turma</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 22
        $context["cont"] = 0;
        // line 23
        echo "            ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["inscricao"]) {
            // line 24
            echo "                ";
            $context["cont"] = ((isset($context["cont"]) ? $context["cont"] : $this->getContext($context, "cont")) + 1);
            // line 25
            echo "                <tr ";
            if (((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")) == $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"))) {
                echo "class=\"success\"
                    ";
            } elseif (($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "movimentacaoInterna") || $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoJudicial"))) {
                // line 26
                echo "class=\"danger\"";
            }
            echo " >
                    <td><strong>";
            // line 27
            echo twig_escape_filter($this->env, (isset($context["cont"]) ? $context["cont"] : $this->getContext($context, "cont")), "html", null, true);
            echo "</strong></td>
                    <td>
                        ";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "nome"), "html", null, true);
            echo " 
                        ";
            // line 30
            if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "movimentacaoInterna")) {
                // line 31
                echo "                            [M.I.]
                        ";
            } elseif ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoJudicial")) {
                // line 32
                echo " 
                            [P.J.]
                        ";
            }
            // line 35
            echo "                    </td>
                    <td> 
                        <span title=\"";
            // line 37
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataModificacao"), "d/m/Y G:i:s"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"), "html", null, true);
            echo "</span>
                    </td>
                    <td>";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        ";
            // line 41
            if (($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::REGULAR"))) {
                echo " 
                            ";
                // line 42
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "rendaPontuada"), "html", null, true);
                echo " 
                        ";
            } else {
                // line 43
                echo " N/A ";
            }
            // line 44
            echo "                    </td>
                    <td>";
            // line 45
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "descricao"), "html", null, true);
            echo "</td>
                    <td>";
            // line 46
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "anoEscolar"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        <a class=\"ajaxLink\" href=\"";
            // line 48
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_consultar", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Visualizar</a> 
                        <br /> <a class=\"ajaxLink\" href=\"";
            // line 49
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_exibirHistorico", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Histórico</a>
                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 53
            echo "                <tr>
                    <td class=\"text-center\" colspan=\"8\"> <em>Não existem inscritos na fila para este zoneamento e ano</em> </td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['inscricao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 57
        echo "         </tbody>
    </table>

    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\"></div>
    </div>
";
    }

    // line 65
    public function block_javascript($context, array $blocks = array())
    {
        // line 66
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\".ajaxLink\").click(function(ev) {
            \$.ajax({
                type: \"GET\",
                url: \$(this).attr(\"href\"),
                success: function(retorno) {
                    \$(\"#divModal\").html(retorno);
                }
            });
            \$('#modalDialog').modal(\"show\");
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:fila.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  180 => 66,  177 => 65,  167 => 57,  158 => 53,  149 => 49,  145 => 48,  140 => 46,  134 => 45,  131 => 44,  128 => 43,  123 => 42,  119 => 41,  114 => 39,  107 => 37,  103 => 35,  98 => 32,  94 => 31,  92 => 30,  88 => 29,  83 => 27,  78 => 26,  72 => 25,  69 => 24,  63 => 23,  61 => 22,  44 => 7,  39 => 6,  35 => 5,  32 => 4,  29 => 3,);
    }
}

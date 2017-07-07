<?php

/* FilaUnicaBundle:Inscricao:listaInscricoes.html.twig */
class __TwigTemplate_c50919acd0f303ee8d21eee4839c7bd51b9a24d15cdb1adf517cdcd975807e82 extends Twig_Template
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
        <em>";
        // line 5
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes"))), "html", null, true);
        echo " inscrições encontradas</em>
    </p>
    <table class=\"table table-hover table-striped\">
        <thead>
            <tr>
                <th>Categoria</th>
                <th>Protocolo</th>
                <th>Criança</th>
                <th>Zoneamento</th>
                <th>Turma</th>
                <th>Status</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["inscricao"]) {
            // line 21
            echo "                <tr>
                    <td>
                        ";
            // line 23
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "nome"), "html", null, true);
            echo " 
                        ";
            // line 24
            if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "movimentacaoInterna")) {
                // line 25
                echo "                            [M.I.]
                        ";
            } elseif ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoJudicial")) {
                // line 27
                echo "                            [P.J.]
                        ";
            }
            // line 29
            echo "                    </td>
                    <td> 
                        <span title=\"Criado por ";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "atendente"), "nome"), "html", null, true);
            echo " em ";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataCadastro"), "d/m/Y G:i:s"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"), "html", null, true);
            echo "</span>
                    </td>
                    <td>";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 34
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "descricao"), "html", null, true);
            echo "</td>
                    <td>";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "anoEscolar"), "nome"), "html", null, true);
            echo "</td>
                    ";
            // line 36
            if ((null === $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "pessoaUltimaModificacao"))) {
                // line 37
                echo "                        <td>
                            <span>";
                // line 38
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "nome"), "html", null, true);
                echo "</span>
                        </td>
                    ";
            } else {
                // line 41
                echo "                        <td>
                            <span title=\"Atualizado por último em ";
                // line 42
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataModificacao"), "d/m/Y G:i:s"), "html", null, true);
                echo " por ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "pessoaUltimaModificacao"), "nome"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "nome"), "html", null, true);
                echo "</span>
                        </td>
                    ";
            }
            // line 45
            echo "                    <td>
                        <a class=\"ajaxLink\" href=\"";
            // line 46
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_consultar", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Gerenciar</a> 
                        <a class=\"ajaxLink\" href=\"";
            // line 47
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_exibirHistorico", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Histórico</a>
                        <br /> <a target=\"_blank\" href=\"";
            // line 48
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_imprimir", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Imprimir</a>
                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 52
            echo "                <tr>
                    <td class=\"text-center\" colspan=\"7\"> <em>Nenhuma inscrição encontrada</em> </td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['inscricao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 56
        echo "         </tbody>
    </table>

    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\"></div>
    </div>
";
    }

    // line 64
    public function block_javascript($context, array $blocks = array())
    {
        // line 65
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
        return "FilaUnicaBundle:Inscricao:listaInscricoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  169 => 65,  166 => 64,  156 => 56,  147 => 52,  138 => 48,  134 => 47,  130 => 46,  127 => 45,  117 => 42,  114 => 41,  108 => 38,  105 => 37,  103 => 36,  99 => 35,  93 => 34,  89 => 33,  80 => 31,  76 => 29,  72 => 27,  68 => 25,  66 => 24,  62 => 23,  58 => 21,  53 => 20,  35 => 5,  32 => 4,  29 => 3,);
    }
}

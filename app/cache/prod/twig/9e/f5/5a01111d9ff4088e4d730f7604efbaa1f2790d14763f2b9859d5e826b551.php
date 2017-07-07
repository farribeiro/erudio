<?php

/* FilaUnicaBundle:Vaga:listaVagas.html.twig */
class __TwigTemplate_9ef55a01111d9ff4088e4d730f7604efbaa1f2790d14763f2b9859d5e826b551 extends Twig_Template
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
        echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["vagas"]) ? $context["vagas"] : $this->getContext($context, "vagas"))), "html", null, true);
        echo " vagas encontradas</em>
    </p>
    <table class=\"table table-hover table-striped\">
        <thead>
            <tr>
                <th class=\"text-center\">Cod.</th>
                <th class=\"text-center\">Unidade</th>
                <th class=\"text-center\">Ano Escolar</th>
                <th class=\"text-center\">Turno</th>
                <th class=\"text-center\">Em Chamada</th>
                <th class=\"text-center\">Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vagas"]) ? $context["vagas"] : $this->getContext($context, "vagas")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["vaga"]) {
            // line 20
            echo "                <tr>
                    <td>
                        <span title=\"Criada em ";
            // line 22
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "dataCadastro"), "d/m/Y G:i:s"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"), "html", null, true);
            echo "</span>
                    </td>
                    <td>";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidadeEscolar"), "nome"), "html", null, true);
            echo " | ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidadeEscolar"), "zoneamento"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "anoEscolar"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 26
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "periodoDia"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        ";
            // line 28
            if ($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada")) {
                echo " 
                            <a class=\"ajaxLink\" href=\"";
                // line 29
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_consultar", array("inscricao" => $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "id"))), "html", null, true);
                echo "\">
                                <span title=\"";
                // line 30
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "dataChamada"), "d/m/Y G:i:s"), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "protocolo"), "html", null, true);
                echo " </span>
                            </a> - ";
                // line 31
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "crianca"), "nome"), "html", null, true);
                echo "
                        ";
            }
            // line 33
            echo "                    </td>
                    <td>
                        ";
            // line 35
            if ($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada")) {
                // line 36
                echo "                            <a class=\"ajaxLink\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_exibirHistorico", array("inscricao" => $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "id"))), "html", null, true);
                echo "\">Histórico</a>
                            <br /> <a class=\"ajaxLink\" href=\"";
                // line 37
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_vaga_formGerencia", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
                echo "\">Atualizar</a>
                        ";
            } elseif ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
                // line 39
                echo "                            <a class=\"ajaxAction\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_vaga_preencher", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
                echo "\">Preencher</a>
                            <br /> <a class=\"ajaxAction\" href=\"";
                // line 40
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_vaga_cancelar", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
                echo "\">Cancelar</a>
                        ";
            }
            // line 42
            echo "                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 45
            echo "                <tr>
                    <td class=\"text-center\" colspan=\"6\"> <em>Nenhuma vaga encontrada</em> </td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vaga'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "         </tbody>
    </table>

    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\"></div>
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
        \$(\".ajaxLink\").click(function(ev) {
            \$.ajax({
                type: \"GET\",
                url: \$(this).attr(\"href\"),
                success: function(retorno) {
                    \$(\"#divModal\").html(retorno);
                }
            });
            \$(\"#modalDialog\").modal(\"show\");
            ev.preventDefault();
        });
        
        \$(\".ajaxAction\").click(function(ev) {
            \$.ajax({
                type: \"POST\",
                url: \$(this).attr(\"href\"),
                data: \$(\"#formPesquisaVaga\").serialize(),
                success: function(retorno) {
                    \$(\"#divListaVagas\").html(retorno);
                }
            });
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Vaga:listaVagas.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  157 => 58,  154 => 57,  144 => 49,  135 => 45,  128 => 42,  123 => 40,  118 => 39,  113 => 37,  108 => 36,  106 => 35,  102 => 33,  97 => 31,  91 => 30,  87 => 29,  83 => 28,  78 => 26,  74 => 25,  68 => 24,  61 => 22,  57 => 20,  52 => 19,  35 => 5,  32 => 4,  29 => 3,);
    }
}

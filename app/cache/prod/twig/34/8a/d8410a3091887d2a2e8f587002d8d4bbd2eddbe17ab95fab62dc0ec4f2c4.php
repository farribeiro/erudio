<?php

/* DGPBundle:Alocacao:alocacoesPorVinculo.html.twig */
class __TwigTemplate_348ad8410a3091887d2a2e8f587002d8d4bbd2eddbe17ab95fab62dc0ec4f2c4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
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
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        if ((((isset($context["showCad"]) ? $context["showCad"] : $this->getContext($context, "showCad")) >= 0) && ((isset($context["showCad"]) ? $context["showCad"] : $this->getContext($context, "showCad")) <= 40))) {
            // line 5
            echo "        <button id=\"btnIncluirAlocacao\" class=\"btn btn-primary\" >
            <span class=\"glyphicon glyphicon-plus\"></span>
            Incluir Alocação
        </button>
        <br /><br />
    ";
        }
        // line 11
        echo "    
    ";
        // line 12
        if (((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")) != "")) {
            // line 13
            echo "        ";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "
    ";
        } else {
            // line 15
            echo "        <strong>Alocações</strong>
        <table class=\"table table-striped table-hover\">
            <thead>
                <tr>
                    <th>Local de trabalho</th>
                    <th>Carga Horária</th>
                    <th>Função</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                ";
            // line 26
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["alocacoes"]) ? $context["alocacoes"] : $this->getContext($context, "alocacoes")));
            $context['_iterated'] = false;
            foreach ($context['_seq'] as $context["_key"] => $context["alocacao"]) {
                // line 27
                echo "                    <tr>
                        <td>";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "localTrabalho"), "pessoaJuridica"), "nome"), "html", null, true);
                echo "</td>
                        <td>";
                // line 29
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "cargaHoraria"), "html", null, true);
                echo "</td>
                        <td>";
                // line 30
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
                echo "</td>
                        <td class=\"text-center\">
                            <a class=\"lnkAlterar\" href=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_alterar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "alocacao" => $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"))), "html", null, true);
                echo "\"><span class=\"glyphicon glyphicon-edit\"></span></a> |
                            <a class=\"lnkExcluir\" href=\"";
                // line 33
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_excluir", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "alocacao" => $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"))), "html", null, true);
                echo "\"><span class=\"glyphicon glyphicon-remove\"></span></a>
                        </td>
                    </tr>
                ";
                $context['_iterated'] = true;
            }
            if (!$context['_iterated']) {
                // line 37
                echo "                    <tr>
                        <td class=\"text-center\" colspan=\"4\">Nenhuma alocação encontrada</td>
                    </tr>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alocacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 41
            echo "            </tbody>
        </table>
    ";
        }
    }

    // line 46
    public function block_javascript($context, array $blocks = array())
    {
        // line 47
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">\t\t\t
        \$(\"#btnIncluirAlocacao\").click(function (e){
            e.preventDefault();
            \$.ajax({
                url: \"";
        // line 52
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_cadastrar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\",
                type: \"GET\",
                success: function (data) {
                    if (data !== \"error\") {
                        \$(\".ajaxAllocation";
        // line 56
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data);
                    } else {
                        \$.bootstrapGrowl(\"Houve um problema com o formulário de alocação, tente mais tarde.\", { type: 'danger', delay: 3000 });
                    }
                }
            });
        });

        \$(\".lnkAlterar\").click(function(ev){
            var url = \$(this).attr(\"href\");
            ev.preventDefault();
            \$.ajax({
                url: url,
                type: \"GET\",
                success: function (data) {
                    \$(\".ajaxAllocation";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data);
                }
            });
        });

        \$(\".lnkExcluir\").click(function(ev){
            var url = \$(this).attr(\"href\");
            ev.preventDefault();
            \$.ajax({
                url: url,
                type: \"POST\",
                success: function (data) {
                    if (data !== \"error\") {
                        \$.bootstrapGrowl(\"Alocação excluída com sucesso.\", { type: 'success', delay: 3000 });
                        \$(\".ajaxAllocation";
        // line 85
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data);
                    } else {
                        \$.bootstrapGrowl(\"Houve um erro com a exclusão de sua alocação.\", { type: 'danger', delay: 3000 });
                    }
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Alocacao:alocacoesPorVinculo.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  171 => 85,  154 => 71,  136 => 56,  129 => 52,  120 => 47,  117 => 46,  110 => 41,  101 => 37,  92 => 33,  88 => 32,  83 => 30,  79 => 29,  75 => 28,  72 => 27,  67 => 26,  54 => 15,  48 => 13,  46 => 12,  43 => 11,  35 => 5,  32 => 4,  29 => 3,);
    }
}

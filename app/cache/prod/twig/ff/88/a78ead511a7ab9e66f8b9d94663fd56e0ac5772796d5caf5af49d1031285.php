<?php

/* ProtocoloBundle:Servidor:listaProtocolos.html.twig */
class __TwigTemplate_ff88a78ead511a7ab9e66f8b9d94663fd56e0ac5772796d5caf5af49d1031285 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Servidor:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Servidor:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Servidor > Requerimentos";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div id=\"div100\">
        <form method=\"post\" action=\"";
        // line 7
        echo $this->env->getExtension('routing')->getUrl("protocolo_servidor_formSolicitacao");
        echo "\">
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    <label for=\"solicitacao\">Fazer novo requerimento:</label>
                    <div class=\"input-group\">
                        <select id=\"solicitacao\" name=\"solicitacao\" class=\"form-control\" required=\"true\">
                            <option value=\"\"></option>
                            ";
        // line 14
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["solicitacoes"]) ? $context["solicitacoes"] : $this->getContext($context, "solicitacoes")));
        foreach ($context['_seq'] as $context["_key"] => $context["solicitacao"]) {
            // line 15
            echo "                                <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "nome"), "html", null, true);
            echo "</option>
                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['solicitacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "                        </select>
                        <span class=\"input-group-btn\">
                            <button type=\"submit\" class=\"btn btn-primary\">Enviar</button>
                        </span>
                    </div>
                </div>
            </div>
        </form>
        <br />
        <table class=\"table table-striped table-hover\">
            <thead>
                <tr>
                    <th></th>
                    <th>Número</th>
                    <th>Categoria</th>
                    <th>Solicitação</th>
                    <th>Situação</th>
                    <th>Abertura</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 39
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["protocolos"]) ? $context["protocolos"] : $this->getContext($context, "protocolos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["protocolo"]) {
            // line 40
            echo "                    <tr>
                        <td>
                            ";
            // line 42
            if (($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual") && $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo"))) {
                // line 43
                echo "                                <span class=\"glyphicon glyphicon-flag text-info\" title=\"Em atividade\"></span>
                            ";
            } elseif ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo")) {
                // line 45
                echo "                                <span class=\"glyphicon glyphicon-flag text-success\" title=\"Novo\"></span>
                            ";
            } else {
                // line 47
                echo "                                <span class=\"glyphicon glyphicon-folder-close text-warning\" title=\"Arquivado\"></span>
                            ";
            }
            // line 49
            echo "                        </td>
                        <td>";
            // line 50
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "protocolo"), "html", null, true);
            echo "</td>
                        <td>";
            // line 51
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "categoria"), "nome"), "html", null, true);
            echo "</td>
                        <td>";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "nome"), "html", null, true);
            echo "</td>
                        <td>";
            // line 53
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "nome"), "html", null, true);
            echo "</td>
                        <td>";
            // line 54
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                        <td>
                            <a class=\"ajaxLink\" href=\"";
            // line 56
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_visualizar", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
            echo "\">Detalhes</a>
                            ";
            // line 57
            if ($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "impresso")) {
                // line 58
                echo "                                <br />
                                <a target=\"_blank\" href=\"";
                // line 59
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_imprimir", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Imprimir</a>
                            ";
            }
            // line 61
            echo "                        </td>
                    </tr>
                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 64
            echo "                    <tr> <td class=\"text-center\" colspan=\"7\">Nenhum protocolo cadastrado nesta categoria.</td> </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['protocolo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        echo "            </tbody>
        </table>    
    </div>
    
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\"></div>
    </div>
";
    }

    // line 75
    public function block_javascript($context, array $blocks = array())
    {
        // line 76
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
            \$('#modalDialog').modal({\"show\": true});
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Servidor:listaProtocolos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  180 => 76,  177 => 75,  166 => 66,  159 => 64,  152 => 61,  147 => 59,  144 => 58,  142 => 57,  138 => 56,  133 => 54,  129 => 53,  125 => 52,  121 => 51,  117 => 50,  114 => 49,  110 => 47,  106 => 45,  102 => 43,  100 => 42,  96 => 40,  91 => 39,  67 => 17,  56 => 15,  52 => 14,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

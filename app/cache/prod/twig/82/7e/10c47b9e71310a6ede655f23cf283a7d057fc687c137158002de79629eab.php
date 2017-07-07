<?php

/* DGPPromocaoBundle:Servidor:promocoes.html.twig */
class __TwigTemplate_827e10c47b9e71310a6ede655f23cf283a7d057fc687c137158002de79629eab extends Twig_Template
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
        echo "Servidor > Requerimentos > Ascensão Funcional";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Promoção Horizontal</div>
        <div class=\"panel-body\">
            <table class=\"table table-hover\">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Solicitado Em</th>
                        <th>Status</th>
                        <th class=\"text-center\">Encerrado Em</th>
                        <th class=\"text-center\">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["promocoesHorizontais"]) ? $context["promocoesHorizontais"] : $this->getContext($context, "promocoesHorizontais")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["promocao"]) {
            // line 21
            echo "                        <tr>
                            <td>";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "protocolo"), "html", null, true);
            echo "</td>
                            <td>";
            // line 23
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataCadastro"), "d/m/Y H:i"), "html", null, true);
            echo "</td>
                            <td>
                                ";
            // line 25
            if ($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "deferido")) {
                // line 26
                echo "                                    <span class=\"text-success\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "</span>
                                ";
            } elseif (($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "indeferido") || $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "cancelado"))) {
                // line 28
                echo "                                    <span class=\"text-danger\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "</span>
                                ";
            } else {
                // line 30
                echo "                                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "
                                ";
            }
            // line 32
            echo "                            </td>
                            <td class=\"text-center\">";
            // line 33
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataEncerramento")) ? (twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataEncerramento"), "d/m/Y")) : ("-")), "html", null, true);
            echo "</td>
                            <td class=\"text-center\">
                                <a class=\"lnkDetalhes\" href=\"";
            // line 35
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoHorizontal_visualizar", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
            echo "\">Detalhes</a>
                                | <a target=\"_blank\" href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoHorizontal_imprimir", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
            echo "\">Imprimir</a>
                                ";
            // line 37
            if (($this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "id") == twig_constant("SME\\DGPPromocaoBundle\\Entity\\Status::AGUARDANDO_ENTREGA"))) {
                // line 38
                echo "                                    | <a class=\"lnkCancelar\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoHorizontal_cancelar", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
                echo "\">Desfazer</a>
                                ";
            }
            // line 40
            echo "                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 43
            echo "                        <tr>
                            <td class=\"text-center\" colspan=\"5\">
                                <em>Nenhuma solicitação cadastrada nesta categoria</em>
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['promocao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "                </tbody>
            </table>
            <a class=\"btn btn-primary\" href=\"";
        // line 51
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoHorizontal_cadastrar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\">Novo Requerimento</a>
        </div>
    </div>
                    
    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Promoção Vertical</div>
        <div class=\"panel-body\">
            <table class=\"table table-hover\">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Solicitado Em</th>
                        <th>Status</th>
                        <th class=\"text-center\">Encerrado Em</th>
                        <th class=\"text-center\">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 69
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["promocoesVerticais"]) ? $context["promocoesVerticais"] : $this->getContext($context, "promocoesVerticais")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["promocao"]) {
            // line 70
            echo "                        <tr>
                            <td>";
            // line 71
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "protocolo"), "html", null, true);
            echo "</td>
                            <td>";
            // line 72
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataCadastro"), "d/m/Y H:i"), "html", null, true);
            echo "</td>
                            <td>
                                ";
            // line 74
            if ($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "deferido")) {
                // line 75
                echo "                                    <span class=\"text-success\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "</span>
                                ";
            } elseif (($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "indeferido") || $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "cancelado"))) {
                // line 77
                echo "                                    <span class=\"text-danger\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "</span>
                                ";
            } else {
                // line 79
                echo "                                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "nome"), "html", null, true);
                echo "
                                ";
            }
            // line 81
            echo "                            </td>
                            <td class=\"text-center\">";
            // line 82
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataInicio")) ? (twig_date_format_filter($this->env, $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "dataInicio"), "d/m/Y")) : ("-")), "html", null, true);
            echo "</td>
                            <td class=\"text-center\">
                                <a class=\"lnkDetalhes\" href=\"";
            // line 84
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoVertical_visualizar", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
            echo "\">Detalhes</a>
                                | <a target=\"_blank\" href=\"";
            // line 85
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoVertical_imprimir", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
            echo "\">Imprimir</a>
                                ";
            // line 86
            if (($this->getAttribute($this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "status"), "id") == twig_constant("SME\\DGPPromocaoBundle\\Entity\\Status::AGUARDANDO_ENTREGA"))) {
                // line 87
                echo "                                    | <a class=\"lnkCancelar\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoVertical_cancelar", array("promocao" => $this->getAttribute((isset($context["promocao"]) ? $context["promocao"] : $this->getContext($context, "promocao")), "id"))), "html", null, true);
                echo "\">Desfazer</a>
                                ";
            }
            // line 89
            echo "                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 92
            echo "                        <tr>
                            <td class=\"text-center\" colspan=\"5\">
                                <em>Nenhuma solicitação cadastrada nesta categoria</em>
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['promocao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 98
        echo "                </tbody>
            </table>
            <a class=\"btn btn-primary\" href=\"";
        // line 100
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocaoVertical_cadastrar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\">Novo Requerimento</a>
        </div>
    </div>
            
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 80%;\">
            ";
        // line 106
        $this->env->loadTemplate("DGPPromocaoBundle:Servidor:promocoes.html.twig", "2016886532")->display($context);
        // line 110
        echo "        </div>
    </div>
";
    }

    // line 114
    public function block_javascript($context, array $blocks = array())
    {
        // line 115
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\".lnkDetalhes\").click(function(ev) {
            \$.ajax({
                type: \"GET\",
                url: \$(this).attr(\"href\"),
                success: function(retorno) {
                    \$(\"#divDetalhes\").html(retorno);
                }
            });
            \$('#modalDialog').modal(\"show\");
            ev.preventDefault();
        });
        
        \$(\".lnkCancelar\").click(function(ev) {
            if(!confirm(\"Deseja realmente cancelar este requerimento?\")) {
                ev.preventDefault();
            } else {
                
            }
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPPromocaoBundle:Servidor:promocoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  262 => 115,  259 => 114,  253 => 110,  251 => 106,  242 => 100,  238 => 98,  227 => 92,  220 => 89,  214 => 87,  212 => 86,  208 => 85,  204 => 84,  199 => 82,  196 => 81,  190 => 79,  184 => 77,  178 => 75,  176 => 74,  171 => 72,  167 => 71,  164 => 70,  159 => 69,  138 => 51,  134 => 49,  123 => 43,  116 => 40,  110 => 38,  108 => 37,  104 => 36,  100 => 35,  95 => 33,  92 => 32,  86 => 30,  80 => 28,  74 => 26,  72 => 25,  67 => 23,  63 => 22,  60 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}


/* DGPPromocaoBundle:Servidor:promocoes.html.twig */
class __TwigTemplate_827e10c47b9e71310a6ede655f23cf283a7d057fc687c137158002de79629eab_2016886532 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::templateModal.html.twig");

        $this->blocks = array(
            'modalTitle' => array($this, 'block_modalTitle'),
            'modalBody' => array($this, 'block_modalBody'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::templateModal.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 107
    public function block_modalTitle($context, array $blocks = array())
    {
        echo "Detalhes";
    }

    // line 108
    public function block_modalBody($context, array $blocks = array())
    {
        echo "<div id=\"divDetalhes\"></div>";
    }

    public function getTemplateName()
    {
        return "DGPPromocaoBundle:Servidor:promocoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  338 => 108,  332 => 107,  262 => 115,  259 => 114,  253 => 110,  251 => 106,  242 => 100,  238 => 98,  227 => 92,  220 => 89,  214 => 87,  212 => 86,  208 => 85,  204 => 84,  199 => 82,  196 => 81,  190 => 79,  184 => 77,  178 => 75,  176 => 74,  171 => 72,  167 => 71,  164 => 70,  159 => 69,  138 => 51,  134 => 49,  123 => 43,  116 => 40,  110 => 38,  108 => 37,  104 => 36,  100 => 35,  95 => 33,  92 => 32,  86 => 30,  80 => 28,  74 => 26,  72 => 25,  67 => 23,  63 => 22,  60 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}

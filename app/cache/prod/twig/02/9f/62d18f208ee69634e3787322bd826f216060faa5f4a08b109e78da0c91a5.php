<?php

/* ProtocoloBundle:Protocolo:listaProtocolos.html.twig */
class __TwigTemplate_029f62d18f208ee69634e3787322bd826f216060faa5f4a08b109e78da0c91a5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
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
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Protocolo";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <table class=\"table table-hover\">
        <thead>
            <tr>
                <th></th>
                <th>Número</th>
                <th>Abertura</th>
                <th>Requerente</th>
                <th>Solicitação</th>
                <th>Situação</th>
                <th>Responsável</th>
                <th style=\"width: 13%;\">Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["protocolos"]) ? $context["protocolos"] : $this->getContext($context, "protocolos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["protocolo"]) {
            // line 21
            echo "                <tr>
                    <td> 
                        ";
            // line 23
            if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "encaminhado")) {
                // line 24
                echo "                            <span class=\"glyphicon glyphicon-flag text-danger\" title=\"Encaminhado\"></span> 
                        ";
            } elseif (($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual") && $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo"))) {
                // line 26
                echo "                            <span class=\"glyphicon glyphicon-flag text-info\" title=\"Em atividade\"></span>
                        ";
            } elseif ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo")) {
                // line 28
                echo "                            <span class=\"glyphicon glyphicon-flag text-success\" title=\"Novo\"></span>
                        ";
            } else {
                // line 30
                echo "                            <span class=\"glyphicon glyphicon-folder-close text-warning\" title=\"Arquivado\"></span>
                        ";
            }
            // line 32
            echo "                    </td>
                    <td>";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "protocolo"), "html", null, true);
            echo "</td>
                    <td>";
            // line 34
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td>
                        <a class=\"ajaxLink\" href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("protocolo_admin_consultarRequerente", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "requerente"), "nome"), "html", null, true);
            echo "</a>
                    </td>
                    <td>";
            // line 38
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "nome"), "html", null, true);
            echo "</td>
                    <td>
                        ";
            // line 41
            if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual")) {
                echo " ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual"), "nome"), "html", null, true);
                echo " ";
            }
            // line 42
            echo "                    </td>
                    <td>
                        ";
            // line 44
            if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "encaminhado")) {
                // line 45
                echo "                            <a class=\"ajaxAction\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_cancelarEncaminhamento", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Retornar</a> 
                        ";
            } elseif ((($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual") && ($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual"), "id") == $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "id"))) && $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo"))) {
                // line 47
                echo "                            <a class=\"ajaxLink\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_formAtualizacao", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Editar</a>
                            | <a class=\"ajaxLink\" href=\"";
                // line 48
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_formEncaminhamento", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Encaminhar</a>
                        ";
            } elseif ((($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual") && $this->env->getExtension('security')->isGranted("ROLE_PROTOCOLO_GERENTE")) && $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo"))) {
                // line 50
                echo "                            <a class=\"ajaxAction\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_tomarPosse", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Tomar posse</a>
                        ";
            } elseif ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "ativo")) {
                // line 52
                echo "                            <a class=\"ajaxAction\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_receber", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Receber</a>
                        ";
            } else {
                // line 54
                echo "                            <a class=\"ajaxLink\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_visualizar", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Detalhes</a>
                        ";
            }
            // line 56
            echo "                        ";
            if ($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "impresso")) {
                // line 57
                echo "                            <br />
                            <a target=\"_blank\" href=\"";
                // line 58
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_imprimir", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
                echo "\">Imprimir</a>
                        ";
            }
            // line 60
            echo "                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 63
            echo "                <tr> <td class=\"text-center\" colspan=\"8\"> <em>Nenhum protocolo encontrado</em> </td> </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['protocolo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 65
        echo "        </tbody>
    </table>
    
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\"></div>
    </div>
";
    }

    // line 73
    public function block_javascript($context, array $blocks = array())
    {
        // line 74
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
        
        \$(\".ajaxAction\").click(function(ev) {
            \$.ajax({
                type: \"POST\",
                url: \$(this).attr(\"href\"),
                data: \$(\"#formPesquisaProtocolo\").serialize(),
                success: function(retorno) {
                    \$(\"#divListaProtocolos\").html(retorno);
                }
            });
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Protocolo:listaProtocolos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  194 => 74,  191 => 73,  181 => 65,  174 => 63,  167 => 60,  162 => 58,  159 => 57,  156 => 56,  150 => 54,  144 => 52,  138 => 50,  133 => 48,  128 => 47,  122 => 45,  120 => 44,  116 => 42,  110 => 41,  105 => 39,  101 => 38,  94 => 36,  89 => 34,  85 => 33,  82 => 32,  78 => 30,  74 => 28,  70 => 26,  66 => 24,  64 => 23,  60 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}

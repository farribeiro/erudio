<?php

/* ProtocoloBundle:Protocolo:modalFormAtualizacao.html.twig */
class __TwigTemplate_1d195a0fdae91b590f4393511535ac6ddd575f92a5ee9ee680607ebde4327e96 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"modal-content\">
    <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        <h4 class=\"modal-title\">Protocolo ";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "protocolo"), "html", null, true);
        echo "</h4>
    </div>
    <div class=\"modal-body\">
        <form id=\"formProtocolo\">
            <table class=\"table table-striped table-hover\">
                <tr>
                    <th>Requerente</th>
                    <td>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "requerente"), "nome"), "html", null, true);
        echo "</td>
                </tr>
                <tr>
                    <th>Solicitação</th>
                    <td>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "nome"), "html", null, true);
        echo "</td>
                </tr>
                <tr>
                    <th>Situação</th>
                    <td>
                        <select id=\"situacao\" name=\"situacao\" class=\"form-control\" required=\"true\">
                            ";
        // line 21
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["situacoes"]) ? $context["situacoes"] : $this->getContext($context, "situacoes")));
        foreach ($context['_seq'] as $context["_key"] => $context["situacao"]) {
            // line 22
            echo "                                ";
            if ($this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "terminal")) {
                // line 23
                echo "                                    <option ";
                if (($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "id") == $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"))) {
                    echo "selected=\"true\"";
                }
                echo " class=\"terminal text-warning\" value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"), "html", null, true);
                echo "\"> 
                                        ";
                // line 24
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "nome"), "html", null, true);
                echo "
                                    </option>
                                ";
            } else {
                // line 27
                echo "                                    <option ";
                if (($this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "id") == $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"))) {
                    echo "selected=\"true\"";
                }
                echo " value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"), "html", null, true);
                echo "\">
                                        ";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "nome"), "html", null, true);
                echo "
                                    </option>
                                ";
            }
            // line 31
            echo "                            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['situacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Último Responsável</th>
                    <td>";
        // line 37
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "responsavelAtual"), "nome"), "html", null, true);
        echo "</td>
                </tr>
                <tr>
                    <th>Data de Abertura</th>
                    <td>";
        // line 41
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataCadastro"), "d/m/Y"), "html", null, true);
        echo "</td>
                </tr>
                <tr>
                    <th>Última atualização</th>
                    <td>";
        // line 45
        if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataModificacao")) {
            echo " ";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataModificacao"), "d/m/Y"), "html", null, true);
            echo " ";
        }
        echo "</td>
                </tr>
                <tr>
                    <th>Data de Encerramento</th>
                    <td>";
        // line 49
        if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataEncerramento")) {
            echo " ";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataEncerramento"), "d/m/Y"), "html", null, true);
            echo " ";
        }
        echo "</td>
                </tr>
                <tr>
                    <th>Observação</th>
                    <td>
                        <textarea id=\"observacao\" name=\"observacao\" class=\"form-control form-control-static\" rows=\"5\">";
        // line 54
        echo twig_escape_filter($this->env, trim($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "observacao")), "html", null, true);
        echo "</textarea>
                    </td>
                </tr>
            </table>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    <button id=\"btnAtualizar\" type=\"button\" class=\"btn btn-primary btn-block\">Atualizar</button>
                </div>
                <div class=\"col-lg-6\">
                    <button id=\"btnEncerrar\" type=\"button\" class=\"btn btn-block ";
        // line 63
        if ((!$this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "terminal"))) {
            echo "disabled";
        } else {
            echo "btn-danger";
        }
        echo "\">Encerrar</button>
                </div>
            </div>
            <input type=\"hidden\" id=\"encerrar\" name=\"encerrar\" value=\"0\" />
        </form>
    </div>
</div>

<script type=\"text/javascript\">
    \$(\"#btnAtualizar\").click(function() {
        \$(\"#encerrar\").attr('value', 0);
        \$.ajax({
            type: \"POST\",
            url: \"";
        // line 76
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_atualizar", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
        echo "\",
            data: \$(\"#formProtocolo, #formPesquisaProtocolo\").serialize(),
            success: function(retorno) {
                \$(\"#divListaProtocolos\").html(retorno);
            }
        });
        \$(\"#modalDialog\").modal('toggle');
    });
    
    \$(\"#btnEncerrar\").click(function() {
        if(confirm(\"Deseja realmente finalizar esta solicitação ?\")) {
            \$(\"#encerrar\").attr('value', 1);
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 90
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_atualizar", array("protocolo" => $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "id"))), "html", null, true);
        echo "\",
                data: \$(\"#formProtocolo, #formPesquisaProtocolo\").serialize(),
                success: function(retorno) {
                    \$(\"#divListaProtocolos\").html(retorno);
                }
            });
        }
        \$(\"#modalDialog\").modal('toggle');
    });
    
    \$(\"#situacao\").change(function() {
        if(\$(this).find(\":selected\").hasClass(\"terminal\")) {
            \$(\"#btnEncerrar\").addClass(\"btn-danger\");
            \$(\"#btnEncerrar\").removeClass(\"disabled\");
        } else {
            \$(\"#btnEncerrar\").addClass(\"disabled\");
            \$(\"#btnEncerrar\").removeClass(\"btn-danger\");
        }
    });
</script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Protocolo:modalFormAtualizacao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  186 => 90,  169 => 76,  149 => 63,  137 => 54,  125 => 49,  114 => 45,  107 => 41,  100 => 37,  93 => 32,  87 => 31,  81 => 28,  72 => 27,  66 => 24,  57 => 23,  54 => 22,  50 => 21,  41 => 15,  34 => 11,  24 => 4,  19 => 1,);
    }
}

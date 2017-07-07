<?php

/* ProtocoloBundle:Protocolo:modalConsultaProtocolo.html.twig */
class __TwigTemplate_1d3a0a162cbd3fb43debb9cf9fd12b518051a00314f36e10b05d7c1fdc31c652 extends Twig_Template
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
        <table class=\"table table-striped table-hover\">
            <tr>
                <th style=\"width: 20%;\">Requerente</th>
                <td>";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "requerente"), "nome"), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Solicitação</th>
                <td>";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "solicitacao"), "nome"), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Situação</th>
                <td>";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "situacao"), "nome"), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Data de Abertura</th>
                <td>";
        // line 22
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataCadastro"), "d/m/Y"), "html", null, true);
        echo "</td>
            </tr>
            <tr>
                <th>Última atualização</th>
                <td>";
        // line 26
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
        // line 30
        if ($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataEncerramento")) {
            echo " ";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "dataEncerramento"), "d/m/Y"), "html", null, true);
            echo " ";
        }
        echo "</td>
            </tr>
            <tr>
                <th>Observação</th>
                <td>";
        // line 34
        echo twig_escape_filter($this->env, trim($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "observacao")), "html", null, true);
        echo "</td>
            </tr>
        </table>
        <p> <strong>Histórico:</strong> </p>
        <table class=\"table table-striped table-hover\">
            <tr>
                <th>Data</th>
                <th>Encaminhado por</th>
                <th>Recebido por</th>
                <th>Motivo</th>
            </tr>
            ";
        // line 45
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["protocolo"]) ? $context["protocolo"] : $this->getContext($context, "protocolo")), "encaminhamentos"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["encaminhamento"]) {
            // line 46
            echo "                <tr>
                    <td>";
            // line 47
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td>";
            // line 48
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "pessoaEncaminha"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 49
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "pessoaRecebe"), "nome"), "html", null, true);
            echo "</td>
                    ";
            // line 50
            if ((!(null === $this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "motivo")))) {
                // line 51
                echo "                        <td>";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["encaminhamento"]) ? $context["encaminhamento"] : $this->getContext($context, "encaminhamento")), "motivo"), "descricao"), "html", null, true);
                echo "</td>
                    ";
            } else {
                // line 53
                echo "                        <td></td>
                    ";
            }
            // line 55
            echo "                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 57
            echo "                <tr> 
                    <td class=\"text-center\" colspan=\"4\"> <em>Nenhum encaminhamento realizado</em> </td> 
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['encaminhamento'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "        </table>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Protocolo:modalConsultaProtocolo.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  144 => 61,  135 => 57,  129 => 55,  125 => 53,  119 => 51,  117 => 50,  113 => 49,  109 => 48,  105 => 47,  102 => 46,  97 => 45,  83 => 34,  72 => 30,  61 => 26,  54 => 22,  47 => 18,  40 => 14,  33 => 10,  24 => 4,  19 => 1,);
    }
}

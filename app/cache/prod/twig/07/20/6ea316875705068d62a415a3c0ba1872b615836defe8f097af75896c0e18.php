<?php

/* ProtocoloBundle:Forms:LicencaPremio.html.twig */
class __TwigTemplate_07206ea316875705068d62a415a3c0ba1872b615836defe8f097af75896c0e18 extends Twig_Template
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
        echo "Requerimento de Licença-Prêmio";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_incluirSolicitacao", array("solicitacao" => $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"))), "html", null, true);
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"vinculo\">Cargo da licença:</label>
                <select name=\"vinculo\" class=\"form-control\" required=\"true\">
                    ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 12
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
            echo " horas (";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "nome"), "html", null, true);
            echo ")</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"alocacao\">Função atual:</label>
                <select name=\"alocacao\" class=\"form-control\" required >
                    ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 20
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "alocacoes"));
            foreach ($context['_seq'] as $context["_key"] => $context["alocacao"]) {
                // line 21
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "localTrabalho"), "pessoaJuridica"), "nome"), "html", null, true);
                echo "</option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alocacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 24
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
             <div class=\"col-lg-4\">
                <label for=\"matricula\">Matrícula:</label>
                <input type=\"text\" name=\"matricula\" class=\"form-control\" required />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"quinquenio\">Quinquênio (Ex: 2010/2014):</label>
                <input type=\"text\" name=\"quinquenio\" class=\"form-control\" required placeholder=\"Ano / ano\"/>
            </div>
            <div class=\"col-lg-4\">
                <label for=\"data_inicio\">Data de início da licença:</label>
                <input type=\"text\" name=\"data_inicio\" class=\"form-control datepickerSME\" required />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"conversao_abono\">Conversão de 1/3 (um mês) em abono pecuniário:</label>
                <select id=\"conversaoAbono\" name=\"conversao_abono\" class=\"form-control\" required >
                        <option value=\"Não\">Não</option>
                        <option value=\"Sim\">Sim</option>
                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <button id=\"btnIncluir\" type=\"submit\" class=\"btn btn-primary\">Incluir</button>
            </div>
        </div>
    </form>
";
    }

    // line 58
    public function block_javascript($context, array $blocks = array())
    {
        // line 59
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#conversaoAbono\").change(function() {
            if(\$(this).val() === \"Sim\") {
                \$(\"#divDataAbono\").show();
            } else {
                \$(\"#divDataAbono\").hide();
            }
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Forms:LicencaPremio.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  141 => 59,  138 => 58,  102 => 24,  96 => 23,  83 => 21,  78 => 20,  74 => 19,  67 => 14,  52 => 12,  48 => 11,  39 => 6,  36 => 5,  30 => 3,);
    }
}

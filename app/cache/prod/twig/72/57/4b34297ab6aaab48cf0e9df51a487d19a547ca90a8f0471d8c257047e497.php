<?php

/* ProtocoloBundle:Forms:TempoServico.html.twig */
class __TwigTemplate_72574b34297ab6aaab48cf0e9df51a487d19a547ca90a8f0471d8c257047e497 extends Twig_Template
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
        echo "Requerimento de Tempo de Serviço no Magistério Público Municipal";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_incluirSolicitacao", array("solicitacao" => $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"))), "html", null, true);
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"matricula\">Matrícula:</label>
                <input type=\"text\" name=\"matricula\" class=\"form-control\" required=\"true\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"objetivo_label\">Finalidade do documento:</label>
                <select name=\"objetivo\" id=\"objetivo\" class=\"form-control\" required=\"true\">
                    <option value=\"Simulação de aposentadoria\">Simulação de aposentadoria</option>
                    <option value=\"Processo seletivo\">Processo seletivo</option>
                    <option value=\"Concurso público\">Concurso público</option>
                    <option value=\"Outro\">Outro</option>
                 </select>
            </div>
            <div class=\"col-lg-6\">
                <label id=\"outro_label\" for=\"outro\" style=\"display: none;\">Outro:</label>
                <input id=\"outro\" name=\"outro\" type=\"text\" size=\"30\" style=\"display: none;\" class=\"form-control\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"anoInicio\">Quando trabalhou pela primeira vez no município:</label>
                <select name=\"anoInicio\" class=\"form-control\" required=\"true\">
                    <option value=\"Antes de 1970\">Antes de 1970</option>
                    <option value=\"Entre 1970 e 1975\">Entre 1970 e 1975</option>
                    <option value=\"Entre 1975 e 1980\">Entre 1975 e 1980</option>
                    <option value=\"Entre 1980 e 1985\">Entre 1980 e 1985</option>
                    <option value=\"Entre 1985 e 1990\">Entre 1985 e 1990</option>
                    <option value=\"Entre 1990 e 1995\">Entre 1990 e 1995</option>
                    <option value=\"Entre 1995 e 2000\">Entre 1995 e 2000</option>
                    <option value=\"Entre 2000 e 2005\">Entre 2000 e 2005</option>
                    <option value=\"Após 2005\">Após 2005</option>
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

    // line 52
    public function block_javascript($context, array $blocks = array())
    {
        // line 53
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#objetivo\").change(function() {
            if(\$(this).val() === \"Outro\") {
                \$(\"#outro\").show();
                \$(\"#outro_label\").show();
            } else {
                \$(\"#outro\").hide();
                \$(\"#outro_label\").hide();
            }
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Forms:TempoServico.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 53,  89 => 52,  39 => 6,  36 => 5,  30 => 3,);
    }
}

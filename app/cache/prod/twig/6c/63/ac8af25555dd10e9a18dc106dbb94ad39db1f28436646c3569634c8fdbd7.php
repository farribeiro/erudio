<?php

/* FilaUnicaBundle:Inscricao:formOrdemJudicial.html.twig */
class __TwigTemplate_6c63ac8af25555dd10e9a18dc106dbb94ad39db1f28436646c3569634c8fdbd7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FilaUnicaBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
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
    public function block_headerTitle($context, array $blocks = array())
    {
        echo " Fila Única > Inscrição > Processo Judicial";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formordemJudicial\" method=\"post\" action=\"";
        echo $this->env->getExtension('routing')->getPath("fu_inscricao_cadastrarOrdemJudicial");
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"protocolo\">Protocolo da inscrição:</label>
                <input id=\"protocolo\" name=\"protocolo\" type=\"text\" class=\"form-control\" required=\"true\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"ordemJudicial\">Número da ordem judicial:</label>
                <input id=\"ordemJudicial\" name=\"ordemJudicial\" type=\"text\" class=\"form-control\" required=\"true\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"dataProcessoJudicial\">Data do Processo:</label>
                <input type=\"text\" id=\"dataProcessoJudicial\" name=\"dataProcessoJudicial\" class=\"form-control datepickerSME\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <button id=\"btnCadastrar\" type=\"submit\" class=\"btn btn-primary\">Cadastrar</button>
            </div>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:formOrdemJudicial.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 6,  35 => 5,  29 => 3,);
    }
}

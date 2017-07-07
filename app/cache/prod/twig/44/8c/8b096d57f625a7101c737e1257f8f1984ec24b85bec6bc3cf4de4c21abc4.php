<?php

/* ProtocoloBundle:Forms:Simples.html.twig */
class __TwigTemplate_448c8b096d57f625a7101c737e1257f8f1984ec24b85bec6bc3cf4de4c21abc4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Servidor:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
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
        echo "Requerimento Simples";
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
            <div class=\"col-lg-12\">
                <label for=\"pedido\">Pedido:</label>
                <textarea name=\"pedido\" class=\"form-control\" cols=\"40\" rows=\"5\" required=\"true\">O servidor abaixo assinado requer a Vsª se digne conceder </textarea>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"motivo\">Motivo:</label>
                <textarea name=\"motivo\" class=\"form-control\" cols=\"40\" rows=\"5\" required=\"true\"></textarea>
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

    public function getTemplateName()
    {
        return "ProtocoloBundle:Forms:Simples.html.twig";
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

<?php

/* ProtocoloBundle:Forms:ProrrogacaoLicencaMaternidade.html.twig */
class __TwigTemplate_9b6ac0f4c03f88bccfc52370681f221199162ce13d1b7578166235f150e37dfa extends Twig_Template
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
        echo "Requerimento de Prorrogação de Licença Maternidade / Licença à Adotante";
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
                <label for=\"matricula\">Matrícula:</label>
                <input type=\"text\" name=\"matricula\" class=\"form-control\" required=\"true\"/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"vinculo\">Selecione um cargo:</label>
                <select name=\"vinculo\" class=\"form-control\" required=\"true\">
                    ";
        // line 15
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 16
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
        // line 18
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"nome_crianca\">Nome da criança:</label>
                <input type=\"text\" name=\"nome_crianca\" class=\"form-control\" required=\"true\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"nome_crianca\">Licença concedida amteriormente</label>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"tipo_licenca\">Tipo:</label>
                <select name=\"tipo_licenca\" class=\"form-control\" required=\"true\">
                        <option value=\"Gestante\">Gestante</option>
                        <option value=\"Adotante\">Adotante</option>
                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"inicio_licenca\">Data de início da licença:</label>
                <input type=\"text\" name=\"inicio_licenca\" class=\"form-control datepickerSME\" required=\"true\"/>
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
        return "ProtocoloBundle:Forms:ProrrogacaoLicencaMaternidade.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 18,  55 => 16,  51 => 15,  38 => 6,  35 => 5,  29 => 3,);
    }
}

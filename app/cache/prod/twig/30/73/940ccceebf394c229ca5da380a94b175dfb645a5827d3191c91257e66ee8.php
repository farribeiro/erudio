<?php

/* ProtocoloBundle:Protocolo:formPesquisaProtocolo.html.twig */
class __TwigTemplate_3073940ccceebf394c229ca5da380a94b175dfb645a5827d3191c91257e66ee8 extends Twig_Template
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
        echo " Protocolo > Gerência > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "nome"), "html", null, true);
        echo " ";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <ul class=\"nav nav-tabs\">
        <li class=\"active\"><a href=\"#\">";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "nome"), "html", null, true);
        echo "</a></li>
        <li><a href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_admin_listarEncaminhamentos", array("categoria" => $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "id"))), "html", null, true);
        echo "\">Encaminhamento</a></li>
    </ul>
    <form id=\"formPesquisaProtocolo\" method=\"post\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"solicitacao\">Solicitação:</label>
                <select type=\"text\" id=\"solicitacao\" name=\"solicitacao\" class=\"form-control\">
                    <option></option>
                    ";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["solicitacoes"]) ? $context["solicitacoes"] : $this->getContext($context, "solicitacoes")));
        foreach ($context['_seq'] as $context["_key"] => $context["solicitacao"]) {
            // line 17
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['solicitacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"numero\">Número:</label>
                <input type=\"text\" id=\"numero\" name=\"numero\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"requerente\">Requerente:</label>
                <input type=\"text\" id=\"requerente\" name=\"requerente\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-6\">
                <label for=\"ativo\">Estado:</label>
                <div class=\"input-group\" style=\"padding-top: 0;\">
                    <select id=\"estado\" name=\"estado\" class=\"form-control\" required=\"true\">
                        <option value=\"1\">Ativo</option>
                        <option value=\"2\">Novo</option>
                        <option value=\"3\">Em atividade</option>
                        <option value=\"0\">Arquivado</option>
                    </select>
                    <span class=\"input-group-btn\">
                        <button id=\"btnBuscar\" type=\"button\" class=\"btn btn-primary\">Buscar</button>
                    </span>
                </div>
            </div>
        </div>
    </form>
    <div id=\"divListaProtocolos\"></div>
";
    }

    // line 50
    public function block_javascript($context, array $blocks = array())
    {
        // line 51
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#btnBuscar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 56
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("protocolo_admin_pesquisar", array("categoria" => $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "id"))), "html", null, true);
        echo "\",
                data: \$(\"#formPesquisaProtocolo\").serialize(),
                success: function(retorno){
                    \$(\"#divListaProtocolos\").html(retorno);  
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Protocolo:formPesquisaProtocolo.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 56,  110 => 51,  107 => 50,  74 => 19,  63 => 17,  59 => 16,  48 => 8,  44 => 7,  41 => 6,  38 => 5,  30 => 3,);
    }
}

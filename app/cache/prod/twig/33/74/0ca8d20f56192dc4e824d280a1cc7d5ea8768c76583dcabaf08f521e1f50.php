<?php

/* ProtocoloBundle:Forms:CancelamentoValeTransporte.html.twig */
class __TwigTemplate_33740ca8d20f56192dc4e824d280a1cc7d5ea8768c76583dcabaf08f521e1f50 extends Twig_Template
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
        echo "Requerimento de Cancelamento do Vale Transporte";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "<!-- 
Perguntar pro jhony de tem algum padrão de tabela e perguntar como puxar os dados de endereço
Lembrar que precisa do RG, dados de endereço(endereço, número, complemento, bairro, cep, município), e celular
-->
    <form method=\"post\" action=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_incluirSolicitacao", array("solicitacao" => $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"))), "html", null, true);
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"matricula\">Matrícula:</label>
                <input type=\"text\" name=\"matricula\" class=\"form-control\"  required />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"empresa\">Empresa de transporte:</label>
                <input type=\"text\" name=\"empresa\" class=\"form-control\"  required/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"cartao_sim\">Número do cartão SIM:</label>
                <input type=\"text\" name=\"cartao_sim\" class=\"form-control\"  required=\"true\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"verifica endereço\">Antes de cadastrar o requerimento, verifique se seu endereço está correto.</label>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <table  class=\"table table-striped table-hover\">
                   <th colspan=\"2\">Dados do endereço</th>
                    <tr>
                        <td>Endereço</td>
                        <td>";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "logradouro"), "html", null, true);
        echo "</td>
                    </tr>
                    <tr>
                        <td>Número</td>
                        <td>";
        // line 42
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "numero"), "html", null, true);
        echo "</td>
                    </tr>
                    <tr>
                        <td>Complemento</td>
                        <td>";
        // line 46
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "complemento"), "html", null, true);
        echo "</td>
                    </tr>
                    <tr>
                        <td>Bairro</td>
                        <td>";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "bairro"), "html", null, true);
        echo "</td>
                    </tr>
                    <tr>
                        <td>CEP</td>
                        <td>";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "cep"), "html", null, true);
        echo "</td>
                    </tr>
                    <tr>
                        <td>Cidade</td>
                        <td>";
        // line 58
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "cidade"), "nome"), "html", null, true);
        echo "</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <button id=\"btnIncluir\" type=\"submit\" class=\"btn btn-info\">Incluir</button>
            </div>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Forms:CancelamentoValeTransporte.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  110 => 58,  103 => 54,  96 => 50,  89 => 46,  82 => 42,  75 => 38,  44 => 10,  38 => 6,  35 => 5,  29 => 3,);
    }
}

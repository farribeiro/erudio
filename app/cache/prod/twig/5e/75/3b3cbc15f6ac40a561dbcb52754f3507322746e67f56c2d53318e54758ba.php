<?php

/* SuporteTecnicoBundle:Chamado:formEdicao.html.twig */
class __TwigTemplate_5e753b3cbc15f6ac40a561dbcb52754f3507322746e67f56c2d53318e54758ba extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'css' => array($this, 'block_css'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SuporteTecnicoBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Suporte Técnico > Chamados > Nº ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"), "html", null, true);
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "
<div style=\"margin-bottom: 10px;\">
    <div class=\"row\" id=\"formErrors\">
        ";
        // line 9
        echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
        echo "
    </div>
    ";
        // line 11
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("action" => $this->env->getExtension('routing')->getPath("suporte_chamado_gerenciar", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id")))));
        echo "
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"local\">Local:</label>
                <input id=\"local\" disabled class=\"form-control\" value=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "local"), "nome"), "html", null, true);
        echo "\" />
            </div>
            <div class=\"col-lg-6\">
                <label for=\"pessoaCadastrou\">Solicitante:</label>
                <input id=\"pessoaCadastrou\" disabled class=\"form-control\" value=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "pessoaCadastrou"), "nome"), "html", null, true);
        echo "\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"endereco\">Endereço:</label>
                <input id=\"endereco\" disabled class=\"form-control\" value=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "endereco"), "html", null, true);
        echo "\" />
            </div>
            <div class=\"col-lg-6\">
                <label for=\"telefones\">Telefones:</label>
                <input id=\"telefones\" disabled class=\"form-control\" value=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "telefones"), "html", null, true);
        echo "\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 34
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'label');
        echo "
                ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"dataCadastro\">Data de Cadastro:</label>
                <input id=\"pessoaCadastrou\" disabled class=\"form-control\" value=\"";
        // line 41
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "dataCadastro"), "d/m/Y H:i"), "html", null, true);
        echo "\" />
            </div>
            <div class=\"col-lg-4\">
                ";
        // line 44
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "prioridade"), 'label');
        echo "
                ";
        // line 45
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "prioridade"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
            </div>
            <div class=\"col-lg-4\">
                ";
        // line 48
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'label');
        echo "
                ";
        // line 49
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 54
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'label');
        echo "
                ";
        // line 55
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'widget', array("attr" => array("class" => "form-control", "rows" => 5)));
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div id=\"divTags\" class=\"col-lg-12\">
                ";
        // line 60
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tags"), 'label');
        echo "
                ";
        // line 61
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tags"), 'widget');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 66
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "solucao"), 'label');
        echo "
                ";
        // line 67
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "solucao"), 'widget', array("attr" => array("class" => "form-control", "rows" => 5)));
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <a href=\"";
        // line 72
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("suporte_chamado_imprimir", array("chamado" => $this->getAttribute((isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")), "id"))), "html", null, true);
        echo "\" target=\"_blank\" class=\"btn btn-primary btn-block\">
                    <span class=\"glyphicon glyphicon-print\"></span> Imprimir
                </a>
            </div>
            <div class=\"col-lg-6\">
                ";
        // line 77
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'widget', array("attr" => array("class" => "btn btn-primary btn-block")));
        echo "
            </div>
        </div>
    ";
        // line 80
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
</div>

";
        // line 83
        $this->env->loadTemplate("SuporteTecnicoBundle:Atividade:atividades.html.twig")->display(array_merge($context, array("chamado" => (isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")))));
        // line 84
        echo "
";
        // line 85
        $this->env->loadTemplate("SuporteTecnicoBundle:Anotacao:anotacoes.html.twig")->display(array_merge($context, array("chamado" => (isset($context["chamado"]) ? $context["chamado"] : $this->getContext($context, "chamado")))));
        // line 86
        echo "
";
        // line 87
        $this->displayBlock('css', $context, $blocks);
        // line 96
        echo "
";
    }

    // line 87
    public function block_css($context, array $blocks = array())
    {
        // line 88
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
    <style type=\"text/css\">
        #divTags input {
            margin-left: 1em;
            margin-right: 0.2em;
        }
    </style>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:formEdicao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  205 => 88,  202 => 87,  197 => 96,  195 => 87,  192 => 86,  190 => 85,  187 => 84,  185 => 83,  179 => 80,  173 => 77,  165 => 72,  157 => 67,  153 => 66,  145 => 61,  141 => 60,  133 => 55,  129 => 54,  121 => 49,  117 => 48,  111 => 45,  107 => 44,  101 => 41,  92 => 35,  88 => 34,  80 => 29,  73 => 25,  64 => 19,  57 => 15,  50 => 11,  45 => 9,  40 => 6,  37 => 5,  30 => 3,);
    }
}

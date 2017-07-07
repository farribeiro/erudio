<?php

/* SuporteTecnicoBundle:Chamado:formPesquisa.html.twig */
class __TwigTemplate_0886ecc07a06b8339a2edbcdaccfe7180c95e9e432c808e72053eef7871a9ac3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
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
        echo "Suporte Técnico > Chamados > Pesquisa";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "
";
        // line 7
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "formPesquisa")));
        echo "
    <div class=\"row\">
        <div class=\"col-lg-6\">
            ";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'label');
        echo "
            ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'label');
        echo "
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 18
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'label');
        echo "
            ";
        // line 19
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-3\">
            <label for=\"\">Cadastrado entre:</label>
            ";
        // line 25
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodoCadastroInicio"), 'widget', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            <label>Até:</label>
            ";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodoCadastroFim"), 'widget', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 32
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "ordenacao"), 'label');
        echo "
            ";
        // line 33
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "ordenacao"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 36
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), 'label');
        echo "
            ";
        // line 37
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-6\">
            <button id=\"btnPesquisar\" type=\"submit\" class=\"btn btn-primary\">
                <span class=\"glyphicon glyphicon-list\"></span> Listar
            </button>
            <button id=\"btnImprimir\" formtarget=\"_blank\" type=\"submit\" formaction=\"";
        // line 45
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "?pdf\" class=\"btn btn-primary\">
                <span class=\"glyphicon glyphicon-print\"></span> Imprimir
            </button>
        </div>
    </div>
";
        // line 50
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "

<div id=\"chamados\"></div>

<div id=\"divPaginas\" style=\"display: none; padding-top: 0.5em;\">
    <em>Página <span id=\"numeroPagina\">1</span></em>
    <a class=\"btn-link\" id=\"lnkAnterior\">Anterior</a> | <a class=\"btn-link\" id=\"lnkProximo\">Próxima</a>
</div>

<div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
    <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 85%;\"></div>
</div>

";
    }

    // line 65
    public function block_javascript($context, array $blocks = array())
    {
        // line 66
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        if(typeof(Storage)!==\"undefined\") {
            \$(\"#";
        // line 69
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\"));
            \$(\"#";
        // line 70
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\"));
            \$(\"#";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\"));
        }

        function carregarChamados(event) {
            \$.ajax({
                 type: \"POST\",
                 url: \"";
        // line 77
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "\",
                 data: \$(\"#formPesquisa\").serialize(),
                 success: function(retorno) {
                     \$(\"#chamados\").html(retorno);
                     \$(\"#divPaginas\").show();
                 }
             });
             sessionStorage.setItem(\"";
        // line 84
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val());
             sessionStorage.setItem(\"";
        // line 85
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\").val());
             sessionStorage.setItem(\"";
        // line 86
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\").val());
        }
        
        \$(\"#btnPesquisar\").click(function(event) {
            event.preventDefault();
            carregarChamados(event);
            \$(\"#divPaginas\").show();
        });

        \$(\"#lnkProximo\").click(function() {
            \$(\"#";
        // line 96
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val((parseInt(\$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1).toString() );
            \$(\"#numeroPagina\").html(parseInt(\$(\"#";
        // line 97
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1);
            carregarChamados();
        });

        \$(\"#lnkAnterior\").click(function() {
            if(\$(\"#";
        // line 102
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val() > 0) {
                \$(\"#";
        // line 103
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val((parseInt(\$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) - 1).toString() );
                \$(\"#numeroPagina\").html(parseInt(\$(\"#";
        // line 104
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1);
                carregarChamados();
            }
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:formPesquisa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  239 => 104,  233 => 103,  229 => 102,  221 => 97,  215 => 96,  200 => 86,  194 => 85,  188 => 84,  178 => 77,  167 => 71,  161 => 70,  155 => 69,  148 => 66,  145 => 65,  127 => 50,  119 => 45,  108 => 37,  104 => 36,  98 => 33,  94 => 32,  88 => 29,  81 => 25,  72 => 19,  68 => 18,  62 => 15,  58 => 14,  52 => 11,  48 => 10,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

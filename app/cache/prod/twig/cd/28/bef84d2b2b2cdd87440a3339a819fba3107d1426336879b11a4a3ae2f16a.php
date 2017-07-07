<?php

/* SuporteTecnicoBundle:Chamado:formCadastro.html.twig */
class __TwigTemplate_cd28bef84d2b2b2cdd87440a3339a819fba3107d1426336879b11a4a3ae2f16a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
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
        echo "Suporte Técnico > Chamados > Cadastro";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "
";
        // line 7
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
    <div class=\"row\" id=\"formErrors\">
        ";
        // line 9
        echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
        echo "
    </div>
    <div class=\"row\">
        <div class=\"col-lg-12\">
            ";
        // line 13
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "local"), 'label');
        echo "
            ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "local"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-12 \">
            ";
        // line 19
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'label');
        echo "
            ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'widget', array("attr" => array("class" => "hidden")));
        echo "
            <div id=\"comboCategoria\">
                ";
        // line 22
        $this->env->loadTemplate("SuporteTecnicoBundle:Categoria:comboCategoria.html.twig")->display(array_merge($context, array("categoria" => null, "subcategoria" => null)));
        // line 23
        echo "            </div>
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-12\">
            ";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'label');
        echo "
            ";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'widget', array("attr" => array("class" => "form-control", "rows" => 5)));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div id=\"divTags\" class=\"col-lg-12\">
            ";
        // line 34
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tags"), 'label');
        echo "
            ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tags"), 'widget');
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-12\">
            ";
        // line 40
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'widget', array("attr" => array("class" => "btn btn-primary")));
        echo "
        </div>
    </div>
";
        // line 43
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "

";
    }

    // line 47
    public function block_javascript($context, array $blocks = array())
    {
        // line 48
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        function loadComboCategoria(categoria) {
            \$(\"#";
        // line 51
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val(categoria);
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 54
        echo $this->env->getExtension('routing')->getPath("suporte_categoria_gerarCombo");
        echo "\",
                data: {\"categoria\": categoria},
                success: function(retorno) {
                    \$(\"#comboCategoria\").html(retorno);  
                }
            });
        }
        
        function loadTags(categoria) {
            var data = {};
            data[\$(\"#";
        // line 64
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").attr(\"name\")] = categoria;
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 67
        echo $this->env->getExtension('routing')->getPath("suporte_chamado_cadastrar");
        echo "\",
                data: data,
                success: function(retorno) {
                    html = \$.parseHTML(retorno);
                    \$(\"#divTags\").replaceWith(
                        \$(html).find(\"#divTags\")
                    );  
                }
            });
        }
    </script>
";
    }

    // line 80
    public function block_css($context, array $blocks = array())
    {
        // line 81
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
        return "SuporteTecnicoBundle:Chamado:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 81,  173 => 80,  157 => 67,  151 => 64,  138 => 54,  132 => 51,  125 => 48,  122 => 47,  115 => 43,  109 => 40,  101 => 35,  97 => 34,  89 => 29,  85 => 28,  78 => 23,  76 => 22,  71 => 20,  67 => 19,  59 => 14,  55 => 13,  48 => 9,  43 => 7,  40 => 6,  37 => 5,  31 => 3,);
    }
}

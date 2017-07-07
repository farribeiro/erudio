<?php

/* DGPBundle:Pessoa:formEndereco.html.twig */
class __TwigTemplate_d1213aacd57871b8da0d5542417f09372a0734a0adada74ea426b65e8bfef6ee extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Pessoa:cadastroPessoa.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'tabContent' => array($this, 'block_tabContent'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Pessoa:cadastroPessoa.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["activeTab"] = "2";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " > Endereço";
    }

    // line 7
    public function block_tabContent($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "editForm")));
        echo "
        ";
        // line 9
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'errors');
        echo "
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cep"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cidade"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "bairro"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 23
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "logradouro"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 31
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "complemento"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 36
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "latitude"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 39
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "longitude"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 44
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'row');
        echo "
                </div>
            </div>
    ";
        // line 47
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
";
    }

    // line 50
    public function block_javascript($context, array $blocks = array())
    {
        // line 51
        echo "\t";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
\t<script type=\"text/javascript\">\t\t
            var cidades = new Array(); 
            ";
        // line 54
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["cidades"]) ? $context["cidades"] : $this->getContext($context, "cidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["cidade"]) {
            // line 55
            echo "                cidades[\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "id"), "html", null, true);
            echo "\"] = \"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "nome"), "html", null, true);
            echo "\";
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 57
        echo "
            \$(\"#EnderecoForm_cep\").change(function () {
                \$.ajax({
                    url: 'http://cep.republicavirtual.com.br/web_cep.php',
                    type: 'POST',
                    data: { cep: \$(this).val(), formato: 'json' },
                    success: function (data)
                    {
                        \$(\"#EnderecoForm_bairro\").val(data.bairro);
                        \$(\"#EnderecoForm_logradouro\").val(data.tipo_logradouro + ' ' + data.logradouro);
                        var city = cidades.indexOf(data.cidade);
                        \$(\"#EnderecoForm_cidade\").val(city);
                    }
                });
            });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Pessoa:formEndereco.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  147 => 57,  136 => 55,  132 => 54,  125 => 51,  122 => 50,  116 => 47,  110 => 44,  102 => 39,  96 => 36,  88 => 31,  82 => 28,  74 => 23,  68 => 20,  60 => 15,  54 => 12,  48 => 9,  43 => 8,  40 => 7,  32 => 5,  27 => 3,);
    }
}

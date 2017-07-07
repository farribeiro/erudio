<?php

/* DGPBundle:Pessoa:formPesquisa.html.twig */
class __TwigTemplate_8e1e88c7ec7cbe3d7441a00a5528833e2980506d5cf3d8c1a787f3fa7c369bea extends Twig_Template
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
        echo "DGP > Pessoas > Busca";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "searchForm")));
        echo "
        ";
        // line 7
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'errors');
        echo "
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pessoaFisicaMinified"), "nome"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 13
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pessoaFisicaMinified"), "email"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 18
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pessoaFisicaMinified"), "cpfCnpj"), 'row');
        echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
        // line 21
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vinculos"), "cargo"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 26
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vinculos"), "tipoVinculo"), 'row');
        echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
        // line 31
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "Buscar"), 'row');
        echo "
                </div>
            </div>
    ";
        // line 34
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
    <hr />
    <div class=\"container\">
        <div class=\"row\" id=\"ajaxList\">

        </div>
    </div>
";
    }

    // line 43
    public function block_javascript($context, array $blocks = array())
    {
        // line 44
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(document).ready(function (){
            \$(\"#";
        // line 47
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "Buscar"), "vars"), "id"), "html", null, true);
        echo "\").click(function(ev){
                ev.preventDefault();
                \$.ajax({
                    url: \"";
        // line 50
        echo $this->env->getExtension('routing')->getPath("dgp_pessoa_pesquisar");
        echo "\",
                    type: 'POST',
                    data: \$(\"#searchForm\").serialize(),
                    success: function (data)
                    {
                        \$(\"#ajaxList\").html(data);
                    }
                });
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Pessoa:formPesquisa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  120 => 50,  114 => 47,  107 => 44,  104 => 43,  92 => 34,  86 => 31,  78 => 26,  70 => 21,  64 => 18,  56 => 13,  50 => 10,  44 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

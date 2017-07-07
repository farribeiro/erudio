<?php

/* PublicBundle:Public:modalFormacaoExternaMatricula.html.twig */
class __TwigTemplate_fd84814e0785de9bb69e5bf7ea6994402be638bc8b1a8bca2c97bba8e4c942c3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::templateModal.html.twig");

        $this->blocks = array(
            'modalTitle' => array($this, 'block_modalTitle'),
            'modalBody' => array($this, 'block_modalBody'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::templateModal.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_modalTitle($context, array $blocks = array())
    {
        echo " Nova Matricula ";
    }

    // line 5
    public function block_modalBody($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"container\">
        <div class=\"row\">
            ";
        // line 8
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "matriculaForm")));
        echo "
                ";
        // line 9
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "nome"), 'row', array("attr" => array("class" => "form-control", "placeholder" => "Digite seu nome")));
        echo "
                ";
        // line 10
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cpf"), 'row', array("attr" => array("class" => "form-control", "placeholder" => "Digite seu CPF")));
        echo "
                ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "email"), 'row', array("attr" => array("class" => "form-control", "placeholder" => "Digite seu email")));
        echo "
                ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "profissao"), 'row', array("attr" => array("class" => "form-control", "placeholder" => "Digite sua profissão")));
        echo "
                <hr />
                ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "Inscrever"), 'row');
        echo "
            ";
        // line 15
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
        </div>
    </div>
            
";
    }

    // line 21
    public function block_javascript($context, array $blocks = array())
    {
        // line 22
        echo "    <script type=\"text/javascript\">
        \$('document').ready(function(){
            \$(\"#matriculaForm\").submit(function (event){
                event.preventDefault();

                var jsonObject = { 'nome': \$(\"#form_nome\").val(), 'cpf': \$(\"#form_cpf\").val(), 'email': \$(\"#form_email\").val(), 'profissao': \$(\"#form_profissao\").val() };
                var jsonString = JSON.stringify(jsonObject);

                \$.ajax({
                    url: '";
        // line 31
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_publico_postMatricula", array("formacao" => $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "id"))), "html", null, true);
        echo "',
                    type: \"POST\",
                    dataType: \"json\",
                    data: jsonString,
                    success: function (data){
                        if (data.result === \"success\") {
                            \$.bootstrapGrowl(data.message, { type: 'success', delay: 3000 });
                        } else {
                            \$.bootstrapGrowl(data.message, { type: 'danger', delay: 3000 });
                        }
                    }
                });
            });    
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "PublicBundle:Public:modalFormacaoExternaMatricula.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 31,  80 => 22,  77 => 21,  68 => 15,  64 => 14,  59 => 12,  55 => 11,  51 => 10,  47 => 9,  43 => 8,  39 => 6,  36 => 5,  30 => 3,);
    }
}

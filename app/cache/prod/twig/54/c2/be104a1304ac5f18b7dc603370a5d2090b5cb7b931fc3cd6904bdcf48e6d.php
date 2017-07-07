<?php

/* DGPBundle:Alocacao:formAlteracao.html.twig */
class __TwigTemplate_54c2be104a1304ac5f18b7dc603370a5d2090b5cb7b931fc3cd6904bdcf48e6d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
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
    public function block_body($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        if (((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")) != "")) {
            // line 5
            echo "        ";
            echo twig_escape_filter($this->env, (isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")), "html", null, true);
            echo "
    ";
        } else {
            // line 7
            echo "        <div class=\"row\" id=\"formErrors\">
            ";
            // line 8
            echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
            echo "
        </div>
        ";
            // line 10
            echo             $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "alocacaoForm")));
            echo "
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 13
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "localTrabalho"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 16
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cargaHoraria"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 21
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodo"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 24
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "funcaoAtual"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 29
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "localLotacao"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 32
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "original"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 37
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "observacao"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 40
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "motivoEncaminhamento"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 45
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'row');
            echo "
                </div>
            </div>
        ";
            // line 48
            echo             $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
            echo "
    ";
        }
    }

    // line 52
    public function block_javascript($context, array $blocks = array())
    {
        // line 53
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">\t\t\t
        \$(\"#";
        // line 55
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), "vars"), "id"), "html", null, true);
        echo "\").click(function(ev){
            ev.preventDefault();
            \$.ajax({
                url: \"";
        // line 58
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_alterar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "alocacao" => $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"))), "html", null, true);
        echo "\",
                type: \"POST\",
                data: \$(\"#alocacaoForm\").serialize(),
                success: function (data)
                {
                    if (data !== \"error\") {
                        \$.bootstrapGrowl(\"Alocação salva com sucesso\", { type: 'success', delay: 3000 });
                        \$.ajax({
                            url: \"";
        // line 66
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\",
                            type: \"GET\",
                            success: function (data)
                            {
                                if (data !== \"error\") {
                                    \$(\".ajaxAllocation";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data).show();
                                } else {
                                    \$.bootstrapGrowl(\"O sistema não pôde mostrar as alocações, aguarde um pouco e tente novamente.\", { type: 'danger', delay: 3000 });
                                }
                            }
                        });
                    } else {
                        
                        \$.bootstrapGrowl(\"Houve um problema na atualização, verifique se não houve estouro de carga horária\", { type: \"danger\", delay: 5000 });
                    }
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Alocacao:formAlteracao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 71,  150 => 66,  139 => 58,  133 => 55,  127 => 53,  124 => 52,  117 => 48,  111 => 45,  103 => 40,  97 => 37,  89 => 32,  83 => 29,  75 => 24,  69 => 21,  61 => 16,  55 => 13,  49 => 10,  44 => 8,  41 => 7,  35 => 5,  32 => 4,  29 => 3,);
    }
}

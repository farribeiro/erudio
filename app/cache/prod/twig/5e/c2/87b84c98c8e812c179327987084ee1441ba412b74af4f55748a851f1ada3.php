<?php

/* DGPBundle:Alocacao:formCadastro.html.twig */
class __TwigTemplate_5ec287b84c98c8e812c179327987084ee1441ba412b74af4f55748a851f1ada3 extends Twig_Template
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
            echo "        <button label=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\" class=\"btn btn-danger cancelCadAlloc\">
            <span class=\"glyphicon glyphicon-remove\"></span>
            Cancelar
        </button>

        <div class=\"row\" id=\"formErrors\">
            ";
            // line 13
            echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
            echo "
        </div>
        ";
            // line 15
            echo             $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "alocacaoForm")));
            echo "
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 18
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "localTrabalho"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 21
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cargaHoraria"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 26
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodo"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 29
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "funcaoAtual"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 34
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "localLotacao"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 37
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "original"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 42
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "observacao"), 'row');
            echo "
                </div>
                <div class=\"col-lg-6\">
                    ";
            // line 45
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "motivoEncaminhamento"), 'row');
            echo "
                </div>
            </div>
            <div class=\"row\">
                <div class=\"col-lg-6\">
                    ";
            // line 50
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'row');
            echo "
                </div>
            </div>
        ";
            // line 53
            echo             $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
            echo "
    ";
        }
    }

    // line 57
    public function block_javascript($context, array $blocks = array())
    {
        // line 58
        echo "\t";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
\t<script type=\"text/javascript\">
            \$(\"#editForm ul\").remove();

            \$(\".cancelCadAlloc\").click(function (e){
                e.preventDefault();

                \$.ajax({
                    url: \$(this).attr('label'),
                    type: \"GET\",
                    success: function (data)
                    {
                        if (data !== \"error\") {
                            \$(\".ajaxAllocation";
        // line 71
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data);
                        } else {
                            \$.bootstrapGrowl(\"Houve um problema no carregamento da lista, tente mais tarde.\", { type: 'danger', delay: 3000 });
                        }
                    }
                });
            });

            \$(\"#";
        // line 79
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), "vars"), "id"), "html", null, true);
        echo "\").click(function(ev) {
                ev.preventDefault();
                \$.ajax({
                    url: \"";
        // line 82
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_cadastrar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\",
                    type: \"POST\",
                    data: \$(\"#alocacaoForm\").serialize(),
                    success: function (data)
                    {
                        if (data === \"success\") {
                            \$.bootstrapGrowl(\"Alocação salva com sucesso\", { type: 'success', delay: 3000 });
                            \$.ajax({
                                url: \"";
        // line 90
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
        echo "\",
                                type: \"GET\",
                                success: function (data)
                                {
                                    if (data !== \"error\") {
                                        \$(\".ajaxAllocation";
        // line 95
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data).show();
                                    } else {
                                        \$.bootstrapGrowl(\"O sistema não pôde mostrar as alocações, aguarde um pouco e tente novamente.\", { type: 'danger', delay: 3000 });
                                    }
                                }
                            });
                        } else {
                            \$.bootstrapGrowl(\"Ocorreu um erro ao tentar salvar, verifique se a carga horária não ultrapassou o limite\", { type: 'danger', delay: 5000 });
                            \$(\".ajaxAllocation";
        // line 103
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
        echo "\").html(data);
                        }
                    }
                });
            });
\t</script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Alocacao:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  198 => 103,  187 => 95,  179 => 90,  168 => 82,  162 => 79,  151 => 71,  134 => 58,  131 => 57,  124 => 53,  118 => 50,  110 => 45,  104 => 42,  96 => 37,  90 => 34,  82 => 29,  76 => 26,  68 => 21,  62 => 18,  56 => 15,  51 => 13,  41 => 7,  35 => 5,  32 => 4,  29 => 3,);
    }
}

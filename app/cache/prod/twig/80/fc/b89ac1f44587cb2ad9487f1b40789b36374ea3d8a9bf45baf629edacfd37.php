<?php

/* DGPBundle:Vinculo:formCadastro.html.twig */
class __TwigTemplate_80fcb89ac1f44587cb2ad9487f1b40789b36374ea3d8a9bf45baf629edacfd37 extends Twig_Template
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
        $context["activeTab"] = "5";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " > Vínculos > ";
        echo twig_escape_filter($this->env, ((array_key_exists("vinculo", $context)) ? ($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id")) : ("Novo")), "html", null, true);
        echo " ";
    }

    // line 7
    public function block_tabContent($context, array $blocks = array())
    {
        // line 8
        echo "    <div class=\"row\" id=\"formErrors\">
        ";
        // line 9
        echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
        echo "
    </div>
    ";
        // line 11
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "vinculoForm")));
        echo "
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cargo"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 17
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tipoVinculo"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "cargaHoraria"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 25
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "inscricaoVinculacao"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                ";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "convocacaoVinculacao"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12 hidden vinculoOriginal\">
                ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vinculoOriginal"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 40
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "dataInicio"), 'label');
        echo "
                ";
        // line 41
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "dataInicio"), 'widget', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
            </div>
            <div class=\"col-lg-6\">
                ";
        // line 44
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "dataTermino"), 'row', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
            </div>
        </div>
         <div class=\"row\">
            <div class=\"col-lg-3\">
                ";
        // line 49
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "portaria"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 52
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "edicaoJornalNomeacao"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 55
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "matricula"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 58
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "quadroEspecial"), 'row');
        echo "\t\t            
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-3\">
                ";
        // line 63
        if ((!array_key_exists("vinculo", $context))) {
            // line 64
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "gratificacao"), 'row', array("attr" => array("value" => "I e II")));
            echo "
                ";
        } else {
            // line 66
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "gratificacao"), 'row');
            echo "
                ";
        }
        // line 68
        echo "            </div>
            <div class=\"col-lg-3\">
                ";
        // line 70
        if ((!array_key_exists("vinculo", $context))) {
            // line 71
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "lotacaoSecretaria"), 'row', array("attr" => array("value" => "25")));
            echo "
                ";
        } else {
            // line 73
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "lotacaoSecretaria"), 'row');
            echo "
                ";
        }
        // line 75
        echo "            </div>
            <div class=\"col-lg-3\">
                ";
        // line 77
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "codigoDepartamento"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 80
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "codigoSetor"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 85
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "bancoContaBancaria"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 88
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "agenciaContaBancaria"), 'row');
        echo "
            </div>
            <div class=\"col-lg-3\">
                ";
        // line 91
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numeroContaBancaria"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 96
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "observacao"), 'row');
        echo "
            </div>
            <div class=\"col-lg-6\">
                ";
        // line 99
        if (((!array_key_exists("vinculo", $context)) || (null === $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id")))) {
            // line 100
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numeroControle"), 'row', array("attr" => array("value" => (isset($context["numeroControle"]) ? $context["numeroControle"] : $this->getContext($context, "numeroControle")))));
            echo "
                ";
        } else {
            // line 102
            echo "                    ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numeroControle"), 'row');
            echo "
                ";
        }
        // line 104
        echo "            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 108
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'row', array("attr" => array("class" => "btn btn-primary btn-block")));
        echo "
            </div>
            <div class=\"col-lg-6\">
                <a class=\"btn btn-danger btn-block\" href=\"";
        // line 111
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_vinculo_listar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Cancelar</a>
            </div>
        </div>
    ";
        // line 114
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
";
    }

    // line 117
    public function block_javascript($context, array $blocks = array())
    {
        // line 118
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        if (\$(\"#";
        // line 120
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tipoVinculo"), "vars"), "id"), "html", null, true);
        echo "\").val() == 3) {
            \$(\".vinculoOriginal\").removeClass('hidden');
        }
        
        \$(\"#";
        // line 124
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "tipoVinculo"), "vars"), "id"), "html", null, true);
        echo "\").change(function () {
            if (\$(this).val() == 3) {
                \$(\".vinculoOriginal\").removeClass('hidden');
            } else {
                \$(\".vinculoOriginal\").addClass('hidden');
            }
        });
        
        \$(\"#";
        // line 132
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "inscricaoVinculacao"), "vars"), "id"), "html", null, true);
        echo "\").change(function () {
            loadComboConvocacao(\$(\"#";
        // line 133
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "inscricaoVinculacao"), "vars"), "id"), "html", null, true);
        echo "\").val());
        });
        
        function loadComboConvocacao(inscricao) {
            var data = {};
            data[\$(\"#";
        // line 138
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "inscricaoVinculacao"), "vars"), "id"), "html", null, true);
        echo "\").attr(\"name\")] = inscricao;
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 141
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "\",
                data: data,
                success: function(retorno) {
                    html = \$.parseHTML(retorno);
                    \$(\"#";
        // line 145
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "convocacaoVinculacao"), "vars"), "id"), "html", null, true);
        echo "\").replaceWith(
                        \$(html).find(\"#";
        // line 146
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "convocacaoVinculacao"), "vars"), "id"), "html", null, true);
        echo "\")
                    );
                    \$(\"#";
        // line 148
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numeroControle"), "vars"), "id"), "html", null, true);
        echo "\").replaceWith(
                        \$(html).find(\"#";
        // line 149
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numeroControle"), "vars"), "id"), "html", null, true);
        echo "\")
                    );
                }
            });
        }
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Vinculo:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  326 => 149,  322 => 148,  317 => 146,  313 => 145,  306 => 141,  300 => 138,  292 => 133,  288 => 132,  277 => 124,  270 => 120,  264 => 118,  261 => 117,  255 => 114,  249 => 111,  243 => 108,  237 => 104,  231 => 102,  225 => 100,  223 => 99,  217 => 96,  209 => 91,  203 => 88,  197 => 85,  189 => 80,  183 => 77,  179 => 75,  173 => 73,  167 => 71,  165 => 70,  161 => 68,  155 => 66,  149 => 64,  147 => 63,  139 => 58,  133 => 55,  127 => 52,  121 => 49,  113 => 44,  107 => 41,  103 => 40,  95 => 35,  87 => 30,  79 => 25,  71 => 20,  65 => 17,  59 => 14,  53 => 11,  48 => 9,  45 => 8,  42 => 7,  32 => 5,  27 => 3,);
    }
}

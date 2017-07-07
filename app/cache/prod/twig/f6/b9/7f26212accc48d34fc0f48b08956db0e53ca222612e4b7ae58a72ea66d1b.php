<?php

/* SuporteTecnicoBundle:Chamado:formPesquisaAdmin.html.twig */
class __TwigTemplate_f6b97f26212accc48d34fc0f48b08956db0e53ca222612e4b7ae58a72ea66d1b extends Twig_Template
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
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), 'label');
        echo "
            ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-6\">
            ";
        // line 14
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "local"), 'label');
        echo "
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "local"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-9\">
            ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'label');
        echo "
            ";
        // line 21
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 24
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'label');
        echo "
            ";
        // line 25
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-3\">
            <label for=\"\">Cadastrado entre:</label>
            ";
        // line 31
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodoCadastroInicio"), 'widget', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            <label>Até:</label>
            ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "periodoCadastroFim"), 'widget', array("attr" => array("class" => "form-control datepickerSME")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 38
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'label');
        echo "
            ";
        // line 39
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 42
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "bairro"), 'label');
        echo "
            ";
        // line 43
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "bairro"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-3\">
            ";
        // line 48
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "ordenacao"), 'label');
        echo "
            ";
        // line 49
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "ordenacao"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            ";
        // line 52
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), 'label');
        echo "
            ";
        // line 53
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), 'widget', array("attr" => array("class" => "form-control")));
        echo "
        </div>
        <div class=\"col-lg-3\">
            <button id=\"btnListar\" title=\"Lista\" type=\"submit\" class=\"btn btn-primary\" style=\"margin-top: 1.8em;\">
                <span class=\"glyphicon glyphicon-list\"></span>
            </button>
            <button id=\"btnMapa\" title=\"Mapa\" type=\"submit\" class=\"btn btn-primary\" style=\"margin-top: 1.8em;\">
                <span class=\"glyphicon glyphicon-map-marker\"></span>
            </button>
            <button id=\"btnImprimir\" title=\"Impressão\" type=\"submit\" formtarget=\"_blank\"  formaction=\"";
        // line 62
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "?view=pdf\" class=\"btn btn-primary\" style=\"margin-top: 1.8em;\">
                <span class=\"glyphicon glyphicon-print\"></span>
            </button>
        </div>
    </div>
";
        // line 67
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

    // line 82
    public function block_javascript($context, array $blocks = array())
    {
        // line 83
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDGYOmrW5zjuyneSG5b5D5Bvmwi5GmJfTA&sensor=false\"></script>
    <script type=\"text/javascript\">
        if(typeof(Storage)!==\"undefined\") {
            \$(\"#";
        // line 87
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), "vars"), "id"), "html", null, true);
        echo "\"));
            \$(\"#";
        // line 88
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\"));
            \$(\"#";
        // line 89
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\"));
            \$(\"#";
        // line 90
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\").val(sessionStorage.getItem(\"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\"));
        }
        
        \$(\"#btnListar\").click(function(event) {
            event.preventDefault();
            carregarChamados(event);
            \$(\"#divPaginas\").show();
        });
        
        \$(\"#btnMapa\").click(function(event) {
            event.preventDefault();
            carregarChamados(event, true);
            \$(\"#divPaginas\").hide();
        });
        
        \$(\"#lnkProximo\").click(function() {
            \$(\"#";
        // line 106
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val((parseInt(\$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1).toString() );
            \$(\"#numeroPagina\").html(parseInt(\$(\"#";
        // line 107
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1);
            carregarChamados();
        });

        \$(\"#lnkAnterior\").click(function() {
            if(\$(\"#";
        // line 112
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val() > 0) {
                \$(\"#";
        // line 113
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val((parseInt(\$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) - 1).toString() );
                \$(\"#numeroPagina\").html(parseInt(\$(\"#";
        // line 114
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "pagina"), "vars"), "id"), "html", null, true);
        echo "\").val()) + 1);
                carregarChamados();
            }
        });

        function carregarChamados(event, mapa) {
            var url = mapa ? \"";
        // line 120
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "?view=mapa\" : \"";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "\";
            \$.ajax({
                 type: \"POST\",
                 url: url,
                 data: \$(\"#formPesquisa\").serialize(),
                 success: function(retorno) {
                     \$(\"#chamados\").html(retorno);
                 }
             });
             sessionStorage.setItem(\"";
        // line 129
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "equipe"), "vars"), "id"), "html", null, true);
        echo "\").val());
             sessionStorage.setItem(\"";
        // line 130
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val());
             sessionStorage.setItem(\"";
        // line 131
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "status"), "vars"), "id"), "html", null, true);
        echo "\").val());
             sessionStorage.setItem(\"";
        // line 132
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\", \$(\"#";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "aberto"), "vars"), "id"), "html", null, true);
        echo "\").val());
        }
                
        /**
                    (function poll() {
                        setTimeout(function() {
                            if(\$(\"#";
        // line 138
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "categoria"), "vars"), "id"), "html", null, true);
        echo "\").val() > 0) {
                                \$.ajax({
                                    global: false,
                                    type: \"POST\",
                                    url: \"";
        // line 142
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "vars"), "action"), "html", null, true);
        echo "\",
                                    data: \$(\"#formPesquisa\").serialize(),
                                    success: function(retorno) {
                                        \$(\"#chamados\").html(retorno);
                                    }
                                });
                            }
                            poll();
                        }, 15000);
                    })();
                */
    </script>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:formPesquisaAdmin.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  313 => 142,  306 => 138,  295 => 132,  289 => 131,  283 => 130,  277 => 129,  263 => 120,  254 => 114,  248 => 113,  244 => 112,  236 => 107,  230 => 106,  209 => 90,  203 => 89,  197 => 88,  191 => 87,  183 => 83,  180 => 82,  162 => 67,  154 => 62,  142 => 53,  138 => 52,  132 => 49,  128 => 48,  120 => 43,  116 => 42,  110 => 39,  106 => 38,  100 => 35,  93 => 31,  84 => 25,  80 => 24,  74 => 21,  70 => 20,  62 => 15,  58 => 14,  52 => 11,  48 => 10,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

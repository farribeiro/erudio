<?php

/* FilaUnicaBundle:Inscricao:formFila.html.twig */
class __TwigTemplate_8dddc42c10be2857711a388274105b22f2806f43cf1be9fc94848cca58bbd388 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FilaUnicaBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FilaUnicaBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo " Fila Única > Consulta Interna ";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formFila\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
               <label for=\"zoneamento\">Zoneamento:</label>
               <select id=\"zoneamento\" name=\"zoneamento\" class=\"form-control\">
                    ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["zoneamentos"]) ? $context["zoneamentos"] : $this->getContext($context, "zoneamentos")));
        foreach ($context['_seq'] as $context["_key"] => $context["zoneamento"]) {
            // line 12
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "descricao"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['zoneamento'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "               </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"ano\">Turma:</label>
                <select id=\"ano\" name=\"ano\" class=\"form-control\">
                    ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["anosEscolares"]) ? $context["anosEscolares"] : $this->getContext($context, "anosEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["ano"]) {
            // line 20
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ano'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
           <div class=\"col-lg-6\">
                <label for=\"protocolo\">OU buscar por protocolo:</label>
                <input type=\"text\" id=\"protocolo\" name=\"protocolo\" class=\"form-control\">
            </div>
            ";
        // line 31
        echo "                <div class=\"col-lg-4\">
                    <label for=\"filaPublica\">Incluir processos internos:</label> <br /> 
                    <input id=\"filaPublica\" name=\"filaPublica\" value=\"1\" type=\"radio\" /> Sim 
                    <input id=\"filaPublica\" name=\"filaPublica\" value=\"0\" type=\"radio\" checked /> Não
                </div>
            ";
        // line 37
        echo "            <div class=\"col-lg-2\">
                <br /> 
                <button id=\"btnBuscar\" type=\"button\" class=\"btn btn-primary btn-block\">Buscar</button>
            </div>
        </div>
    </form>

    <div id=\"divFila\"></div>
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
        \$(\"#btnBuscar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 53
        echo $this->env->getExtension('routing')->getPath("fu_inscricao_exibirFila");
        echo "\",
                data: \$(\"#formFila\").serialize(),
                success: function(retorno){
                    \$(\"#divFila\").html(retorno);  
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:formFila.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 53,  117 => 48,  114 => 47,  102 => 37,  95 => 31,  85 => 22,  74 => 20,  70 => 19,  63 => 14,  50 => 12,  46 => 11,  39 => 6,  36 => 5,  30 => 3,);
    }
}

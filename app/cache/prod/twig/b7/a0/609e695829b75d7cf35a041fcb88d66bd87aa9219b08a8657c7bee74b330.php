<?php

/* FilaUnicaBundle:Vaga:formPesquisa.html.twig */
class __TwigTemplate_b7a0609e695829b75d7cf35a041fcb88d66bd87aa9219b08a8657c7bee74b330 extends Twig_Template
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
        echo " Fila Única > Vagas > Gerência ";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formPesquisaVaga\">
        ";
        // line 7
        if ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
            // line 8
            echo "            <div class=\"row\">
                <div class=\"col-lg-12\">
                    <label for=\"zoneamento\">Zoneamento:</label>
                    <select id=\"zoneamento\" name=\"zoneamento\" class=\"form-control\">
                        <option value=\"\">Todos</option>
                        ";
            // line 13
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["zoneamentos"]) ? $context["zoneamentos"] : $this->getContext($context, "zoneamentos")));
            foreach ($context['_seq'] as $context["_key"] => $context["zoneamento"]) {
                // line 14
                echo "                            <option value=\"";
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
            // line 16
            echo "                    </select>
               </div>
            </div>
        ";
        }
        // line 20
        echo "        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"unidade\">Unidade:</label>
                <select id=\"unidade\" name=\"unidade\" class=\"form-control\">
                    ";
        // line 24
        if ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
            echo " <option value=\"\">Todos</option> ";
        }
        // line 25
        echo "                    ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 26
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo " | ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"ano\">Turma:</label>
                <select id=\"ano\" name=\"ano\" class=\"form-control\">
                    <option value=\"\">Todos</option>
                    ";
        // line 34
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["anosEscolares"]) ? $context["anosEscolares"] : $this->getContext($context, "anosEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["ano"]) {
            // line 35
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
        // line 37
        echo "                </select>
           </div>
        </div>
        <div class=\"row\">
           <div class=\"col-lg-6\">
               <button id=\"btnBuscar\" type=\"button\" class=\"btn btn-primary\">Buscar</button>
           </div>
        </div>
    </form>

    <div id=\"divListaVagas\"></div>
";
    }

    // line 50
    public function block_javascript($context, array $blocks = array())
    {
        // line 51
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#btnBuscar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 56
        echo $this->env->getExtension('routing')->getPath("fu_vaga_pesquisar");
        echo "\",
                data: \$(\"#formPesquisaVaga\").serialize(),
                success: function(retorno){
                    \$(\"#divListaVagas\").html(retorno);  
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Vaga:formPesquisa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 56,  143 => 51,  140 => 50,  125 => 37,  114 => 35,  110 => 34,  102 => 28,  89 => 26,  84 => 25,  80 => 24,  74 => 20,  68 => 16,  55 => 14,  51 => 13,  44 => 8,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

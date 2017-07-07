<?php

/* FilaUnicaBundle:Vaga:formCadastro.html.twig */
class __TwigTemplate_ee018437b6cf5f1d074caa76d9caa73b6e9c20b9178f98a0f386c63752fd4a83 extends Twig_Template
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
        echo " Fila Única > Vagas > Abertura ";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formCadastroVaga\" method=\"post\" action=\"";
        echo $this->env->getExtension('routing')->getPath("fu_vaga_cadastrar");
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"unidade\">Unidade:</label>
                <select class=\"form-control\" id=\"unidade\" name=\"unidade\" required >
                    <option>Selecione uma unidade</option>
                    ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 13
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo " | ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"ano\">Turma:</label>
                <select class=\"form-control\" id=\"ano\" name=\"ano\" required >
                    <option>Selecione um ano escolar</option>
                    ";
        // line 21
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["anosEscolares"]) ? $context["anosEscolares"] : $this->getContext($context, "anosEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["ano"]) {
            // line 22
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
        // line 24
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"periodo\">Período:</label>
                <select class=\"form-control\" id=\"periodo\" name=\"periodo\" required >
                    ";
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["periodos"]) ? $context["periodos"] : $this->getContext($context, "periodos")));
        foreach ($context['_seq'] as $context["_key"] => $context["periodo"]) {
            // line 32
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['periodo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"\">Número de vagas:</label>
                <input class=\"form-control\" id=\"quantidade\" name=\"quantidade\" type=\"number\" min=\"1\" value=\"1\" required />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <button id=\"btnCadastrar\" type=\"submit\" class=\"btn btn-primary\">Cadastrar</button>
            </div>
        </div>
    </form>
";
    }

    // line 49
    public function block_javascript($context, array $blocks = array())
    {
        // line 50
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#btnCadastrar\").click( function(ev) {
            if(\$(\"#unidade\").val() > 0 && \$(\"#ano\").val() > 0) {
                if(confirm(\"Confirmar criação das vagas?\")) {
                    \$(\"#formCadastroVaga\").submit();
                }
            } else {
                alert(\"Preencha todos os campos\");
            }
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Vaga:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 50,  130 => 49,  113 => 34,  102 => 32,  98 => 31,  89 => 24,  78 => 22,  74 => 21,  66 => 15,  53 => 13,  49 => 12,  39 => 6,  36 => 5,  30 => 3,);
    }
}

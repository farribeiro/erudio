<?php

/* EstagioBundle:Vaga:editVaga.html.twig */
class __TwigTemplate_9f19f018849c4ff4a9a96f6a9e5b659b536f96f968dd886734fc5558cf055993 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest")) ? ("::templateAjax.html.twig") : ("::template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_css($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "<a href=\"";
        echo $this->env->getExtension('routing')->getPath("vagas_estagio");
        echo "\"><button style=\"margin-top: -5px;\" class=\"btn btn-danger\">Voltar</button></a> Vagas de Estágio - Editar";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row\" id=\"formErrors\">
        ";
        // line 11
        echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
        echo "
    </div>
    ";
        // line 13
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
        <div class=\"row\">
            <div class=\"col-md-6\"><strong>Etapa*</strong>";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "titulo"), 'widget');
        echo "</div>
            <div class=\"col-md-6\"><strong>Modalidade*</strong>";
        // line 16
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "disciplina"), 'widget');
        echo "</div>
            <div class=\"col-md-6\"><strong>Unidade</strong>
                <select id=\"VagaForm_unidade\" name=\"VagaForm_unidade\" class=\"form-control\">             
                    ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 20
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "                </select>
            </div>
            <div class=\"col-md-6\"><strong>Turno*</strong>";
        // line 24
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "turno"), 'widget');
        echo "</div>
        </div>
        <div class=\"row\">
            <div class=\"col-md-12\"><strong>Vagas*</strong>";
        // line 27
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "totalVagas"), 'widget');
        echo "</div>
            <div class=\"col-md-12\"><strong>Descrição</strong>";
        // line 28
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'widget');
        echo "</div>
            <div class=\"col-md-12\">";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "Salvar"), 'row');
        echo "</div>
        </div>
    ";
        // line 31
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
";
    }

    // line 34
    public function block_javascript($context, array $blocks = array())
    {
        // line 35
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$('document').ready(function (){
           \$('option[value=\"";
        // line 38
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidade"), "html", null, true);
        echo "\"]').attr('selected','selected');
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:editVaga.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 38,  120 => 35,  117 => 34,  111 => 31,  106 => 29,  102 => 28,  98 => 27,  92 => 24,  88 => 22,  77 => 20,  73 => 19,  67 => 16,  63 => 15,  58 => 13,  53 => 11,  50 => 10,  47 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

<?php

/* EstagioBundle:Vaga:listaVaga.html.twig */
class __TwigTemplate_a4bd3a5f992ae2b7b4dcb7d169496e60a43d0d4aed2c24ee542dbbd0aefb88d5 extends Twig_Template
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
        echo "Vagas de Estágio";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row\">
        <div class=\"col-md-12\">
            <a href=\"";
        // line 12
        echo $this->env->getExtension('routing')->getPath("adicionar_vaga");
        echo "\" class=\"btn btn-primary\">Adicionar Vaga de Estágio</a>
        </div>
        <div class=\"col-md-12\">
            <div class=\"col-md-6\">
                <label>Unidade:</label>
                <select id=\"unidade_vagas\" name=\"unidade_vagas\" class=\"form-control\">
                    <option></option>
                    ";
        // line 19
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["entidades"]) ? $context["entidades"] : $this->getContext($context, "entidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["entidade"]) {
            // line 20
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 22
        echo "                </select>
            </div>
                
            <div class=\"col-md-6\">
                <label>Turno:</label>
                <select id=\"turno_vagas\" name=\"turno_vagas\" class=\"form-control\">
                    <option></option>
                    ";
        // line 29
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["turnos"]) ? $context["turnos"] : $this->getContext($context, "turnos")));
        foreach ($context['_seq'] as $context["_key"] => $context["turno"]) {
            // line 30
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['turno'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "                </select>
            </div>
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-md-12 vagas_box\"></div>
    </div>
";
    }

    // line 41
    public function block_javascript($context, array $blocks = array())
    {
        // line 42
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$('document').ready(function (){
           \$(\"#unidade_vagas, #turno_vagas\").change(function(){
                var unidade = \$(\"#unidade_vagas\").val();
                var turno = \$(\"#turno_vagas\").val();
                if (unidade !== '' || turno !== '') {
                    \$.ajax({
                        url: '";
        // line 50
        echo $this->env->getExtension('routing')->getPath("buscar_vagas_geral");
        echo "',
                        dataType: 'html',
                        type: 'POST',
                        data: { 'unidade_vagas': unidade, 'turno_vagas': turno },
                        success: function (data) {
                            \$('.vagas_box').html(data);
                        }
                    });
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:listaVaga.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  127 => 50,  115 => 42,  112 => 41,  101 => 32,  90 => 30,  86 => 29,  77 => 22,  66 => 20,  62 => 19,  52 => 12,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

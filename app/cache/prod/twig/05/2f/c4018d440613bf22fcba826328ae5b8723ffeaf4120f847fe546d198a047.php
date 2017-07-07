<?php

/* EstagioBundle:Orientador:inscrever.html.twig */
class __TwigTemplate_052fc4018d440613bf22fcba826328ae5b8723ffeaf4120f847fe546d198a047 extends Twig_Template
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
        echo "Inscrever Estagiário";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <form method='POST'>
        <div class=\"row\" style=\"margin: 1rem;\">
                <div class=\"col-md-6\">
                    <label>Nome:</label>
                    <input type=\"text\" class=\"form-control\" name=\"nomeEstagio\" id=\"nomeEstagio\" placeholder='Nome' />
                </div>
                <div class=\"col-md-6\">
                    <label>CPF:</label>
                    <input type=\"text\" class=\"form-control\" name=\"cpfEstagio\" id=\"cpfEstagio\" placeholder='CPF' />
                </div>
                <div class=\"col-md-6\">
                    <label>E-mail:</label>
                    <input type=\"text\" class=\"form-control\" name=\"emailEstagio\" id=\"emailEstagio\" placeholder='E-mail' />
                </div>
                <div class=\"col-md-6\">
                    <label>Data de Nascimento:</label>
                    <input type=\"text\" class=\"form-control\" name=\"dataEstagio\" id=\"dataEstagio\" placeholder='Data de Nascimento' />
                </div>
                <div class=\"col-md-6\">
                    <label>Início do Estágio(Observação):</label>
                    <input type=\"text\" class=\"form-control\" name=\"inicioObsEstagio\" id=\"inicioObsEstagio\" placeholder='Início do Estágio - Observação' />
                </div>
                <div class=\"col-md-6\">
                    <label>Final do Estágio(Observação):</label>
                    <input type=\"text\" class=\"form-control\" name=\"finalObsEstagio\" id=\"finalObsEstagio\" placeholder='Final do Estágio - Observação' />
                </div>
                <div class=\"col-md-6\">
                    <label>Início do Estágio(Intervenção):</label>
                    <input type=\"text\" class=\"form-control\" name=\"inicioEstagio\" id=\"inicioEstagio\" placeholder='Início do Estágio - Intervenção' />
                </div>
                <div class=\"col-md-6\">
                    <label>Final do Estágio(Intervenção):</label>
                    <input type=\"text\" class=\"form-control\" name=\"finalEstagio\" id=\"finalEstagio\" placeholder='Final do Estágio - Intervenção' />
                </div>
                <div class=\"col-md-6\">
                    <label>Telefone:</label>
                    <input type=\"text\" class=\"form-control\" name=\"telefoneEstagio\" id=\"telefoneEstagio\" placeholder='Telefone' />
                </div>
                <div class=\"col-md-6\">
                    <label>Unidade:</label>
                    <select id=\"unidade_vagas\" name=\"unidade_vagas\" class=\"form-control\">
                        <option></option>
                        ";
        // line 52
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["entidades"]) ? $context["entidades"] : $this->getContext($context, "entidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["entidade"]) {
            // line 53
            echo "                            <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "nome"), "html", null, true);
            echo "</option>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        echo "                    </select>
                </div>
                <div class=\"col-md-6\">
                    <label>Turno:</label>
                    <select id=\"turno_vagas\" name=\"turno_vagas\" class=\"form-control\">
                        <option></option>
                        ";
        // line 61
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["turnos"]) ? $context["turnos"] : $this->getContext($context, "turnos")));
        foreach ($context['_seq'] as $context["_key"] => $context["turno"]) {
            // line 62
            echo "                            <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "nome"), "html", null, true);
            echo "</option>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['turno'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 64
        echo "                    </select>
                </div>
                <div class=\"col-md-6\">
                    <label>Vaga pretendida:</label>
                    <select class=\"form-control\" name=\"vagaEstagio\" id=\"vagaEstagio\">
                        
                    </select>
                </div>
        </div>
        <div class=\"row\" style=\"margin: 1rem;\">
            <div class=\"col-md-12\"><input type=\"submit\" class=\"form-control btn btn-primary\" value='Inscrever' /></div>
        </div>
    </form>
";
    }

    // line 79
    public function block_javascript($context, array $blocks = array())
    {
        // line 80
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"";
        // line 81
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.maskedinput.min.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\">
        \$(document).ready(function(){
            \$(\"#dataEstagio\").mask(\"99/99/9999\");
            \$(\"#inicioEstagio\").mask(\"99/99/9999\");
            \$(\"#finalEstagio\").mask(\"99/99/9999\");
            \$(\"#inicioObsEstagio\").mask(\"99/99/9999\");
            \$(\"#finalObsEstagio\").mask(\"99/99/9999\");
            \$(\"#telefoneEstagio\").mask(\"(99)99999999?9\");
            
            \$(\"#unidade_vagas, #turno_vagas\").change(function(){
                var unidade = \$(\"#unidade_vagas\").val();
                var turno = \$(\"#turno_vagas\").val();
                if (unidade !== '' || turno !== '') {
                    \$.ajax({
                        url: '";
        // line 96
        echo $this->env->getExtension('routing')->getPath("buscar_vagas");
        echo "',
                        dataType: 'html',
                        type: 'POST',
                        data: { 'unidade_vagas': unidade, 'turno_vagas': turno },
                        success: function (data) {
                            \$('#vagaEstagio').html(data);
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
        return "EstagioBundle:Orientador:inscrever.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  173 => 96,  155 => 81,  150 => 80,  147 => 79,  130 => 64,  119 => 62,  115 => 61,  107 => 55,  96 => 53,  92 => 52,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

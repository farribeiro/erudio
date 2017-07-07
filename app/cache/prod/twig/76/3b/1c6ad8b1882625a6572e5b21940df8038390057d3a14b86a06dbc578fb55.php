<?php

/* EstagioBundle:Vaga:listarDeferidosPorEscola.html.twig */
class __TwigTemplate_763b1c6ad8b1882625a6572e5b21940df8038390057d3a14b86a06dbc578fb55 extends Twig_Template
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
        echo "Estágios Deferidos";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row\">
        <div class=\"col-md-12\">
            <div class=\"panel-group\" id=\"accordion\" role=\"tablist\" aria-multiselectable=\"true\">
                ";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")));
        foreach ($context['_seq'] as $context["_key"] => $context["inscricao"]) {
            // line 14
            echo "                    <div class=\"panel panel-default\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"heading";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
            echo "\">
                            <h4 class=\"panel-title\">
                                <a role=\"button\" data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
            echo "\" aria-expanded=\"true\" aria-controls=\"collapse";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
            echo "\">
                                    ";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "estagiario"), "nome"), "html", null, true);
            echo "
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapse";
            // line 22
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
            echo "\" class=\"panel-collapse collapse\" role=\"tabpanel\" aria-labelledby=\"heading";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
            echo "\">
                            <div class=\"panel-body\">
                                <h3>Dados do Estagiário</h3>
                                <strong>Nome:</strong> ";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "estagiario"), "nome"), "html", null, true);
            echo "
                                <br />
                                <strong>E-mail:</strong> ";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "estagiario"), "email"), "html", null, true);
            echo "
                                <br />
                                ";
            // line 29
            if ((!twig_test_empty($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "telefone")))) {
                // line 30
                echo "                                    <strong>Telefone:</strong> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "telefone"), "html", null, true);
                echo "
                                    <br />
                                ";
            }
            // line 33
            echo "                                <hr />
                                <h3>Dados do Estágio</h3>
                                <strong>Unidade de Estágio:</strong> ";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "unidade"), "nome"), "html", null, true);
            echo "
                                <br />
                                <strong>Etapa / Modalidade:</strong> ";
            // line 37
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "titulo"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "disciplina"), "html", null, true);
            echo "
                                <br />
                                <strong>Turno:</strong> ";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turnos"]) ? $context["turnos"] : $this->getContext($context, "turnos")), $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vaga"), "turno"), array(), "array"), "html", null, true);
            echo "
                                <br />
                                ";
            // line 41
            if ((($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicioObservacao") != "0000-00-00") && ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fimObservacao") != "0000-00-00"))) {
                // line 42
                echo "                                    <strong>Período de Observação:</strong> ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicioObservacao"), "d/m/Y"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fimObservacao"), "d/m/Y"), "html", null, true);
                echo "
                                    <br />
                                ";
            }
            // line 45
            echo "                                ";
            if ((($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicio") != "0000-00-00") && ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fim") != "0000-00-00"))) {
                // line 46
                echo "                                    <strong>Período de Intervenção:</strong> ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "inicio"), "d/m/Y"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "fim"), "d/m/Y"), "html", null, true);
                echo "
                                    <br />
                                ";
            }
            // line 49
            echo "                                
                                <hr />
                                <h3>Dados do Orientador</h3>
                                <strong>Orientador:</strong> ";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "orientador"), "nome"), "html", null, true);
            echo "
                                <br />
                                ";
            // line 54
            if ($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : null), "orientador", array(), "any", false, true), "instituicao", array(), "any", true, true)) {
                // line 55
                echo "                                    <strong>Instituição:</strong> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["instituicoes"]) ? $context["instituicoes"] : $this->getContext($context, "instituicoes")), $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "orientador"), "instituicao"), array(), "array"), "html", null, true);
                echo "
                                ";
            }
            // line 57
            echo "                            </div>
                        </div>
                    </div>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['inscricao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 61
        echo "            </div>
        </div>
    </div>
";
    }

    // line 66
    public function block_javascript($context, array $blocks = array())
    {
        // line 67
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$('document').ready(function (){
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Vaga:listarDeferidosPorEscola.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  186 => 67,  183 => 66,  176 => 61,  167 => 57,  161 => 55,  159 => 54,  154 => 52,  149 => 49,  140 => 46,  137 => 45,  128 => 42,  126 => 41,  121 => 39,  114 => 37,  109 => 35,  105 => 33,  98 => 30,  96 => 29,  91 => 27,  86 => 25,  78 => 22,  71 => 18,  65 => 17,  60 => 15,  57 => 14,  53 => 13,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

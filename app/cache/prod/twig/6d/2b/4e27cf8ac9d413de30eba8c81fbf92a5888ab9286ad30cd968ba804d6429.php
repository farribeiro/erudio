<?php

/* QuestionarioBundle:Questionario:listarQuestionarios.html.twig */
class __TwigTemplate_6d2b4e27cf8ac9d413de30eba8c81fbf92a5888ab9286ad30cd968ba804d6429 extends Twig_Template
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
        echo "Questionários";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "        <div class=\"row\">
            ";
        // line 11
        if (((isset($context["msgRetorno"]) ? $context["msgRetorno"] : $this->getContext($context, "msgRetorno")) != null)) {
            // line 12
            echo "                <div class=\"col-md-12\">
                    <div class=\"alert alert-info\" style=\"padding: 7px; font-size: 12px; width: 95%; margin-left: 2.5%; margin-bottom: 1%; margin-top: 2%;\">
                        ";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["msgRetorno"]) ? $context["msgRetorno"] : $this->getContext($context, "msgRetorno")), "html", null, true);
            echo "
                    </div>
                </div>
            ";
        }
        // line 18
        echo "            <div class=\"col-md-12\">
                <table class=\"table table-striped table-hover tableDGP\">
                    <thead>
                        <tr>
                            <th>Nome</td>
                        </tr>
                    </thead>
                    <tbody>
                        ";
        // line 26
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["questionarios"]) ? $context["questionarios"] : $this->getContext($context, "questionarios")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["questionario"]) {
            // line 27
            echo "                            <tr>
                                <td><a href=\"";
            // line 28
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("questionarios_responder", array("id" => $this->getAttribute((isset($context["questionario"]) ? $context["questionario"] : $this->getContext($context, "questionario")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["questionario"]) ? $context["questionario"] : $this->getContext($context, "questionario")), "nome"), "html", null, true);
            echo "</a></td>
                            </tr>
                        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 31
            echo "                            <tr>
                                <td colspan=\"5\" class=\"text-center\">Nenhum questionario disponível até o momento.</td>
                            </tr>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['questionario'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "                    </tbody>
                </table>
            </div>
        </div>
                        
        <div class=\"row\">
            <div class=\"col-md-12\">
                <h3>Questionários Respondidos ou Iniciados</h3>
                <table class=\"table table-striped table-hover tableDGP\">
                    <thead>
                        <tr>
                            <th>Nome</td>
                        </tr>
                    </thead>
                    <tbody>
                        ";
        // line 50
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["respondidos"]) ? $context["respondidos"] : $this->getContext($context, "respondidos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["respondido"]) {
            // line 51
            echo "                            <tr>
                                <td><a href=\"";
            // line 52
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("questionarios_responder", array("id" => $this->getAttribute((isset($context["respondido"]) ? $context["respondido"] : $this->getContext($context, "respondido")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["respondido"]) ? $context["respondido"] : $this->getContext($context, "respondido")), "nome"), "html", null, true);
            echo "</a></td>
                            </tr>
                        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 55
            echo "                            <tr>
                                <td colspan=\"5\" class=\"text-center\">Nenhum questionario respondido até o momento.</td>
                            </tr>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['respondido'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 59
        echo "                    </tbody>
                </table>
            </div>
        </div>         
";
    }

    // line 65
    public function block_javascript($context, array $blocks = array())
    {
        // line 66
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
        return "QuestionarioBundle:Questionario:listarQuestionarios.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  156 => 66,  153 => 65,  145 => 59,  136 => 55,  126 => 52,  123 => 51,  118 => 50,  101 => 35,  92 => 31,  82 => 28,  79 => 27,  74 => 26,  64 => 18,  57 => 14,  53 => 12,  51 => 11,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

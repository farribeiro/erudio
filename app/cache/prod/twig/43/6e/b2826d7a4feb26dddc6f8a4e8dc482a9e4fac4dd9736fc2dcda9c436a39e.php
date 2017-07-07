<?php

/* SuporteTecnicoBundle:Index:index.html.twig */
class __TwigTemplate_436eb2826d7a4feb26dddc6f8a4e8dc482a9e4fac4dd9736fc2dcda9c436a39e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'page' => array($this, 'block_page'),
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
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Suporte Técnico SME";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        if ((!$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest"))) {
            // line 7
            echo "        <div class=\"row\">
            <menu class=\"col-lg-2\">
                <div class=\"list-group\">
                    <a class=\"list-group-item\" href=\"";
            // line 10
            echo $this->env->getExtension('routing')->getPath("suporte_chamado_cadastrar");
            echo "\">Abrir Chamado</a>
                    <a class=\"list-group-item\" href=\"";
            // line 11
            echo $this->env->getExtension('routing')->getPath("suporte_chamado_pesquisar");
            echo "\">Chamados</a>
                    ";
            // line 12
            if ($this->env->getExtension('security')->isGranted("ROLE_SUPORTE_ADMIN")) {
                // line 13
                echo "                        <a class=\"list-group-item\" href=\"";
                echo $this->env->getExtension('routing')->getPath("suporte_equipe_pesquisar");
                echo "\">Equipes</a>
                        <a class=\"list-group-item\" href=\"";
                // line 14
                echo $this->env->getExtension('routing')->getPath("suporte_categoria_pesquisar");
                echo "\">Categorias</a>
                    ";
            }
            // line 16
            echo "                </div>
            </menu>
            <div id=\"page\" class=\"col-lg-10\">
                ";
            // line 19
            $this->displayBlock('page', $context, $blocks);
            // line 20
            echo "            </div>
        </div>
    ";
        } else {
            // line 23
            echo "        ";
            $this->displayBlock("page", $context, $blocks);
            echo "
    ";
        }
    }

    // line 19
    public function block_page($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Index:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 19,  77 => 23,  72 => 20,  70 => 19,  65 => 16,  60 => 14,  55 => 13,  53 => 12,  49 => 11,  45 => 10,  40 => 7,  37 => 6,  34 => 5,  28 => 3,);
    }
}

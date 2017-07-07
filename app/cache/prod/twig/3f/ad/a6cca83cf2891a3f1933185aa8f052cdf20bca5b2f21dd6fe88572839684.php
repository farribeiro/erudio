<?php

/* DGPBundle:Pessoa:cadastroPessoa.html.twig */
class __TwigTemplate_3fada6cca83cf2891a3f1933185aa8f052cdf20bca5b2f21dd6fe88572839684 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'tabContent' => array($this, 'block_tabContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:servidor.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <ul class=\"nav nav-tabs\">
        <li ";
        // line 7
        if ((((array_key_exists("activeTab", $context)) ? (_twig_default_filter((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")), "1")) : ("1")) == "1")) {
            echo "class=\"active\"";
        }
        echo "> 
            <a href=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_alterar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Dados Pessoais</a>
        </li>
        <li ";
        // line 10
        if (((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")) == "2")) {
            echo "class=\"active\"";
        }
        echo ">
            <a href=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_endereco_alterar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Endereço</a>
        </li>
        <li ";
        // line 13
        if (((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")) == "3")) {
            echo "class=\"active\"";
        }
        echo ">
            <a href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_telefone_cadastrar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Telefones</a>
        </li>
        <li ";
        // line 16
        if (((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")) == "4")) {
            echo "class=\"active\"";
        }
        echo ">
            <a href=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_formacao_listar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Titulação</a>
        </li>
        <li ";
        // line 19
        if (((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")) == "5")) {
            echo "class=\"active\"";
        }
        echo ">
            <a href=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_vinculo_listar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Vínculos</a>
        </li>
        <li ";
        // line 22
        if (((isset($context["activeTab"]) ? $context["activeTab"] : $this->getContext($context, "activeTab")) == "6")) {
            echo "class=\"active\"";
        }
        echo ">
            <a href=\"";
        // line 23
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_usuario_alterarSenha", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\">Usuário</a>
        </li>
    </ul>
    <div id=\"tabContent\" class=\"container\">
        ";
        // line 27
        $this->displayBlock('tabContent', $context, $blocks);
        // line 28
        echo "    </div>
";
    }

    // line 27
    public function block_tabContent($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "DGPBundle:Pessoa:cadastroPessoa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  118 => 27,  113 => 28,  111 => 27,  104 => 23,  98 => 22,  93 => 20,  87 => 19,  82 => 17,  76 => 16,  71 => 14,  65 => 13,  54 => 10,  49 => 8,  43 => 7,  40 => 6,  37 => 5,  30 => 3,  220 => 109,  214 => 106,  206 => 101,  200 => 98,  192 => 93,  186 => 90,  177 => 84,  171 => 81,  163 => 76,  157 => 73,  146 => 65,  138 => 60,  132 => 57,  123 => 51,  117 => 48,  108 => 42,  102 => 39,  94 => 34,  88 => 31,  80 => 26,  74 => 23,  66 => 18,  60 => 11,  56 => 14,  50 => 11,  45 => 9,  42 => 8,  39 => 7,  31 => 5,  26 => 3,);
    }
}

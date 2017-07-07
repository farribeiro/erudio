<?php

/* EstagioBundle:Orientador:emailAdmin.html.twig */
class __TwigTemplate_1b4c8006ca77065e44e0b9a5cdb2c05a670ebc2110c1208234c4665a213950d6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt-br\">    

";
        // line 4
        $this->displayBlock('body', $context, $blocks);
        // line 50
        echo "    
</html>    ";
    }

    // line 4
    public function block_body($context, array $blocks = array())
    {
        // line 5
        echo "\t<div style=\"background: #6699cc; width: 100%; float: left; box-shadow: 0px 5px 10px #000;\">
\t\t<img src=\"http://educacao.itajai.sc.gov.br/images/stories/logoo.png\" style=\"width: 30%;\" />
\t</div>
\t<div id=\"content\" style=\"float: left; margin-top: 10px;\">
\t\t<p>
                    <strong>Uma inscrição de estágio foi realizada.</strong>
                    <br />
                    Segue abaixo os dados do candidato:
                    <br /><br />
                    Nome: ";
        // line 14
        echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
        echo "
                    <br />
                    Email: ";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["email"]) ? $context["email"] : $this->getContext($context, "email")), "html", null, true);
        echo "
                    <br />
                    Telefone: ";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "html", null, true);
        echo "
                    ";
        // line 19
        if (((isset($context["inicioObs"]) ? $context["inicioObs"] : $this->getContext($context, "inicioObs")) != "0000-00-00")) {
            // line 20
            echo "                    <br />
                    Início do Estágio(Observação): ";
            // line 21
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["inicioObs"]) ? $context["inicioObs"] : $this->getContext($context, "inicioObs")), "d/m/Y"), "html", null, true);
            echo "
                    ";
        }
        // line 23
        echo "                    ";
        if (((isset($context["fimObs"]) ? $context["fimObs"] : $this->getContext($context, "fimObs")) != "0000-00-00")) {
            // line 24
            echo "                    <br />
                    Final do Estágio(Observação): ";
            // line 25
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["fimObs"]) ? $context["fimObs"] : $this->getContext($context, "fimObs")), "d/m/Y"), "html", null, true);
            echo "
                    ";
        }
        // line 27
        echo "                    ";
        if (((isset($context["inicio"]) ? $context["inicio"] : $this->getContext($context, "inicio")) != "0000-00-00")) {
            // line 28
            echo "                    <br />
                    Início do Estágio(Intervenção): ";
            // line 29
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["inicio"]) ? $context["inicio"] : $this->getContext($context, "inicio")), "d/m/Y"), "html", null, true);
            echo "
                    ";
        }
        // line 31
        echo "                    ";
        if (((isset($context["fim"]) ? $context["fim"] : $this->getContext($context, "fim")) != "0000-00-00")) {
            // line 32
            echo "                    <br />
                    Final do Estágio(Intervenção): ";
            // line 33
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, (isset($context["fim"]) ? $context["fim"] : $this->getContext($context, "fim")), "d/m/Y"), "html", null, true);
            echo "
                    ";
        }
        // line 35
        echo "                    <br />
                    Turno: ";
        // line 36
        echo twig_escape_filter($this->env, (isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "html", null, true);
        echo "
                    <br />
                    Unidade: ";
        // line 38
        echo twig_escape_filter($this->env, (isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "html", null, true);
        echo "
                    <br />
                    Vaga: ";
        // line 40
        echo twig_escape_filter($this->env, (isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "html", null, true);
        echo "
                    <br /><br />
                    Para ver todos os inscritos nesta vaga, <a href=\"http://intranet.educacao.itajai.sc.gov.br/";
        // line 42
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("listar_inscritos", array("id" => (isset($context["vaga_id"]) ? $context["vaga_id"] : $this->getContext($context, "vaga_id")))), "html", null, true);
        echo "\">clique aqui</a>.
                    <br /><br />
                    Atenciosamente.
                    <br />
                    Secretaria Municipal de Educação.
\t\t</p>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Orientador:emailAdmin.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  121 => 42,  116 => 40,  111 => 38,  106 => 36,  103 => 35,  98 => 33,  95 => 32,  92 => 31,  87 => 29,  84 => 28,  81 => 27,  76 => 25,  73 => 24,  70 => 23,  65 => 21,  62 => 20,  60 => 19,  56 => 18,  51 => 16,  46 => 14,  35 => 5,  32 => 4,  27 => 50,  25 => 4,  20 => 1,);
    }
}

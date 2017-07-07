<?php

/* IntranetBundle:Acesso:email.html.twig */
class __TwigTemplate_2b11b123d146974ca8a94ecf1c36191f4a46a2ea1e717e306cd05d9e97d33722 extends Twig_Template
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
        // line 23
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
\t\t<h1>Olá ";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), "html", null, true);
        echo ".</h1>
\t\t<p>
\t\tRecebemos uma tentativa de <i>recuperação de senha</i>, caso não tenha solicitado, desconsidere esta mensagem.
\t\t<br />
\t\tCaso contrário, para recuperar sua senha de acesso, <a href=\"";
        // line 13
        echo $this->env->getExtension('routing')->getUrl("intranet_set_new_password");
        echo "?token=";
        echo twig_escape_filter($this->env, (isset($context["token"]) ? $context["token"] : $this->getContext($context, "token")), "html", null, true);
        echo "\">clique aqui.</a>
\t\t<br /><br />
\t\tEste link tem validade até o dia <strong>";
        // line 15
        echo twig_escape_filter($this->env, (isset($context["expire"]) ? $context["expire"] : $this->getContext($context, "expire")), "html", null, true);
        echo "</strong>.
\t\t<br /><br />
\t\tAtenciosamente.
\t\t<br />
\t\tSecretaria Municipal de Educação.
\t\t</p>
\t</div>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Acesso:email.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  55 => 15,  48 => 13,  41 => 9,  35 => 5,  32 => 4,  27 => 23,  25 => 4,  20 => 1,);
    }
}

<?php

/* IntranetBundle:Usuario:formPesquisaUsuario.html.twig */
class __TwigTemplate_6e9f23096d91d8c52972bee3e0a1ff1250398b307e96b385d708c603346d3fd0 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:administrador.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:administrador.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Gerenciamento de usuários";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formPesquisaUsuario\" method=\"post\">
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"nome\" class=\"control-label\">Nome: </label>
                <input id=\"nome\" name=\"nome\" type=\"text\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-4\">
                <label for=\"username\" class=\"control-label\">Nome de usuário: </label>
                <input id=\"username\" name=\"username\" type=\"text\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-4\">
                <label for=\"permissao\" class=\"control-label\">OU Permissão: </label>
                <select id=\"permissao\" name=\"permissao\" class=\"form-control\">
                    <option value=\"\"></option>
                    ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["roles"]) ? $context["roles"] : $this->getContext($context, "roles")));
        foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
            // line 21
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "nomeExibicao"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-3\">
                <button id=\"btnPesquisar\" type=\"button\" class=\"btn btn-primary\">Pesquisar</button>
            </div>
        </div>
    </form>
    <div id=\"divListaUsuarios\" style=\"padding-top: 5px;\"></div>
";
    }

    // line 35
    public function block_javascript($context, array $blocks = array())
    {
        // line 36
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#btnPesquisar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 41
        echo $this->env->getExtension('routing')->getPath("intranet_admin_pesquisarUsuario");
        echo "\",
                data: \$(\"#formPesquisaUsuario\").serialize(),
                success: function(retorno){
                    \$(\"#divListaUsuarios\").html(retorno);  
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Usuario:formPesquisaUsuario.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  96 => 41,  87 => 36,  84 => 35,  70 => 23,  59 => 21,  55 => 20,  39 => 6,  36 => 5,  30 => 3,);
    }
}

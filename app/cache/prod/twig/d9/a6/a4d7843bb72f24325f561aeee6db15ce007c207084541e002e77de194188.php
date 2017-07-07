<?php

/* IntranetBundle:Usuario:listaRoles.html.twig */
class __TwigTemplate_d9a6a4d7843bb72f24325f561aeee6db15ce007c207084541e002e77de194188 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:administrador.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
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
        echo "Gerenciamento de permissões";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <p>
        <strong>Usuário:</strong> ";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["usuario"]) ? $context["usuario"] : $this->getContext($context, "usuario")), "username"), "html", null, true);
        echo "
    </p>
    <p>
        <strong>Nome de exibição:</strong> ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["usuario"]) ? $context["usuario"] : $this->getContext($context, "usuario")), "nomeExibicao"), "html", null, true);
        echo "
    </p>
    <p>
        <strong>Permissões:</strong>
    </p>
    <div id=\"divRoles\">
        <table class=\"table table-striped table-hover tableRoles\">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Role</th>
                    <th>Opções</th>
                </tr>
            </thead>
            <tbody>
                ";
        // line 25
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["usuario"]) ? $context["usuario"] : $this->getContext($context, "usuario")), "rolesAtribuidas"));
        foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
            // line 26
            echo "                    <tr>
                        <td>";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "nomeExibicao"), "html", null, true);
            echo "</td>
                        <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "role"), "html", null, true);
            echo "</td>
                        <td>
                            <a class=\"btn-link\" href=\"";
            // line 30
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("intranet_admin_retirarRole", array("usuario" => $this->getAttribute((isset($context["usuario"]) ? $context["usuario"] : $this->getContext($context, "usuario")), "id"), "role" => $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "id"))), "html", null, true);
            echo "\">Remover</a>
                        </td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "            </tbody>
        </table>
    </div>
    <form method=\"post\" action=\"";
        // line 37
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("intranet_admin_atribuirRole", array("usuario" => $this->getAttribute((isset($context["usuario"]) ? $context["usuario"] : $this->getContext($context, "usuario")), "id"))), "html", null, true);
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <div class=\"input-group\">
                    <select id=\"role\" name=\"role\" class=\"form-control\">
                        ";
        // line 42
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["roles"]) ? $context["roles"] : $this->getContext($context, "roles")));
        foreach ($context['_seq'] as $context["_key"] => $context["role"]) {
            // line 43
            echo "                            <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "nomeExibicao"), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["role"]) ? $context["role"] : $this->getContext($context, "role")), "role"), "html", null, true);
            echo ")</option>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['role'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 45
        echo "                    </select>
                    <span class=\"input-group-btn\">
                        <button id=\"btnAddRole\" type=\"submit\" class=\"btn btn-primary\">+</button>
                    </span>
                </div>
            </div>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Usuario:listaRoles.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 45,  108 => 43,  104 => 42,  96 => 37,  91 => 34,  81 => 30,  76 => 28,  72 => 27,  69 => 26,  65 => 25,  47 => 10,  41 => 7,  38 => 6,  35 => 5,  29 => 3,);
    }
}

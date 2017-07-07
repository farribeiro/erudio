<?php

/* DGPBundle:Telefone:formCadastro.html.twig */
class __TwigTemplate_7f9069eae5d2898a152ec5cbd983dec368fb34ffb25c02a4120b0cfa168bb3f8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Pessoa:cadastroPessoa.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'tabContent' => array($this, 'block_tabContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Pessoa:cadastroPessoa.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["activeTab"] = "3";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " > Telefones ";
    }

    // line 7
    public function block_tabContent($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start', array("attr" => array("id" => "telefoneForm")));
        echo "
        ";
        // line 9
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'errors');
        echo "
        <div class=\"row\">
            <div class=\"col-lg-4\">
                ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "numero"), 'row');
        echo "
            </div>
            <div class=\"col-lg-4\">
                ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "descricao"), 'row');
        echo "
            </div>
            <div class=\"col-lg-4\">
                ";
        // line 18
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "falarCom"), 'row');
        echo "
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                ";
        // line 23
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "btnSalvar"), 'row', array("attr" => array("class" => "btn btn-primary")));
        echo "
            </div>
        </div>
    ";
        // line 26
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
    <hr />
    <table class=\"table table-striped table-hover tableDGP\">
        <thead>
            <tr>
                <th>Número</th>
                <th>Descrição</th>
                <th>Falar Com</th>
                <th class=\"text-center\">Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 38
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["telefones"]) ? $context["telefones"] : $this->getContext($context, "telefones")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["telefone"]) {
            // line 39
            echo "                <tr>
                    <td>";
            // line 40
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "numero"), "html", null, true);
            echo "</td>
                    <td>";
            // line 41
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "descricao"), "html", null, true);
            echo "</td>
                    <td>";
            // line 42
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "falarCom"), "html", null, true);
            echo "</td>
                    <td class=\"text-center\">
                        <a class=\"lnkExcluir\" href=\"";
            // line 44
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_telefone_excluir", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"), "telefone" => $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "id"))), "html", null, true);
            echo "\">
                            <span title=\"Excluir\" class=\"glyphicon glyphicon-remove\"></span>
                        </a>
                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 50
            echo "                <tr>
                    <td colspan=\"4\">A pessoa não possui nenhum telefone cadastrado</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['telefone'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 54
        echo "        </tbody>
    </table>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Telefone:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 54,  126 => 50,  115 => 44,  110 => 42,  106 => 41,  102 => 40,  99 => 39,  94 => 38,  79 => 26,  73 => 23,  65 => 18,  59 => 15,  53 => 12,  47 => 9,  42 => 8,  39 => 7,  31 => 5,  26 => 3,);
    }
}

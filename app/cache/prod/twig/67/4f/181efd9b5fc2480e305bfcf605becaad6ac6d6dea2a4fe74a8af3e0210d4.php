<?php

/* SuporteTecnicoBundle:Categoria:comboCategoria.html.twig */
class __TwigTemplate_674f181efd9b5fc2480e305bfcf605becaad6ac6d6dea2a4fe74a8af3e0210d4 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if (((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")) && $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "categoriaPai"))) {
            // line 2
            echo "    ";
            $this->env->loadTemplate("SuporteTecnicoBundle:Categoria:comboCategoria.html.twig")->display(array_merge($context, array("categoria" => $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "categoriaPai"), "subcategoria" => (isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")))));
        } else {
            // line 4
            echo "    <select required onchange=\"if(\$(this).val()) { loadComboCategoria(\$(this).val()); loadTags(\$(this).val()); }\" class=\"comboCategoria form-control\" style=\"width: auto; float: left;\">
        <option value=\"\">Selecione uma categoria</option>
        ";
            // line 6
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["categoriasRaiz"]) ? $context["categoriasRaiz"] : $this->getContext($context, "categoriasRaiz")));
            foreach ($context['_seq'] as $context["_key"] => $context["c"]) {
                // line 7
                echo "            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "id"), "html", null, true);
                echo "\" ";
                if (((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")) && ($this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "id") == $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "id")))) {
                    echo "selected";
                }
                echo " >
                ";
                // line 8
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "nome"), "html", null, true);
                echo "
            </option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['c'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 11
            echo "    </select>
";
        }
        // line 13
        echo "
";
        // line 14
        if (((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")) && twig_length_filter($this->env, $this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "subcategorias")))) {
            // line 15
            echo "    <select required onchange=\"if(\$(this).val()) { loadComboCategoria(\$(this).val()); loadTags(\$(this).val()); }\" class=\"comboCategoria form-control\" style=\"width: auto; float: left;\">
        <option value=\"\">Selecione uma subcategoria</option>
        ";
            // line 17
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["categoria"]) ? $context["categoria"] : $this->getContext($context, "categoria")), "subcategorias"));
            foreach ($context['_seq'] as $context["_key"] => $context["c"]) {
                // line 18
                echo "            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "id"), "html", null, true);
                echo "\" ";
                if (((isset($context["subcategoria"]) ? $context["subcategoria"] : $this->getContext($context, "subcategoria")) && ($this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "id") == $this->getAttribute((isset($context["subcategoria"]) ? $context["subcategoria"] : $this->getContext($context, "subcategoria")), "id")))) {
                    echo "selected";
                }
                echo " >
                ";
                // line 19
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["c"]) ? $context["c"] : $this->getContext($context, "c")), "nome"), "html", null, true);
                echo "
            </option>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['c'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 22
            echo "    </select>
";
        }
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Categoria:comboCategoria.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 22,  77 => 19,  68 => 18,  64 => 17,  60 => 15,  58 => 14,  51 => 11,  42 => 8,  33 => 7,  29 => 6,  25 => 4,  21 => 2,  19 => 1,  176 => 81,  173 => 80,  157 => 67,  151 => 64,  138 => 54,  132 => 51,  125 => 48,  122 => 47,  115 => 43,  109 => 40,  101 => 35,  97 => 34,  89 => 29,  85 => 28,  78 => 23,  76 => 22,  71 => 20,  67 => 19,  59 => 14,  55 => 13,  48 => 9,  43 => 7,  40 => 6,  37 => 5,  31 => 3,);
    }
}

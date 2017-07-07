<?php

/* DGPBundle:Pessoa:pessoas.html.twig */
class __TwigTemplate_176c63a390fbd45ce34d3615cb69ac73672995066d25263294ca8911304bbc9c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('body', $context, $blocks);
        // line 50
        echo "
";
        // line 51
        $this->displayBlock('javascript', $context, $blocks);
    }

    // line 1
    public function block_body($context, array $blocks = array())
    {
        // line 2
        echo "    <table class=\"table table-striped table-hover tableDGP\">
        <thead>
            <tr>
                <th>Servidor</td>
                <th>CPF</th>
                <th>Data Nascimento</td>
                <th>Email</td>
                <th>Ativo</td>
            </tr>
        </thead>
        <tbody>
            ";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["results"]) ? $context["results"] : $this->getContext($context, "results")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["result"]) {
            // line 14
            echo "                <tr>
                    <td><a href=\"";
            // line 15
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_alterar", array("pessoa" => $this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "id"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "nome"), "html", null, true);
            echo "</a></td>
                    <td>";
            // line 16
            echo twig_escape_filter($this->env, $this->env->getExtension('commons_extension')->cpfFilter($this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "cpf")), "html", null, true);
            echo "</td>
                    <td>";
            // line 17
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "dataNascimento"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td>";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "email"), "html", null, true);
            echo "</td>
                    ";
            // line 19
            if ($this->getAttribute((isset($context["result"]) ? $context["result"] : $this->getContext($context, "result")), "ativo")) {
                // line 20
                echo "                        <td><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/ok.png"), "html", null, true);
                echo "\" /></td>
                    ";
            } else {
                // line 22
                echo "                        <td><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/error.png"), "html", null, true);
                echo "\" /></td>
                    ";
            }
            // line 24
            echo "                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 26
            echo "                <tr>
                    <td colspan=\"5\" class=\"text-center\">Nenhuma pessoa encontrada</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['result'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        echo "        </tbody>
    </table>
    ";
        // line 32
        if (((isset($context["pages"]) ? $context["pages"] : $this->getContext($context, "pages")) > 1)) {
            // line 33
            echo "        <div id=\"row\">
            ";
            // line 34
            if (((isset($context["paginaAnterior"]) ? $context["paginaAnterior"] : $this->getContext($context, "paginaAnterior")) > 0)) {
                // line 35
                echo "                <a class=\"goToPage\" style=\"cursor:pointer;\" title=\"Primeira Página\" label=\"1\"><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/first.png"), "html", null, true);
                echo "\" /></a>
                <a class=\"goToPage\" style=\"cursor:pointer;\" title=\"Página Anterior\" label=\"";
                // line 36
                echo twig_escape_filter($this->env, (isset($context["paginaAnterior"]) ? $context["paginaAnterior"] : $this->getContext($context, "paginaAnterior")), "html", null, true);
                echo "\"><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/prev.png"), "html", null, true);
                echo "\" /></a>
            ";
            }
            // line 38
            echo "            ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["pages"]) ? $context["pages"] : $this->getContext($context, "pages"))));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 39
                echo "                ";
                if (((isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")) == (isset($context["page"]) ? $context["page"] : $this->getContext($context, "page")))) {
                    echo "<strong>";
                }
                // line 40
                echo "                    <a style=\"cursor:pointer;\" class=\"goPage\">";
                echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
                echo "</a>
                ";
                // line 41
                if (((isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")) == (isset($context["page"]) ? $context["page"] : $this->getContext($context, "page")))) {
                    echo "</strong>";
                }
                // line 42
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            echo "            ";
            if (((isset($context["proximaPagina"]) ? $context["proximaPagina"] : $this->getContext($context, "proximaPagina")) < (isset($context["pages"]) ? $context["pages"] : $this->getContext($context, "pages")))) {
                // line 44
                echo "                <a class=\"goToPage\" style=\"cursor:pointer;\" title=\"Próxima Página\" label=\"";
                echo twig_escape_filter($this->env, (isset($context["proximaPagina"]) ? $context["proximaPagina"] : $this->getContext($context, "proximaPagina")), "html", null, true);
                echo "\"><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/next.png"), "html", null, true);
                echo "\" /></a>
                <a class=\"goToPage\" style=\"cursor:pointer;\" title=\"Última Página\" label=\"";
                // line 45
                echo twig_escape_filter($this->env, (isset($context["pages"]) ? $context["pages"] : $this->getContext($context, "pages")), "html", null, true);
                echo "\"><img src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/images/last.png"), "html", null, true);
                echo "\" /></a>
            ";
            }
            // line 47
            echo "        </div>
    ";
        }
    }

    // line 51
    public function block_javascript($context, array $blocks = array())
    {
        // line 52
        echo "    <script type=\"text/javascript\">
        \$(document).ready(function (){
            \$(\".goPage\").click(function(ev){
                ev.preventDefault();
                \$.ajax({
                        url: '";
        // line 57
        echo $this->env->getExtension('routing')->getPath("dgp_pessoa_pesquisar");
        echo "?page='+\$(this).html(),
                        type: 'POST',
                        data: \$(\"#searchForm\").serialize(),
                        success: function (data)
                        {
                            \$(\"#ajaxList\").html(data);
                        }
                });
            });

            \$(\".goToPage\").click(function(ev){
                ev.preventDefault();
                \$.ajax({
                        url: '";
        // line 70
        echo $this->env->getExtension('routing')->getPath("dgp_pessoa_pesquisar");
        echo "?page='+\$(this).attr('label'),
                        type: 'POST',
                        data: \$(\"#searchForm\").serialize(),
                        success: function (data)
                        {
                            \$(\"#ajaxList\").html(data);
                        }
                });
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Pessoa:pessoas.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  198 => 70,  182 => 57,  175 => 52,  172 => 51,  166 => 47,  159 => 45,  152 => 44,  149 => 43,  143 => 42,  139 => 41,  134 => 40,  129 => 39,  124 => 38,  117 => 36,  112 => 35,  110 => 34,  107 => 33,  105 => 32,  101 => 30,  92 => 26,  86 => 24,  80 => 22,  74 => 20,  72 => 19,  68 => 18,  64 => 17,  60 => 16,  54 => 15,  51 => 14,  46 => 13,  33 => 2,  30 => 1,  26 => 51,  23 => 50,  21 => 1,);
    }
}

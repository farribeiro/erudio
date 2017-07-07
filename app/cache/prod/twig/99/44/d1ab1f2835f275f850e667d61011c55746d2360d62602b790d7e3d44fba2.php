<?php

/* DGPBundle:Vinculo:vinculosPorPessoa.html.twig */
class __TwigTemplate_9944d1ab1f2835f275f850e667d61011c55746d2360d62602b790d7e3d44fba2 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Pessoa:cadastroPessoa.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'tabContent' => array($this, 'block_tabContent'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Pessoa:cadastroPessoa.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        $context["activeTab"] = "5";
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "DGP > Pessoas > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " > Vínculos";
    }

    // line 7
    public function block_tabContent($context, array $blocks = array())
    {
        echo "\t
        <div class=\"row\" style=\"margin-top: 10px; cursor: pointer;\">
            <a href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_vinculo_cadastrar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"))), "html", null, true);
        echo "\" class=\"btn btn-primary\">
                <span class=\"glyphicon glyphicon-plus\"></span> Cadastrar
            </a>
        </div>
        <div class=\"row\">
            <table class=\"table table-striped table-hover tableRoles\">
                <thead>
                    <tr>
                        <th class=\"text-center\">Cargo</th>
                        <th>Tipo</th>
                        <th>C.H.</th>
                        <th>Processo Admissional</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 25
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 26
            echo "                        <tr>
                            <td>";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
            echo "</td>
                            <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "nome"), "html", null, true);
            echo "</td>
                            <td>";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
            echo "</td>
                            <td>
                                ";
            // line 31
            if ($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "inscricaoVinculacao")) {
                // line 32
                echo "                                    ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "inscricaoVinculacao"), "cargo"), "processo"), "nome"), "html", null, true);
                echo "
                                ";
            }
            // line 34
            echo "                            </td>
                            <td>
                                <a class=\"lnkAlterar\" href=\"";
            // line 36
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_vinculo_alterar", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"), "vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\">
                                    <span title=\"Alterar\" class=\"glyphicon glyphicon-edit\"></span>
                                </a> |
                                <span title=\"Alocações\" class=\"glyphicon glyphicon-home listAllocation iconAllocation";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "\" label=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "\" id=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_alocacao_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\" style=\"cursor: pointer; width: 16px;\" ></span>
                                <span title=\"Fechar Alocações\" class=\"glyphicon glyphicon-remove-circle closeShowItem changeAllocation";
            // line 40
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "\" style=\"display: none; cursor: pointer; width: 16px;\" ></span> |
                                ";
            // line 41
            if (($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "id") == twig_constant("SME\\DGPBundle\\Entity\\TipoVinculo::EFETIVO"))) {
                // line 42
                echo "                                    <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_promocaoHorizontal_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
                echo "\">
                                        <span title=\"Promoções\" class=\"glyphicon glyphicon-gift\"></span>
                                    </a> |
                                ";
            }
            // line 46
            echo "                                <a class=\"lnkDocumentos\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_vinculo_imprimirDocumentos", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\">
                                    <span title=\"Documentos\" class=\"glyphicon glyphicon-print\"></span>
                                </a> |
                                <a class=\"lnkExcluir\" href=\"";
            // line 49
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_pessoa_vinculo_excluir", array("pessoa" => $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "id"), "vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\">
                                    <span title=\"Excluir\" class=\"glyphicon glyphicon-remove\"></span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td style=\"background: white; display: none;\" colspan=4 class=\"ajaxAllocation";
            // line 55
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo " showAllocations\">

                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 60
            echo "                        <tr>
                            <td class=\"text-center\" colspan=\"5\">
                                <em>A pessoa não possui nenhum vínculo ativo com a Secretaria</em>
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        echo "                </tbody>
            </table>
        </div>
    
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModalDocumentos\" class=\"modal-dialog\" style=\"width: 80%;\"></div>
    </div>
";
    }

    // line 75
    public function block_javascript($context, array $blocks = array())
    {
        // line 76
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        
        \$(document).ready(function (){
           \$(\".lnkDocumentos\").click(function (ev){
               ev.preventDefault();
               \$.ajax({
                   url: \$(this).attr('href'),
                   type: 'GET',
                   success: function (data) {
                       \$('#divModalDocumentos').html(data);
                   }
               });
               \$('#modalDialog').modal(\"show\");
           });          
        });
        
        \$(\".lnkExcluir\").click(function(ev) {
            if(!confirm(\"Deseja realmente excluir o vínculo? (Use esta opção apenas para excluir cadastros incorretos)\")) {
                ev.preventDefault();
            }
        });

        \$(\".listAllocation\").click(function (){
            var selector = 'ajaxAllocation'+\$(this).attr('label');
            var item = \$(this).attr('label');
            var id = \$(this).attr('id');

            \$(\".closeShowItem\").hide();
            \$(\".listAllocation\").show();

            \$.ajax({
                url: id,
                type: 'GET',
                success: function (data)
                {
                    if (data !== \"error\") {
                        \$(\".showAllocations\").html('').hide();
                        \$(\".iconAllocation\"+item).hide();
                        \$(\".changeAllocation\"+item).show();
                        \$(\".\"+selector).html(data).show();
                    } else {
                        \$.bootstrapGrowl(\"O sistema não pôde mostrar as alocações, aguarde um pouco e tente novamente.\", { type: 'danger', delay: 3000 });
                    }
                }
            });
        });

        \$(\".closeShowItem\").click(function(){
            \$(\".showAllocations\").html('').hide();
            \$(\".listAllocation\").show();
            \$(\".closeShowItem\").hide();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Vinculo:vinculosPorPessoa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  177 => 76,  174 => 75,  163 => 66,  152 => 60,  142 => 55,  133 => 49,  126 => 46,  118 => 42,  116 => 41,  112 => 40,  104 => 39,  98 => 36,  94 => 34,  88 => 32,  86 => 31,  81 => 29,  77 => 28,  73 => 27,  70 => 26,  65 => 25,  46 => 9,  40 => 7,  32 => 5,  27 => 3,);
    }
}

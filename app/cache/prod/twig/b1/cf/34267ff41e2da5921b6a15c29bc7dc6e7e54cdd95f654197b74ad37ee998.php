<?php

/* DGPProcessoAnualBundle:ProcessoAnual:processosDisponiveis.html.twig */
class __TwigTemplate_b1cf34267ff41e2da5921b6a15c29bc7dc6e7e54cdd95f654197b74ad37ee998 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Servidor:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "DGPBundle:Servidor:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Servidor > Processos Anuais";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <h3>Minhas Inscrições</h3>
    ";
        // line 7
        if (twig_test_empty((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")))) {
            // line 8
            echo "         <p>Nenhuma inscrição realizada para este ano</p>
    ";
        } else {
            // line 10
            echo "        <table class=\"table table-hover tableRoles\">
            <thead>
                <tr>
                    <th>Cod.</th>
                    <th><i>Processo/Ano</i></th>
                    <th><i>Vínculo</i></th>
                    <th><i>Data de Cadastro</i></th>
                </tr>
            </thead>
            <tbody>
                ";
            // line 20
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["inscricoes"]) ? $context["inscricoes"] : $this->getContext($context, "inscricoes")));
            foreach ($context['_seq'] as $context["_key"] => $context["inscricao"]) {
                // line 21
                echo "                    <tr>
                        <td>";
                // line 22
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "html", null, true);
                echo "</td>
                        <td>";
                // line 23
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoAnual"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "ano"), "html", null, true);
                echo "</td>
                        <td>";
                // line 24
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vinculoServidor"), "cargo"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vinculoServidor"), "tipoVinculo"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vinculoServidor"), "cargaHoraria"), "html", null, true);
                echo "h</td>
                        <td>";
                // line 25
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataCadastro"), "d/m/Y"), "html", null, true);
                echo "</td>
                    </tr>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['inscricao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 28
            echo "            </tbody>
        </table>
        <hr />
    ";
        }
        // line 32
        echo "    
    <h3>Processos Disponíveis</h3>
    ";
        // line 34
        if (twig_test_empty((isset($context["processos"]) ? $context["processos"] : $this->getContext($context, "processos")))) {
            // line 35
            echo "        <p>Não há nenhum processo em aberto no qual não esteja inscrito.</p>
    ";
        } else {
            // line 37
            echo "        <label for=\"vinculoInscricao\">Vínculo Efetivo:</label>
        <select id=\"vinculoInscricao\" style=\"margin-bottom: 2%;\" class=\"form-control\">
            <option>Selecione uma opção</option>
            ";
            // line 40
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
            foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
                // line 41
                echo "                <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
                echo "h</option>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            echo "        </select>

        <div> 
            <table class=\"table table-striped table-hover tableRoles\" style=\"width: 50%;\">
                <thead>
                    <tr>
                        <th>Processo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    ";
            // line 54
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["processos"]) ? $context["processos"] : $this->getContext($context, "processos")));
            foreach ($context['_seq'] as $context["_key"] => $context["processo"]) {
                // line 55
                echo "                        <tr>
                            <td>";
                // line 56
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["processo"]) ? $context["processo"] : $this->getContext($context, "processo")), "nome"), "html", null, true);
                echo "</td>
                            <td>
                                <a class=\"btnInscrever btn btn-primary\" style=\"float: right\"
                                   href=\"";
                // line 59
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_processoAnual_cadastrarInscricao", array("processo" => $this->getAttribute((isset($context["processo"]) ? $context["processo"] : $this->getContext($context, "processo")), "id"))), "html", null, true);
                echo "\">
                                    Inscrever-se
                                </a>
                            </td>
                        </tr>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['processo'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 65
            echo "                </tbody>
            </table>
        </div>
    ";
        }
    }

    // line 71
    public function block_javascript($context, array $blocks = array())
    {
        // line 72
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\".btnInscrever\").click(function(ev){
            var url = \$(this).attr('href');
            var val = \$('#vinculoInscricao').val();

            if(val > 0) {
                \$.ajax({
                    'url': url,
                    'type': 'POST',
                    'data': { vinculo: val },
                    'success': function (data){
                       location.reload(true);
                    }
                });
            } else {
                alert(\"Selecione um de seus vínculos acima para se inscrever no processo\");
            }
            ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPProcessoAnualBundle:ProcessoAnual:processosDisponiveis.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  183 => 72,  180 => 71,  172 => 65,  160 => 59,  154 => 56,  151 => 55,  147 => 54,  134 => 43,  119 => 41,  115 => 40,  110 => 37,  106 => 35,  104 => 34,  100 => 32,  94 => 28,  85 => 25,  77 => 24,  71 => 23,  67 => 22,  64 => 21,  60 => 20,  48 => 10,  44 => 8,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

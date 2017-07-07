<?php

/* DGPFormacaoBundle:Matricula:matriculasServidor.html.twig */
class __TwigTemplate_eca94819ffe830444c179cc4889e2418b6d6ec5c04e49fa47530fc8147541674 extends Twig_Template
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
        echo "Servidor > Formação Continuada";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Inscrições Abertas</div>
        <div class=\"panel-body\">
             ";
        // line 9
        if (twig_test_empty((isset($context["formacoes"]) ? $context["formacoes"] : $this->getContext($context, "formacoes")))) {
            // line 10
            echo "                Não há formações com inscrições em aberto no momento
            ";
        } else {
            // line 12
            echo "                <table class=\"table table-hover\">
                    <thead>
                        <tr>
                            <th class=\"text-center\">Formação</th>
                            <th class=\"text-center\">Carga Horária</th>
                            <th class=\"text-center\">Inscrições</th>
                            <th class=\"text-center\">Vagas</th>
                            <th class=\"text-center\">Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
            // line 23
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["formacoes"]) ? $context["formacoes"] : $this->getContext($context, "formacoes")));
            foreach ($context['_seq'] as $context["_key"] => $context["formacao"]) {
                // line 24
                echo "                            <tr>
                                <td>";
                // line 25
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "nome"), "html", null, true);
                echo "</td>
                                <td class=\"text-center\">";
                // line 26
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "cargaHoraria"), "html", null, true);
                echo "</td>
                                <td class=\"text-center\">";
                // line 27
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "dataInicioInscricao"), "d/m/Y"), "html", null, true);
                echo " a ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "dataTerminoInscricao"), "d/m/Y"), "html", null, true);
                echo "</td>
                                <td class=\"text-center\">
                                    ";
                // line 29
                if (($this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "limiteVagas") > 0)) {
                    echo " ";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "vagasDisponiveis"), "html", null, true);
                    echo " ";
                } else {
                    echo "Sem limite";
                }
                echo "    
                                </td>
                                <td class=\"text-center\">
                                    <a class=\"detalhesFormacao\" href=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_publico_getFormacao", array("id" => $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "id"))), "html", null, true);
                echo "\">Informações</a> |
                                    <a href=\"";
                // line 33
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_formacao_adicionarMatricula", array("formacao" => $this->getAttribute((isset($context["formacao"]) ? $context["formacao"] : $this->getContext($context, "formacao")), "id"), "pessoa" => $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "id"))), "html", null, true);
                echo "\">Inscrever-se</a>
                                </td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['formacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "                    </tbody>
                </table>
            ";
        }
        // line 40
        echo "        </div>
    </div>
    
    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Minhas Formações</div>
        <div class=\"panel-body\">
            ";
        // line 46
        if (twig_test_empty((isset($context["formacoesPessoa"]) ? $context["formacoesPessoa"] : $this->getContext($context, "formacoesPessoa")))) {
            // line 47
            echo "                Você não está participando nem concluiu uma formação
            ";
        } else {
            // line 49
            echo "                <table class=\"table table-hover\">
                    <thead>
                        <tr>
                            <th class=\"text-center\">Matrícula</th>
                            <th class=\"text-center\">Nome</th>
                            <th class=\"text-center\">Carga Horária</th>
                            <th class=\"text-center\">Período</th>
                            <th class=\"text-center\">Status</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
            // line 61
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["formacoesPessoa"]) ? $context["formacoesPessoa"] : $this->getContext($context, "formacoesPessoa")));
            foreach ($context['_seq'] as $context["_key"] => $context["matricula"]) {
                // line 62
                echo "                            <tr>
                                <td class=\"text-center\">";
                // line 63
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "id"), "html", null, true);
                echo "</td>
                                <td><a class=\"detalhesFormacao\" href=\"";
                // line 64
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_publico_getFormacao", array("id" => $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "nome"), "html", null, true);
                echo "</a></td>
                                <td class=\"text-center\">";
                // line 65
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "cargaHoraria"), "html", null, true);
                echo "</td>
                                <td class=\"text-center\">";
                // line 66
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "dataInicioFormacao"), "d/m/Y"), "html", null, true);
                echo " a ";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "dataTerminoFormacao"), "d/m/Y"), "html", null, true);
                echo "</td>
                                <td class=\"text-center\">";
                // line 67
                echo (($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "aprovado")) ? ("Concluído") : ("Inscrito"));
                echo "</td>
                                <td>
                                    ";
                // line 69
                if ($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "aprovado")) {
                    // line 70
                    echo "                                        <a target=\"_blank\" href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_imprimirCertificado", array("matricula" => $this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "id"))), "html", null, true);
                    echo "\">Certificado</a>
                                    ";
                } else {
                    // line 72
                    echo "                                        <a href=\"";
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_formacao_cancelarMatricula", array("formacao" => $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "id"), "matricula" => $this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "id"))), "html", null, true);
                    echo "\">Cancelar</a>
                                    ";
                }
                // line 74
                echo "                                </td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['matricula'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 77
            echo "                    </tbody>
                </table>
            ";
        }
        // line 80
        echo "        </div>
    </div>
   
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 80%;\"></div>
    </div>
";
    }

    // line 88
    public function block_javascript($context, array $blocks = array())
    {
        // line 89
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\" src=\"";
        // line 90
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/bootstrap/assets/js/less.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 91
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/jquery.mousewheel-3.0.6.pack.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\" src=\"";
        // line 92
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/fancybox/jquery.fancybox.js"), "html", null, true);
        echo "\"></script>
    <script type=\"text/javascript\">          
        \$('document').ready(function (){
            \$(\".detalhesFormacao\").click(function(ev){
                ev.preventDefault();

                \$.ajax({
                    type: \"GET\",
                    url: \$(this).attr(\"href\"),
                    success: function(retorno) {
                        \$(\"#divModal\").html(retorno);
                    }
                });
                \$('#modalDialog').modal(\"show\");
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPFormacaoBundle:Matricula:matriculasServidor.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  229 => 92,  225 => 91,  221 => 90,  216 => 89,  213 => 88,  203 => 80,  198 => 77,  190 => 74,  184 => 72,  178 => 70,  176 => 69,  171 => 67,  165 => 66,  161 => 65,  155 => 64,  151 => 63,  148 => 62,  144 => 61,  130 => 49,  126 => 47,  124 => 46,  116 => 40,  111 => 37,  101 => 33,  97 => 32,  85 => 29,  78 => 27,  74 => 26,  70 => 25,  67 => 24,  63 => 23,  50 => 12,  46 => 10,  44 => 9,  39 => 6,  36 => 5,  30 => 3,);
    }
}

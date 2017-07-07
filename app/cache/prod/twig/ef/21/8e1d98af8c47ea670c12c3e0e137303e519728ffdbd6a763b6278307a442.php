<?php

/* PublicBundle:FormacaoExterna:buscaCertificados.html.twig */
class __TwigTemplate_ef218e1d98af8c47ea670c12c3e0e137303e519728ffdbd6a763b6278307a442 extends Twig_Template
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
        if (((isset($context["ajax"]) ? $context["ajax"] : $this->getContext($context, "ajax")) == false)) {
            // line 2
            echo "    <div class=\"container paddingTop certifyResults\">  
";
        }
        // line 4
        echo "    <div class=\"row\">
       <div class=\"col-lg-6\" style=\"text-align: justify;\">
            Digite no campo ao lado seu CPF ou número de matrícula de seu curso, para imprimir os certificados de cursos em que foi aprovado.
       </div>
       <div class=\"col-lg-6\" style=\"text-align: justify;\">
            <form id=\"searchForm\" class=\"form-signin\" action=\"";
        // line 9
        echo $this->env->getExtension('routing')->getPath("formacao_externa_consulta");
        echo "\">
                <div class=\"form-group\">
                    ";
        // line 11
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "_token"), 'widget');
        echo "
                    ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "CPFMatricula"), 'row', array("attr" => array("class" => "form-control")));
        echo "
                </div>
                <div class=\"form-group\">
                    <button class=\"btn btn-lg btn-primary btn-block searchCertify\" type=\"submit\">Buscar</button>
                </div>
            </form>
       </div>
        <div class=\"col-lg-12\" style=\"text-align: justify; float: left;\">                          
            ";
        // line 20
        if (twig_test_empty((isset($context["matriculas"]) ? $context["matriculas"] : $this->getContext($context, "matriculas")))) {
            // line 21
            echo "                <p class=\"text-center text-error\">";
            echo twig_escape_filter($this->env, (isset($context["errorFormacoes"]) ? $context["errorFormacoes"] : $this->getContext($context, "errorFormacoes")), "html", null, true);
            echo "</p>
            ";
        } else {
            // line 23
            echo "                <div id=\"resultsCertify\" style=\" float: left; width: 100%;\">
                    <table class=\"table table-stripped table-hover\">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nome</th>
                                <th>Formação</th>
                                <th>Status</th>
                                <th>Certificado</th>
                            </tr>
                        </thead>
                        <tbody class=\"ajax-itens\">
                            ";
            // line 35
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["matriculas"]) ? $context["matriculas"] : $this->getContext($context, "matriculas")));
            foreach ($context['_seq'] as $context["_key"] => $context["matricula"]) {
                // line 36
                echo "                                <tr>
                                    <td>";
                // line 37
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "id"), "html", null, true);
                echo "</td>
                                    <td>";
                // line 38
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "pessoa"), "nome"), "html", null, true);
                echo "</td>
                                    <td>";
                // line 39
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "formacao"), "nomeCertificado"), "html", null, true);
                echo "</td>
                                    ";
                // line 40
                if ($this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "aprovado")) {
                    // line 41
                    echo "                                        <td>Aprovado</td>
                                        <td class=\"text-center\">
                                            <a target=\"_blank\" href=\"";
                    // line 43
                    echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("formacao_externa_imprimir_certificado", array("matricula" => $this->getAttribute((isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")), "id"))), "html", null, true);
                    echo "\">
                                                Verificar / Imprimir
                                            </a>
                                        </td>
                                    ";
                } else {
                    // line 48
                    echo "                                        <td>Reprovado</td>
                                        <td></td>
                                    ";
                }
                // line 51
                echo "                                </tr>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['matricula'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "                        </tbody>
                    </table>
                </div>
            ";
        }
        // line 57
        echo "        </div>
    </div>
";
        // line 59
        if (((isset($context["ajax"]) ? $context["ajax"] : $this->getContext($context, "ajax")) == false)) {
            // line 60
            echo "    </div>    
";
        }
    }

    public function getTemplateName()
    {
        return "PublicBundle:FormacaoExterna:buscaCertificados.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 60,  129 => 59,  125 => 57,  119 => 53,  112 => 51,  107 => 48,  99 => 43,  95 => 41,  93 => 40,  89 => 39,  85 => 38,  81 => 37,  78 => 36,  74 => 35,  60 => 23,  54 => 21,  52 => 20,  41 => 12,  37 => 11,  32 => 9,  25 => 4,  21 => 2,  19 => 1,);
    }
}

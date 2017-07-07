<?php

/* DGPBundle:Servidor:index.html.twig */
class __TwigTemplate_ff5b28003ead1a437c397599e824b368a4b5ac013bf9b55942e9025e67da7087 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'page' => array($this, 'block_page'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest")) ? ("::templateAjax.html.twig") : ("::template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Servidor > Meus Cargos";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        if ((!$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest"))) {
            // line 7
            echo "        <div class=\"row\">
            <menu class=\"col-lg-2\">
                <div class=\"list-group\"> 
                    <a class=\"list-group-item\" href=\"";
            // line 10
            echo $this->env->getExtension('routing')->getPath("dgp_servidor_listarVinculos");
            echo "\">Meus Cargos</a>
                    <a class=\"list-group-item\" href=\"";
            // line 11
            echo $this->env->getExtension('routing')->getPath("protocolo_servidor_listar", array("categoria" => 1));
            echo "\">Requerimentos</a>
                    <a class=\"list-group-item\" href=\"";
            // line 12
            echo $this->env->getExtension('routing')->getPath("dgp_servidor_listarFormacoes");
            echo "\">Formações</a>
                    <a class=\"list-group-item\" href=\"";
            // line 13
            echo $this->env->getExtension('routing')->getPath("dgp_servidor_permuta_listarIntencoes");
            echo "\">Intenção de Permuta</a>
                    <a class=\"list-group-item\" href=\"";
            // line 14
            echo $this->env->getExtension('routing')->getPath("dgp_servidor_processoAnual_listarDisponiveis");
            echo "\">Processos Anuais</a>
                </div>
            </menu>
            <div id=\"page\" class=\"col-lg-10\" style=\"margin-top: 1em;\">
                ";
            // line 18
            $this->displayBlock('page', $context, $blocks);
            // line 54
            echo "            </div>
        </div>
    ";
        } else {
            // line 57
            echo "        ";
            $this->displayBlock("page", $context, $blocks);
            echo "
    ";
        }
    }

    // line 18
    public function block_page($context, array $blocks = array())
    {
        // line 19
        echo "                        <table class=\"table table-hover\">
                            <thead>
                                <tr>
                                    <th class=\"text-center\">Cód.</th>
                                    <th class=\"text-center\">Cargo</th>
                                    <th class=\"text-center\">Vínculo</th>
                                    <th class=\"text-center\">C.H.</th>
                                    <th class=\"text-center\">Data de Início</th>
                                    <th class=\"text-center\">Requerimentos Específicos</th>
                                </tr>
                            </thead>
                            <tbody>
                                ";
        // line 31
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 32
            echo "                                    <tr>
                                        <td class=\"text-center\">";
            // line 33
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "</td>
                                        <td class=\"text-center\">";
            // line 34
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
            echo "</td>
                                        <td class=\"text-center\">";
            // line 35
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "nome"), "html", null, true);
            echo "</td>
                                        <td class=\"text-center\">";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
            echo "</td>
                                        <td class=\"text-center\">";
            // line 37
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "dataInicio"), "d/m/Y"), "html", null, true);
            echo "</td>
                                        <td class=\"text-center\">
                                            <a href=\"";
            // line 39
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("dgp_servidor_consultarVinculo", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
            echo "\">Informações</a>
                                            ";
            // line 40
            if (($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "id") == twig_constant("SME\\DGPBundle\\Entity\\TipoVinculo::EFETIVO"))) {
                // line 41
                echo "                                                <br /> <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_promocao_listar", array("vinculo" => $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"))), "html", null, true);
                echo "\">Requerimento de Promoção</a>
                                            ";
            }
            // line 43
            echo "                                        </td>
                                    </tr>
                                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 46
            echo "                                    <tr><td colspan=\"5\">Você não possui vínculos ativos</td></tr>
                                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 48
        echo "                            </tbody>
                        </table>
                        <p>
                            <em>* Caso seu cargo não conste no quadro, entrar em contato com o Departamento de Gestão de Pessoas - DGP para realizar o cadastro.</em>
                        </p>
                ";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Servidor:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  155 => 48,  148 => 46,  141 => 43,  135 => 41,  133 => 40,  129 => 39,  124 => 37,  120 => 36,  116 => 35,  112 => 34,  108 => 33,  105 => 32,  100 => 31,  86 => 19,  83 => 18,  75 => 57,  70 => 54,  68 => 18,  61 => 14,  57 => 13,  53 => 12,  49 => 11,  45 => 10,  40 => 7,  37 => 6,  34 => 5,  28 => 3,);
    }
}

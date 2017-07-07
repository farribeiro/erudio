<?php

/* DGPBundle:Servidor:consultaVinculo.html.twig */
class __TwigTemplate_e153a435e62b011dc5e653ac5c68ac75bad39b4ee329b56fe336b72c09575f20 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("DGPBundle:Servidor:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
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
        echo "Servidor > Meus Cargos > Cod. ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div>
        <p>
            <strong>Servidor:</strong> ";
        // line 8
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "servidor"), "nome"), "html", null, true);
        echo " 
        </p>
        <p>
            <strong>Cargo de Origem:</strong> ";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargoOrigem"), "nome"), "html", null, true);
        echo " 
        </p>
        <p>
            <strong>Cargo Atual:</strong> ";
        // line 14
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
        echo " 
        </p>
        <p>
            <strong>Categoria:</strong> ";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "nome"), "html", null, true);
        echo " 
        </p>
        <p>
            <strong>Carga Horária:</strong> ";
        // line 20
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
        echo " 
        </p>
        <p>
            <strong>Data de Nomeação / Início de Contrato:</strong> ";
        // line 23
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "dataInicio"), "d/m/Y"), "html", null, true);
        echo "
        </p>
        ";
        // line 25
        if (($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "tipoVinculo"), "id") == twig_constant("SME\\DGPBundle\\Entity\\TipoVinculo::ACT"))) {
            // line 26
            echo "            <p>
                <strong>Término de Contrato:</strong> ";
            // line 27
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "dataTermino"), "d/m/Y"), "html", null, true);
            echo "
            </p>
        ";
        } else {
            // line 30
            echo "            <p>
                <strong>Data de Posse:</strong> ";
            // line 31
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "dataPosse"), "d/m/Y"), "html", null, true);
            echo "
            </p>
        ";
        }
        // line 34
        echo "        ";
        if ($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "inscricaoVinculacao")) {
            // line 35
            echo "            <p>
                <strong>Processo de Admissão:</strong> ";
            // line 36
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "inscricaoVinculacao"), "processo"), "nome"), "html", null, true);
            echo "
            </p>
        ";
        }
        // line 39
        echo "    </div>
    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Alocações</div>
        <div class=\"panel-body\">
            <table class=\"table table-hover\">
                <tr>
                    <th>Local</th>
                    <th>Carga horária</th>
                    <th>Turno</th>
                    <th>Função</th>
                </tr>
                ";
        // line 50
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "alocacoes"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["alocacao"]) {
            // line 51
            echo "                    <tr>
                        <td>";
            // line 52
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "localTrabalho"), "nome"), "html", null, true);
            echo "</td>
                        <td>";
            // line 53
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "cargaHoraria"), "html", null, true);
            echo "</td>
                        <td>";
            // line 54
            echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "periodo")) ? ($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "periodo"), "nome")) : ("Não informado")), "html", null, true);
            echo "</td>
                        <td>";
            // line 55
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
            echo "</td>
                    </tr>
                ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 58
            echo "                    <tr> <td class=\"text-center\" colspan=\"4\">Você não está alocado em nenhuma unidade</td> </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alocacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 60
        echo "            </table>
            <p>
                <em>* Caso seus locais de trabalho estejam incorretos, entre em contato com o gestor de sua unidade.</em>
            </p>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:Servidor:consultaVinculo.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 60,  151 => 58,  143 => 55,  139 => 54,  135 => 53,  131 => 52,  128 => 51,  123 => 50,  110 => 39,  104 => 36,  101 => 35,  98 => 34,  92 => 31,  89 => 30,  83 => 27,  80 => 26,  78 => 25,  73 => 23,  67 => 20,  61 => 17,  55 => 14,  49 => 11,  43 => 8,  39 => 6,  36 => 5,  29 => 3,);
    }
}

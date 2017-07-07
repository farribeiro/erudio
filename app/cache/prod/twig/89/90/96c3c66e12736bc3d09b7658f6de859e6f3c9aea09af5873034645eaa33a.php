<?php

/* ProtocoloBundle:Forms:AmpliacaoCargaHoraria.html.twig */
class __TwigTemplate_899096c3c66e12736bc3d09b7658f6de859e6f3c9aea09af5873034645eaa33a extends Twig_Template
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
        echo "Requerimento de Ampliação de Carga Horária (ACTs)";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("protocolo_servidor_incluirSolicitacao", array("solicitacao" => $this->getAttribute((isset($context["solicitacao"]) ? $context["solicitacao"] : $this->getContext($context, "solicitacao")), "id"))), "html", null, true);
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"vinculo\">Vínculo:</label>
                <select name=\"vinculo\" class=\"form-control\">
                    ";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 12
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargo"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "cargaHoraria"), "html", null, true);
            echo " horas</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"alocacao\">Local de trabalho:</label>
                <select name=\"alocacao\" class=\"form-control\">
                    <option>Não informado</option>
                    ";
        // line 20
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["vinculos"]) ? $context["vinculos"] : $this->getContext($context, "vinculos")));
        foreach ($context['_seq'] as $context["_key"] => $context["vinculo"]) {
            // line 21
            echo "                        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["vinculo"]) ? $context["vinculo"] : $this->getContext($context, "vinculo")), "alocacoes"));
            foreach ($context['_seq'] as $context["_key"] => $context["alocacao"]) {
                // line 22
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "localTrabalho"), "pessoaJuridica"), "nome"), "html", null, true);
                echo "</option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alocacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 24
            echo "                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['vinculo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"carga_horaria_disponivel\">Quantas horas deseja ampliar:</label>
                <select name=\"carga_horaria_disponivel\" class=\"form-control\"  required >
                    <option value=\"10\">10</option>
                    <option value=\"20\">20</option>
                    <option value=\"30\">30</option>
                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"periodo_disponivel\">Período disponível:</label>
                <select name=\"periodo_disponivel\" class=\"form-control\" required >
                    <option value=\"Matutino\">Matutino</option>
                    <option value=\"Vespertino\">Vespertino</option>
                    <option value=\"Matutino/Vespertino\">Matutino/Vespertino</option>
                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <button id=\"btnIncluir\" type=\"submit\" class=\"btn btn-primary\">Incluir</button>
            </div>
        </div>
    </form>
";
    }

    public function getTemplateName()
    {
        return "ProtocoloBundle:Forms:AmpliacaoCargaHoraria.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  100 => 25,  94 => 24,  81 => 22,  76 => 21,  72 => 20,  64 => 14,  51 => 12,  47 => 11,  38 => 6,  35 => 5,  29 => 3,);
    }
}

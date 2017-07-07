<?php

/* FilaUnicaBundle:Inscricao:formPesquisa.html.twig */
class __TwigTemplate_225bb37a5b0075a070536d5385e5f40a940d351724ae34dabeb86d527feda942 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("FilaUnicaBundle:Index:index.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "FilaUnicaBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo " Fila Única > Inscrição > Pesquisa ";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form method=\"POST\" action=\"";
        echo $this->env->getExtension('routing')->getPath("fu_inscricao_pesquisar");
        echo "\" id=\"formPesquisaInscricao\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"zoneamento\">Zoneamento:</label>
                <select id=\"zoneamento\" name=\"zoneamento\" class=\"form-control\">
                    <option>Todos</option>
                    ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["zoneamentos"]) ? $context["zoneamentos"] : $this->getContext($context, "zoneamentos")));
        foreach ($context['_seq'] as $context["_key"] => $context["zoneamento"]) {
            // line 13
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["zoneamento"]) ? $context["zoneamento"] : $this->getContext($context, "zoneamento")), "descricao"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['zoneamento'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 15
        echo "                </select>
            </div>
            <div class=\"col-lg-3\">
                <label for=\"anoEscolar\">Turma:</label>
                <select id=\"anoEscolar\" name=\"anoEscolar\" class=\"form-control\">
                    <option>Todos</option>
                    ";
        // line 21
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["anosEscolares"]) ? $context["anosEscolares"] : $this->getContext($context, "anosEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["ano"]) {
            // line 22
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["ano"]) ? $context["ano"] : $this->getContext($context, "ano")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['ano'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 24
        echo "                </select>
            </div>
            <div class=\"col-lg-3\">
                <label for=\"tipoInscricao\">Tipo de Inscrição:</label>
                <select id=\"tipoInscricao\" name=\"tipoInscricao\" class=\"form-control\">
                    ";
        // line 29
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["tiposInscricao"]) ? $context["tiposInscricao"] : $this->getContext($context, "tiposInscricao")));
        foreach ($context['_seq'] as $context["_key"] => $context["tipo"]) {
            // line 30
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tipo"]) ? $context["tipo"] : $this->getContext($context, "tipo")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tipo"]) ? $context["tipo"] : $this->getContext($context, "tipo")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tipo'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-3\">
                <label for=\"nome\">Nome:</label>
                <input type=\"text\" id=\"nome\" name=\"nome\" class=\"form-control\">
            </div>
            <div class=\"col-lg-3\">
                <label for=\"protocolo\">Protocolo:</label>
                <input type=\"text\" id=\"protocolo\" name=\"protocolo\" class=\"form-control\">
            </div>
            <div class=\"col-lg-3\">
                <label for=\"status\">Status:</label>
                <select id=\"status\" name=\"status\" class=\"form-control\">
                    <option>Todos</option>
                    ";
        // line 48
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["status"]) ? $context["status"] : $this->getContext($context, "status")));
        foreach ($context['_seq'] as $context["_key"] => $context["st"]) {
            // line 49
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["st"]) ? $context["st"] : $this->getContext($context, "st")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["st"]) ? $context["st"] : $this->getContext($context, "st")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['st'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 51
        echo "                </select>
            </div>
            <div class=\"col-lg-3\">
                <label for=\"processoJudicial\">Processo Judicial:</label>
                <select id=\"processoJudicial\" name=\"processoJudicial\" class=\"form-control\">
                    <option>Sim / Não</option>
                    <option value=\"0\">Não</option>
                    <option value=\"1\">Sim</option>
                </select>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"unidadeOrigem\">Unidade de Origem (se já matriculado na rede):</label>
                <select id=\"unidadeOrigem\" name=\"unidadeOrigem\" class=\"form-control\">
                    <option>Todas / Nenhuma</option>
                    ";
        // line 67
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidadesEscolares"]) ? $context["unidadesEscolares"] : $this->getContext($context, "unidadesEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 68
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 70
        echo "                </select>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"unidadeVagaOfertada\">Vaga Ofertada:</label>
                <select id=\"unidadeVagaOfertada\" name=\"unidadeVagaOfertada\" class=\"form-control\">
                    <option>Nenhuma</option>
                    ";
        // line 76
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidadesEscolares"]) ? $context["unidadesEscolares"] : $this->getContext($context, "unidadesEscolares")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 77
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 79
        echo "                </select>
            </div>
        </div>
        <div style=\"margin-top: 0.5em;\">
            <strong>Período da última atualização:</strong>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-2\">
                <input type=\"text\" id=\"ultimaModificacaoInicio\" name=\"ultimaModificacaoInicio\" class=\"form-control datepickerSME\">
            </div>
            <div class=\"col-lg-1 text-center\">
                <strong>até</strong>
            </div>
            <div class=\"col-lg-2\">
                <input type=\"text\" id=\"ultimaModificacaoTermino\" name=\"ultimaModificacaoTermino\" class=\"form-control datepickerSME\">
            </div>
            <div class=\"col-lg-3\">
                <button id=\"btnBuscar\" type=\"button\" class=\"btn btn-primary\">Buscar</button>
            </div>
        </div>
    </form>

    <div id=\"divListaInscricoes\"></div>
";
    }

    // line 104
    public function block_javascript($context, array $blocks = array())
    {
        // line 105
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\"#btnBuscar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 110
        echo $this->env->getExtension('routing')->getPath("fu_inscricao_pesquisar");
        echo "\",
                data: \$(\"#formPesquisaInscricao\").serialize(),
                dataType: 'html',
                success: function(retorno){
                    \$(\"#divListaInscricoes\").html(retorno);  
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:formPesquisa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  239 => 110,  230 => 105,  227 => 104,  200 => 79,  189 => 77,  185 => 76,  177 => 70,  166 => 68,  162 => 67,  144 => 51,  133 => 49,  129 => 48,  111 => 32,  100 => 30,  96 => 29,  89 => 24,  78 => 22,  74 => 21,  66 => 15,  53 => 13,  49 => 12,  39 => 6,  36 => 5,  30 => 3,);
    }
}

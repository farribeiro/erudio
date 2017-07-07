<?php

/* FilaUnicaBundle:Inscricao:modalHistoricoInscricao.html.twig */
class __TwigTemplate_a2c71c4c7e40c0f32d74d7c861247c66b67c1f8017f4809a3943df1f4ad89a9f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::templateModal.html.twig");

        $this->blocks = array(
            'modalTitle' => array($this, 'block_modalTitle'),
            'modalBody' => array($this, 'block_modalBody'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::templateModal.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_modalTitle($context, array $blocks = array())
    {
        echo " Inscrição ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"), "html", null, true);
        echo " > Histórico ";
    }

    // line 5
    public function block_modalBody($context, array $blocks = array())
    {
        echo "  
    <table class=\"table table-striped table-hover\">
        <tr>
            <th>Evento</th>
            <th>Data</th>
            <th>Responsável</th>
            <th>Opções</th>
        </tr>
        ";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "historico"));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["evento"]) {
            // line 14
            echo "            <tr>
                <td>";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["evento"]) ? $context["evento"] : $this->getContext($context, "evento")), "descricao"), "html", null, true);
            echo "</td>
                <td>";
            // line 16
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["evento"]) ? $context["evento"] : $this->getContext($context, "evento")), "dataOcorrencia"), "d/m/Y"), "html", null, true);
            echo "</td>
                <td>";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["evento"]) ? $context["evento"] : $this->getContext($context, "evento")), "pessoaCadastrou"), "nome"), "html", null, true);
            echo "</td>
                <td>
                    <a class=\"ajaxAction\" href=\"";
            // line 19
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_excluirEvento", array("evento" => $this->getAttribute((isset($context["evento"]) ? $context["evento"] : $this->getContext($context, "evento")), "id"))), "html", null, true);
            echo "\">Excluir</a>
                </td>
            </tr>
        ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 23
            echo "            <tr> 
                <td class=\"text-center\" colspan=\"3\"> <em>Nenhum evento cadastrado</em> </td> 
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['evento'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "    </table>
    <form id=\"formEvento\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
               <label for=\"dataOcorrencia\">Data de Ocorrência:</label>
               <input id=\"dataOcorrencia\" name=\"dataOcorrencia\" class=\"form-control datepickerSME\" required />
            </div>
        </div>
        <div class=\"row\"> 
            <div class=\"col-lg-12\">
               <label for=\"descricao\">Descrição:</label>
               <textarea id=\"descricao\" name=\"descricao\" class=\"form-control form-control-static\" rows=\"3\" required ></textarea>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
               <br />
               <button id=\"btnIncluir\" type=\"button\" class=\"btn btn-primary\">Incluir</button>
            </div>
        </div>
    </form>
";
    }

    // line 50
    public function block_javascript($context, array $blocks = array())
    {
        // line 51
        echo "    <script type=\"text/javascript\">
        \$(\"#btnIncluir\").click(function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 55
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_incluirEvento", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
        echo "\",
                data: \$(\"#formEvento\").serialize(),
                success: function(retorno) {
                    \$(\"#divModal\").html(retorno);
                }
            });
        });

        \$(\".ajaxAction\").click(function(ev) {
                \$.ajax({
                    type: \"POST\",
                    url: \$(this).attr(\"href\"),
                    success: function(retorno) {
                        \$(\"#divModal\").html(retorno);
                    }
                });
                ev.preventDefault();
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:modalHistoricoInscricao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  123 => 55,  117 => 51,  114 => 50,  89 => 27,  80 => 23,  71 => 19,  66 => 17,  62 => 16,  58 => 15,  55 => 14,  50 => 13,  38 => 5,  30 => 3,);
    }
}

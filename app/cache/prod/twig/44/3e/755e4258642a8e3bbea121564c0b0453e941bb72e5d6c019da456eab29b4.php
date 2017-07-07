<?php

/* FilaUnicaBundle:Vaga:modalFormGerencia.html.twig */
class __TwigTemplate_443e755e4258642a8e3bbea121564c0b0453e941bb72e5d6c019da456eab29b4 extends Twig_Template
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
        echo "<div class=\"modal-content\">
    <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
        <h4 class=\"modal-title\">Gerência de Vaga</h4>
    </div>
    <div class=\"modal-body\">
        <p>
            <strong>Unidade:</strong> 
            ";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidadeEscolar"), "nome"), "html", null, true);
        echo " | ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidadeEscolar"), "zoneamento"), "nome"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "unidadeEscolar"), "zoneamento"), "descricao"), "html", null, true);
        echo "
        </p>
        <p>
            <strong>Ano Escolar:</strong> 
            ";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "anoEscolar"), "nome"), "html", null, true);
        echo "
        </p>
        <p>
            <strong>Período:</strong> 
            ";
        // line 17
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "periodoDia"), "nome"), "html", null, true);
        echo "
        </p>

        ";
        // line 20
        if ($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada")) {
            // line 21
            echo "            <div style=\"border: #000 solid 1px; padding: 10px;\">
                <p>
                    <strong> Inscrição em chamada:</strong>
                    ";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "protocolo"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "inscricaoEmChamada"), "crianca"), "nome"), "html", null, true);
            echo "
                </p>
                <form>
                    <div class=\"row\">
                        <div class=\"col-lg-4\">
                            <button id=\"btnMatricula\" type=\"button\" class=\"btn btn-block btn-success\">Confirmação de matrícula</button>
                        </div>
                        <div class=\"col-lg-4\">
                            <button id=\"btnDesistencia\" type=\"button\" class=\"btn btn-block btn-warning\">Desistência da vaga</button>
                        </div>
                        <div class=\"col-lg-4\">
                            <button id=\"btnEliminacao\" type=\"button\" class=\"btn btn-block btn-danger\">Eliminação</button>
                        </div>
                    </div>
                    ";
            // line 38
            if ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
                // line 39
                echo "                        <div class=\"row\">
                            <div class=\"col-lg-4\"></div>
                            <div class=\"col-lg-4\">
                                <button id=\"btnReverter\" type=\"button\" class=\"btn btn-block btn-primary\">Retornar para fila</button>
                            </div>
                            <div class=\"col-lg-4\">
                                <button id=\"btnMovimentacao\" type=\"button\" class=\"btn btn-block btn-primary\">Mover para outra vaga</button>
                            </div>
                        </div>
                        <div class=\"row\">
                            <div class=\"col-lg-10\">
                                <p>
                                    <strong>Vaga cadastrada por: </strong>
                                    ";
                // line 52
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "pessoaCadastro"), "nome"), "html", null, true);
                echo "
                                </p>
                                <p>
                                    <strong>Chamada efetuada por: </strong>
                                    ";
                // line 56
                echo twig_escape_filter($this->env, (($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "pessoaModificacao")) ? ($this->getAttribute($this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "pessoaModificacao"), "nome")) : ("")), "html", null, true);
                echo "
                                </p>
                            </div>
                        </div>
                    ";
            }
            // line 61
            echo "                </form>
            </div>
        ";
        }
        // line 64
        echo "    </div>
</div>

<script type=\"text/javascript\">
    \$(\"#btnMatricula\").click(function() {
        \$.ajax({
            type: \"POST\",
            url: \"";
        // line 71
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_vaga_atualizar", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"), "status" => (isset($context["matricula"]) ? $context["matricula"] : $this->getContext($context, "matricula")))), "html", null, true);
        echo "\",
            success: function(retorno) {
                \$(\"#divListaVagas\").html(retorno);
            }
        });
        \$(\"#modalDialog\").modal(\"toggle\");
    });
    
    \$(\"#btnDesistencia\").click(function() {
        \$.ajax({
            type: \"POST\",
            url: \"";
        // line 82
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_vaga_atualizar", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"), "status" => (isset($context["cancelamento"]) ? $context["cancelamento"] : $this->getContext($context, "cancelamento")))), "html", null, true);
        echo "\",
            success: function(retorno) {
                \$(\"#divListaVagas\").html(retorno);
            }
        });
        \$(\"#modalDialog\").modal(\"toggle\");
    });
    
    \$(\"#btnEliminacao\").click(function() {
        \$.ajax({
            type: \"POST\",
            url: \"";
        // line 93
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_vaga_atualizar", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"), "status" => (isset($context["eliminacao"]) ? $context["eliminacao"] : $this->getContext($context, "eliminacao")))), "html", null, true);
        echo "\",
            success: function(retorno) {
                \$(\"#divListaVagas\").html(retorno);
            }
        });
        \$(\"#modalDialog\").modal(\"toggle\");
    });
    
    \$(\"#btnReverter\").click(function() {
        \$.ajax({
            type: \"POST\",
            url: \"";
        // line 104
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_vaga_reverterChamada", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
        echo "\",
            success: function(retorno) {
                \$(\"#divListaVagas\").html(retorno);
            }
        });
        \$(\"#modalDialog\").modal(\"toggle\");
    });
    
    \$(\"#btnMovimentacao\").click(function() {
        \$.ajax({
            type: \"GET\",
            url: \"";
        // line 115
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_movimentacaoInterna_formMovimentacaoVaga", array("vaga" => $this->getAttribute((isset($context["vaga"]) ? $context["vaga"] : $this->getContext($context, "vaga")), "id"))), "html", null, true);
        echo "\",
            success: function(retorno) {
                \$(\"#divModal\").html(retorno);
            }
        });
    });
</script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Vaga:modalFormGerencia.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 115,  167 => 104,  153 => 93,  139 => 82,  125 => 71,  116 => 64,  111 => 61,  103 => 56,  96 => 52,  81 => 39,  79 => 38,  60 => 24,  55 => 21,  53 => 20,  47 => 17,  40 => 13,  29 => 9,  19 => 1,);
    }
}

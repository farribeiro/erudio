<?php

/* FilaUnicaBundle:Index:index.html.twig */
class __TwigTemplate_26c7a51f39ebba19867a1e981fdf77e320d9ab99f864139408cc207bd9ad2d58 extends Twig_Template
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
        echo "Fila Única";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        if ((!$this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest"))) {
            // line 7
            echo "        <div class=\"row\">
            ";
            // line 8
            if (($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO") || $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "has", array(0 => "unidade"), "method"))) {
                // line 9
                echo "                <menu class=\"col-lg-2\">
                    <div class=\"list-group\">
                        <a class=\"list-group-item\" href=\"";
                // line 11
                echo $this->env->getExtension('routing')->getPath("fu_inscricao_formFila");
                echo "\">Consulta Fila</a>
                            <a class=\"list-group-item\" href=\"";
                // line 12
                echo $this->env->getExtension('routing')->getPath("fu_inscricao_formPesquisa");
                echo "\">Pesquisa Inscrição</a>
                            <a class=\"list-group-item\" href=\"";
                // line 13
                echo $this->env->getExtension('routing')->getPath("fu_inscricao_formInscricao", array("tipoInscricao" => 1));
                echo "\">Cadastro</a>
                            <a class=\"list-group-item\" href=\"";
                // line 14
                echo $this->env->getExtension('routing')->getPath("fu_inscricao_formInscricao", array("tipoInscricao" => 2));
                echo "\">Transferência</a>
                            ";
                // line 15
                if ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
                    // line 16
                    echo "                                <a class=\"list-group-item\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("fu_movimentacaoInterna_formPesquisa");
                    echo "\">Movimentação Int.</a>
                                <a class=\"list-group-item\" href=\"";
                    // line 17
                    echo $this->env->getExtension('routing')->getPath("fu_inscricao_formOrdemJudicial");
                    echo "\">Processo Judicial</a>
                            ";
                }
                // line 19
                echo "                            ";
                if (($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO") || $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "session"), "has", array(0 => "unidade"), "method"))) {
                    // line 20
                    echo "                                <a class=\"list-group-item\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("fu_vaga_formCadastro");
                    echo "\">Abertura de Vagas</a>
                                <a class=\"list-group-item\" href=\"";
                    // line 21
                    echo $this->env->getExtension('routing')->getPath("fu_vaga_formPesquisa");
                    echo "\">Gerência de Vagas</a>
                            ";
                }
                // line 23
                echo "                            ";
                if ($this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO")) {
                    // line 24
                    echo "                                <a class=\"list-group-item\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("fu_unidade_escolar_listar");
                    echo "\">Unidades de Ensino</a> 
                                <a class=\"list-group-item\" href=\"";
                    // line 25
                    echo $this->env->getExtension('routing')->getPath("fu_relatorio_formRelatorioInscricoes");
                    echo "\">Relatórios</a>
                            ";
                }
                // line 27
                echo "                            ";
                if ($this->env->getExtension('security')->isGranted("ROLE_SUPER_ADMIN")) {
                    // line 28
                    echo "                                <a class=\"list-group-item\" href=\"";
                    echo $this->env->getExtension('routing')->getPath("fu_inscricao_reordenarGeral");
                    echo "\">Reord. Trimestral</a>
                                <a class=\"list-group-item\" href=\"";
                    // line 29
                    echo $this->env->getExtension('routing')->getPath("fu_inscricao_redefinirAnosEscolares");
                    echo "\">Virada Anual</a>
                            ";
                }
                // line 31
                echo "                    </div>
                </menu>
            ";
            }
            // line 34
            echo "            <div id=\"page\" class=\"col-lg-10\">
                ";
            // line 35
            $this->displayBlock('page', $context, $blocks);
            // line 36
            echo "            </div>
        </div>
    ";
        } else {
            // line 39
            echo "        ";
            $this->displayBlock("page", $context, $blocks);
            echo "
    ";
        }
    }

    // line 35
    public function block_page($context, array $blocks = array())
    {
        echo " ";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Index:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  139 => 35,  131 => 39,  126 => 36,  124 => 35,  121 => 34,  116 => 31,  111 => 29,  106 => 28,  103 => 27,  98 => 25,  93 => 24,  90 => 23,  85 => 21,  80 => 20,  77 => 19,  72 => 17,  67 => 16,  65 => 15,  61 => 14,  57 => 13,  53 => 12,  49 => 11,  45 => 9,  43 => 8,  40 => 7,  37 => 6,  34 => 5,  28 => 3,);
    }
}

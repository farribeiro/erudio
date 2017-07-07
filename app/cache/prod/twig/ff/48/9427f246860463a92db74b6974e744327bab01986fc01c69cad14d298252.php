<?php

/* DGPPermutaBundle:Intencao:intencoes.html.twig */
class __TwigTemplate_ff489427f246860463a92db74b6974e744327bab01986fc01c69cad14d298252 extends Twig_Template
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
        echo "Servidor > Permuta> Lista de Interessados";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <div class=\"panel panel-default\">
        <div class=\"panel-heading\">Interessados em Remoção por Permuta</div>
        <div class=\"panel-body\">
            <div class=\"alert alert-info\">
                <strong>ATENÇÃO:</strong> SOMENTE PARA PROFESSORES E ESPECIALISTAS QUE JÁ TENHAM TERMINADO O ESTÁGIO PROBATÓRIO.
            </div>
            <p>Esta ferramenta visa promover a divulgação dos interessados em fazer permuta, facilitando a comunicação entre os mesmos. Se deseja adicionar sua intenção na lista, clique em Incluir Nova.</p>
            <p>Para permutar siga os procedimentos:</p>
            <ol>
                <li>Pesquise na lista abaixo as unidades de ensino que lhe interessam;</li>
                <li>Entre em contato com os interessados para combinar a permuta;</li>
                <li>Aguarde o Edital de Remoção por Permuta ser publicado para seguir os procedimentos e formalizar a permuta para 2016.</li>
            </ol>
            <p>
                <a class=\"btn btn-primary\" href=\"";
        // line 20
        echo $this->env->getExtension('routing')->getPath("dgp_servidor_permuta_cadastrarIntencao");
        echo "\">Incluir Nova</a>
            </p>
            <table class=\"table table-hover\">
                <thead>
                    <tr>
                        <th>Cargo</th>
                        <th>Lotação</th>
                        <th class=\"text-center\">C.H.</th>
                        <th class=\"text-center\">Servidor</th>
                        <th class=\"text-center\">Contato</th>
                        <th class=\"text-center\">Bairros de Interesse</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    ";
        // line 35
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["intencoes"]) ? $context["intencoes"] : $this->getContext($context, "intencoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["intencao"]) {
            // line 36
            echo "                        <tr>
                            <td>";
            // line 37
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "cargo"), "nome"), "html", null, true);
            echo "</td>
                            <td>";
            // line 38
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "lotacao"), "nome"), "html", null, true);
            echo "</td>
                            <td class=\"text-center\">";
            // line 39
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "cargaHoraria"), "html", null, true);
            echo "</td>
                            <td>";
            // line 40
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "nome"), "html", null, true);
            echo "</td>
                            <td>
                                ";
            // line 42
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "email"), "html", null, true);
            echo "
                                ";
            // line 43
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "telefone")) {
                // line 44
                echo "                                    <br/>Telefone: ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "telefone"), "numeroFormatado"), "html", null, true);
                echo "
                                ";
            }
            // line 46
            echo "                                ";
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "celular")) {
                // line 47
                echo "                                    <br/>Celular: ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "celular"), "numeroFormatado"), "html", null, true);
                echo "
                                ";
            }
            // line 49
            echo "                            </td>
                            <td>";
            // line 50
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "bairrosDeInteresse"), "html", null, true);
            echo "</td>
                            <td>
                                ";
            // line 52
            if (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "pessoa"), "id") == $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "vinculo"), "servidor"), "id"))) {
                // line 53
                echo "                                    <a class=\"lnkExcluir\" href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("dgp_servidor_permuta_excluirIntencao", array("intencao" => $this->getAttribute((isset($context["intencao"]) ? $context["intencao"] : $this->getContext($context, "intencao")), "id"))), "html", null, true);
                echo "\">
                                        <span title=\"Excluir\" class=\"glyphicon glyphicon-remove\"></span>
                                    </a>
                                ";
            }
            // line 57
            echo "                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 60
            echo "                        <tr>
                            <td class=\"text-center\" colspan=\"6\">
                                <em>Nenhuma intenção de permuta cadastrada</em>
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['intencao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 66
        echo "                </tbody>
            </table>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "DGPPermutaBundle:Intencao:intencoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  154 => 66,  143 => 60,  136 => 57,  128 => 53,  126 => 52,  121 => 50,  118 => 49,  112 => 47,  109 => 46,  103 => 44,  101 => 43,  97 => 42,  92 => 40,  88 => 39,  84 => 38,  80 => 37,  77 => 36,  72 => 35,  54 => 20,  38 => 6,  35 => 5,  29 => 3,);
    }
}

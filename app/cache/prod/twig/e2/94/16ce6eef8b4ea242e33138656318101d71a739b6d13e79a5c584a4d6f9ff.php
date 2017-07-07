<?php

/* DGPBundle:UnidadeEscolar:listaAlocacoes.html.twig */
class __TwigTemplate_e29416ce6eef8b4ea242e33138656318101d71a739b6d13e79a5c584a4d6f9ff extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:unidadeEscolar.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:unidadeEscolar.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Quadro de Servidores";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <p>
        <a class=\"ajaxLink\" href=\"";
        // line 7
        echo $this->env->getExtension('routing')->getUrl("dgp_unidade_formPonto");
        echo "\">Folha Ponto</a>
    </p>
    <table class=\"table table-striped table-hover\">
        <thead>
            <tr>
                <th></th>
                <th>Cargo Atual</th>
                <th>C.H.</th>
                <th>Servidor</th>
                <th>Vínculo</th>
                <th>Função Exercida</th>
                <th>Opções</th>
            </tr>
        </thead>
        <tbody>
            ";
        // line 22
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["alocacoes"]) ? $context["alocacoes"] : $this->getContext($context, "alocacoes")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["alocacao"]) {
            // line 23
            echo "                <tr>
                    <td> <input id=\"selecionadas\" name=\"selecionadas\" value=\"";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"), "html", null, true);
            echo "\" type=\"checkbox\" /> </td>
                    <td>";
            // line 25
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "cargo"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 26
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "cargaHoraria"), "html", null, true);
            echo "</td>
                    <td>";
            // line 27
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "servidor"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 28
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "tipoVinculo"), "nome"), "html", null, true);
            echo "</td>
                    <td>";
            // line 29
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "funcaoAtual"), "html", null, true);
            echo "</td>
                    <td class=\"text-center\">
                        <a style='cursor: pointer;' class='editarEmail' data-attr='";
            // line 31
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "servidor"), "nome"), "html", null, true);
            echo "'>
                            <span data-toggle=\"modal\" data-target=\"#modalEmail\" title=\"Editar e-mail\" class=\"glyphicon glyphicon-edit\"></span>
                        </a>
                        ";
            // line 34
            if ($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "servidor"), "usuario")) {
                // line 35
                echo "                            | <a href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("intranet_unidade_usuario_gerenciar", array("usuario" => $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "vinculoServidor"), "servidor"), "usuario"), "id"))), "html", null, true);
                echo "\">
                                <span title=\"Usuário\" class=\"glyphicon glyphicon-wrench\"></span>
                            </a>
                        ";
            }
            // line 39
            echo "                        | <a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("dgp_unidade_desalocarVinculo", array("alocacao" => $this->getAttribute((isset($context["alocacao"]) ? $context["alocacao"] : $this->getContext($context, "alocacao")), "id"))), "html", null, true);
            echo "\">
                            <span title=\"Desalocar\" class=\"glyphicon glyphicon-remove\"></span>
                        </a>
                    </td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 45
            echo "                <tr>
                    <td colspan=\"7\" class=\"text-center\">Nenhum servidor alocado neste unidade</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['alocacao'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "        </tbody>
    </table>
    
    <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
        <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 50%;\"></div>
    </div>
            
    <div class=\"modal fade\" id=\"modalEmail\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modalEmail\" aria-hidden=\"true\">
        <div class=\"modal-dialog\">
          <div class=\"modal-content\">
            <div class=\"modal-header\">
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
              <h4 class=\"modal-title\" id=\"myModalLabel\">Atualizar Email</h4>
            </div>
            <div class=\"modal-body\">
              Email atual: <span id='emailBusca'></span>
              <br /><br />
              <form id='formAtualizarEmail' action='";
        // line 66
        echo $this->env->getExtension('routing')->getUrl("dgp_unidade_buscarEmailByUsuario");
        echo "'>
                  <input type='text' class='form-control' id='emailInput' />
              </form>
            </div>
            <div class=\"modal-footer\">
              <button type=\"button\" id='atualizarEmail' class=\"btn btn-primary\">Atualizar</button>
            </div>
          </div>
        </div>
    </div>

";
    }

    // line 79
    public function block_javascript($context, array $blocks = array())
    {
        // line 80
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        var nomeServidor = null;
        
        \$(\".ajaxLink\").click(function(ev) {
            \$.ajax({
                type: \"GET\",
                url: \$(this).attr(\"href\"),
                success: function(retorno) {
                    \$(\"#divModal\").html(retorno);
                }
            });
            \$('#modalDialog').modal({\"show\": true});
            ev.preventDefault();
        });
        
        \$('.editarEmail').click(function () {
            var nome = \$(this).attr('data-attr');
            nomeServidor = nome;
            \$.ajax ({
                url: '";
        // line 100
        echo $this->env->getExtension('routing')->getUrl("dgp_unidade_buscarEmailByUsuario");
        echo "',
                type: 'POST',
                data: { 'nome': nome },
                success: function (data) {
                    \$('#emailBusca').html(data);
                }
            });
        });
        
        \$('#atualizarEmail').click(function (){
            \$.ajax ({
                url: '";
        // line 111
        echo $this->env->getExtension('routing')->getUrl("dgp_unidade_salvarEmailServidor");
        echo "',
                type: 'POST',
                data: { 'email': \$('#emailInput').val(), 'nome': nomeServidor },
                success: function (data){
                    if (data === 'true') {
                        \$.bootstrapGrowl(\"Email atualizado com sucesso.\", { type: 'success', delay: 5000 });
                    } else {
                        \$.bootstrapGrowl(data, { type: 'danger', delay: 5000 });
                    }
                    \$('#modalEmail').modal('hide');
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "DGPBundle:UnidadeEscolar:listaAlocacoes.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  206 => 111,  192 => 100,  168 => 80,  165 => 79,  149 => 66,  130 => 49,  121 => 45,  109 => 39,  101 => 35,  99 => 34,  93 => 31,  88 => 29,  84 => 28,  80 => 27,  76 => 26,  72 => 25,  68 => 24,  65 => 23,  60 => 22,  42 => 7,  39 => 6,  36 => 5,  30 => 3,);
    }
}

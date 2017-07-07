<?php

/* FilaUnicaBundle:Inscricao:modalConsultaInscricao.html.twig */
class __TwigTemplate_27a18a051c31caca536df093b7a191b841c2b2b1a5614a222db9b71ee2f3c438 extends Twig_Template
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
        echo " 
    Inscrição ";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "protocolo"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataCadastro"), "d/m/Y"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "nome"), "html", null, true);
        echo " 
    ";
        // line 5
        if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "movimentacaoInterna")) {
            // line 6
            echo "        [M.I.]
    ";
        } elseif ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoJudicial")) {
            // line 7
            echo " 
        [P.J.]
    ";
        }
    }

    // line 12
    public function block_modalBody($context, array $blocks = array())
    {
        // line 13
        echo "    <form id=\"formAlteracaoInscricao\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_alterar", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
        echo "\">
        ";
        // line 14
        if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "processoJudicial")) {
            // line 15
            echo "            <div class=\"row\">
                <div class=\"col-lg-6\">
                    <label for=\"numeroOrdemJudicial\">Número da Ordem Judicial:</label>
                    <input type=\"text\" id=\"numeroOrdemJudicial\" name=\"numeroOrdemJudicial\" value=\"";
            // line 18
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "numeroOrdemJudicial"), "html", null, true);
            echo "\" class=\"form-control\" />
                </div>
                <div class=\"col-lg-6\">
                    <label for=\"dataProcessoJudicial\">Data do Processo:</label>
                    <input type=\"text\" id=\"dataProcessoJudicial\" name=\"dataProcessoJudicial\" value=\"";
            // line 22
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "dataProcessoJudicial"), "d/m/Y"), "html", null, true);
            echo "\" class=\"form-control datepickerSME\" />
                </div>
            </div>
        ";
        }
        // line 26
        echo "        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"zoneamento\">Zoneamento:</label>
                <input type=\"text\" id=\"zoneamento\" readonly value=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "nome"), "html", null, true);
        echo " - ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "zoneamento"), "descricao"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"anoEscolar\">Ano Escolar:</label>
                <input type=\"text\" id=\"anoEscolar\" readonly value=\"";
        // line 33
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "anoEscolar"), "nome"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"periodoDia\">Turno:</label>
                ";
        // line 37
        if (($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "id") == 2)) {
            // line 38
            echo "                    <select id=\"periodoDia\" name=\"periodoDia\" class=\"form-control\" >
                        ";
            // line 39
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["periodos"]) ? $context["periodos"] : $this->getContext($context, "periodos")));
            foreach ($context['_seq'] as $context["_key"] => $context["periodo"]) {
                // line 40
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "id"), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "id") == $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "periodoDia"), "id"))) {
                    echo "selected";
                }
                echo " >
                                ";
                // line 41
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "nome"), "html", null, true);
                echo "
                            </option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['periodo'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 44
            echo "                    </select>
                ";
        } else {
            // line 46
            echo "                    <input type=\"text\" id=\"periodo\" readonly value=\"\" class=\"form-control\" />
                ";
        }
        // line 48
        echo "            </div>
        </div>
        <div class=\"row\">
                <div class=\"col-lg-6\">
                    <label for=\"unidadeDestino\">Unidade Pretendida:</label>
                    ";
        // line 53
        if ((!(isset($context["alteracaoRestrita"]) ? $context["alteracaoRestrita"] : $this->getContext($context, "alteracaoRestrita")))) {
            // line 54
            echo "                        <select id=\"unidadeDestino\" name=\"unidadeDestino\" class=\"form-control\" >
                            ";
            // line 55
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["unidadesEscolares"]) ? $context["unidadesEscolares"] : $this->getContext($context, "unidadesEscolares")));
            foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
                // line 56
                echo "                                <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
                echo "\" ";
                if (($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id") == $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "unidadeDestino"), "id"))) {
                    echo "selected";
                }
                echo " >
                                    ";
                // line 57
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
                echo "
                                </option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 60
            echo "                        </select>
                    ";
        } else {
            // line 62
            echo "                        <input type=\"text\" id=\"unidadeDestino\" readonly value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "unidadeDestino"), "nome"), "html", null, true);
            echo "\" class=\"form-control\" />
                    ";
        }
        // line 64
        echo "                </div>
                <div class=\"col-lg-6\">
                    <label for=\"unidadeDestinoAlternativa\">Unidade Pretendida [2]:</label>
                    <input type=\"text\" id=\"unidadeDestinoAlternativa\" readonly ";
        // line 67
        if (($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "id") == 2)) {
            echo "value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "unidadeDestinoAlternativa"), "nome"), "html", null, true);
            echo "\"";
        }
        echo " class=\"form-control\" />
                </div>
        </div>
        ";
        // line 70
        if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vagaOfertada")) {
            // line 71
            echo "            <div class=\"row\">
                <div class=\"col-lg-12\">
                    ";
            // line 73
            if ((!$this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "terminal"))) {
                // line 74
                echo "                        <label class=\"text-danger\" for=\"vagaOfertada\">Em Chamada:</label>
                        <input type=\"text\" id=\"vagaOfertada\" readonly value=\"";
                // line 75
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vagaOfertada"), "unidadeEscolar"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vagaOfertada"), "periodoDia"), "nome"), "html", null, true);
                echo "\" class=\"form-control alert-danger\" />
                    ";
            } else {
                // line 77
                echo "                        <label for=\"vagaOfertada\">Vaga Ofertada:</label>
                        <input type=\"text\" id=\"vagaOfertada\" readonly value=\"";
                // line 78
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vagaOfertada"), "unidadeEscolar"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "vagaOfertada"), "periodoDia"), "nome"), "html", null, true);
                echo " (";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "nome"), "html", null, true);
                echo ")\" class=\"form-control\" />
                    ";
            }
            // line 80
            echo "                </div>
            </div>
        ";
        }
        // line 83
        echo "        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"nome\">Nome:</label>
                <input type=\"text\" id=\"nome\" name=\"nome\" value=\"";
        // line 86
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "nome"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-6\">
                <label for=\"unidadeAtual\">Matrícula Atual:</label>
                ";
        // line 90
        if ($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "unidadeOrigem")) {
            // line 91
            echo "                    <input type=\"text\" id=\"anoEscolar\" readonly value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "unidadeOrigem"), "entidade"), "pessoaJuridica"), "nome"), "html", null, true);
            echo "\" class=\"form-control\" />
                ";
        } else {
            // line 92
            echo " 
                    <input type=\"text\" id=\"anoEscolar\" readonly value=\"Não Matriculado\" class=\"form-control\" />
                ";
        }
        // line 95
        echo "            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                ";
        // line 99
        if ($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "cpfCnpj")) {
            // line 100
            echo "                    <label for=\"cpf\">CPF:</label>
                    <input type=\"text\" id=\"cpf\" readonly value=\"";
            // line 101
            echo twig_escape_filter($this->env, $this->env->getExtension('commons_extension')->cpfFilter($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "cpfCnpj")), "html", null, true);
            echo "\" class=\"form-control\" />
                ";
        } else {
            // line 103
            echo "                    <label for=\"certidaoNascimento\">Certidão Nascimento:</label>
                    <input type=\"text\" id=\"certidaoNascimento\" readonly value=\"";
            // line 104
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "certidaoNascimento"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "termoCertidaoNascimento"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "livroCertidaoNascimento"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "folhaCertidaoNascimento"), "html", null, true);
            echo "\" class=\"form-control\" />
                ";
        }
        // line 106
        echo "            </div>
            <div class=\"col-lg-4\">
                <label for=\"dataNascimento\">Data Nascimento:</label>
                <input type=\"text\" class=\"form-control datepickerSME\" id=\"dataNascimento\" name=\"dataNascimento\" value=\"";
        // line 109
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "dataNascimento"), "d/m/Y", "UTC"), "html", null, true);
        echo "\"/>
            </div>
            <div class=\"col-lg-4\">
                <label for=\"email\">E-mail:</label>
                <input type=\"text\" id=\"email\" name=\"email\" value=\"";
        // line 113
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "email"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
        </div>
        ";
        // line 116
        if (($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "tipoInscricao"), "id") != twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::TRANSFERENCIA"))) {
            // line 117
            echo "            <div class=\"row\">
                <div class=\"col-lg-4\">
                    <label for=\"situacaoFamiliar\">Situação Familiar:</label>
                    ";
            // line 120
            if ((!(isset($context["alteracaoRestrita"]) ? $context["alteracaoRestrita"] : $this->getContext($context, "alteracaoRestrita")))) {
                // line 121
                echo "                        <select id=\"situacaoFamiliar\" name=\"situacaoFamiliar\" class=\"form-control\" ";
                if ((isset($context["alteracaoRestrita"]) ? $context["alteracaoRestrita"] : $this->getContext($context, "alteracaoRestrita"))) {
                    echo "disabled";
                }
                echo " >
                            ";
                // line 122
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["situacoesFamiliares"]) ? $context["situacoesFamiliares"] : $this->getContext($context, "situacoesFamiliares")));
                foreach ($context['_seq'] as $context["_key"] => $context["situacao"]) {
                    // line 123
                    echo "                                <option value=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"), "html", null, true);
                    echo "\" ";
                    if (($this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id") == $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "situacaoFamiliar"), "id"))) {
                        echo "selected";
                    }
                    echo " >
                                    ";
                    // line 124
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "descricao"), "html", null, true);
                    echo "
                                </option>
                            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['situacao'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 127
                echo "                        </select>
                    ";
            } else {
                // line 129
                echo "                        <input type=\"text\" id=\"situacaoFamiliar\" readonly value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "situacaoFamiliar"), "descricao"), "html", null, true);
                echo "\" class=\"form-control\" />
                    ";
            }
            // line 131
            echo "                </div>
                <div class=\"col-lg-4\">
                    <label for=\"rendaPerCapita\">Renda Per Capita:</label>
                    ";
            // line 134
            if ((!(isset($context["alteracaoRestrita"]) ? $context["alteracaoRestrita"] : $this->getContext($context, "alteracaoRestrita")))) {
                // line 135
                echo "                        <div class=\"input-group\" style=\"padding-top: 0;\">
                            <input type=\"text\" id=\"rendaPerCapita\" readonly value=\"R\$ ";
                // line 136
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "rendaPerCapita"), 2, ",", "."), "html", null, true);
                echo "\" class=\"form-control\" />
                            <span class=\"input-group-btn\">
                                <button id=\"btnAlterarRendaFamiliar\" type=\"button\" class=\"btn btn-primary\">
                                    <span class=\"glyphicon glyphicon-edit\" />
                                </button>
                            </span>
                        </div>
                    ";
            } else {
                // line 144
                echo "                        <input type=\"text\" id=\"rendaPerCapita\" readonly value=\"R\$ ";
                echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "rendaPerCapita"), 2, ",", "."), "html", null, true);
                echo "\" class=\"form-control\" />
                    ";
            }
            // line 146
            echo "                </div>
                <div class=\"col-lg-4\">
                    <label for=\"rendaPontuada\">Pontuação:</label>
                    <input type=\"text\" id=\"rendaPontuada\" readonly value=\"";
            // line 149
            echo twig_escape_filter($this->env, twig_number_format_filter($this->env, $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "calcularPontuacao"), 2, ",", "."), "html", null, true);
            echo "\" class=\"form-control\" />
                </div>
            </div>
        ";
        }
        // line 153
        echo "        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"nomeMae\">Filiação - Mãe:</label>
                <input type=\"text\" id=\"nomeMae\" name=\"nomeMae\" value=\"";
        // line 156
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "nomeMae"), "html", null, true);
        echo "\" class=\"form-control\" required />
            </div>
            <div class=\"col-lg-6\">
                <label for=\"nomePai\">Filiação - Pai:</label>
                <input type=\"text\" id=\"nomePai\" name=\"nomePai\" value=\"";
        // line 160
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "nomePai"), "html", null, true);
        echo "\" class=\"form-control\" required />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-8\">
                <label for=\"endereco\">Endereço:</label>
                <input type=\"text\" id=\"endereco\" name=\"endereco\" value=\"";
        // line 166
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "endereco"), "logradouro"), "html", null, true);
        echo "\" class=\"form-control\" required />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"numero\">Número:</label>
                <input type=\"text\" id=\"numero\" name=\"numero\" value=\"";
        // line 170
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "endereco"), "numero"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"complemento\">Complemento:</label>
                <input type=\"text\" id=\"complemento\" name=\"complemento\" value=\"";
        // line 176
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "endereco"), "complemento"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"Bairro\">Bairro:</label>
                <input type=\"text\" id=\"bairro\" name=\"bairro\" value=\"";
        // line 180
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "endereco"), "bairro"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"cep\">CEP:</label>
                <input type=\"text\" id=\"cep\" name=\"cep\" value=\"";
        // line 184
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "endereco"), "cep"), "html", null, true);
        echo "\" class=\"form-control\" />
            </div>
        </div>
    </form>
    <div class=\"row\">
        <div class=\"col-lg-4\">
            <strong>Telefones:</strong>
            <ul>
                ";
        // line 192
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "telefones"));
        foreach ($context['_seq'] as $context["_key"] => $context["telefone"]) {
            // line 193
            echo "                    <li>
                        ";
            // line 194
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "numeroFormatado"), "html", null, true);
            echo "  
                        <a href=\"";
            // line 195
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_excluirTelefone", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"), "telefone" => $this->getAttribute((isset($context["telefone"]) ? $context["telefone"] : $this->getContext($context, "telefone")), "id"))), "html", null, true);
            echo "\" class=\"ajaxAction\">
                            <span class=\"glyphicon glyphicon-remove\" />
                        </a>
                    </li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['telefone'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 200
        echo "            </ul>
            <div class=\"input-group\" style=\"padding-top: 0;\">
                <input type=\"text\" id=\"telefone\" name=\"telefone\" class=\"form-control\" placeholder=\"4700000000\" />
                <span class=\"input-group-btn\">
                    <button id=\"btnIncluirTelefone\" type=\"button\" class=\"btn btn-primary\">
                        <span class=\"glyphicon glyphicon-plus-sign\" />
                    </button>
                </span>
            </div>
        </div>
        <div class=\"col-lg-8 border-r\">
            <strong>Responsáveis:</strong>
            <ul>
                ";
        // line 213
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "crianca"), "relacoes"));
        foreach ($context['_seq'] as $context["_key"] => $context["responsavel"]) {
            // line 214
            echo "                    <li> 
                        ";
            // line 215
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["responsavel"]) ? $context["responsavel"] : $this->getContext($context, "responsavel")), "parente"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->env->getExtension('commons_extension')->cpfFilter($this->getAttribute($this->getAttribute((isset($context["responsavel"]) ? $context["responsavel"] : $this->getContext($context, "responsavel")), "parente"), "cpfCnpj")), "html", null, true);
            echo "
                        ";
            // line 216
            if ($this->getAttribute((isset($context["responsavel"]) ? $context["responsavel"] : $this->getContext($context, "responsavel")), "parentesco")) {
                echo " (";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["responsavel"]) ? $context["responsavel"] : $this->getContext($context, "responsavel")), "parentesco"), "nome"), "html", null, true);
                echo ") ";
            }
            // line 217
            echo "                    </li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['responsavel'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 219
        echo "            </ul>
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-lg-4\">
            ";
        // line 224
        if (($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\Status::DESISTENTE_INSCRICAO"))) {
            // line 225
            echo "                <a target=\"_blank\" class=\"btn btn-info btn-block\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_imprimirTermoDesistencia", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Termo Desistência</a>
            ";
        } else {
            // line 227
            echo "                <a target=\"_blank\" class=\"btn btn-info btn-block\" href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_imprimirComprovante", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
            echo "\">Comprovante 2ª Via</a>
            ";
        }
        // line 229
        echo "        </div>
        ";
        // line 230
        if (((!$this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "terminal")) || $this->env->getExtension('security')->isGranted("ROLE_INFANTIL_MEMBRO"))) {
            // line 231
            echo "            <div class=\"col-lg-4\">
                <button id=\"btnAtualizar\" type=\"button\" class=\"btn btn-success btn-block\">Atualizar</button>
            </div>
        ";
        }
        // line 235
        echo "        ";
        if (((!$this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "terminal")) && ($this->getAttribute($this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "status"), "id") != twig_constant("SME\\FilaUnicaBundle\\Entity\\Status::EM_CHAMADA")))) {
            // line 236
            echo "            <div class=\"col-lg-4\">
                <button id=\"btnCancelar\" type=\"button\" class=\"btn btn-danger btn-block\">Cancelar</button>
            </div>
        ";
        }
        // line 240
        echo "    </div>
";
    }

    // line 243
    public function block_javascript($context, array $blocks = array())
    {
        // line 244
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
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
       
        \$(\"#btnAtualizar\").click( function() {
            \$.ajax({
                type: \"POST\",
                url: \$(\"#formAlteracaoInscricao\").attr('action'),
                data: \$(\"#formAlteracaoInscricao\").serialize(),
                success: function(retorno){
                    \$(\"#divModal\").html(retorno);  
                }
            });
        });
        
        \$(\"#btnCancelar\").click( function() {
            if(confirm('Deseja realmente cancelar esta solicitação de vaga? É necessária a assinatura de um responsável para este procedimento'))
            {
                \$.ajax({
                    type: \"POST\",
                    url: \"";
        // line 273
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_cancelar", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
        echo "\",
                    success: function(retorno){
                        \$(\"#page\").html(retorno);  
                    }
                });
                \$('#modalDialog').modal(\"hide\");
            }
        });
        
        \$(\"#btnIncluirTelefone\").click(function() {
            \$.ajax({
                type: \"POST\",
                url: \"";
        // line 285
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getUrl("fu_inscricao_incluirTelefone", array("inscricao" => $this->getAttribute((isset($context["inscricao"]) ? $context["inscricao"] : $this->getContext($context, "inscricao")), "id"))), "html", null, true);
        echo "\",
                data: {\"telefone\": \$(\"#telefone\").val()},
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
        return "FilaUnicaBundle:Inscricao:modalConsultaInscricao.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  617 => 285,  602 => 273,  569 => 244,  566 => 243,  561 => 240,  555 => 236,  552 => 235,  546 => 231,  544 => 230,  541 => 229,  535 => 227,  529 => 225,  527 => 224,  520 => 219,  513 => 217,  507 => 216,  501 => 215,  498 => 214,  494 => 213,  479 => 200,  468 => 195,  464 => 194,  461 => 193,  457 => 192,  446 => 184,  439 => 180,  432 => 176,  423 => 170,  416 => 166,  407 => 160,  400 => 156,  395 => 153,  388 => 149,  383 => 146,  377 => 144,  366 => 136,  363 => 135,  361 => 134,  356 => 131,  350 => 129,  346 => 127,  337 => 124,  328 => 123,  324 => 122,  317 => 121,  315 => 120,  310 => 117,  308 => 116,  302 => 113,  295 => 109,  290 => 106,  279 => 104,  276 => 103,  271 => 101,  268 => 100,  266 => 99,  260 => 95,  255 => 92,  249 => 91,  247 => 90,  240 => 86,  235 => 83,  230 => 80,  221 => 78,  218 => 77,  211 => 75,  208 => 74,  206 => 73,  202 => 71,  200 => 70,  190 => 67,  185 => 64,  179 => 62,  175 => 60,  166 => 57,  157 => 56,  153 => 55,  150 => 54,  148 => 53,  141 => 48,  137 => 46,  133 => 44,  124 => 41,  115 => 40,  111 => 39,  108 => 38,  106 => 37,  99 => 33,  90 => 29,  85 => 26,  78 => 22,  71 => 18,  66 => 15,  64 => 14,  59 => 13,  56 => 12,  49 => 7,  45 => 6,  43 => 5,  35 => 4,  30 => 3,);
    }
}

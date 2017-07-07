<?php

/* FilaUnicaBundle:Inscricao:formCadastro.html.twig */
class __TwigTemplate_5c74bc49c0b3f1d509ec607ba94660f40522b63ba484432a9fc21c096e354b19 extends Twig_Template
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
        echo " Fila Única > Inscrição > ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "nome"), "html", null, true);
        echo " ";
    }

    // line 5
    public function block_page($context, array $blocks = array())
    {
        // line 6
        echo "    <form id=\"formCadastroInscricao\" method=\"post\" action=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("fu_inscricao_cadastrar", array("tipoInscricao" => $this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id"))), "html", null, true);
        echo "\">
        <input id=\"tipoInscricao\" name=\"tipoInscricao\" value=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id"), "html", null, true);
        echo "\" type=\"hidden\" />
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"unidade\">Unidade Pretendida:</label>
                <select id=\"unidade\" name=\"unidade\" class=\"form-control\" required>
                    <option value=\"\">Selecione uma unidade</option>
                    ";
        // line 13
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 14
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo " | ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "descricao"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 16
        echo "                </select>
            </div>
        </div>
        ";
        // line 19
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::TRANSFERENCIA"))) {
            // line 20
            echo "            <div class=\"row\">
                <div class=\"col-lg-12\">
                    <label for=\"unidadeAlternativa\">Unidade Pretendida - 2ª Opção:</label>
                    <select id=\"unidadeAlternativa\" name=\"unidadeAlternativa\" class=\"form-control\">
                        <option>Nenhuma</option>
                        ";
            // line 25
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
            foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
                // line 26
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
                echo " | ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "nome"), "html", null, true);
                echo " - ";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "descricao"), "html", null, true);
                echo " </option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 28
            echo "                    </select>
                </div>
            </div>
        ";
        }
        // line 32
        echo "        <div class=\"row\">
            <div class=\"col-lg-12\">
                <label for=\"unidadeAtual\">Matrícula Atual:</label>
                <select id=\"unidadeAtual\" name=\"unidadeAtual\" class=\"form-control\" required>
                    <option value=\"0\">
                        ";
        // line 37
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") != twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::TRANSFERENCIA"))) {
            echo " Não matriculado na rede ";
        }
        // line 38
        echo "                    </option>
                    ";
        // line 39
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["unidades"]) ? $context["unidades"] : $this->getContext($context, "unidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["unidade"]) {
            // line 40
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "nome"), "html", null, true);
            echo " | ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "nome"), "html", null, true);
            echo " - ";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["unidade"]) ? $context["unidade"] : $this->getContext($context, "unidade")), "zoneamento"), "descricao"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['unidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
            ";
        // line 46
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::TRANSFERENCIA"))) {
            // line 47
            echo "                <div class=\"col-lg-4\">
                    <label for=\"periodo\">Período:</label>
                    <select id=\"periodo\" name=\"periodo\" class=\"form-control\">
                    ";
            // line 50
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["periodos"]) ? $context["periodos"] : $this->getContext($context, "periodos")));
            foreach ($context['_seq'] as $context["_key"] => $context["periodo"]) {
                // line 51
                echo "                        <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "id"), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["periodo"]) ? $context["periodo"] : $this->getContext($context, "periodo")), "nome"), "html", null, true);
                echo " </option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['periodo'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 53
            echo "                    </select>
                </div>
                <div class=\"col-lg-4\">
                    <label for=\"codigoAluno\">Número de Matrícula Erudio:</label>
                    <input id=\"codigoAluno\" name=\"codigoAluno\" type=\"text\" class=\"form-control\" required />
                </div>
                <div class=\"col-lg-4\">
                    <label for=\"dataMatricula\">Data de Cadastro Erudio:</label>
                    <input type=\"text\" id=\"dataMatricula\" name=\"dataMatricula\" class=\"form-control datepickerSME\" required />
                </div>
            ";
        } else {
            // line 64
            echo "                <div class=\"col-lg-6\">
                    <label for=\"situacaoFamiliar\">Situação Familiar:</label>
                    <select id=\"situacaoFamiliar\" name=\"situacaoFamiliar\" required class=\"form-control\">
                        <option value=\"\"> </option>
                        ";
            // line 68
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["situacoesFamiliares"]) ? $context["situacoesFamiliares"] : $this->getContext($context, "situacoesFamiliares")));
            foreach ($context['_seq'] as $context["_key"] => $context["situacao"]) {
                // line 69
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "id"), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["situacao"]) ? $context["situacao"] : $this->getContext($context, "situacao")), "descricao"), "html", null, true);
                echo " </option>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['situacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 71
            echo "                    </select>
                </div>
                <div class=\"col-lg-6\">
                    <label for=\"rendaPerCapita\">Renda Per Capita:</label>
                    <div class=\"input-group\" style=\"padding-top: 0;\">
                        <input id=\"rendaPerCapita\" name=\"rendaPerCapita\" type=\"text\" class=\"form-control\" readonly=\"true\"/>
                        <span class=\"input-group-btn\">
                            <button id=\"btnRenda\" type=\"button\" class=\"btn btn-primary\">
                                <span class=\"glyphicon glyphicon-edit\"></span>
                            </button>
                        </span>
                    </div>
                </div>
            ";
        }
        // line 85
        echo "        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"nome\">Nome:</label>
                <input id=\"nome\" name=\"nome\" type=\"text\" class=\"form-control\" required />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"dataNascimento\">Data Nascimento:</label>
                <input id=\"dataNascimento\" name=\"dataNascimento\" required type=\"text\" class=\"form-control datepickerSME\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"documentoIdentificacao\">Documento de Identificação:</label>
                <select id=\"documentoIdentificacao\" name=\"documentoIdentificacao\" class=\"form-control\" required >
                    <option value=\"certidaoNascimentoNova\">Certidão de Nascimento - 32 Dígitos</option>
                    <option value=\"certidaoNascimentoAntiga\">Certidão de Nascimento - Termo, Livro e Folha</option>
                    <option value=\"cpf\">CPF (Estrangeiros)</option>
                </select>
            </div>
        </div>
        <div class=\"row\" id=\"divCertidaoNova\">
            <div class=\"col-lg-12\">
                <label for=\"certidaoNascimento\">Certidão Nascimento (32 dígitos):</label>
                <input id=\"certidaoNascimento\" name=\"certidaoNascimento\" type=\"text\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\" id=\"divCertidaoAntiga\">
            <div class=\"col-lg-4\">
                <label for=\"termoCertidaoNascimento\">Termo:</label>
                <input id=\"termoCertidaoNascimento\" name=\"termoCertidaoNascimento\" type=\"text\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"livroCertidaoNascimento\">Livro:</label>
                <input id=\"livroCertidaoNascimento\" name=\"livroCertidaoNascimento\" type=\"text\" class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"folhaCertidaoNascimento\">Folha:</label>
                <input id=\"folhaCertidaoNascimento\" name=\"folhaCertidaoNascimento\" type=\"text\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\" id=\"divCpf\">
            <div class=\"col-lg-12\">
                <label for=\"cpf\">CPF (apenas números):</label>
                <input id=\"cpf\" name=\"cpf\" type=\"text\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"raca\">Raça:</label>
                <select id=\"raca\" name=\"raca\" class=\"form-control\">
                    <option value=\"\">Não informado</option>
                    ";
        // line 135
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["racas"]) ? $context["racas"] : $this->getContext($context, "racas")));
        foreach ($context['_seq'] as $context["_key"] => $context["raca"]) {
            // line 136
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["raca"]) ? $context["raca"] : $this->getContext($context, "raca")), "id"), "html", null, true);
            echo "\"> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["raca"]) ? $context["raca"] : $this->getContext($context, "raca")), "nome"), "html", null, true);
            echo " </option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['raca'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 138
        echo "                </select>
            </div>
             <div class=\"col-lg-4\">
                <label for=\"nomeMae\">Filiação - Mãe:</label>
                <input id=\"nomeMae\" name=\"nomeMae\" type=\"text\" required class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"nomePai\">Filiação - Pai:</label>
                <input id=\"nomePai\" name=\"nomePai\" type=\"text\" required class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"endereco\">Endereço:</label>
                <input id=\"endereco\" name=\"endereco\" type=\"text\" required class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"numero\">Número:</label>
                <input id=\"numero\" name=\"numero\" type=\"text\" required class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"complemento\">Complemento:</label>
                <input id=\"complemento\" name=\"complemento\" type=\"text\" class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-4\">
                <label for=\"bairro\">Bairro:</label>
                <input id=\"bairro\" name=\"bairro\" type=\"text\" required class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"cep\">CEP:</label>
                <input id=\"cep\" name=\"cep\" type=\"text\" required class=\"form-control\" />
            </div>
            <div class=\"col-lg-4\">
                <label for=\"email\">E-mail:</label>
                <input id=\"email\" name=\"email\" type=\"text\" required class=\"form-control\" />
            </div>
        </div>
        <div class=\"row\">
            ";
        // line 178
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(1, 6));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 179
            echo "                <div class=\"col-lg-2\">
                    <label for=\"telefone";
            // line 180
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\">Telefone ";
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo ":</label>
                    <input id=\"telefone";
            // line 181
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" name=\"telefone[]\" type=\"text\" class=\"form-control\" />
                </div>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 184
        echo "        </div>
        
        <div id=\"divFiliacao\" style=\"margin-top: 12px;\">
            <p> <strong>Responsáveis:</strong> </p>
            <table class=\"table table-striped\">
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>RG</th>
                    <th>Parentesco</th>
                </tr>
                ";
        // line 195
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(1, 2));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 196
            echo "                    <tr>
                        <td> <input id=\"filiacaoNome";
            // line 197
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" name=\"filiacaoNome";
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" type=\"text\" class=\"form-control\" required /> </td>
                        <td> <input id=\"filiacaoCPF";
            // line 198
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" name=\"filiacaoCPF";
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" type=\"text\" class=\"form-control\" required/> </td>
                        <td> <input id=\"filiacaoRG";
            // line 199
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" name=\"filiacaoRG";
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" type=\"text\" class=\"form-control\" required/></td>
                        <td> 
                            <select id=\"filiacaoParentesco";
            // line 201
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" name=\"filiacaoParentesco";
            echo twig_escape_filter($this->env, (isset($context["i"]) ? $context["i"] : $this->getContext($context, "i")), "html", null, true);
            echo "\" class=\"form-control\" required>
                                <option value=\"\"> </option>
                                ";
            // line 203
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["parentescos"]) ? $context["parentescos"] : $this->getContext($context, "parentescos")));
            foreach ($context['_seq'] as $context["_key"] => $context["parentesco"]) {
                // line 204
                echo "                                    <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["parentesco"]) ? $context["parentesco"] : $this->getContext($context, "parentesco")), "id"), "html", null, true);
                echo "\"> ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["parentesco"]) ? $context["parentesco"] : $this->getContext($context, "parentesco")), "nome"), "html", null, true);
                echo " </option>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['parentesco'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 206
            echo "                            </select> 
                        </td>
                    </tr>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 210
        echo "            </table>
        </div>
            
        <div id=\"divFiliacao\" style=\"margin-top: 12px;\">
            <p> <strong>Deficiências / Transtornos:</strong> </p>
            ";
        // line 215
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["particularidades"]) ? $context["particularidades"] : $this->getContext($context, "particularidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
            // line 216
            echo "                <input type=\"checkbox\" id=\"particularidades";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")), "id"), "html", null, true);
            echo "\" value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")), "id"), "html", null, true);
            echo "\" name=\"particularidades[]\" /> ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["p"]) ? $context["p"] : $this->getContext($context, "p")), "nome"), "html", null, true);
            echo "
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['p'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 218
        echo "        </div>
        
        ";
        // line 220
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::REGULAR"))) {
            // line 221
            echo "            <div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
                <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 75%;\">
                    <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">&times;</button>
                            <h4 class=\"modal-title\">Cálculo de renda per capita</h4>
                        </div>
                        <div class=\"modal-body\">
                            <p class=\"text-center text-danger\">
                                O responsável pela inscrição da criança declara que as seguintes pessoas moram sob o mesmo teto:
                            </p>
                            <table class=\"table table-striped table-hover\">
                                <thead>
                                    <tr>
                                        <th>Nome Completo</th>
                                        <th>Parentesco</th>
                                        <th>Atividade</th>
                                        <th>Local de Trabalho</th>
                                        <th>Rendimento Mensal</th>
                                        <th>Responsável</th>
                                    </tr>
                                </thead>
                                <tbody id=\"componentesRenda\">
                                    <tr id=\"componenteRenda1\">
                                        <td> <input class=\"form-control nome1\" name=\"nomeComponenteRenda[]\" type=\"text\" /> </td>
                                        <td> 
                                            <select id=\"relacao\" name=\"relacao[]\" class=\"form-control relacao\"><option></option><option value=\"1\">Pai</option><option value=\"2\">Mãe</option><option value=\"5\">Irmão/Irmã</option><option value=\"3\">Avô/Avó</option><option value=\"8\">Madrasta</option><option value=\"7\">Padrasto</option><option value=\"6\">Primo/Prima</option><option value=\"4\">Tio/Tia</option><option value=\"9\">Outro</option></select>
                                            <input class=\"form-control\" name=\"parentescoComponenteRenda[]\" type=\"text\" value=\"Criança\" readOnly /> 
                                        </td>
                                        <td> <input class=\"form-control\" name=\"atividadeComponenteRenda[]\" type=\"text\" readOnly /> </td>
                                        <td> <input class=\"form-control\" name=\"localTrabalhoComponenteRenda[]\" type=\"text\" readOnly /> </td>
                                        <td> <input id=\"rendimentoComponenteRenda1\" class=\"form-control\" name=\"rendimentoComponenteRenda[]\" type=\"text\" value=\"0\" readOnly /> </td>
                                        <td> <input style=\"display:none;\" id='responsavel1' class=\"form-control responsaveis\" name=\"responsavel[]\" value='1' type=\"checkbox\" /> </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p>
                                <strong>Renda Per Capita:</strong>
                                R\$ <span id=\"rendaCalculada\">0.00</span>
                                <button id=\"btnCalcular\" type=\"button\" class=\"btn btn-link\">Calcular</button>
                            </p>
                            <p>
                                <button id=\"btnAdicionarComponenteRenda\" class=\"btn btn-primary\" type=\"button\">Adicionar</button>
                                <button id=\"btnRemoverComponenteRenda\" class=\"btn btn-primary\" type=\"button\">Remover</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        ";
        }
        // line 271
        echo "        <div class=\"row\" style=\"padding-top: 1em;\">
            <div class=\"col-lg-4\">
                <button id=\"btnCadastrar\" type=\"button\" class=\"btn btn-primary\">Cadastrar</button>
            </div>
        </div>
    </form>
";
    }

    // line 279
    public function block_javascript($context, array $blocks = array())
    {
        // line 280
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(\".responsavel\").each(function(){
            var val = \$(this).val();
        });
        
        \$(\"#componenteRenda1 td select\").hide();
        
        \$(\"#btnCadastrar\").click( function(ev) {
            if(\$(\"#unidade\").val() === \"\") {
                alert(\"Preencha o campo unidade pretendida\");
            } else if (\$(\"#filiacaoNome1\").val().length === 0) {
                alert(\"Preencha ao menos um campo de responsáveis\");
            } else if (\$(\"#filiacaoCPF1\").val().length === 0) {
                alert(\"Preencha ao menos um campo de responsáveis\");
            } else if (\$(\"#filiacaoRG1\").val().length === 0) {
                alert(\"Preencha ao menos um campo de responsáveis\");
            } else if (\$(\"#filiacaoParentesco1\").val().length === 0) {
                alert(\"Preencha ao menos um campo de responsáveis\");
            ";
        // line 299
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::TRANSFERENCIA"))) {
            // line 300
            echo "            } else if (\$(\"#codigoAluno\").val().length === 0) {
                alert(\"Preencha o código de aluno do Erudio\");
            } else if (\$(\"#dataMatricula\").val().length === 0) {
                alert(\"Preencha a data de matrícula do Erudio\");
            ";
        }
        // line 305
        echo "            } else {
                \$.ajax({
                    type: \"POST\",
                    url: \"";
        // line 308
        echo $this->env->getExtension('routing')->getPath("fu_inscricao_validar");
        echo "\",
                    data: \$(\"#formCadastroInscricao\").serialize(),
                    success: function(retorno){
                        if(retorno.valida === true) {
                            \$(\"#formCadastroInscricao\").submit();
                        } else {
                            \$(\"#error\").html(retorno.erro);
                            \$(\"#modalError\").modal(\"show\");
                        }
                    },
                    error: function() {
                        \$(\"#error\").html(\"Falha na comunicação com o servidor. Verifique sua conexão de Internet.\");
                        \$(\"#modalError\").modal(\"show\");
                    }
                });
            }
            ev.preventDefault();
        });
             
        \$(\"#divCpf\").hide();
        \$(\"#divCertidaoAntiga\").hide();
        
        \$(\"#documentoIdentificacao\").change( function() {
            switch(\$(this).val()) {
                case \"cpf\":
                    \$(\"#divCpf\").show();
                    \$(\"#divCertidaoNova\").hide();
                    \$(\"#divCertidaoAntiga\").hide();
                    break;
                case \"certidaoNascimentoNova\":
                    \$(\"#divCpf\").hide();
                    \$(\"#divCertidaoNova\").show();
                    \$(\"#divCertidaoAntiga\").hide();
                    break;
                case \"certidaoNascimentoAntiga\":
                    \$(\"#divCpf\").hide();
                    \$(\"#divCertidaoNova\").hide();
                    \$(\"#divCertidaoAntiga\").show();
                    break;
            }
        });
        
        ";
        // line 350
        if (($this->getAttribute((isset($context["tipoInscricao"]) ? $context["tipoInscricao"] : $this->getContext($context, "tipoInscricao")), "id") == twig_constant("SME\\FilaUnicaBundle\\Entity\\TipoInscricao::REGULAR"))) {
            // line 351
            echo "            \$(\"#btnRenda\").click( function() {
                \$('#modalDialog').modal(\"show\");
            });
            
            \$('.nome1').change(function(){
                \$('#nome').val(\$(this).val());
            });

            \$(\"#btnAdicionarComponenteRenda\").click( function() {
                var id = \$(\"#componentesRenda tr\").size() + 1;
                \$(\"#componentesRenda\").append('<tr id=\"componenteRenda' + id + '\" >' +
                    '<td> <input class=\"form-control nome' + id + '\" name=\"nomeComponenteRenda[]\" type=\"text\" /> </td>' +
                    '<td> <select id=\"relacao' + id + '\" name=\"relacao[]\" class=\"form-control relacao\"><option></option><option value=\"1\">Pai</option><option value=\"2\">Mãe</option><option value=\"5\">Irmão/Irmã</option><option value=\"3\">Avô/Avó</option><option value=\"8\">Madrasta</option><option value=\"7\">Padrasto</option><option value=\"6\">Primo/Prima</option><option value=\"4\">Tio/Tia</option><option value=\"10\">Guarda Judicial</option><option value=\"9\">Outro</option></select> <input id=\"outro' + id + '\" class=\"form-control\" name=\"parentescoComponenteRenda[]\" placeholder=\"Insira Parentesco\" type=\"hidden\" /> </td>' +
                    '<td> <input class=\"form-control\" name=\"atividadeComponenteRenda[]\" type=\"text\" /> </td>' +
                    '<td> <input class=\"form-control\" name=\"localTrabalhoComponenteRenda[]\" type=\"text\" /> </td>' +
                    '<td> <input id=\"rendimentoComponenteRenda' + id + '\" class=\"form-control\" name=\"rendimentoComponenteRenda[]\" type=\"text\" /> </td>' +
                    '<td> <input id=\"responsavel' + id + '\" class=\"form-control responsaveis\" value=\"' + id + '\" name=\"responsavel[]\" type=\"checkbox\" /> </td>' +
                    '</tr>');
                
                \$('#outro'+id).hide();
                
                \$('#relacao'+id).change(function(){
                    if (\$(this).val() === \"9\") { \$('#outro'+id).attr('type','text').show(); \$('#outro'+id).val(''); } else { \$('#outro'+id).attr('type','hidden'); var val = \$(this).val(); \$(\"#outro\"+id).val(\$('#relacao'+id+' option[value=\"' + val + '\"]').html()); }
                    if (\$(this).val() === \"1\") { \$('#nomePai').val(\$('.nome'+id).val()); }
                    if (\$(this).val() === \"2\") { \$('#nomeMae').val(\$('.nome'+id).val()); }
                });
                                
                \$('.responsaveis').unbind().click(function(event){
                    var contador = 0;
                    \$('#componentesRenda input:checked').each(function(){ contador++; });
                    if (contador > 2) {
                        event.preventDefault();
                    } else {
                        if (\$(this).is(':checked')) {
                            var id = \$(this).val();
                            var nome = \$('.nome'+id).val();
                            var parentesco = \$('#relacao'+id).val();
                            if (\$(\"#filiacaoNome1\").val() === \"\") {
                                \$(this).addClass('responsavel1');
                                \$(\"#filiacaoNome1\").val(nome).attr('readonly','readonly');
                                \$('#filiacaoParentesco1 option[value=\"' + parentesco + '\"]').attr('selected','selected');
                                \$('#filiacaoParentesco1').attr('readonly','readonly');
                            } else {
                                \$(this).addClass('responsavel2');
                                \$(\"#filiacaoNome2\").val(nome).attr('readonly','readonly');
                                \$('#filiacaoParentesco2 option[value=\"' + parentesco + '\"]').attr('selected','selected');
                                \$('#filiacaoParentesco2').attr('readonly','readonly');
                            }
                        } else {
                            if (\$(this).hasClass('responsavel1')) {
                                \$(\"#filiacaoNome1\").val('').removeAttr('readonly');
                                \$('#filiacaoParentesco1').removeAttr('readonly');
                            } else {
                                \$(\"#filiacaoNome2\").val('').removeAttr('readonly');
                                \$('#filiacaoParentesco2').removeAttr('readonly');
                            }
                        }
                    }
                });
            });
            

            \$(\"#btnRemoverComponenteRenda\").click( function() {
                var id = \$(\"#componentesRenda tr\").size();
                \$(\"#componentesRenda\").children(\"#componenteRenda\" + id).remove();
            });

            \$(\"#btnCalcular\").click( function() {
                var renda = 0.0;
                for(var i = 1; i <= \$(\"#componentesRenda tr\").size(); i++) {
                    renda += parseFloat(\$('#rendimentoComponenteRenda' + i).val().replace(\",\",\".\"));
                }
                var rendaPerCapita = renda / \$(\"#componentesRenda tr\").size();
                \$(\"#rendaCalculada\").text(rendaPerCapita.toString().replace(\".\",\",\"));
                \$(\"#rendaPerCapita\").attr('value', rendaPerCapita.toString().replace(\".\",\",\"));
            });
        ";
        }
        // line 428
        echo "    </script>
";
    }

    public function getTemplateName()
    {
        return "FilaUnicaBundle:Inscricao:formCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  683 => 428,  604 => 351,  602 => 350,  557 => 308,  552 => 305,  545 => 300,  543 => 299,  520 => 280,  517 => 279,  507 => 271,  455 => 221,  453 => 220,  449 => 218,  436 => 216,  432 => 215,  425 => 210,  416 => 206,  405 => 204,  401 => 203,  394 => 201,  387 => 199,  381 => 198,  375 => 197,  372 => 196,  368 => 195,  355 => 184,  346 => 181,  340 => 180,  337 => 179,  333 => 178,  291 => 138,  280 => 136,  276 => 135,  224 => 85,  208 => 71,  197 => 69,  193 => 68,  187 => 64,  174 => 53,  163 => 51,  159 => 50,  154 => 47,  152 => 46,  146 => 42,  131 => 40,  127 => 39,  124 => 38,  120 => 37,  113 => 32,  107 => 28,  92 => 26,  88 => 25,  81 => 20,  79 => 19,  74 => 16,  59 => 14,  55 => 13,  46 => 7,  41 => 6,  38 => 5,  30 => 3,);
    }
}

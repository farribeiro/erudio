<?php

/* IntranetBundle:Cadastro:consultaCadastro.html.twig */
class __TwigTemplate_562fdba8936e8c06c88252b35c868d95603f4ab9046c40d7553c8b5df6a62f72 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("IntranetBundle:Index:servidor.html.twig");

        $this->blocks = array(
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "IntranetBundle:Index:servidor.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Meu Perfil";
    }

    // line 5
    public function block_body($context, array $blocks = array())
    {
        // line 6
        echo "    <form method=\"post\" action=\"";
        echo $this->env->getExtension('routing')->getUrl("intranet_atualizarCadastro");
        echo "\">
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label>Nome: </label>
                <span class=\"form-control\"> ";
        // line 10
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "nome"), "html", null, true);
        echo " </span>
            </div>
            <div class=\"col-lg-6\">
                <label>CPF: </label>
                <span class=\"form-control\"> ";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('commons_extension')->cpfFilter($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "cpfCnpj")), "html", null, true);
        echo " </span>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label>Data de Nascimento: </label>
                <span class=\"form-control\">";
        // line 20
        echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "dataNascimento"), "d/m/Y"), "html", null, true);
        echo "</span>
            </div>
            <div class=\"col-lg-6\">
                <label>RG:</label>
                <span class=\"form-control\">";
        // line 24
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "numeroRg"), "html", null, true);
        echo " / ";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "orgaoExpedidorRg"), "html", null, true);
        echo "</span>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label>Estado Civil: </label>
                <span class=\"form-control\"> ";
        // line 30
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "estadoCivil"), "nome"), "html", null, true);
        echo " </span>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"email\">E-mail: </label>
                <input id=\"email\" name=\"email\" type=\"email\" value=\"";
        // line 34
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "email"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"telefone\">Telefone: </label>
                <input id=\"telefone\" name=\"telefone\" type=\"text\" placeholder=\"DDD + Número\" value=\"";
        // line 40
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "telefone"), "numero"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"celular\">Celular: </label>
                <input id=\"celular\" name=\"celular\" type=\"text\" placeholder=\"DDD + Número\" value=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "celular"), "numero"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"logradouro\">Rua: </label>
                <input id=\"logradouro\" name=\"logradouro\" type=\"text\" value=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "logradouro"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"numero\">Número: </label>
                <input id=\"numero\" name=\"numero\" type=\"text\" value=\"";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "numero"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"complemento\">Complemento: </label>
                <input id=\"complemento\" name=\"complemento\" type=\"text\" value=\"";
        // line 60
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "complemento"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"bairro\">Bairro: </label>
                <input id=\"bairro\" name=\"bairro\" type=\"text\" value=\"";
        // line 64
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "bairro"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-lg-6\">
                <label for=\"cep\">CEP: </label>
                <input id=\"cep\" name=\"cep\" type=\"text\" value=\"";
        // line 70
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "cep"), "html", null, true);
        echo "\" class=\"form-control\"/>
            </div>
            <div class=\"col-lg-6\">
                <label for=\"cidade\">Cidade: </label>
                <select id=\"cidade\" name=\"cidade\" type=\"text\" class=\"form-control\">
                    ";
        // line 75
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["cidades"]) ? $context["cidades"] : $this->getContext($context, "cidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["cidade"]) {
            // line 76
            echo "                        ";
            if (($this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "id") == $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["pessoa"]) ? $context["pessoa"] : $this->getContext($context, "pessoa")), "endereco"), "cidade"), "id"))) {
                // line 77
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "id"), "html", null, true);
                echo "\" selected>";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "nome"), "html", null, true);
                echo "</option>
                        ";
            } else {
                // line 79
                echo "                            <option value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "id"), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["cidade"]) ? $context["cidade"] : $this->getContext($context, "cidade")), "nome"), "html", null, true);
                echo "</option>
                        ";
            }
            // line 81
            echo "                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['cidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 82
        echo "                </select>
            </div>
        </div>
        <div class=\"row\">
        \t<div class=\"col-lg-6\">
        \t\t<label for=\"password\">Modificar Senha: </label>
        \t\t<input id=\"password\" name=\"password\" type=\"password\" value=\"\" class=\"form-control\"/>
        \t</div>
        \t<div class=\"col-lg-6\">
        \t\t<label for=\"password\">Repetir Senha: </label>
        \t\t<input id=\"repeat_password\" name=\"repeat_password\" type=\"password\" value=\"\" class=\"form-control\"/>
        \t</div>
        </div>
        <br />
                <p class=\"alert alert-warning\">Atenção: Somente informações para contato podem ser alteradas. Para alterar outros dados, contate a Diretoria de Gestão de Pessoas.</p>
        <div class=\"row\">
            <div class=\"col-lg-12\">
                <button id=\"btnAtualizar\" type=\"submit\" class=\"btn btn-primary\">Atualizar</button>
            </div>
        </div>
    </form>
    
";
    }

    // line 106
    public function block_javascript($context, array $blocks = array())
    {
        // line 107
        echo "\t";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
\t<script type=\"text/javascript\" src=\"";
        // line 108
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("lib/jquery/validatePassword.js"), "html", null, true);
        echo "\"></script>\t
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Cadastro:consultaCadastro.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  216 => 108,  211 => 107,  208 => 106,  182 => 82,  176 => 81,  168 => 79,  160 => 77,  157 => 76,  153 => 75,  145 => 70,  136 => 64,  129 => 60,  120 => 54,  113 => 50,  104 => 44,  97 => 40,  88 => 34,  81 => 30,  70 => 24,  63 => 20,  54 => 14,  47 => 10,  39 => 6,  36 => 5,  30 => 3,);
    }
}

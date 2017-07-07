<?php

/* IntranetBundle:Acesso:firstAccess.html.twig */
class __TwigTemplate_960ae7e31c6ccf6e81fa2fad29daa72429f57562fe7e1770033bc7ef08e87fde extends Twig_Template
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
        echo "<div class=\"container paddingTop\">
    <div class=\"row\">
        <div class=\"col-lg-6\" style=\"text-align: justify;\">
            Se você é servidor da rede e esta é a sua primeira vez na Intranet, você provavelmente ainda não possui um usuário.<br />
            Para criar um e ter acesso a todos os recursos, insira seu CPF (que será seu login), e uma senha com no mínimo 6 caracteres. Após a mensagem de que seu usuário foi criado, faça seu acesso na parte superior da página.
            <br /><br />
            Se você <strong>tentou acessar</strong> e recebeu uma mensagem de <strong>senha incorreta</strong>, isso quer dizer que você já tem um usuário, mas talvez tenha esquecido sua senha. Neste caso, clique em \"solicitar nova senha\" abaixo do botão \"Entrar\", e siga as instruções que serão enviadas ao seu e-mail.
            <br /><br />
        </div>

        <div class=\"col-lg-6\" style=\"text-align: justify;\">
            <form id=\"formCreateUser\">
                <div class=\"input-group\">
                    <span class=\"input-group-addon\">CPF:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu CPF\" type=\"text\" name=\"newUsername\" id=\"newUsername\" /><br />
                </div>
                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Senha:</span>
                    <input class=\"form-control\" placeholder=\"Digite sua senha\" type=\"password\" name=\"newPassword\" id=\"newPassword\" /><br />
                </div>
                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Repita sua senha:</span>
                    <input class=\"form-control\" placeholder=\"Repita sua senha\" type=\"password\" name=\"new_repeat_password\" id=\"new_repeat_password\" /><br />
                </div>
                <button label=\"";
        // line 25
        echo $this->env->getExtension('routing')->getPath("intranet_create_user");
        echo "\" style=\"margin-top: 5px; margin-bottom: 15px;\" class=\"btn btn-primary\" id=\"btnCreateUser\" type=\"submit\" >Criar usuário</button>
            </form>
        </div>
    </div>
</div>";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Acesso:firstAccess.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,  138 => 32,  129 => 30,  124 => 29,  115 => 27,  111 => 26,  106 => 24,  102 => 23,  98 => 22,  93 => 21,  90 => 20,  85 => 18,  79 => 12,  75 => 11,  71 => 10,  66 => 9,  63 => 8,  57 => 4,  52 => 43,  47 => 19,  45 => 25,  37 => 14,  35 => 8,  28 => 4,  23 => 1,  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 157,  286 => 137,  277 => 135,  273 => 134,  268 => 132,  264 => 131,  260 => 130,  256 => 129,  252 => 128,  248 => 127,  244 => 126,  240 => 125,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 100,  187 => 99,  183 => 98,  176 => 94,  161 => 82,  155 => 79,  149 => 76,  143 => 73,  137 => 70,  131 => 67,  86 => 25,  81 => 23,  72 => 17,  68 => 16,  62 => 13,  59 => 12,  56 => 11,  50 => 20,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

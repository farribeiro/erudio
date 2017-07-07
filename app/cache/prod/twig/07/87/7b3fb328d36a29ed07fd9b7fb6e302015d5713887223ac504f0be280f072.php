<?php

/* PublicBundle:RequerimentoExterno:requerimentosExternos.html.twig */
class __TwigTemplate_07877b3fb328d36a29ed07fd9b7fb6e302015d5713887223ac504f0be280f072 extends Twig_Template
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
        echo "<div class=\"container\">
    <div style=\"text-align: justify;\">
        Os requerimentos listados aqui são destinados <strong>exclusivamente</strong> à <strong>servidores já desligados</strong> da rede municipal.
        <br /> Se você é um servidor ativo, deve acessar com seu usuário.
   </div>
    <div class=\"row\">
        <div class=\"col-md-12\">
            <p class=\"title_url\" id=\"";
        // line 8
        echo $this->env->getExtension('routing')->getPath("protocolo_api_getSolicitacoes");
        echo "\" >Qual é a sua solicitação?</p>
            <form id=\"chooseForm\"></form>
        </div>
        <hr />
        <div class=\"col-md-12 form-requerimento divSimples\" label=\"";
        // line 12
        echo $this->env->getExtension('routing')->getPath("protocolo_api_getDocumento", array("protocolo" => "00"));
        echo "\" id=\"26\">
            <h2>Requerimento Simples</h2>
            <form id=\"formRSimples\" action=\"";
        // line 14
        echo $this->env->getExtension('routing')->getPath("protocolo_api_postProtocolo", array("solicitacao" => "26"));
        echo "\" method=\"POST\">
                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Nome:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu Nome\" type=\"text\" name=\"nome\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">CPF:</span>
                    <input class=\"form-control cpf\" placeholder=\"Digite o seu CPF\" type=\"text\" name=\"cpf\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">RG:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu RG\" type=\"text\" name=\"rg\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Email:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu Email\" type=\"text\" name=\"email\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Telefone:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu Telefone\" type=\"text\" name=\"fone\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Celular:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu Celular\" type=\"text\" name=\"celular\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Pedido:</span>
                    <input class=\"form-control\" placeholder=\"Digite o seu Pedido\" type=\"text\" name=\"pedido\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Motivo:</span>
                    <textarea class=\"form-control\" placeholder=\"Digite o seu Motivo\" name=\"motivo\" /></textarea> <br />
                </div>

                <button class=\"btn btn-primary btnRSimples\">Gerar Requerimento</button>
            </form>
        </div>
        <div class=\"col-md-12 form-requerimento divTempo\" label=\"";
        // line 58
        echo $this->env->getExtension('routing')->getPath("protocolo_api_getDocumento", array("protocolo" => "00"));
        echo "\" id=\"24\">
            <h2>Requerimento por Tempo de Serviço</h2>
            <form id=\"formRTempo\" action=\"";
        // line 60
        echo $this->env->getExtension('routing')->getPath("protocolo_api_postProtocolo", array("solicitacao" => "24"));
        echo "\" method=\"POST\">
                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Nome:</span>
                    <input class=\"form-control nomeTempo\" placeholder=\"Digite o seu Nome\" type=\"text\" name=\"nome\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">CPF:</span>
                    <input class=\"form-control cpf cpfTempo\" placeholder=\"Digite o seu CPF\" type=\"text\" name=\"cpf\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">RG:</span>
                    <input class=\"form-control rgTempo\" placeholder=\"Digite o seu RG\" type=\"text\" name=\"rg\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Email:</span>
                    <input class=\"form-control emailTempo\" placeholder=\"Digite o seu Email\" type=\"text\" name=\"email\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Telefone:</span>
                    <input class=\"form-control foneTempo\" placeholder=\"Digite o seu Telefone\" type=\"text\" name=\"telefone\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Celular:</span>
                    <input class=\"form-control celularTempo\" placeholder=\"Digite o seu Celular\" type=\"text\" name=\"celular\" /><br />
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Ano em que começou a trabalhar na rede:</span>
                    <select class=\"form-control anoInicioTempo\" name=\"anoInicio\">
                        <option value=\"Antes de 1970\">Antes de 1970</option>
                        <option value=\"Entre 1970 e 1975\">Entre 1970 e 1975</option>
                        <option value=\"Entre 1975 e 1980\">Entre 1975 e 1980</option>
                        <option value=\"Entre 1980 e 1985\">Entre 1980 e 1985</option>
                        <option value=\"Entre 1985 e 1990\">Entre 1985 e 1990</option>
                        <option value=\"Entre 1990 e 1995\">Entre 1990 e 1995</option>
                        <option value=\"Entre 1995 e 2000\">Entre 1995 e 2000</option>
                        <option value=\"Entre 2000 e 2005\">Entre 2000 e 2005</option>
                        <option value=\"Após 2005\">Após 2005</option>
                    </select>
                </div>

                <div class=\"input-group\">
                    <span class=\"input-group-addon\">Finalidade:</span>
                    <select class=\"form-control objetivoTempo\" name=\"objetivo\" id=\"objective\">
                        <option value=\"Simulação de Aposentadoria\">Simulação de Aposentadoria</option>
                        <option value=\"Processo Seletivo\">Processo Seletivo</option>
                        <option value=\"Concurso Público\">Concurso Público</option>
                        <option value=\"\">Outro</option>
                    </select>
                </div>

                <div class=\"input-group outro\">
                    <span class=\"input-group-addon\">Outro:</span>
                    <input class=\"form-control outroTempo\" placeholder=\"Digite a finalidade do requerimento\" type=\"text\" name=\"outro\" /><br />
                </div>

                <button class=\"btn btn-primary btnRTempo\">Gerar Requerimento</button>
            </form>
        </div>
    </div>     
    <br /><br />
</div>";
    }

    public function getTemplateName()
    {
        return "PublicBundle:RequerimentoExterno:requerimentosExternos.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 60,  87 => 58,  40 => 14,  1361 => 391,  1352 => 390,  1350 => 389,  1347 => 388,  1331 => 384,  1324 => 383,  1322 => 382,  1319 => 381,  1296 => 377,  1271 => 376,  1269 => 375,  1266 => 374,  1254 => 369,  1249 => 368,  1247 => 367,  1244 => 366,  1235 => 360,  1229 => 358,  1226 => 357,  1221 => 356,  1219 => 355,  1216 => 354,  1209 => 349,  1200 => 347,  1196 => 346,  1193 => 345,  1190 => 344,  1188 => 343,  1185 => 342,  1177 => 338,  1175 => 337,  1172 => 336,  1166 => 332,  1160 => 330,  1157 => 329,  1155 => 328,  1152 => 327,  1143 => 322,  1141 => 321,  1118 => 320,  1115 => 319,  1112 => 318,  1109 => 317,  1106 => 316,  1103 => 315,  1100 => 314,  1098 => 313,  1095 => 312,  1088 => 308,  1084 => 307,  1079 => 306,  1077 => 305,  1074 => 304,  1067 => 299,  1064 => 298,  1056 => 293,  1053 => 292,  1051 => 291,  1048 => 290,  1040 => 285,  1036 => 284,  1032 => 283,  1029 => 282,  1027 => 281,  1024 => 280,  1016 => 276,  1014 => 272,  1012 => 271,  1009 => 270,  1004 => 266,  982 => 261,  979 => 260,  976 => 259,  973 => 258,  970 => 257,  967 => 256,  964 => 255,  961 => 254,  958 => 253,  955 => 252,  952 => 251,  950 => 250,  947 => 249,  939 => 243,  936 => 242,  934 => 241,  931 => 240,  923 => 236,  920 => 235,  918 => 234,  915 => 233,  903 => 229,  900 => 228,  897 => 227,  894 => 226,  892 => 225,  889 => 224,  881 => 220,  878 => 219,  876 => 218,  873 => 217,  865 => 213,  862 => 212,  860 => 211,  857 => 210,  849 => 206,  846 => 205,  844 => 204,  841 => 203,  833 => 199,  830 => 198,  828 => 197,  825 => 196,  817 => 192,  814 => 191,  812 => 190,  809 => 189,  801 => 185,  798 => 184,  796 => 183,  793 => 182,  785 => 178,  783 => 177,  780 => 176,  772 => 172,  769 => 171,  767 => 170,  764 => 169,  756 => 165,  753 => 164,  751 => 163,  749 => 162,  746 => 161,  739 => 156,  729 => 155,  724 => 154,  721 => 153,  715 => 151,  712 => 150,  710 => 149,  707 => 148,  699 => 142,  697 => 141,  696 => 140,  695 => 139,  694 => 138,  689 => 137,  683 => 135,  680 => 134,  678 => 133,  675 => 132,  666 => 126,  662 => 125,  658 => 124,  654 => 123,  649 => 122,  643 => 120,  640 => 119,  638 => 118,  635 => 117,  619 => 113,  617 => 112,  614 => 111,  598 => 107,  596 => 106,  593 => 105,  576 => 101,  564 => 99,  557 => 96,  555 => 95,  550 => 94,  547 => 93,  529 => 92,  527 => 91,  524 => 90,  515 => 85,  512 => 84,  509 => 83,  503 => 81,  501 => 80,  496 => 79,  493 => 78,  490 => 77,  480 => 75,  478 => 74,  470 => 73,  467 => 72,  464 => 71,  461 => 70,  459 => 69,  456 => 68,  450 => 64,  442 => 62,  437 => 61,  433 => 60,  428 => 59,  426 => 58,  423 => 57,  414 => 52,  408 => 50,  405 => 49,  403 => 48,  400 => 47,  390 => 43,  388 => 42,  385 => 41,  377 => 37,  374 => 36,  371 => 35,  368 => 34,  366 => 33,  363 => 32,  355 => 27,  350 => 26,  344 => 24,  342 => 23,  337 => 22,  335 => 21,  332 => 20,  316 => 16,  313 => 15,  311 => 14,  299 => 8,  293 => 6,  290 => 5,  288 => 4,  285 => 3,  281 => 388,  278 => 387,  276 => 381,  271 => 374,  266 => 366,  263 => 365,  258 => 354,  255 => 353,  253 => 342,  250 => 341,  245 => 335,  243 => 327,  238 => 312,  235 => 311,  233 => 304,  230 => 303,  227 => 301,  225 => 298,  222 => 297,  220 => 290,  217 => 289,  215 => 280,  212 => 279,  210 => 270,  207 => 269,  204 => 267,  202 => 266,  199 => 265,  197 => 249,  194 => 248,  189 => 240,  186 => 239,  184 => 233,  181 => 232,  179 => 224,  174 => 217,  171 => 216,  169 => 210,  166 => 209,  164 => 203,  159 => 196,  156 => 195,  154 => 189,  151 => 188,  146 => 181,  144 => 176,  141 => 175,  139 => 169,  136 => 168,  134 => 161,  126 => 147,  121 => 131,  116 => 116,  114 => 111,  109 => 105,  104 => 90,  101 => 89,  96 => 67,  94 => 57,  91 => 56,  84 => 41,  76 => 31,  69 => 13,  64 => 3,  61 => 2,  125 => 57,  119 => 117,  112 => 51,  107 => 48,  99 => 68,  95 => 41,  89 => 47,  78 => 36,  74 => 20,  60 => 23,  54 => 21,  41 => 12,  32 => 9,  25 => 4,  21 => 2,  24 => 2,  19 => 1,  138 => 32,  129 => 148,  124 => 132,  115 => 27,  111 => 110,  106 => 104,  102 => 23,  98 => 22,  93 => 40,  90 => 20,  85 => 38,  79 => 32,  75 => 11,  71 => 19,  66 => 12,  63 => 8,  57 => 4,  52 => 20,  47 => 19,  45 => 25,  37 => 11,  35 => 12,  28 => 8,  23 => 1,  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 13,  286 => 137,  277 => 135,  273 => 380,  268 => 373,  264 => 131,  260 => 363,  256 => 129,  252 => 128,  248 => 336,  244 => 126,  240 => 326,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 246,  187 => 99,  183 => 98,  176 => 223,  161 => 202,  155 => 79,  149 => 182,  143 => 73,  137 => 70,  131 => 160,  86 => 46,  81 => 40,  72 => 17,  68 => 16,  62 => 13,  59 => 12,  56 => 11,  50 => 20,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

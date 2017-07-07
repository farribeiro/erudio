<?php

/* EstagioBundle:Public:lista.html.twig */
class __TwigTemplate_7c8ce3ebe59558b50decf700925329bf6a96a147cfc1a68e939b3d55471be8b7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("::paginaExterna.html.twig");

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "::paginaExterna.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_css($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
";
    }

    // line 7
    public function block_body($context, array $blocks = array())
    {
        // line 8
        echo "    <div class=\"row vagas\" style=\"margin: 1rem;\">
        
        <div class=\"col-md-12\">
            <button style=\"display:none;\" class=\"btn btn-primary vagasBtn\">Consulta de vagas de estágio</button> <button class=\"btn btn-primary estagioBtn\">Consulta de inscrição</button>
        </div>
        
        <div class=\"col-md-12 vagasBox\">
            <h1>Vagas de Estágio</h1>
            
            <div class=\"col-md-12\">
                <small>Serão concedidos espaço nas Unidades de Ensino da Rede Municipal de Ensino para a realização de estágios obrigatórios supervisionados das instituições de ensino profissionalizante e ensino superior, devidamente credenciadas pelo Ministério da Educação - MEC, que firmarem com o Município de Itajaí, por intermédio da Secretaria Municipal de Educação.</small>
            </div>
            
            <div class=\"col-md-6\">
                <label>Unidade:</label>
                <select id=\"unidade_vagas\" name=\"unidade_vagas\" class=\"form-control\">
                    <option></option>
                    ";
        // line 25
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["entidades"]) ? $context["entidades"] : $this->getContext($context, "entidades")));
        foreach ($context['_seq'] as $context["_key"] => $context["entidade"]) {
            // line 26
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["entidade"]) ? $context["entidade"] : $this->getContext($context, "entidade")), "pessoaJuridica"), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['entidade'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        echo "                </select>
            </div>
                
            <div class=\"col-md-6\">
                <label>Turno:</label>
                <select id=\"turno_vagas\" name=\"turno_vagas\" class=\"form-control\">
                    <option></option>
                    ";
        // line 35
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["turnos"]) ? $context["turnos"] : $this->getContext($context, "turnos")));
        foreach ($context['_seq'] as $context["_key"] => $context["turno"]) {
            // line 36
            echo "                        <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "id"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["turno"]) ? $context["turno"] : $this->getContext($context, "turno")), "nome"), "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['turno'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 38
        echo "                </select>
            </div>
                
            <div class=\"vagas_box\"></div>
        </div>
                
        <div style=\"display:none;\" class=\"col-md-12 consultaBox\">
            <h1>Consulta de Estágio</h1>

            <div class=\"col-md-12\">
                <small>Digite seu nome para verificar o status da inscrição à vaga de estágio.</small>
            </div>

            <div class=\"col-md-6\">
                <label>Nome:</label>
                <input type=\"text\" class=\"form-control\" name=\"nome\" id=\"nome\" />
            </div>
            <div class=\"col-md-6\">
                <button style=\"margin-top: 2.4rem;\" class=\"buscaNomeEstagio btn btn-primary\">Buscar</button>
            </div>

            <div class=\"vagas_box\"></div>
        </div>
    </div>
";
    }

    // line 64
    public function block_javascript($context, array $blocks = array())
    {
        // line 65
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$(document).ready(function(){
            \$(\".vagasBtn\").click(function (){
                \$(\".estagioBtn\").show();
                \$('.consultaBox').hide();
                \$('.vagasBox').show();
                \$(this).hide();
                \$('.vagas_box').html('');
            });
            
            \$('.estagioBtn').click(function (){
                \$(\".vagasBtn\").show();
                \$('.consultaBox').show();
                \$('.vagasBox').hide();
                \$(this).hide();
                \$('.vagas_box').html('');
            });
            
            \$(\"#unidade_vagas, #turno_vagas\").change(function(){
                var unidade = \$(\"#unidade_vagas\").val();
                var turno = \$(\"#turno_vagas\").val();
                if (unidade !== '' || turno !== '') {
                    \$.ajax({
                        url: '";
        // line 89
        echo $this->env->getExtension('routing')->getPath("public_vaga_estagio");
        echo "',
                        dataType: 'html',
                        type: 'POST',
                        data: { 'unidade_vagas': unidade, 'turno_vagas': turno },
                        success: function (data) {
                            \$('.vagas_box').html(data);
                        }
                    });
                }
            });
            
            \$(\".buscaNomeEstagio\").click(function(){
                var nome = \$(\"#nome\").val();
                if (nome !== '') {
                    \$.ajax({
                        url: '";
        // line 104
        echo $this->env->getExtension('routing')->getPath("public_consulta_estagio");
        echo "',
                        dataType: 'html',
                        type: 'POST',
                        data: { 'nome': nome },
                        success: function (data) {
                            \$('.vagas_box').html(data);
                        }
                    });
                }
            });
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "EstagioBundle:Public:lista.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  178 => 104,  160 => 89,  132 => 65,  77 => 28,  43 => 8,  92 => 60,  87 => 58,  40 => 7,  1361 => 391,  1352 => 390,  1350 => 389,  1347 => 388,  1331 => 384,  1324 => 383,  1322 => 382,  1319 => 381,  1296 => 377,  1271 => 376,  1269 => 375,  1266 => 374,  1254 => 369,  1249 => 368,  1247 => 367,  1244 => 366,  1235 => 360,  1229 => 358,  1226 => 357,  1221 => 356,  1219 => 355,  1216 => 354,  1209 => 349,  1200 => 347,  1196 => 346,  1193 => 345,  1190 => 344,  1188 => 343,  1185 => 342,  1177 => 338,  1175 => 337,  1172 => 336,  1166 => 332,  1160 => 330,  1157 => 329,  1155 => 328,  1152 => 327,  1143 => 322,  1141 => 321,  1118 => 320,  1115 => 319,  1112 => 318,  1109 => 317,  1106 => 316,  1103 => 315,  1100 => 314,  1098 => 313,  1095 => 312,  1088 => 308,  1084 => 307,  1079 => 306,  1077 => 305,  1074 => 304,  1067 => 299,  1064 => 298,  1056 => 293,  1053 => 292,  1051 => 291,  1048 => 290,  1040 => 285,  1036 => 284,  1032 => 283,  1029 => 282,  1027 => 281,  1024 => 280,  1016 => 276,  1014 => 272,  1012 => 271,  1009 => 270,  1004 => 266,  982 => 261,  979 => 260,  976 => 259,  973 => 258,  970 => 257,  967 => 256,  964 => 255,  961 => 254,  958 => 253,  955 => 252,  952 => 251,  950 => 250,  947 => 249,  939 => 243,  936 => 242,  934 => 241,  931 => 240,  923 => 236,  920 => 235,  918 => 234,  915 => 233,  903 => 229,  900 => 228,  897 => 227,  894 => 226,  892 => 225,  889 => 224,  881 => 220,  878 => 219,  876 => 218,  873 => 217,  865 => 213,  862 => 212,  860 => 211,  857 => 210,  849 => 206,  846 => 205,  844 => 204,  841 => 203,  833 => 199,  830 => 198,  828 => 197,  825 => 196,  817 => 192,  814 => 191,  812 => 190,  809 => 189,  801 => 185,  798 => 184,  796 => 183,  793 => 182,  785 => 178,  783 => 177,  780 => 176,  772 => 172,  769 => 171,  767 => 170,  764 => 169,  756 => 165,  753 => 164,  751 => 163,  749 => 162,  746 => 161,  739 => 156,  729 => 155,  724 => 154,  721 => 153,  715 => 151,  712 => 150,  710 => 149,  707 => 148,  699 => 142,  697 => 141,  696 => 140,  695 => 139,  694 => 138,  689 => 137,  683 => 135,  680 => 134,  678 => 133,  675 => 132,  666 => 126,  662 => 125,  658 => 124,  654 => 123,  649 => 122,  643 => 120,  640 => 119,  638 => 118,  635 => 117,  619 => 113,  617 => 112,  614 => 111,  598 => 107,  596 => 106,  593 => 105,  576 => 101,  564 => 99,  557 => 96,  555 => 95,  550 => 94,  547 => 93,  529 => 92,  527 => 91,  524 => 90,  515 => 85,  512 => 84,  509 => 83,  503 => 81,  501 => 80,  496 => 79,  493 => 78,  490 => 77,  480 => 75,  478 => 74,  470 => 73,  467 => 72,  464 => 71,  461 => 70,  459 => 69,  456 => 68,  450 => 64,  442 => 62,  437 => 61,  433 => 60,  428 => 59,  426 => 58,  423 => 57,  414 => 52,  408 => 50,  405 => 49,  403 => 48,  400 => 47,  390 => 43,  388 => 42,  385 => 41,  377 => 37,  374 => 36,  371 => 35,  368 => 34,  366 => 33,  363 => 32,  355 => 27,  350 => 26,  344 => 24,  342 => 23,  337 => 22,  335 => 21,  332 => 20,  316 => 16,  313 => 15,  311 => 14,  299 => 8,  293 => 6,  290 => 5,  288 => 4,  285 => 3,  281 => 388,  278 => 387,  276 => 381,  271 => 374,  266 => 366,  263 => 365,  258 => 354,  255 => 353,  253 => 342,  250 => 341,  245 => 335,  243 => 327,  238 => 312,  235 => 311,  233 => 304,  230 => 303,  227 => 301,  225 => 298,  222 => 297,  220 => 290,  217 => 289,  215 => 280,  212 => 279,  210 => 270,  207 => 269,  204 => 267,  202 => 266,  199 => 265,  197 => 249,  194 => 248,  189 => 240,  186 => 239,  184 => 233,  181 => 232,  179 => 224,  174 => 217,  171 => 216,  169 => 210,  166 => 209,  164 => 203,  159 => 196,  156 => 195,  154 => 189,  151 => 188,  146 => 181,  144 => 176,  141 => 175,  139 => 169,  136 => 168,  134 => 161,  126 => 147,  121 => 131,  116 => 116,  114 => 111,  109 => 105,  104 => 90,  101 => 38,  96 => 67,  94 => 57,  91 => 56,  84 => 41,  76 => 31,  69 => 13,  64 => 3,  61 => 2,  125 => 57,  119 => 117,  112 => 51,  107 => 48,  99 => 68,  95 => 41,  89 => 47,  78 => 36,  74 => 20,  60 => 23,  54 => 21,  41 => 12,  32 => 9,  25 => 4,  21 => 2,  24 => 2,  19 => 1,  138 => 32,  129 => 64,  124 => 132,  115 => 27,  111 => 110,  106 => 104,  102 => 23,  98 => 22,  93 => 40,  90 => 36,  85 => 38,  79 => 32,  75 => 11,  71 => 19,  66 => 26,  63 => 8,  57 => 4,  52 => 20,  47 => 19,  45 => 25,  37 => 11,  35 => 12,  28 => 8,  23 => 1,  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 13,  286 => 137,  277 => 135,  273 => 380,  268 => 373,  264 => 131,  260 => 363,  256 => 129,  252 => 128,  248 => 336,  244 => 126,  240 => 326,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 246,  187 => 99,  183 => 98,  176 => 223,  161 => 202,  155 => 79,  149 => 182,  143 => 73,  137 => 70,  131 => 160,  86 => 35,  81 => 40,  72 => 17,  68 => 16,  62 => 25,  59 => 12,  56 => 11,  50 => 20,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

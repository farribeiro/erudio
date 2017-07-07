<?php

/* PublicBundle:Public:formacaoExterna.html.twig */
class __TwigTemplate_c11dc37a45315da055e6ea7776f9b49e267236870b85e87c0f788c596a1f5143 extends Twig_Template
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
        echo "<input type=\"hidden\" id=\"urlFormacoes\" value=\"";
        echo $this->env->getExtension('routing')->getPath("dgp_publico_getFormacoes");
        echo "\" />
<input type=\"hidden\" id=\"urlFormacao\" value=\"";
        // line 2
        echo $this->env->getExtension('routing')->getPath("dgp_publico_getFormacao", array("id" => 0));
        echo "\" />
<input type=\"hidden\" id=\"urlFormacaoExterna\" value=\"";
        // line 3
        echo $this->env->getExtension('routing')->getPath("formacao_externa_matricula", array("formacao" => 0));
        echo "\" />
<div class=\"container paddingTop\">        
    <div class=\"row\">
        <div class=\"col-lg-12\" style=\"text-align: justify;\">
            <p>Aqui você fica sabendo das formações continuadas abertas ao público que são ofertadas pela Secretaria Municipal de Educação.</p>
            <div id=\"formacoes\">
            </div>
        </div>
    </div>
</div>

<div id=\"modalDialog\" class=\"modal fade\" role=\"dialog\">
    <div id=\"divModal\" class=\"modal-dialog\" style=\"width: 80%;\"></div>
</div>

<div id=\"modalDialogForm\" class=\"modal fade\" role=\"dialog\">
    <div id=\"divModalForm\" class=\"modal-dialog\" style=\"width: 30%;\"></div>
</div>";
    }

    public function getTemplateName()
    {
        return "PublicBundle:Public:formacaoExterna.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 2,  19 => 1,  138 => 32,  129 => 30,  124 => 29,  115 => 27,  111 => 26,  106 => 24,  102 => 23,  98 => 22,  93 => 21,  90 => 20,  85 => 18,  79 => 12,  75 => 11,  71 => 10,  66 => 9,  63 => 8,  57 => 4,  52 => 43,  47 => 19,  45 => 25,  37 => 14,  35 => 8,  28 => 3,  23 => 1,  354 => 195,  351 => 194,  349 => 193,  314 => 160,  310 => 158,  308 => 157,  286 => 137,  277 => 135,  273 => 134,  268 => 132,  264 => 131,  260 => 130,  256 => 129,  252 => 128,  248 => 127,  244 => 126,  240 => 125,  236 => 124,  232 => 123,  228 => 122,  224 => 121,  219 => 120,  216 => 119,  208 => 114,  191 => 100,  187 => 99,  183 => 98,  176 => 94,  161 => 82,  155 => 79,  149 => 76,  143 => 73,  137 => 70,  131 => 67,  86 => 25,  81 => 23,  72 => 17,  68 => 16,  62 => 13,  59 => 12,  56 => 11,  50 => 20,  46 => 7,  42 => 6,  38 => 5,  33 => 4,  30 => 3,);
    }
}

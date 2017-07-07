<?php

/* IntranetBundle:Usuario:listaNotificacoes.html.twig */
class __TwigTemplate_95e2060fd7c5c5363530c9f3466ea988573eb3c34389750e68882e0d40e9bc76 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('body', $context, $blocks);
        // line 64
        echo "
";
        // line 65
        $this->displayBlock('javascript', $context, $blocks);
    }

    // line 1
    public function block_body($context, array $blocks = array())
    {
        // line 2
        echo "\t<div id=\"notifications_title\" style=\"padding-bottom: inherit;\">
\t\t<p class=\"nav_delete\">
\t\t\t";
        // line 4
        if (((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")) == false)) {
            // line 5
            echo "\t\t\t\t<span class=\"glyphicon glyphicon-trash deleteAll\" title=\"Excluir Tudo\" style=\"cursor: pointer;\"></span>
\t\t\t";
        }
        // line 7
        echo "\t\t</p>
\t\t<p class=\"nav_title\">Notificações</p>
\t\t<p class=\"nav_notify\">
\t\t\t";
        // line 10
        if (((isset($context["pagina"]) ? $context["pagina"] : $this->getContext($context, "pagina")) > 1)) {
            // line 11
            echo "\t\t\t\t<span class=\"glyphicon glyphicon-chevron-left prevNotify\" title=\"Página Anterior\" style=\"cursor: pointer;\"></span>
\t\t\t";
        }
        // line 13
        echo "\t\t\t";
        if ((isset($context["mostraProximo"]) ? $context["mostraProximo"] : $this->getContext($context, "mostraProximo"))) {
            // line 14
            echo "\t\t\t\t<span class=\"glyphicon glyphicon-chevron-right nextNotify\" title=\"Próxima Página\" style=\"cursor: pointer;\"></span>
\t\t\t";
        }
        // line 16
        echo "\t\t</p>
\t</div>
\t<div id=\"notifications\">
\t\t";
        // line 19
        if ((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error"))) {
            // line 20
            echo "\t\t\t<div id=\"no_notifications\">
\t\t\t\tVocê não tem<br/>notificações<br />por enquanto.<br/><br/>:)
\t\t\t</div>
\t\t";
        } else {
            // line 24
            echo "\t\t\t";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["notificacoes"]) ? $context["notificacoes"] : $this->getContext($context, "notificacoes")));
            foreach ($context['_seq'] as $context["_key"] => $context["notificacao"]) {
                // line 25
                echo "\t\t\t\t";
                if (($this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "tipo") == "success")) {
                    // line 26
                    echo "\t\t\t\t\t<div class=\"notification alert alert-success-custom\">
\t\t\t\t\t\t<div class=\"notification_icon\">
\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-ok\"></span>
\t\t\t\t\t\t</div>
\t\t\t\t";
                } elseif (($this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "tipo") == "danger")) {
                    // line 31
                    echo "\t\t\t\t\t<div class=\"notification alert alert-danger-custom\">
\t\t\t\t\t\t<div class=\"notification_icon\">
\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-remove\"></span>
\t\t\t\t\t\t</div>
\t\t\t\t";
                } elseif (($this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "tipo") == "warning")) {
                    // line 36
                    echo "\t\t\t\t\t<div class=\"notification alert alert-warning-custom\">
\t\t\t\t\t\t<div class=\"notification_icon\">
\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-warning-sign\"></span>
\t\t\t\t\t\t</div>
\t\t\t\t";
                } elseif (($this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "tipo") == "info")) {
                    // line 41
                    echo "\t\t\t\t\t<div class=\"notification alert alert-info-custom\">
\t\t\t\t\t\t<div class=\"notification_icon\">
\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-info-sign\"></span>
\t\t\t\t\t\t</div>
\t\t\t\t";
                } else {
                    // line 46
                    echo "\t\t\t\t\t<div class=\"notification\">
\t\t\t\t\t\t<div class=\"notification_icon\">
\t\t\t\t\t\t\t<span class=\"glyphicon glyphicon-home\"></span>
\t\t\t\t\t\t</div>
\t\t\t\t";
                }
                // line 51
                echo "\t\t\t\t\t
\t\t\t\t\t<div class=\"notification_text\">
\t\t\t\t\t\t";
                // line 53
                echo $this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "texto");
                echo "
\t\t\t\t\t</div>
\t\t\t\t\t
\t\t\t\t\t<div class=\"notification_actions\">
\t\t\t\t\t\t<span class=\"glyphicon glyphicon-trash delete_notify\" label=\"";
                // line 57
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["notificacao"]) ? $context["notificacao"] : $this->getContext($context, "notificacao")), "id"), "html", null, true);
                echo "\" style=\"cursor: pointer;\"></span>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['notificacao'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 61
            echo "\t\t";
        }
        // line 62
        echo "\t</div>
";
    }

    // line 65
    public function block_javascript($context, array $blocks = array())
    {
        // line 66
        echo "\t<script type=\"text/javascript\">
\t\t\$(document).ready(function (){\t\t\t
\t\t\t";
        // line 68
        if (((isset($context["error"]) ? $context["error"] : $this->getContext($context, "error")) == false)) {
            // line 69
            echo "\t\t\t\t\$('#notifications').jScrollPane({ mouseWheelSpeed: 20 });
\t\t\t";
        }
        // line 71
        echo "\t\t\t
\t\t\t\$(\".delete_notify\").click(function (){
\t\t\t\tvar notify_id = \$(this).attr('label');
\t\t\t\t\$.ajax({
\t\t\t\t\turl: '";
        // line 75
        echo $this->env->getExtension('routing')->getPath("intranet_excluir_notificacao");
        echo "',
\t\t\t\t\ttype: 'POST',
\t\t\t\t\tdata: { id: notify_id, user_id: ";
        // line 77
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"), "html", null, true);
        echo " },
\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t{
\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\$.ajax({
\t\t\t\t\t\t\t\turl: '";
        // line 82
        echo $this->env->getExtension('routing')->getPath("intranet_total_notificacoes");
        echo "',
\t\t\t\t\t\t\t\ttype: 'POST',
\t\t\t\t\t\t\t\tdata: { user_id: ";
        // line 84
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"), "html", null, true);
        echo " },
\t\t\t\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t\t\t\t{
\t\t\t\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\t\t\t\$(\".notification_total\").html(data);
\t\t\t\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t}
\t\t\t\t\t\t\t\t}
\t\t\t\t\t\t\t});
\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\$(\"#notification_area\").html(data);
\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t
\t\t\t\t\t\t}
\t\t\t\t\t}
\t\t\t\t});
\t\t\t});
\t\t\t
\t\t\t\$(\".deleteAll\").click(function (){
\t\t\t\tvar answer = confirm(\"Esta ação irá excluir todas notificações desta página. Prosseguir?\");
\t\t\t\tif (answer) 
\t\t\t\t{
\t\t\t\t\tvar ids = new Array();
\t\t\t\t\tvar cont = 0;
\t\t\t\t\t\$('.delete_notify').each(function (){
\t\t\t\t\t\tids[cont] = \$(this).attr('label');
\t\t\t\t\t\tcont = cont + 1;
\t\t\t\t\t});
\t\t\t\t\t
\t\t\t\t\t\$.ajax({
\t\t\t\t\t\turl: '";
        // line 115
        echo $this->env->getExtension('routing')->getPath("intranet_excluir_notificacoes");
        echo "',
\t\t\t\t\t\ttype: 'POST',
\t\t\t\t\t\tdata: { ids: JSON.stringify(ids), user_id: ";
        // line 117
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"), "html", null, true);
        echo " },
\t\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t\t{
\t\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\t\$.ajax({
\t\t\t\t\t\t\t\t\turl: '";
        // line 122
        echo $this->env->getExtension('routing')->getPath("intranet_total_notificacoes");
        echo "',
\t\t\t\t\t\t\t\t\ttype: 'POST',
\t\t\t\t\t\t\t\t\tdata: { user_id: ";
        // line 124
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"), "html", null, true);
        echo " },
\t\t\t\t\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t\t\t\t\t{
\t\t\t\t\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\t\t\t\t\$(\".notification_total\").html(data);
\t\t\t\t\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\t\t}
\t\t\t\t\t\t\t\t\t}
\t\t\t\t\t\t\t\t});
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t\t\$(\"#notification_area\").html(data);
\t\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t}
\t\t\t\t\t\t}
\t\t\t\t\t});
\t\t\t\t}
\t\t\t});
\t\t\t
\t\t\t";
        // line 144
        if ((isset($context["mostraProximo"]) ? $context["mostraProximo"] : $this->getContext($context, "mostraProximo"))) {
            // line 145
            echo "\t\t\t\t\$(\".nextNotify\").click(function (){
\t\t\t\t\tvar page = ";
            // line 146
            echo twig_escape_filter($this->env, ((isset($context["pagina"]) ? $context["pagina"] : $this->getContext($context, "pagina")) + 1), "html", null, true);
            echo ";
\t\t\t\t\t\$.ajax({
\t\t\t\t\t\turl: '";
            // line 148
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("intranet_buscar_notificacoes", array("id" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"))), "html", null, true);
            echo "',
\t\t\t\t\t\ttype: 'POST',
\t\t\t\t\t\tdata: { page: page },
\t\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t\t{
\t\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\t\$(\"#notification_area\").html(data);
\t\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t}
\t\t\t\t\t\t}
\t\t\t\t\t});
\t\t\t\t});
\t\t\t";
        }
        // line 162
        echo "\t\t\t
\t\t\t";
        // line 163
        if (((isset($context["pagina"]) ? $context["pagina"] : $this->getContext($context, "pagina")) > 1)) {
            // line 164
            echo "\t\t\t\t\$(\".prevNotify\").click(function (){
\t\t\t\t\tvar page = ";
            // line 165
            echo twig_escape_filter($this->env, ((isset($context["pagina"]) ? $context["pagina"] : $this->getContext($context, "pagina")) - 1), "html", null, true);
            echo ";
\t\t\t\t\t\$.ajax({
\t\t\t\t\t\turl: '";
            // line 167
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("intranet_buscar_notificacoes", array("id" => $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "user"), "id"))), "html", null, true);
            echo "',
\t\t\t\t\t\ttype: 'POST',
\t\t\t\t\t\tdata: { page: page },
\t\t\t\t\t\tsuccess: function (data)
\t\t\t\t\t\t{
\t\t\t\t\t\t\tif (data != \"error\") {
\t\t\t\t\t\t\t\t\$(\"#notification_area\").html(data);
\t\t\t\t\t\t\t} else {
\t\t\t\t\t\t\t\t
\t\t\t\t\t\t\t}
\t\t\t\t\t\t}
\t\t\t\t\t});
\t\t\t\t});
\t\t\t";
        }
        // line 181
        echo "\t\t});
\t</script>
";
    }

    public function getTemplateName()
    {
        return "IntranetBundle:Usuario:listaNotificacoes.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  315 => 181,  298 => 167,  293 => 165,  290 => 164,  288 => 163,  285 => 162,  268 => 148,  263 => 146,  260 => 145,  258 => 144,  235 => 124,  230 => 122,  222 => 117,  217 => 115,  183 => 84,  178 => 82,  170 => 77,  165 => 75,  159 => 71,  155 => 69,  153 => 68,  149 => 66,  146 => 65,  141 => 62,  138 => 61,  128 => 57,  121 => 53,  117 => 51,  110 => 46,  103 => 41,  96 => 36,  89 => 31,  82 => 26,  79 => 25,  74 => 24,  68 => 20,  66 => 19,  61 => 16,  57 => 14,  54 => 13,  50 => 11,  48 => 10,  43 => 7,  39 => 5,  37 => 4,  33 => 2,  30 => 1,  26 => 65,  23 => 64,  21 => 1,);
    }
}

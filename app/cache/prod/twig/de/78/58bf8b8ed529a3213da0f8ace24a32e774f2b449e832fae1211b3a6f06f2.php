<?php

/* PublicBundle:Public:consultaFilaUnica.html.twig */
class __TwigTemplate_de7858bf8b8ed529a3213da0f8ace24a32e774f2b449e832fae1211b3a6f06f2 extends Twig_Template
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
        echo "<input type=\"hidden\" id=\"urlFilaUnica\" value=\"";
        echo $this->env->getExtension('routing')->getPath("consulta_fila_unica");
        echo "\" />
<div class=\"container paddingTop\">        
    <div class=\"col-lg-6\" style=\"text-align: justify;\">
        Digite no campo ao lado o número do protocolo para verificar sua posição na chamada do Fila Única.
   </div>
   <div class=\"col-lg-6\" style=\"text-align: justify;\">
        <form class=\"form-signin\">
            <div class=\"form-group\">
                <label for=\"protocolo\">Protocolo</label>
                <input type=\"text\" class=\"form-control\" placeholder=\"Digite seu número de protocolo\" name=\"protocolo\" id=\"protocolo\" />
            </div>
            <div class=\"form-group\">
                <button class=\"btn btn-lg btn-primary btn-block searchFila\" type=\"submit\">Buscar</button>
            </div>
        </form>
   </div>
   <div class=\"col-lg-12\" style=\"text-align: justify;\">
       <div id=\"results\" style=\" float: left; width: 100%;\">
            <table class=\"fu_table\">
                <tbody>
                    <tr><td class=\"fu_title\">Status:</td><td class=\"status fu_text\"></td></tr>
                    <tr><td class=\"fu_title\">Zoneamento:</td><td class=\"zone fu_text\"></td></tr>
                    <tr><td class=\"fu_title\">Turma:</td><td class=\"room fu_text\"></td></tr>
                    <tr><td class=\"fu_title\">Data da Inscrição:</td><td class=\"startDate fu_text\"></td></tr>
                    <tr>
                        <td colspan=2>
                            <small>* Caso o status conste como \"Em Reserva\", a inscrição entrará na fila na próxima reclassificação (realizadas nos dias 1º dos meses abril, agosto e dezembro); ou se todas as inscrições atualmente na fila sejam chamadas e existam vagas remanescentes.</small>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class=\"table table-stripped table-hover\">
                <thead>
                    <tr>
                        <th>Classificação</th>
                        <th>Protocolo</th>
                        <th>Data de Cadastro</th>
                    </tr>
                </thead>
                <tbody class=\"ajax-itens\">

                </tbody>
            </table>
        </div>
   </div>
</div>";
    }

    public function getTemplateName()
    {
        return "PublicBundle:Public:consultaFilaUnica.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}

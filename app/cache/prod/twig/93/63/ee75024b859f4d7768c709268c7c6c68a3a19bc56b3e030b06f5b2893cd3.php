<?php

/* PresencaBundle:Index:index.html.twig */
class __TwigTemplate_9363ee75024b859f4d7768c709268c7c6c68a3a19bc56b3e030b06f5b2893cd3 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'css' => array($this, 'block_css'),
            'headerTitle' => array($this, 'block_headerTitle'),
            'body' => array($this, 'block_body'),
            'javascript' => array($this, 'block_javascript'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate((($this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request"), "isXmlHttpRequest")) ? ("::templateAjax.html.twig") : ("::template.html.twig")));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
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
    public function block_headerTitle($context, array $blocks = array())
    {
        echo "Controle de Presença";
    }

    // line 9
    public function block_body($context, array $blocks = array())
    {
        // line 10
        echo "    <div class=\"row\" id=\"formErrors\">
        ";
        // line 11
        echo (isset($context["erros"]) ? $context["erros"] : $this->getContext($context, "erros"));
        echo "
    </div>
    <div class=\"row\">
        ";
        // line 14
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
        <div class=\"col md-12\">
            <div class=\"col-md-6\">
                <label for=\"PresencaForm_dataCadastro\">Data:*</label>
                ";
        // line 18
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "dataCadastro"), 'widget');
        echo "
            </div>
            <div class=\"col-md-6\">
                <label for=\"PresencaForm_turma\">Turma:*</label>
                ";
        // line 22
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "turma"), 'widget');
        echo "
            </div>
            <div class=\"col-md-6\">
                <label for=\"PresencaForm_periodo\">Período:*</label>
                ";
        // line 26
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "turno"), 'widget');
        echo "
            </div>
            <div class=\"col-md-6\">
                <label for=\"PresencaForm_qtdeAlunos\">Quantidade de Alunos:*</label>
                ";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "qtdeAlunos"), 'widget');
        echo "
            </div>
            <div class=\"col-md-6\">
                ";
        // line 33
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "Salvar"), 'widget');
        echo "
            </div>            
        </div>
        ";
        // line 36
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
        echo "
    </div>
    <hr />
    
    <h3>Presenças cadastradas no mês.</h3>
    
    <div class=\"row\">
        ";
        // line 43
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), 'form_start');
        echo "
        <div class=\"col md-12\">
            <div class=\"col-md-3\">
                <label for=\"PresencaRelatorioForm_turma\">Turma:</label>
                ";
        // line 47
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), "turma"), 'widget');
        echo "
            </div>
            <div class=\"col-md-3\">
                <label for=\"PresencaRelatorioForm_periodo\">Período:</label>
                ";
        // line 51
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), "turno"), 'widget');
        echo "
            </div>
            <div class=\"col-md-2\">
                <label for=\"PresencaRelatorioForm_dataInicial\">Data Inicial:</label>
                ";
        // line 55
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), "dataInicial"), 'widget');
        echo "
            </div>
            <div class=\"col-md-2\">
                <label for=\"PresencaRelatorioForm_dataFinal\">Data Final:</label>
                ";
        // line 59
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), "dataFinal"), 'widget');
        echo "
            </div>
            <div class=\"col-md-2\">
                ";
        // line 62
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), "Buscar"), 'widget');
        echo "
            </div>            
        </div>
        ";
        // line 65
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["formRelatorio"]) ? $context["formRelatorio"] : $this->getContext($context, "formRelatorio")), 'form_end');
        echo "
    </div>
    
    <hr />
    
    <table class=\"table table-striped table-hover tableDGP\">
        <thead>
            <tr>
                <th>Data</th>
                <th>Turma</th>
                <th>Turno</th>
                <th>Quantidade de Alunos</th>
                <td>Remover</td>
            </tr>
        </thead>
        <tbody>
            ";
        // line 81
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["presencas"]) ? $context["presencas"] : $this->getContext($context, "presencas")));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["presenca"]) {
            // line 82
            echo "                <tr>
                    <td>";
            // line 83
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["presenca"]) ? $context["presenca"] : $this->getContext($context, "presenca")), "dataCadastro"), "d/m/Y"), "html", null, true);
            echo "</td>
                    <td>";
            // line 84
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["presenca"]) ? $context["presenca"] : $this->getContext($context, "presenca")), "turma"), "html", null, true);
            echo "</td>
                    <td>";
            // line 85
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["presenca"]) ? $context["presenca"] : $this->getContext($context, "presenca")), "turno"), "html", null, true);
            echo "</td>
                    <td>";
            // line 86
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["presenca"]) ? $context["presenca"] : $this->getContext($context, "presenca")), "qtdeAlunos"), "html", null, true);
            echo "</td>
                    <td><a href=\"";
            // line 87
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("presenca_remover", array("id" => $this->getAttribute((isset($context["presenca"]) ? $context["presenca"] : $this->getContext($context, "presenca")), "id"))), "html", null, true);
            echo "\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></a></span></td>
                </tr>
            ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 90
            echo "                <tr>
                    <td colspan=\"5\">Nenhuma presença cadastrada até o momento.</td>
                </tr>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['presenca'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 94
        echo "        </tbody>
    </table>
";
    }

    // line 98
    public function block_javascript($context, array $blocks = array())
    {
        // line 99
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    <script type=\"text/javascript\">
        \$('document').ready(function (){
            /*\$('#PresencaForm_turma').change(function(){
                var val = \$('#PresencaForm_turma option:selected').val();
                if (((val.match(/Berçario/g) || []).length > 0) || ((val.match(/Maternal/g) || []).length > 0)) {
                    \$('#PresencaForm_turno option').each(function (index){
                        \$(this).show(); if (index === 0 || index === 1 || index === 2) { \$(this).hide(); }
                    });
                }
                
                if ((val.match(/Jardim/g) || []).length > 0) {
                    \$('#PresencaForm_turno option').each(function (){ \$(this).show(); });
                }
                
                if ((val.match(/Pré/g) || []).length > 0) {
                    \$('#PresencaForm_turno option').each(function (index){ 
                        \$(this).show(); if (index === 3) { \$(this).hide(); }
                    });
                }
            });*/
        });
    </script>
";
    }

    public function getTemplateName()
    {
        return "PresencaBundle:Index:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  216 => 99,  213 => 98,  207 => 94,  198 => 90,  190 => 87,  186 => 86,  182 => 85,  178 => 84,  174 => 83,  171 => 82,  166 => 81,  147 => 65,  141 => 62,  135 => 59,  128 => 55,  121 => 51,  114 => 47,  107 => 43,  97 => 36,  91 => 33,  85 => 30,  78 => 26,  71 => 22,  64 => 18,  57 => 14,  51 => 11,  48 => 10,  45 => 9,  39 => 7,  32 => 4,  29 => 3,);
    }
}

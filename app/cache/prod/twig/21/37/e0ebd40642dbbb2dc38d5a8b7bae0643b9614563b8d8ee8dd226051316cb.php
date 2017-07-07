<?php

/* SuporteTecnicoBundle:Chamado:mapa.html.twig */
class __TwigTemplate_2137e0ebd40642dbbb2dc38d5a8b7bae0643b9614563b8d8ee8dd226051316cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SuporteTecnicoBundle:Index:index.html.twig");

        $this->blocks = array(
            'page' => array($this, 'block_page'),
            'javascript' => array($this, 'block_javascript'),
            'css' => array($this, 'block_css'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SuporteTecnicoBundle:Index:index.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_page($context, array $blocks = array())
    {
        // line 4
        echo "    <div style=\"margin-top: 1em;\" id=\"mapa\"></div>
";
    }

    // line 7
    public function block_javascript($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->displayParentBlock("javascript", $context, $blocks);
        echo "
    
    <script type=\"text/javascript\">        
        \$(document).ready( function() {
            function criarMarcador(marcador) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(marcador.latitude, marcador.longitude),
                    map: mapa
                });
                (marcador !== undefined) ? marker.setIcon(icone) : '';
                var infowindow = new google.maps.InfoWindow(), marker;
                infowindow.idWindow = windowsContents.length - 1;
                infoWindows.push(infowindow);
                latlngbounds.extend(marker.position);
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        for (var p=0; p<infoWindows.length; p++) {
                            if (infoWindows[p].idWindow !== infowindow.idWindow) {
                                infoWindows[p].close();
                            }
                        }
                        infowindow.setContent(windowsContents[infowindow.idWindow]);
                        infowindow.open(mapa, marker);
                    };
                })(marker));
                mapa.fitBounds(latlngbounds);
                windowContent = null;
                icone = null;
                prioridade = null;
            }
            
            var local = null;
            var prioridade = null;
            var icone = null;
            var infoWindows = [];
            var windowContent = null;
            var windowsContents = [];

            var opcoesMapa = { zoom: 14, center: { lat: -26.908422, lng: -48.701378}, mapTypeId: google.maps.MapTypeId.HYBRID };
            var mapa = new google.maps.Map(document.getElementById(\"mapa\"), opcoesMapa);
            var latlngbounds = new google.maps.LatLngBounds();
            var markers = JSON.parse(\"";
        // line 49
        echo twig_escape_filter($this->env, (isset($context["marcadores"]) ? $context["marcadores"] : $this->getContext($context, "marcadores")), "html", null, true);
        echo "\".replace(/&quot;/g, '\"'));
            \$.each(markers, function(i) {
                var bandeira = null;
                if (markers[i].prioridade === 4) {
                    bandeira = '<span class=\"glyphicon glyphicon-exclamation-sign text-danger\" title=\"Urgente\"></span>';
                } else if (markers[i].prioridade === 3) {
                    bandeira = '<span class=\"glyphicon glyphicon-flag text-warning\" title=\"Alta\"></span>';
                } else if (markers[i].prioridade === 2) {
                    bandeira = '<span class=\"glyphicon glyphicon-flag text-info\" title=\"Normal\"></span>';
                } else {
                    bandeira = '<span class=\"glyphicon glyphicon-flag text-success\" title=\"Baixa\"></span>';
                }
                
                if (local !== markers[i].local) {
                    if (windowContent !== null) { criarMarcador(markers[i-1]); }
                    local = markers[i].local;
                    prioridade = markers[i].prioridade;
                    icone = markers[i].icone;
                    if (local !== null) {
                        windowContent = \"<strong>\" + markers[i].local + \"</strong> <hr style='margin-top: 10px; margin-bottom: 0px;' /> <br /> \" + bandeira + \" <a href='\" + markers[i].href + \"'>Chamado #\" + markers[i].id + \"</a> - \" + markers[i].status + \" - <em>\" + markers[i].categoria + \"</em>\";
                        windowsContents.push(windowContent);
                    }
                } else {
                    if (markers[i].prioridade > prioridade) { prioridade = markers[i].prioridade; icone = markers[i].icone; }
                    windowsContents[windowsContents.length - 1] += '<br /> ' + bandeira + ' <a href=\"' + markers[i].href + '\">Chamado #' + markers[i].id + '</a> - ' + markers[i].status + ' - <em>' + markers[i].categoria + '</em>';
                }
            });
            if (windowContent !== null) { criarMarcador(markers[markers.length - 1]); }
        });
    </script>
";
    }

    // line 81
    public function block_css($context, array $blocks = array())
    {
        // line 82
        echo "    ";
        $this->displayParentBlock("css", $context, $blocks);
        echo "
    <style type=\"text/css\">
        #mapa {
            width: 100%;
            height: 35em;
            border: 2px solid #ddd; 
        }
    </style>
";
    }

    public function getTemplateName()
    {
        return "SuporteTecnicoBundle:Chamado:mapa.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  124 => 82,  121 => 81,  86 => 49,  41 => 8,  38 => 7,  33 => 4,  30 => 3,);
    }
}

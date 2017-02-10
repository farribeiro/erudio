<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *    @author Municipio de Itajaí - Secretaria de Educação - DITEC         *
 *    @updated 30/06/2016                                                  *
 *    Pacote: Erudio                                                       *
 *                                                                         *
 *    Copyright (C) 2016 Prefeitura de Itajaí - Secretaria de Educação     *
 *                       DITEC - Diretoria de Tecnologias educacionais     *
 *                        ditec@itajai.sc.gov.br                           *
 *                                                                         *
 *    Este  programa  é  software livre, você pode redistribuí-lo e/ou     *
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme     *
 *    publicada pela Free  Software  Foundation,  tanto  a versão 2 da     *
 *    Licença   como  (a  seu  critério)  qualquer  versão  mais  nova.    *
 *                                                                         *
 *    Este programa  é distribuído na expectativa de ser útil, mas SEM     *
 *    QUALQUER GARANTIA. Sem mesmo a garantia implícita de COMERCIALI-     *
 *    ZAÇÃO  ou  de ADEQUAÇÃO A QUALQUER PROPÓSITO EM PARTICULAR. Con-     *
 *    sulte  a  Licença  Pública  Geral  GNU para obter mais detalhes.     *
 *                                                                         *
 *    Você  deve  ter  recebido uma cópia da Licença Pública Geral GNU     *
 *    junto  com  este  programa. Se não, escreva para a Free Software     *
 *    Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA     *
 *    02111-1307, USA.                                                     *
 *                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace AvaliacaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_avaliacao_qualitativa")
*/
class AvaliacaoQualitativa extends Avaliacao {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "regime_fechamento", type = "boolean", nullable = false) 
    */
    private $fechamentoMedia = false;
    
    /**
    * @ORM\ManyToMany(targetEntity="Habilidade")
    * @ORM\JoinTable(name="edu_avaliacao_qualitativa_habilidade",
    *      joinColumns={@ORM\JoinColumn(name="avaliacao_qualitativa_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="habilidade_id", referencedColumnName="id")}
    *   )
    */
    private $habilidades;
    
    public function __construct() {
        $this->habilidades = new ArrayCollection();
    }
    
    function getFechamentoMedia() {
        return $this->fechamentoMedia;
    }
    
    function getHabilidades() {
        return $this->habilidades;
    }

    function setHabilidades(ArrayCollection $habilidades) {
        $this->habilidades = $habilidades;
    }
    
}

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

namespace CalendarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;


/**
* @ORM\Entity
* @ORM\Table(name = "edu_calendario_periodo")
*/
class Periodo extends AbstractEditableEntity {
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(nullable = false) 
    */
    private $media;
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @JMS\Type("DateTime<'Y-m-d'>")
    * @ORM\Column(name = "data_inicio", type = "date") 
    */
    private $dataInicio;
    
    /** 
        * @JMS\Groups({"LIST"}) 
        * @JMS\Type("DateTime<'Y-m-d'>")
        * @ORM\Column(name = "data_termino", type = "date") 
        */
    private $dataTermino;
    
    /** 
        * @JMS\Groups({"LIST"})
        * @ORM\ManyToOne(targetEntity = "Calendario") 
        * @ORM\JoinColumn(name = "calendario_id") 
        */
    private $calendario;
    
    /** 
        * @JMS\Groups({"LIST"})
        * @JMS\Type("AvaliacaoBundle\Entity\SistemaAvaliacao")
        * @ORM\ManyToOne(targetEntity = "AvaliacaoBundle\Entity\SistemaAvaliacao") 
        * @ORM\JoinColumn(name = "sistema_avaliacao_id") 
        */
    private $sistemaAvaliacao;
    
    function getMedia() {
        return $this->media;
    }

    function getDataInicio() {
        return $this->dataInicio;
    }

    function getDataTermino() {
        return $this->dataTermino;
    }

    function getCalendario() {
        return $this->calendario;
    }

    function getSistemaAvaliacao() {
        return $this->sistemaAvaliacao;
    }

    function setMedia($media) {
        $this->media = $media;
    }

    function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    function setDataTermino($dataTermino) {
        $this->dataTermino = $dataTermino;
    }

    function setCalendario($calendario) {
        $this->calendario = $calendario;
    }

    function setSistemaAvaliacao($sistemaAvaliacao) {
        $this->sistemaAvaliacao = $sistemaAvaliacao;
    }


}

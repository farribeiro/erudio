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
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_calendario_dia")
*/
class Dia extends AbstractEditableEntity {
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @JMS\Type("DateTime<'Y-m-d'>")
    * @ORM\Column(name = "data_dia", type = "date") 
    */
    private $data;
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(type = "boolean") 
    */
    private $letivo = true;
    
    /**
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(type = "boolean")
    */
    private $efetivo = true;
    
    /** 
    * @JMS\Exclude
    * @ORM\ManyToOne(targetEntity = "Calendario", inversedBy = "dias") 
    */
    private $calendario;
    
    /**
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "DiaEvento", mappedBy = "dia", cascade = {"all"}, fetch="EXTRA_LAZY") 
    */
    private $eventos;
    
    function __construct(Calendario $calendario, \DateTime $data) {
        $this->calendario = $calendario;
        $this->data = $data;
    }
    
    function init() {
        $this->eventos = new ArrayCollection();
    }
    
    function getData() {
        return $this->data;
    }

    function getLetivo() {
        return $this->letivo;
    }

    function getEfetivo() {
        return $this->efetivo;
    }

    function getCalendario() {
        return $this->calendario;
    }

    function setLetivo($letivo) {
        $this->letivo = $letivo;
    }

    function setEfetivo($efetivo) {
        $this->efetivo = $efetivo;
    }
    
    /**  
    * @JMS\VirtualProperty 
    * @JMS\Groups({"LIST"})
    * @JMS\Type("ArrayCollection<CalendarioBundle\Entity\DiaEvento>")
    */
    function getEventos() {
        return $this->eventos->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }

}

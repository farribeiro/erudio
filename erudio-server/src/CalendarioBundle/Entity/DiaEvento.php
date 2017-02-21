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

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_calendario_evento_dia")
*/
class DiaEvento extends AbstractEditableEntity {
    
    /** 
     * @JMS\Groups({"LIST"})  
     * @JMS\Type("DateTime<'H:i:s'>")
     * @ORM\Column(type = "time") 
     */
    private $inicio;
    
    /** 
        * @JMS\Groups({"LIST"})  
        * @JMS\Type("DateTime<'H:i:s'>")
        * @ORM\Column(type = "time") 
        */
    private $termino;
    
    /** 
        * @ORM\ManyToOne(targetEntity = "Dia", inversedBy = "eventos") 
        */
    private $dia;
    
    /** 
     * @JMS\Groups({"LIST"})  
     * @ORM\ManyToOne(targetEntity = "Evento", inversedBy = "dias") 
     */
    private $evento;
    
    function __construct(Dia $dia, Evento $evento, $inicio = null, $termino = null) {
        $this->dia = $dia;
        $this->evento = $evento;
        $this->inicio = $inicio;
        $this->termino = $termino;
    }
    
    function getInicio() {
        return $this->inicio;
    }

    function getTermino() {
        return $this->termino;
    }

    function getDia() {
        return $this->dia;
    }

    function getEvento() {
        return $this->evento;
    }

    function setInicio($inicio) {
        $this->inicio = $inicio;
    }

    function setTermino($termino) {
        $this->termino = $termino;
    }
    
}

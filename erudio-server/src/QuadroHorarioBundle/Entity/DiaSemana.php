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

namespace QuadroHorarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_quadro_horario_dia_semana")
*/
class DiaSemana extends AbstractEditableEntity {
    
    const DOMINGO = 1;
    const SEGUNDA_FEIRA = 2;
    const TERCA_FEIRA = 3;
    const QUARTA_FEIRA = 4;
    const QUINTA_FEIRA = 5;
    const SEXTA_FEIRA = 6;
    const SABADO = 7;
    
    /** 
    * @JMS\Groups({"LIST"})  
    * @ORM\Column(name = "dia_semana") 
    */
    private $diaSemana;
    
    /**  
    * @JMS\Exclude
    * @ORM\ManyToOne(targetEntity = "QuadroHorario", inversedBy = "diasSemana") 
    * @ORM\JoinColumn(name = "quadro_horario_id", nullable = false)
    */
    private $quadroHorario;
    
    function __construct(QuadroHorario $quadroHorario, $diaSemana) {
        $this->quadroHorario = $quadroHorario;
        $this->diaSemana = $diaSemana;
    }
    
    function getDiaSemana() {
        return $this->diaSemana;
    }

    function getQuadroHorario() {
        return $this->quadroHorario;
    }

    function setDiaSemana($diaSemana) {
        $this->diaSemana = $diaSemana;
    }
    
    function setQuadroHorario($quadroHorario) {
        if($this->quadroHorario == null) {
            $this->quadroHorario = $quadroHorario;
        }
    }
    
}


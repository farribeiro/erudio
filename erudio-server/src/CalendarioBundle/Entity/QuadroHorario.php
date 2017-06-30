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
* @ORM\Table(name = "edu_quadro_horario")
*/
class QuadroHorario extends AbstractEditableEntity {   
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /** 
    * @JMS\Type("DateTime<'H:i:s'>")
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "turno_inicio", type = "time", nullable = false) 
    */
    private $inicio;
    
    /**
    * @JMS\Type("DateTime<'H:i:s'>")
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "turno_termino", type = "time", nullable = false) 
    */
    private $termino;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @ORM\ManyToOne(targetEntity = "ModeloQuadroHorario") 
    */
    private $modelo;
    
    /**
    * @JMS\Groups({"DETAILS"})       
    * @JMS\Type("PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\UnidadeEnsino")
    * @ORM\JoinColumn(name = "unidade_ensino_id") 
    */
    private $unidadeEnsino;
    
    /**
    * @JMS\Groups({"DETAILS"})       
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Turno") 
    */
    private $turno;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @ORM\OneToMany(targetEntity = "DiaSemana", mappedBy = "quadroHorario", cascade = {"persist"}) 
    */
    private $diasSemana;
    
    /**
    * @JMS\Groups({"DETAILS"}) 
    * @ORM\OneToMany(targetEntity = "Horario", mappedBy = "quadroHorario", cascade = {"persist"}) 
    */
    private $horarios;
    
    function init() {
        $this->validarHorarioInicio($this->inicio);
        $this->horarios = new ArrayCollection();         
        $duracaoAula = new \DateInterval('PT'.$this->modelo->getDuracaoAula().'M');
        $posicaoIntervalo = $this->modelo->getPosicaoIntervalo();
        $duracaoIntervalo = new \DateInterval('PT'.$this->modelo->getDuracaoIntervalo().'M');
        foreach ($this->diasSemana as $diaSemana) {
            $diaSemana->setQuadroHorario($this);
            $inicio = \DateTime::createFromFormat('H:i:s', $this->inicio->format('H:i:s'));
            for ($i = 1; $i <= $this->modelo->getQuantidadeAulas(); $i++) {
                if ($i == $posicaoIntervalo) {                                                              
                    $inicio = \DateTime::createFromFormat('H:i:s', $inicio->format('H:i:s'));
                    $inicio->add($duracaoIntervalo);
                }
                $termino = \DateTime::createFromFormat('H:i:s', $inicio->format('H:i:s'));
                $horario = new Horario($this, $inicio, $termino->add($duracaoAula), $diaSemana);
                $this->horarios->add($horario);
                $inicio = $termino;
            }   
        }
        $this->termino = $termino;
    }
   
    function validarHorarioInicio(\DateTime $inicio) {
        $inicio->setDate(2000, 1, 1);
        $this->turno->getInicio()->setDate(2000, 1, 1);
        $this->turno->getTermino()->setDate(2000, 1, 1);
        if ($inicio < $this->turno->getInicio() || $inicio > $this->turno->getTermino()) {
            throw new \Exception("Horário de início deve ser entre {$this->turno->getInicio()->format('H:i:s')} e"
                . $this->turno->getTermino()->format('H:i:s'));
        }
        return true;
    }
    
    static function of($nome, $modelo, $turno, $unidadeEnsino) {
        $quadro = new QuadroHorario();
        $quadro->nome = $nome;
        $quadro->modelo = $modelo;
        $quadro->unidadeEnsino = $unidadeEnsino;
        $quadro->turno = $turno;
        $quadro->inicio = $turno->getInicio();
        $quadro->diasSemana = [];
        for($i = 2; $i < 7; $i++) {
            $quadro->diasSemana[] = new DiaSemana($quadro, $i);
        }
        return $quadro;
    }

    function getNome() {
        return $this->nome;
    }

    function getInicio() {
        return $this->inicio;
    }

    function getTermino() {
        return $this->termino;
    }

    function getModelo() {
        return $this->modelo;
    }

    function getUnidadeEnsino() {
        return $this->unidadeEnsino;
    }

    function getTurno() {
        return $this->turno;
    }
    
    function getDiasSemana() {
        return $this->diasSemana;
    }

    function getHorarios() {
        return $this->horarios;
    }
    
    function setNome($nome) {
        $this->nome = $nome;
    }

    function setInicio($inicio) {
        $this->inicio = $inicio;
    }  

}
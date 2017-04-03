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
* @ORM\Table(name = "edu_calendario")
*/
class Calendario extends AbstractEditableEntity {
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
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
        * @JMS\Type("PessoaBundle\Entity\Instituicao")
        * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\Instituicao") 
        */
    private $instituicao;
     
    /** 
        * @JMS\Type("CalendarioBundle\Entity\Calendario")
        * @ORM\ManyToOne(targetEntity = "Calendario") 
        * @ORM\JoinColumn(name = "calendario_base_id")
        */
    private $calendarioBase;
    
    /** 
        * @JMS\Exclude 
        * @ORM\OneToMany(targetEntity = "Dia", mappedBy = "calendario", cascade = {"all"}) 
        */
    private $dias;
    
    function init() {
        $this->dias = new ArrayCollection();
        if($this->calendarioBase instanceof Calendario) {
            $this->clonar($this->calendarioBase);
        } else {
            $this->criar();
        }
    }
    
    function finalize() {
        parent::finalize();
        foreach($this->dias as $dia) {
            $dia->finalize();
        }
    }
    
    private function criar() {
        $data = \DateTime::createFromFormat('Y-m-d', $this->dataInicio->format('Y-m-d'));
        while($data <= $this->dataTermino) {
            $dia = new Dia($this, new \DateTime($data->format('Y-m-d')));
            if($data->format('l') == 'Saturday' || $data->format('l') == 'Sunday') {
                $dia->setEfetivo(false);
                $dia->setLetivo(false);
            }
            $this->dias->add($dia);
            $data->add(new \DateInterval('P1D'));
        }
    }
    
    private function clonar(Calendario $calendario) {
        foreach($calendario->getDias() as $d) {
            $dia = new Dia($this, $d->getData());
            $dia->setEfetivo($d->getEfetivo());
            $dia->setLetivo($d->getLetivo());
            if ($d->getEventos() != null) {
                foreach($d->getEventos() as $e) {
                    $e = new DiaEvento($dia, $e->getEvento(), $e->getInicio(), $e->getTermino());
                    $dia->init();
                    $dia->getEventos()->add($e);
                }
            }
            $this->dias->add($dia);
        }
    }
    
    function getNome() {
        return $this->nome;
    }
    
    function getAno() {
        return $this->dataInicio->format('Y');
    }
 
    function getDataInicio() {
        return $this->dataInicio;
    }

    function getDataTermino() {
        return $this->dataTermino;
    }

    function getInstituicao() {
        return $this->instituicao;
    }

    function getCalendarioBase() {
        return $this->calendarioBase;
    }

    function getDias() {
        return $this->dias;
    }
    
    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDataInicio($dataInicio) {
        $this->dataInicio = $dataInicio;
    }

    function setDataTermino($dataTermino) {
        $this->dataTermino = $dataTermino;
    }
    
}

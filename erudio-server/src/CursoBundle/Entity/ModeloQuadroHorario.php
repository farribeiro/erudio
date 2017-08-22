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

namespace CursoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_quadro_horario_modelo")
*/
class ModeloQuadroHorario extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("integer") 
    * @ORM\Column(name = "quantidade_aulas", nullable = false) 
    */
    private $quantidadeAulas;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("integer")
    * @ORM\Column(name = "duracao_aula", nullable = false) 
    */
    private $duracaoAula;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("integer")
    * @ORM\Column(name = "duracao_intervalo", nullable = false) 
    */
    private $duracaoIntervalo;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\Type("integer") 
    * @ORM\Column(name = "posicao_intervalo", nullable = false) 
    */
    private $posicaoIntervalo;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "CursoBundle\Entity\Curso") 
    */
    private $curso;
    
    function getNome() {
        return $this->nome;
    }
    
    function getCurso() {
        return $this->curso;
    }
    
    function getQuantidadeAulas() {
        return $this->quantidadeAulas;
    }

    function getDuracaoAula() {
        return $this->duracaoAula;
    }

    function getDuracaoIntervalo() {
        return $this->duracaoIntervalo;
    }
    
    function getPosicaoIntervalo() {
        return $this->posicaoIntervalo;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setQuantidadeAulas($quantidadeAulas) {
        $this->quantidadeAulas = $quantidadeAulas;
    }

    function setDuracaoAula($duracaoAula) {
        $this->duracaoAula = $duracaoAula;
    }

    function setDuracaoIntervalo($duracaoIntervalo) {
        $this->duracaoIntervalo = $duracaoIntervalo;
    }
    
}

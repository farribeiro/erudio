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

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_turma_disciplina")
*/
class DisciplinaOfertada extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToOne(targetEntity = "Turma", inversedBy = "disciplinas") 
    */
    private $turma;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "Disciplina") 
    */
    private $disciplina;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\OneToMany(targetEntity = "QuadroHorarioBundle\Entity\HorarioDisciplina", mappedBy = "disciplina", fetch="EAGER")
    */
    private $horarios;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\MaxDepth(depth = 2)
    * @ORM\ManyToMany(targetEntity="VinculoBundle\Entity\Alocacao", inversedBy = "disciplinasMinistradas")
    * @ORM\JoinTable(name="edu_turma_disciplina_alocacao",
    *      joinColumns={@ORM\JoinColumn(name="turma_disciplina_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="alocacao_id", referencedColumnName="id")}
    *   )
    */
    private $professores;
    
    function __construct(Turma $turma, Disciplina $disciplina) {
        $this->turma = $turma;
        $this->disciplina = $disciplina;
    }
    
    function setTurma(Turma $turma) {
        if (is_null($this->turma)) {
            $this->turma = $turma;
        }
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getNome() {
        return $this->disciplina->getNome();
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getAgrupamento() {
        return $this->disciplina->getAgrupamento();
    }
    
    /**
    * @JMS\VirtualProperty
    */
    function getStatus() {
        return $this->turma->getStatus();
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getNomeExibicao() {
        return $this->disciplina->getNomeExibicao();
    }
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\VirtualProperty
     */
    function getSigla() {
        return $this->disciplina->getSigla();
    }
    
    function getTurma() {
        return $this->turma;
    }

    function getDisciplina() {
        return $this->disciplina;
    }
    
    function getHorarios() {
        return $this->horarios;
    }
    
    function getProfessores() {
        return $this->professores;
    }
    
    function getProfessoresAsString() {
        $professores = $this->getProfessores();
        $nomesProfessores = '';
        $numeroProfessores = count($professores);
        foreach ($professores as $i => $professor) {
            $nomesProfessores .= $professor->getVinculo()->getFuncionario()->getNome();
            if ($i + 1 < $numeroProfessores) {
                $nomesProfessores .= ", ";
            }
        }
        return $nomesProfessores;
    }
    
    function setProfessores(ArrayCollection $professores) {
        if (!$professores->isEmpty()) {
            $this->professores = $professores;
        }
    }
    
}

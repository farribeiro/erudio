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
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_etapa")
*/
class Etapa extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "nome_exibicao", nullable = false) 
    */
    private $nomeExibicao;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(type = "integer", nullable = false) 
    */
    private $ordem;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "limite_alunos", type = "integer") 
    */
    private $limiteAlunos;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @JMS\MaxDepth(depth = 1)
    * @ORM\ManyToOne(targetEntity = "Modulo") 
    */
    private $modulo;
    
    /** 
    * @JMS\Groups({"DETAILS"})
    * @ORM\ManyToOne(targetEntity = "Curso", inversedBy = "etapas") 
    */
    private $curso;
    
    /**
    * @JMS\Groups({"DETAILS"})
    * @JMS\MaxDepth(depth = 1)   
    * @ORM\ManyToOne(targetEntity = "CalendarioBundle\Entity\ModeloQuadroHorario")
    * @ORM\JoinColumn(name = "quadro_horario_modelo_id") 
    */
    private $modeloQuadroHorario;
    
    /**
    * @JMS\Groups({"DETAILS"})       
    * @ORM\ManyToOne(targetEntity = "AvaliacaoBundle\Entity\SistemaAvaliacao")
    * @ORM\JoinColumn(name = "sistema_avaliacao_id") 
    */
    private $sistemaAvaliacao;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(name = "idade_recomendada", type = "integer") 
    */
    private $idadeRecomendada;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "boolean", nullable = false) 
    */
    private $integral = true;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "boolean", nullable = false, name = "frequencia_unificada") 
    */
    private $frequenciaUnificada = false;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Disciplina", mappedBy = "etapa") 
    */
    private $disciplinas;
    
    function init() {
        $this->disciplinas = new ArrayCollection();
    }
    
    function isSistemaQuantitativo() {
        return $this->getSistemaAvaliacao()->isQuantitativo();
    }
    
    function isSistemaQualitativo() {
        return $this->getSistemaAvaliacao()->isQualitativo();
    }
    
    function getUnidadeRegime() {
        return $this->sistemaAvaliacao->getRegime()->getUnidade();
    }
    
    function getDisciplinas() {
        return $this->disciplinas->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );
    }
    
    function getNome() {
        return $this->nome;
    }

    function getNomeExibicao() {
        return $this->nomeExibicao;
    }

    function getOrdem() {
        return $this->ordem;
    }

    function getLimiteAlunos() {
        return $this->limiteAlunos;
    }

    function getIntegral() {
        return $this->integral;
    }
    
    function getModulo() {
        return $this->modulo;
    }

    function getCurso() {
        return $this->curso;
    }
        
    function getModeloQuadroHorario() {
        return $this->modeloQuadroHorario;
    }
    
    function getSistemaAvaliacao() {
        return $this->sistemaAvaliacao;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setNomeExibicao($nomeExibicao) {
        $this->nomeExibicao = $nomeExibicao;
    }

    function setOrdem($ordem) {
        $this->ordem = $ordem;
    }

    function setLimiteAlunos($limiteAlunos) {
        $this->limiteAlunos = $limiteAlunos;
    }

    function setIntegral($integral) {
        $this->integral = $integral;
    }
    
    function setModulo(Modulo $modulo) {
        $this->modulo = $modulo;
    }
    
    function getFrequenciaUnificada() {
        return $this->frequenciaUnificada;
    }

    function setFrequenciaUnificada($frequenciaUnificada) {
        $this->frequenciaUnificada = $frequenciaUnificada;
    }
    
    function getIdadeRecomendada() {
        return $this->idadeRecomendada;
    }

    function setIdadeRecomendada($idadeRecomendada) {
        $this->idadeRecomendada = $idadeRecomendada;
    }
    
}

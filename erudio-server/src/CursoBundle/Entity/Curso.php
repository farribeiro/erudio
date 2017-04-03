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
* @ORM\Table(name = "edu_curso")
*/
class Curso extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column(type = "boolean", nullable = false) 
    */
    private $alfabetizatorio = false;
    
    /**
    * @ORM\Column(type = "integer") 
    */
    private $limiteDefasagem = 0;
    
    /**
    * @JMS\MaxDepth(depth = 1)
    * @JMS\Type("PessoaBundle\Entity\Instituicao")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\Instituicao")
    */
    private $instituicao;
    
    /** 
    * @JMS\Groups({"LIST"}) 
    * @ORM\ManyToOne(targetEntity = "ModalidadeEnsino") 
    * @ORM\JoinColumn(name = "modalidade_ensino_id")
    */
    private $modalidade;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Etapa", mappedBy = "curso", cascade = {"all"}) 
    */
    private $etapas;
    
    /** 
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Disciplina", mappedBy = "curso", cascade = {"all"}) 
    */
    private $disciplinas;
    
    function __construct() {
        $this->etapas = new ArrayCollection();
        $this->disciplinas = new ArrayCollection();
    }
    
    function getNome() {
        return $this->nome;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }
    
    function getInstituicao() {
        return $this->instituicao;
    }
    
    function getAlfabetizatorio() {
        return $this->alfabetizatorio;
    }

    function setAlfabetizatorio($alfabetizatorio) {
        $this->alfabetizatorio = $alfabetizatorio;
    }
    
    function getEtapas() {
        return $this->etapas;
    }

    function getDisciplinas() {
        return $this->disciplinas;
    }
    
    function getModalidade() {
        return $this->modalidade;
    }

    function setModalidade(ModalidadeEnsino $modalidade) {
        $this->modalidade = $modalidade;
    }
    
    function getLimiteDefasagem() {
        return $this->limiteDefasagem;
    }

    function setLimiteDefasagem($limiteDefasagem) {
        $this->limiteDefasagem = $limiteDefasagem;
    }
    
}

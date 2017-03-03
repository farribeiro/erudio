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

namespace PessoaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_unidade_ensino")
*/
class UnidadeEnsino extends Instituicao {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToOne(targetEntity = "TipoUnidadeEnsino") 
    */
    private $tipo;
    
    /**
    * @JMS\Groups({"LIST"})
    * @ORM\ManyToMany(targetEntity="\CursoBundle\Entity\Curso")
    * @ORM\JoinTable(name="edu_unidade_ensino_curso",
    *      joinColumns={@ORM\JoinColumn(name="unidade_ensino_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="curso_id", referencedColumnName="id")}
    *   )
    */
    private $cursos;
        
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\VirtualProperty()
    */
    function getNomeCompleto() {
        return $this->tipo->getSigla() . ' ' . $this->getNome();
    }    
    
    function getTipo() {
        return $this->tipo;
    }

    function setTipo(TipoUnidadeEnsino $tipo) {
        $this->tipo = $tipo;
    }
    
    function getCursos() {
        return $this->cursos;
    }

    function setCursos(ArrayCollection $cursos) {
        $this->cursos = $cursos;
    }
    
}

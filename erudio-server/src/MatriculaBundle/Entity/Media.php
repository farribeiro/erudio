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

namespace MatriculaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_media")
*/
class Media extends AbstractEditableEntity {
    
    /**  
    * @JMS\Groups({"LIST"}) 
    * @ORM\Column
    */
    private $nome;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\Type("integer")
    * @ORM\Column(type = "integer")
    */
    private $numero;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\Type("float") 
    * @ORM\Column
    */
    private $valor;
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\Type("integer")
    * @ORM\Column
    */
    private $peso;
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\Type("integer")
     * @ORM\Column
     */
    private $faltas = 0;
    
    /**
     * @JMS\Groups({"LIST"})
     * @JMS\Type("float")
     * @ORM\Column 
     */
    private $frequencia = 100;
    
    /**  
    *  @JMS\Groups({"LIST"})
    *  @JMS\MaxDepth(depth = 2)
    *  @ORM\ManyToOne(targetEntity = "DisciplinaCursada", cascade = {"all"}, inversedBy="medias") 
    *  @ORM\JoinColumn(name = "matricula_disciplina_id") 
    */
    private $disciplinaCursada;
    
    /**  
    * @JMS\Exclude
    * @ORM\OneToMany(targetEntity = "Nota", mappedBy = "media", cascade= {"all"}, fetch = "EXTRA_LAZY")
    */
    private $notas;
    
    function __construct(DisciplinaCursada $disciplinaCursada, $numero, $peso = 1, $nome = null) {
        $this->disciplinaCursada = $disciplinaCursada;
        $this->numero = $numero;
        $this->nome = is_null($nome) ? 'M' . $numero : $nome;
        $this->peso = $peso;
        $this->notas = new ArrayCollection();
    }
    
    /**  
    * @JMS\VirtualProperty 
    * @JMS\MaxDepth(depth = 3)
    */
    function getNotas() {
        return $this->notas->matching(
            Criteria::create()->where(Criteria::expr()->eq('ativo', true))
        );    
    }
    
    /**
     * Em médias de um sistema qualitativo, retorna as habilidades avaliadas na média
     * com seus respectivos conceitos.
     * 
     * @return Traversable habilidades avaliadas
     */
    function getHabilidadesAvaliadas() {
        $notasFechamento = $this->getNotas()->filter(function($n) {
           return $n instanceof NotaQualitativa && $n->getAvaliacao()->getFechamentoMedia();
        });
        return $notasFechamento->count()
                ? $notasFechamento->first()->getHabilidadesAvaliadas()
                : [];
    }
    
    function removeNota($nota) {   
        $this->notas->removeElement($nota);
    }
    
    function resetar() {
        $this->valor = null;
    }
    
    function getNome() {
        return $this->nome;
    }

    function getNumero() {
        return $this->numero;
    }

    function getValor() {
        return $this->valor;
    }
    
    function getFaltas() {
        return $this->faltas;
    }

    function getFrequencia() {
        return $this->frequencia;
    }

    function getDisciplinaCursada() {
        return $this->disciplinaCursada;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }
    
    function setFaltas($faltas) {
        $this->faltas = $faltas;
    }
    
    function setFrequencia($frequencia) {
        $this->frequencia = $frequencia;
    }     
    
    function getPeso() {
        return $this->peso;
    }
    
}

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
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_nota_qualitativa")
*/
class NotaQualitativa extends Nota {
    
    /**  
    * @JMS\Groups({"LIST"})
    * @JMS\Type("AvaliacaoBundle\Entity\AvaliacaoQualitativa")
    * @JMS\MaxDepth(2)
    * @ORM\ManyToOne(targetEntity = "AvaliacaoBundle\Entity\AvaliacaoQualitativa")
    * @ORM\JoinColumn(name = "avaliacao_qualitativa_id", nullable = false) 
    */
    private $avaliacao;
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\OneToMany(targetEntity = "NotaHabilidade", mappedBy = "notaQualitativa", fetch = "EAGER", cascade = {"persist"}) 
    */
    private $habilidadesAvaliadas;
    
    function getAvaliacao() {
        return $this->avaliacao;
    }
    
    function getHabilidadesAvaliadas() {
        return $this->habilidadesAvaliadas;
    }
    
    function getFechamentoMedia() {
        return $this->getAvaliacao()->getFechamentoMedia();
    }
    
}

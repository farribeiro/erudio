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

namespace AvaliacaoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEntity;

/**
* @ORM\Entity(readOnly = true)
* @ORM\Table(name = "edu_avaliacao_conceito")
*/
class Conceito extends AbstractEntity {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $nome;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false) 
    */
    private $sigla;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "valor_min", nullable = false) 
    */
    private $valorMinimo;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name = "valor_max", nullable = false) 
    */
    private $valorMaximo;
    
    function getNome() {
        return $this->nome;
    }

    function getSigla() {
        return $this->sigla;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setSigla($sigla) {
        $this->sigla = $sigla;
    }
    
    function getValorMinimo() {
        return $this->valorMinimo;
    }

    function getValorMaximo() {
        return $this->valorMaximo;
    }

    function setValorMinimo($valorMinimo) {
        $this->valorMinimo = $valorMinimo;
    }

    function setValorMaximo($valorMaximo) {
        $this->valorMaximo = $valorMaximo;
    }
    
}

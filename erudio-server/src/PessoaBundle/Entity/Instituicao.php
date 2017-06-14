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

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use JMS\Serializer\Annotation\Groups;

/**
* @ORM\Entity
* @ORM\Table(name = "edu_instituicao")
* @ORM\InheritanceType("JOINED")
* @ORM\DiscriminatorColumn(name = "tipo_pessoa", type = "string")
* @ORM\DiscriminatorMap({"Instituicao" = "Instituicao", "UnidadeEnsino" = "UnidadeEnsino"})
*/
class Instituicao extends PessoaJuridica {
    
    /** 
    * @JMS\Groups({"LIST"})   
    * @JMS\Type("PessoaBundle\Entity\Instituicao")
    * @ORM\ManyToOne(targetEntity = "Instituicao")
    * @ORM\JoinColumn(name = "instituicao_pai_id") 
    */
    private $instituicaoPai;
    
    function getInstituicaoPai() {
        return $this->instituicaoPai;
    }

    function setInstituicaoPai(Instituicao $instituicaoPai) {
        $this->instituicaoPai = $instituicaoPai;
    }
    
}

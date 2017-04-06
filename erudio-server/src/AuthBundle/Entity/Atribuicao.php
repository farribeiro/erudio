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

namespace AuthBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="edu_acesso_atribuicao")
 */
class Atribuicao extends AbstractEditableEntity {
    
    /**
    * @JMS\Groups({"LIST"}) 
    * @ORM\ManyToOne(targetEntity = "Usuario", inversedBy = "atribuicoes") 
    */
    private $usuario;
   
    /**
    * @JMS\Groups({"LIST"})   
    * @ORM\ManyToOne(targetEntity = "Grupo")
    */
    private $grupo;
    
    /**
    * @JMS\Groups({"LIST"})
    * @JMS\Type("PessoaBundle\Entity\Instituicao")
    * @ORM\ManyToOne(targetEntity = "PessoaBundle\Entity\Instituicao")
    */
    private $instituicao;
    
    function getUsuario() {
        return $this->usuario;
    }
    
    function getGrupo() {
        return $this->grupo;
    }

    function getInstituicao() {
        return $this->instituicao;
    }
    
    static function criarAtribuicao($usuario, $grupo, $instituicao) {
        $atribuicao = new Atribuicao();
        $atribuicao->usuario = $usuario;
        $atribuicao->grupo = $grupo;
        $atribuicao->instituicao = $instituicao;
        return $atribuicao;
    }
    
}


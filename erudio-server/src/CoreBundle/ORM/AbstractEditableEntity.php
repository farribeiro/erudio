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

namespace CoreBundle\ORM;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
* @ORM\MappedSuperclass
*/
abstract class AbstractEditableEntity extends AbstractEntity {
    
    /**
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name="data_cadastro", type="datetime", nullable=false) 
    */
    protected $dataCadastro;
    
    /**
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @ORM\Column(name="data_modificacao", type="datetime", nullable=true) 
    */
    protected $dataModificacao;
    
    /** 
    * @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
    * @JMS\Exclude
    * @ORM\Column(name="data_exclusao", type="datetime", nullable=true)
    */
    protected $dataExclusao = null;
    
    function getDataCadastro() {
        return $this->dataCadastro;
    }

    function getDataModificacao() {
        return $this->dataModificacao;
    }

    function getDataExclusao() {
        return $this->dataExclusao;
    }
    
    function merge($entity) {
        $this->dataModificacao = new \DateTime();
        $class = new \ReflectionClass($entity);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $size = count($methods);
        for($i = 0; $i < $size; $i++) {
            if(strpos($methods[$i]->name, 'set') === 0) {
                $getter = $class->getMethod(str_replace('set', 'get', $methods[$i]->name));
                $value = $getter ? $getter->invoke($entity) : null;
                if(is_array($value)) {
                    $value = new ArrayCollection($value);
                }
                if($value !== null) {
                    $methods[$i]->invoke($this, $value);
                }
            }
        }
    }
    
    function finalize() {
        $this->ativo = false;
        $this->dataExclusao = new \DateTime();
    }
    
}

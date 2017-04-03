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

namespace AssetsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use CoreBundle\ORM\AbstractEditableEntity;

/**
* @deprecated
* @ORM\Entity
* @ORM\Table(name = "edu_arquivo")
*/
class Asset extends AbstractEditableEntity {
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = true, name="nome")
    */
    private $label = null;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = false, type="string", name="nome_arquivo")
    * @Assert\NotBlank(message="Campo obrigatório.")
    * @Assert\File(mimeTypes={" image/jpeg, image/pjpeg, image/png, image/bmp "})
    */
    private $file;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(name="folder_id", type = "integer", nullable = true, name="pasta_id") 
    */
    private $folder = null;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = true, name="tipo")
    */
    private $type = null;
    
    /** 
    * @JMS\Groups({"LIST"})
    * @ORM\Column(nullable = true, name="tamanho")
    */
    private $size = null;
    
    function __toString() {
        return "$this->id";
    }
        
    function getLabel() {
        return $this->label;
    }

    function getFile() {
        return $this->file;
    }
    
    function getFolder() {
        return $this->folder;
    }
    
    function getType() {
        return $this->type;
    }

    function getSize() {
        return $this->size;
    }

    function setLabel($label) {
        $this->label = $label;
    }

    function setFile($file) {
        $this->file = $file;
        return $this;
    }

    function setFolder($folder) {
        $this->folder = $folder;
    }
    
    function setType($type) {
        $this->type = $type;
    }
    
    function setSize($size) {
        $this->size = $size;
    }
}

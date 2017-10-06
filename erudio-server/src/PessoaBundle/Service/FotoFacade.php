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

namespace PessoaBundle\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use CoreBundle\ORM\Exception\IllegalOperationException;

class FotoFacade {
    
    private $validator;
    private $uploadPath;
    
    function __construct(ValidatorInterface $validator, $uploadPath) {
        $this->validator = $validator;
        $this->uploadPath = $uploadPath . '/pessoa';
    }
 
    function carregar($id) {
        return file_get_contents("{$this->uploadPath}/{$id}.jpg");
    }
    
    function gravar($id, UploadedFile $foto) {
        $this->validar($foto);
        $foto->move($this->uploadPath, "$id.jpg");
    }
    
    function apagar($id) {
        unlink("{$this->uploadPath}/{$id}.jpg");
    }
    
    function validar(UploadedFile $foto) {
        $erros = $this->validator->validate($foto, $this->getValidationConstraint());
        if ($erros->count() > 0) {
            throw new IllegalOperationException($erros[0]->getMessage());
        }
        return true;
    }
    
    function getValidationConstraint() {
        $constraint = new Assert\Image();
        $constraint->mimeTypes = ['image/jpeg'];
        $constraint->minWidth = 100;
        $constraint->minHeight = 100;
        $constraint->maxSize = '1M';
        $constraint->mimeTypesMessage = 'Somente arquivos no formato JPEG são permitidos';
        $constraint->minWidthMessage = 'A imagem deve ter no mínimo 100 x 100 pixels';
        $constraint->minHeightMessage = 'A imagem deve ter no mínimo 100 x 100 pixels';
        $constraint->maxSizeMessage = 'O arquivo deve ter no máximo 1MB';
        return $constraint;
    }
    
}

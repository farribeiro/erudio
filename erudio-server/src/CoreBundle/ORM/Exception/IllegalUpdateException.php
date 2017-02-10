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

namespace CoreBundle\ORM\Exception;

class IllegalUpdateException extends \Exception {
    
    const OBJECT_IS_READONLY = 1;
    const OBJECT_NOT_FOUND = 2;
    const FINAL_STATE = 3;
    const ILLEGAL_STATE_TRANSITION = 4;
    
    private $errorCode;
    
    function __construct($errorCode, $specificMessage = null) {
        parent::__construct($specificMessage ? $specificMessage : $this->createMessage($errorCode));
        $this->errorCode = $errorCode;
    }
    
    function createMessage($errorCode) {
        switch($errorCode) {
            case self::OBJECT_IS_READONLY:
                return 'Este tipo de objeto não pode ser modificado';
            case self::OBJECT_NOT_FOUND:
                return 'Objetos excluídos não podem ser modificados';
            case self::FINAL_STATE:
                return 'O objeto não pode ser mais ser modificado em seu estado atual';
            case self::ILLEGAL_STATE_TRANSITION:
                return 'Tentativa de modificação ilegal do objeto em seu estado atual';
            default:
                return 'Um erro desconhecido ocorreu ao tentar modificar o objeto';
        }
    }
    
    function getErrorCode() {
        return $this->errorCode;
    }
    
}


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

namespace PessoaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityController;
use PessoaBundle\Service\PaisFacade;

/**
 * @FOS\NamePrefix("paises")
 */
class PaisController extends AbstractEntityController {
    
    function __construct(PaisFacade $facade) {
        parent::__construct($facade);
    }  
    
    /**
    *   @ApiDoc(
    *       section = "Módulo Pessoa",
    *       description = "Busca um país por seu id",
    *       parameters={
    *           {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
    *       },
    *       statusCodes = {
    *           200 = "Retorno do objeto",
    *           404 = "Objeto não encontrado"
    *       }
    *   )
    * 
    *   @FOS\Get("paises/{id}") 
    */
    function getAction(Request $request, $id) {
        return $this->getOne($request, $id);
    }
    
    /**
    *   @ApiDoc(
    *       resource = true,
    *       section = "Módulo Pessoa",
    *       description = "Busca de países",
    *       statusCodes = {
    *           200 = "Retorno dos resultados da busca"
    *       }
    *   ) 
    * 
    *   @FOS\Get("paises")
    *   @FOS\QueryParam(name = "nome", nullable = true)
    *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    */
    function getListAction(Request $request, ParamFetcherInterface $paramFetcher) {
        return $this->getList($request, $paramFetcher->all());
    }

}

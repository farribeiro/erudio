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
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;

/**
* @RouteResource("cidades")
*/
class CidadeController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'PessoaBundle:Cidade';
    }
    
    
    function queryAlias() {
        return 'c';
    }
    
    function parameterMap() {
        return array (
            'estado' => function(QueryBuilder $qb, $value) {
                $qb->join('c.estado', 'e')->andWhere('e.id = :estado')->setParameter('estado', $value);
            },
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('c.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            }
        );
    }
    
    /**
        * @ApiDoc(
        *   section = "Módulo Pessoa",
        *   description = "Busca uma cidade por seu id",
        * parameters={
        *      {"name" = "id", "dataType"="integer", "required"=true, "description"="id do objeto"}
        *  },
        *   statusCodes = {
        *       200 = "Retorno do objeto",
        *       404 = "Objeto não encontrado"
        *   }
        * )
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        * @ApiDoc(
        *   resource = true,
        *   section = "Módulo Pessoa",
        *   description = "Busca de cidades",
        *   statusCodes = {
        *       200 = "Retorno dos resultados da busca"
        *   }
        * )
        * 
        * @QueryParam(name = "page", requirements="\d+", default = null) 
        * @QueryParam(name = "nome", nullable = true) 
        * @QueryParam(name = "estado", requirements="\d+", nullable = true) 
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }

}

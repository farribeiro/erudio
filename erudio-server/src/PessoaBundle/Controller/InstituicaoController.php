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
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use PessoaBundle\Entity\Instituicao;

/**
* @FOS\RouteResource("instituicoes")
*/
class InstituicaoController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'PessoaBundle:Instituicao';
    }
    
    function queryAlias() {
        return 'i';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('i.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'instituicaoPai' => function(QueryBuilder $qb, $value) {
                $qb->join('i.instituicaoPai', 'instituicaoPai')
                    ->andWhere('instituicaoPai.id = :instituicaoPai')->setParameter('instituicaoPai', $value);
            }                    
            
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->andWhere('i INSTANCE OF PessoaBundle\Entity\Instituicao');
    }
    
    /**
    * @ApiDoc(
    *   section = "Módulo Pessoa",
    *   description = "Busca uma instituição por seu id",
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
    *   description = "Busca de instituições",
    *   statusCodes = {
    *       200 = "Retorno dos resultados da busca"
    *   }
    * )
    * 
    * @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
    * @FOS\QueryParam(name = "nome", nullable = true) 
    * @FOS\QueryParam(name = "instituicaoPai", requirements="\d+", nullable = true) 
    */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Post("instituicoes")
    * @ParamConverter("instituicao", converter="fos_rest.request_body")
    */
    function postAction(Request $request, Instituicao $instituicao, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($instituicao);
    }
    
    /**
    * @ApiDoc()
    * 
    * @FOS\Put("instituicoes/{id}")
    * @ParamConverter("instituicao", converter="fos_rest.request_body")
    */
    function putAction(Request $request, $id, Instituicao $instituicao, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $instituicao);
    }
    
    /**
    * @ApiDoc()
    */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}

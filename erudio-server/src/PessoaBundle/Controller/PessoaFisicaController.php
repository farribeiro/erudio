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
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;
use PessoaBundle\Entity\PessoaFisica;

/**
* @RouteResource("pessoas")
*/
class PessoaFisicaController extends AbstractEntityResource {
    
    function getEntityClass() {
        return 'PessoaBundle:PessoaFisica';
    }
    
    function queryAlias() {
        return 'pessoa';
    }
    
    function parameterMap() {
        return array (
            'nome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nome LIKE :nome')->setParameter('nome', '%' . $value . '%');
            },
            'sobrenome' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nome LIKE :sobrenome')->setParameter('sobrenome', '%' . $value . '%');
            },
            'dataNascimento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.dataNascimento = :dataNascimento')->setParameter('dataNascimento', $value);
            },
            'cpf' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.cpfCnpj = :cpf')->setParameter('cpf', $value);
            },
            'certidaoNascimento' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.certidaoNascimento LIKE :certidao')->setParameter('certidao', '%' . $value . '%');
            },
            'nomeMae' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nomeMae LIKE :nomeMae')->setParameter('nomeMae', '%' . $value . '%');
            },
            'nomePai' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.nomePai LIKE :nomePai')->setParameter('nomePai', '%' . $value . '%');
            },
            'avatar' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.avatar = :avatar')->setParameter('avatar', '%' . $value . '%');
            },
            'usuario' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('pessoa.usuario = :usuario')->setParameter('usuario', $value
                        );
            },
        );
    }
    
    /**
        * @ApiDoc(
        *   section = "Módulo Pessoa",
        *   description = "Busca uma pessoa por seu id",
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
        *   description = "Busca de pessoas",
        *   statusCodes = {
        *       200 = "Retorno dos resultados da busca"
        *   }
        * ) 
        * 
        * @QueryParam(name = "page", requirements="\d+", default = null) 
        * @QueryParam(name = "nome", nullable = true) 
        * @QueryParam(name = "sobrenome", nullable = true) 
        * @QueryParam(name = "dataNascimento", nullable = true) 
        * @QueryParam(name = "cpf", nullable = true) 
        * @QueryParam(name = "certidaoNascimento", requirements="\d+", nullable = true) 
        * @QueryParam(name = "nomeMae", nullable = true) 
        * @QueryParam(name = "nomePai", nullable = true)    
        * @QueryParam(name = "avatar", nullable = true)
        * @QueryParam(name = "usuario", nullable = true)
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        * @ApiDoc()
        * '
        * @Post("pessoas")
        * @ParamConverter("pessoa", converter="fos_rest.request_body")
        */
    function postAction(Request $request, PessoaFisica $pessoa, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->create($pessoa);
    }
    
    /**
        * @ApiDoc()
        * 
        * @Put("pessoas/{id}")
        * @ParamConverter("pessoa", converter="fos_rest.request_body")
        */
    function putAction(Request $request, $id, PessoaFisica $pessoa, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->update($id, $pessoa);
    }
    
    /**
        * @ApiDoc()
        * 
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}

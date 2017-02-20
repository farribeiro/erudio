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

namespace CoreBundle\REST;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\ORM\AbstractEditableEntity;

/**
 * @deprecated
* Controlador REST que serve como base aos demais.
* Oferece os métodos CRUD já implementados, usando o repositório informado na implementação do método getEntityClass.
*/
abstract class AbstractEntityResource extends FOSRestController implements ClassResourceInterface {
    
    const DEFAULT_QUERY_ALIAS = 'entidade';
    const ATTR_ID = 'id';
    const ATTR_ATIVO = 'ativo';
    
    const SERIALIZER_GROUP_LIST = 'LIST';
    
    const PAGE_PARAM = 'page';
    const PAGE_SIZE = 10;
    
    abstract function getEntityClass();
    
    function queryAlias() {
        return self::DEFAULT_QUERY_ALIAS;
    }
    
    function parameterMap() {
        return array();
    }
    
    function getOne($id) {
        $entidade = $this->loadEntity($id);
        if($entidade == null) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404, 'Objeto não encontrado');
        }
        $view = View::create($entidade, $entidade != null ? Codes::HTTP_OK : Codes::HTTP_NOT_FOUND);
        $view->getSerializationContext()->enableMaxDepthChecks();
        return $this->handleView($view);
    }

    function getList($params) {
        if($params instanceof ParamFetcher) {
            $params = $params->all();
        }
        $resultados = key_exists(self::PAGE_PARAM, $params)
            ? $this->runQuery($params, $params[self::PAGE_PARAM])
            : $this->runQuery($params);
        $view = View::create($resultados, Codes::HTTP_OK);
        $view->getSerializationContext()->enableMaxDepthChecks();
        $view->getSerializationContext()->setGroups(array(self::SERIALIZER_GROUP_LIST));
        return $this->handleView($view);
    }

    function create($entidade) {
        try {
            $this->getDoctrine()->getManager()->beginTransaction();
            $entidade->init();
            $this->beforeCreate($entidade);
            $this->getDoctrine()->getManager()->persist($entidade);
            $this->getDoctrine()->getManager()->flush();
            $this->afterCreate($entidade);
            $this->getDoctrine()->getManager()->commit();
            $view = View::create($entidade, Codes::HTTP_OK);
        } catch(\Exception $ex) {
            $this->getDoctrine()->getManager()->rollback();
            $view = View::create(array('error' => $ex->getMessage()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    function createBatch(ArrayCollection $entidades) {
        try {
            $this->getDoctrine()->getManager()->beginTransaction();
            foreach($entidades as $entidade) {
                $entidade->init();
                $this->beforeCreate($entidade);
                $this->getDoctrine()->getManager()->persist($entidade);
                $this->getDoctrine()->getManager()->flush();
                $this->afterCreate($entidade);
            }
            $this->getDoctrine()->getManager()->commit();
            $view = View::create(null, Codes::HTTP_NO_CONTENT);
        } catch(\Exception $ex) {
            $this->getDoctrine()->getManager()->rollback();
            $view = View::create(array('error' => $ex->getMessage()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }

    function update($id, $mergeObject) {
        try {
            $this->getDoctrine()->getManager()->detach($mergeObject);
            $entidade = $this->loadEntity($id);
            if(!$entidade instanceof AbstractEditableEntity) {
                return $this->handleView(View::create('', Codes::HTTP_FORBIDDEN));
            }
            $this->getDoctrine()->getManager()->beginTransaction();
            $this->beforeUpdate($entidade);
            $entidade->merge($mergeObject);
            $this->getDoctrine()->getManager()->flush();
            $this->afterUpdate($entidade);
            $this->getDoctrine()->getManager()->commit();
            $view = View::create($entidade, Codes::HTTP_OK);
        } catch(\Exception $ex) {
            $this->getDoctrine()->getManager()->rollback();
            $view = View::create(array('error' => $ex->getMessage()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    function updateBatch(ArrayCollection $mergeObjects) {
        try {
            $this->getDoctrine()->getManager()->beginTransaction();
            foreach($mergeObjects as $mergeObject) {
                $this->getDoctrine()->getManager()->detach($mergeObject);
                $entidade = $this->loadEntity($mergeObject->getId());
                if(!$entidade instanceof AbstractEditableEntity || !$entidade->getAtivo()) {
                    throw new Exception('Forbidden');
                }
                $this->beforeUpdate($entidade);
                $entidade->merge($mergeObject);
                $this->getDoctrine()->getManager()->flush();
                $this->afterUpdate($entidade);
            }
            $this->getDoctrine()->getManager()->commit();
            $view = View::create($mergeObjects->toArray(), Codes::HTTP_OK);
        } catch(\Exception $ex) {
            $this->getDoctrine()->getManager()->rollback();
            $view = View::create(array('error' => $ex->getMessage()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    function remove($id) {
        $entidade = $this->loadEntity($id);
        if($entidade != null) {
            try {
                $this->getDoctrine()->getManager()->beginTransaction();
                $this->beforeRemove($entidade);
                $entidade->finalize();
                $this->getDoctrine()->getManager()->flush();
                $this->afterRemove($entidade);
                $this->getDoctrine()->getManager()->commit();
            } catch(\Exception $ex) {
                $this->getDoctrine()->getManager()->rollback();
                throw $ex;
            }
        }
        $view = View::create(null, $entidade != null ? Codes::HTTP_NO_CONTENT : Codes::HTTP_NOT_FOUND);
        return $this->handleView($view);
    }
    
    function removeBatch(array $ids) {
        try {
            $this->getDoctrine()->getManager()->beginTransaction();
            foreach($ids as $id) {
                $entidade = $this->loadEntity($id);
                if($entidade != null) {
                    $this->beforeRemove($entidade);
                    $entidade->finalize();
                    $this->getDoctrine()->getManager()->merge($entidade);
                    $this->getDoctrine()->getManager()->flush();
                    $this->afterRemove($entidade);
                } else {
                    throw new \Exception('Not Found');
                }
            }
            $this->getDoctrine()->getManager()->commit();
            $view = View::create(null, Codes::HTTP_NO_CONTENT);
        } catch(\Exception $ex) {
            $this->getDoctrine()->getManager()->rollback();
            $view = View::create(array('error' => $ex->getMessage()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->handleView($view);
    }
    
    protected function loadEntity($id, $entityClass = null) {
        $entityClass = $entityClass == null ? $this->getEntityClass() : $entityClass;
        $entidade = $this->getDoctrine()->getRepository($entityClass)->findOneBy(
            array(self::ATTR_ID => $id, self::ATTR_ATIVO => true)
        );
        return $entidade;
    }
    
    protected function buildQuery(array $params) {
        $qb = $this->getDoctrine()->getRepository($this->getEntityClass())
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = true');
        $this->prepareQuery($qb, $params);
        foreach($params as $k => $v) {
            if($k != self::PAGE_PARAM && $v != null && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        } 
        $this->beforeExecuteQuery($qb, $params);
        return $qb;
    }
    
    protected function runQuery(array $params, $page = null) {
        if(is_numeric($page)) {
            return $this->buildQuery($params)
                ->setMaxResults(self::PAGE_SIZE)
                ->setFirstResult(self::PAGE_SIZE * $page)
                ->getQuery()->getResult();
        }
        return $this->buildQuery($params)->getQuery()->getResult();
    }
    
    protected function handleValidationErrors(ConstraintViolationListInterface $errors) {
        return $this->handleView(View::create($errors, Codes::HTTP_BAD_REQUEST));
    }
    
    /* Métodos de extensão */
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {

    }
    
    protected function beforeExecuteQuery(QueryBuilder $qb, array $params) {

    }
    
    protected function beforeCreate($entidade) {
        
    }
    
    protected function afterCreate($entidade) {
        
    }
    
    protected function beforeUpdate($entidade) {
        
    }
    
    protected function afterUpdate($entidade) {
        
    }
    
    protected function beforeRemove($entidade) {
        
    }
    
    protected function afterRemove($entidade) {
        
    }
    
}

/**  Wrapper class for DELETE batch operation */
class IdCollection {
    
    /** @JMS\Type("array<integer>") */
    public $ids;
    
}

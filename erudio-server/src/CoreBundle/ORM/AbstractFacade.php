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

use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use CoreBundle\ORM\Exception\IllegalUpdateException;

/**
* Classe que especifica um serviço CRUD de uma entidade, usado como uma interface única de operações sobre ela. 
*/
abstract class AbstractFacade {
    
    const DEFAULT_QUERY_ALIAS = 'entidade';
    const ATTR_ID = 'id';
    const ATTR_ATIVO = 'ativo';
    const PAGE_SIZE = 50;
    
    protected $orm;
    
    public function __construct (Registry $doctrine) {
        $this->orm = $doctrine;
    }
    
    abstract function getEntityClass();
    
    function queryAlias() {
        return self::DEFAULT_QUERY_ALIAS;
    }
    
    function parameterMap() {
        return array();
    }
    
    function uniqueMap() {
        return array();
    }
    
    function find($id) {
        $entidade = $this->loadEntity($id);
        if(!$entidade) {
            throw new EntityNotFoundException();
        }
        return $entidade;
    }
    
    function findDeleted($id) {
        $entidade = $this->loadDeletedEntity($id);
        if(!$entidade) {
            throw new EntityNotFoundException();
        }
        return $entidade;
    }

    function findAll($params, $page = null) {
        if(is_numeric($page)) {
            return $this->buildQuery($params)
                ->setMaxResults(self::PAGE_SIZE)
                ->setFirstResult(self::PAGE_SIZE * $page)
                ->getQuery()->getResult();
        }
        return $this->buildQuery($params)->getQuery()->getResult();
    }
    
    function findAllDeleted($params, $page = null) {
        if(is_numeric($page)) {
            return $this->buildReverseQuery($params)
                ->setMaxResults(self::PAGE_SIZE)
                ->setFirstResult(self::PAGE_SIZE * $page)
                ->getQuery()->getResult();
        }
        return $this->buildReverseQuery($params)->getQuery()->getResult();
    }

    function create($entidade) {
        try {
            $this->orm->getManager()->beginTransaction();
            $entidade->init();
            $this->beforeCreate($entidade);
            $this->orm->getManager()->persist($entidade);
            $this->orm->getManager()->flush();
            $this->afterCreate($entidade);
            $this->orm->getManager()->commit();
            return $entidade;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    function createBatch(ArrayCollection $entidades) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($entidades as $entidade) {
                $entidade->init();
                $this->beforeCreate($entidade);
                $this->orm->getManager()->persist($entidade);
                $this->orm->getManager()->flush();
                $this->afterCreate($entidade);
            }
            $this->orm->getManager()->commit();
            return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }

    function update($id, $mergeObject) {
        try {
            $this->orm->getManager()->detach($mergeObject);
            $entidade = $this->loadEntity($id);
            if($entidade === null) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_NOT_FOUND);
            }
            if(!$entidade instanceof AbstractEditableEntity) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_IS_READONLY);
            }
            $this->orm->getManager()->beginTransaction();
            $this->beforeUpdate($entidade);
            $entidade->merge($mergeObject);
            $this->orm->getManager()->flush();
            $this->afterUpdate($entidade);
            $this->orm->getManager()->commit();
            return $entidade;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    function updateDeleted($id, $mergeObject) {
        try {
            $this->orm->getManager()->detach($mergeObject);
            $entidade = $this->loadDeletedEntity($id);
            if($entidade === null) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_NOT_FOUND);
            }
            if(!$entidade instanceof AbstractEditableEntity) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_IS_READONLY);
            }
            $this->orm->getManager()->beginTransaction();
            $this->beforeUpdate($entidade);
            $entidade->mergeDeleted($mergeObject);
            $this->orm->getManager()->flush();
            $this->afterUpdate($entidade);
            $this->orm->getManager()->commit();
            return $entidade;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    function updateBatch(ArrayCollection $mergeObjects) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($mergeObjects as $mergeObject) {
                $this->orm->getManager()->detach($mergeObject);
                $entidade = $this->loadEntity($mergeObject->getId());
                if($entidade === null) {
                    throw new IllegalUpdateException(IllegalUpdateException::OBJECT_NOT_FOUND);
                }
                if(!$entidade instanceof AbstractEditableEntity) {
                    throw new IllegalUpdateException(IllegalUpdateException::OBJECT_IS_READONLY);
                }
                $this->beforeUpdate($entidade);
                $entidade->merge($mergeObject);
                $this->orm->getManager()->flush();
                $this->afterUpdate($entidade);
            }
            $this->orm->getManager()->commit();
            return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    function remove($id) {
        $entidade = $this->loadEntity($id);
        if($entidade != null) {
            try {
                $this->orm->getManager()->beginTransaction();
                $this->beforeRemove($entidade);
                $entidade->finalize();
                $this->orm->getManager()->flush();
                $this->afterRemove($entidade);
                $this->orm->getManager()->commit();
                return true;
            } catch(\Exception $ex) {
                $this->orm->getManager()->rollback();
                throw $ex;
            }
        }
    }
    
    function removeBatch(array $ids) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($ids as $id) {
                $entidade = $this->loadEntity($id);
                if($entidade != null) {
                    $this->beforeRemove($entidade);
                    $entidade->finalize();
                    $this->orm->getManager()->merge($entidade);
                    $this->orm->getManager()->flush();
                    $this->afterRemove($entidade);
                } else {
                    throw new EntityNotFoundException();
                }
            }
            $this->orm->getManager()->commit();
            return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    protected function loadEntity($id, $entityClass = null) {
        $entityClass = $entityClass == null ? $this->getEntityClass() : $entityClass;
        $qb = $this->orm->getRepository($entityClass)
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = true')
            ->andWhere($this->queryAlias() . '.' . self::ATTR_ID . ' = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleResult();
    }
    
    protected function loadDeletedEntity($id, $entityClass = null) {
        $entityClass = $entityClass == null ? $this->getEntityClass() : $entityClass;
        $qb = $this->orm->getRepository($entityClass)
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = false')
            ->andWhere($this->queryAlias() . '.' . self::ATTR_ID . ' = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleResult();
    }
    
    protected function buildQuery(array $params) {
        $qb = $this->orm->getRepository($this->getEntityClass())
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = true');
        $this->prepareQuery($qb, $params);
        foreach($params as $k => $v) {
            if($v != null && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        }
        $this->beforeExecuteQuery($qb, $params);
        return $qb;
    }
    
    protected function buildReverseQuery(array $params) {
        $qb = $this->orm->getRepository($this->getEntityClass())
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = false');
        $this->prepareQuery($qb, $params);
        foreach($params as $k => $v) {
            if($v != null && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        }
        $this->beforeExecuteQuery($qb, $params);
        return $qb;
    }
    
    /* Métodos de extensão */
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {}
    
    protected function beforeExecuteQuery(QueryBuilder $qb, array $params) {}
    
    protected function beforeCreate($entidade) {}
    
    protected function afterCreate($entidade) {}
    
    protected function beforeUpdate($entidade) {}
    
    protected function afterUpdate($entidade) {}
    
    protected function beforeRemove($entidade) {}
    
    protected function afterRemove($entidade) {}
    
}



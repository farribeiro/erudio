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

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use CoreBundle\ORM\Exception\IllegalUpdateException;
use CoreBundle\ORM\Exception\UniqueViolationException;

/**
* Classe que especifica um serviço de operações sobre uma entidade. Estão inclusas
* as operações CRUD básicas, com métodos de extensão para realizar operações antes
* e após uma inclusão, atualização ou remoção.
* 
*/
abstract class AbstractFacade {
    
    const DEFAULT_QUERY_ALIAS = 'entidade';
    const ATTR_ID = 'id';
    const ATTR_ATIVO = 'ativo';
    const PAGE_SIZE = 50;
    const MAX_RESULTS = 300;
    
    protected $orm;
    protected $logger;
    protected $eventDispatcher;
    
    function __construct (RegistryInterface $doctrine, LoggerInterface $logger = null, 
            EventDispatcherInterface $eventDispatcher = null) {
        $this->orm = $doctrine;
        $this->logger = $logger;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    abstract function getEntityClass();
    
    /**
    * Retorna o alias usado nas queries das funções de consulta nesta classe.
    * 
    * @return string alias usado na query de consulta
    */
    function queryAlias() {
        return self::DEFAULT_QUERY_ALIAS;
    }
    
    /**
    * Define um mapa de possíveis parâmetros de busca da entidade, onda as chaves são
    * os nomes dos parâmetros e os valores são funções para adicioná-los na query. 
    * 
    * Cada função deve receber como parâmetros um queryBuilder e o valor de busca
    * passado como argumento.
    * 
    * @return array mapa de parâmetros de busca
    */
    function parameterMap() {
        return [];
    }
    
    /**
    * Define um mapa de regras de unicidade, checado nas operações de inserção e atualização
    * para garantir que não sejam criados objetos conflitantes. A estrutura do mapa é
    * basicamente um array de arrays, onde cada array interno contém como chaves os nomes dos 
    * atributos a serem checados na regra, e os valores dos mesmos na entidade passada como
    * parâmetro. 
    * 
    * @param AbstractEntity $entidade
    * @return array mapa de regras de unicidade
    */
    function uniqueMap($entidade) {
        return [];
    }
    
    /**
     * Recupera uma entidade por seu id numérico.
     * 
     * @param int $id
     * @return AbstractEntity entidade encontrada
     * @throws EntityNotFoundException caso não exista entidade ativa com este id
     */
    function find($id) {
        $entidade = $this->loadEntity($id);
        if(!$entidade) {
            throw new EntityNotFoundException();
        }
        return $entidade;
    }
    
    /**
    * Recupera uma entidade, e apenas uma, de acordo com os parâmetros de busca informados,
    * ou null, caso nenhuma seja encontrada.
    * 
    * @param array $params
    * @return AbstractEntity entidade encontrada
    */
    function findOne($params = []) {
        return $this->buildQuery($params)->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }

    /**
    * Recupera uma entidade, e apenas uma, de acordo com os parâmetros de busca informados,
    * ou um array vazio, caso nenhuma seja encontrada.
    * 
    * @param array $params
    * @return array entidades encontradas, ou um array vazio
    */
    function findAll($params = [], $page = null, $limit = self::MAX_RESULTS) {
        if(is_numeric($page)) {
            return $this->buildQuery($params)
                ->setMaxResults(self::PAGE_SIZE)
                ->setFirstResult(self::PAGE_SIZE * $page)
                ->getQuery()->getResult();
        }
        return $this->buildQuery($params)->setMaxResults($limit)->getQuery()->getResult();
    }

    /**
     * Retorna a contagem de entidades que satisfazem os parâmetros de busca informados.
     * 
     * @param array $params
     * @return int quantidade de entidades
     */
    function count($params = []) {
        return $this->buildQuery($params)
            ->select("COUNT({$this->queryAlias()}.id)")
            ->getQuery()->getSingleScalarResult();
    }
    
    /**
     * 
     * @param type $entidade
     * @param type $isTransaction
     * @return type
     * @throws \Exception
     */
    function create($entidade, $isTransaction = true) {
        try {
            if ($isTransaction) { $this->orm->getManager()->beginTransaction(); }
            $entidade->init();
            $this->beforeCreate($entidade);
            $this->checkUniqueness($entidade);
            $this->orm->getManager()->persist($entidade);
            $this->orm->getManager()->flush();
            $this->afterCreate($entidade);
            if ($isTransaction) { $this->orm->getManager()->commit(); }
            return $entidade;
        } catch(\Exception $ex) {
            if ($isTransaction) { $this->orm->getManager()->rollback(); }
            throw $ex;
        }
    }
    
    /**
     * 
     * @param ArrayCollection $entidades
     * @return boolean
     * @throws \Exception
     */
    function createBatch(ArrayCollection $entidades) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($entidades as $entidade) {
                $this->create($entidade, false);
            }
           $this->orm->getManager()->commit();
           return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }

    /**
    * 
    * @param type $id
    * @param type $mergeObject
    * @param type $isTransaction
    * @return \CoreBundle\ORM\AbstractEditableEntity
    * @throws \Exception
    * @throws IllegalUpdateException
    */
    function update($id, $entidade, $isTransaction = true) {
        try {
            $mergeObject = clone $entidade;
            $this->orm->getManager()->detach($mergeObject);
            $this->orm->getManager()->refresh($entidade);
            if ($entidade === null) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_NOT_FOUND);
            }
            if (!$entidade instanceof AbstractEditableEntity) {
                throw new IllegalUpdateException(IllegalUpdateException::OBJECT_IS_READONLY);
            }
            if ($isTransaction) { $this->orm->getManager()->beginTransaction(); }
            $this->beforeUpdate($entidade);
            $entidade->merge($mergeObject);
            $this->orm->getManager()->flush();
            $this->checkUniqueness($entidade, true);
            $this->afterUpdate($entidade);
            if ($isTransaction) { $this->orm->getManager()->commit(); }
            return $entidade;
        } catch(\Exception $ex) {
            if ($isTransaction) { $this->orm->getManager()->rollback(); }
            throw $ex;
        }
    }
    
    /**
    * 
    * @param ArrayCollection $mergeObjects
    * @return boolean
    * @throws \Exception
    */
    function updateBatch(ArrayCollection $mergeObjects) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($mergeObjects as $mergeObject) {
                $this->update($mergeObject->getId(), $mergeObject, false);
            }
            $this->orm->getManager()->commit();
            return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    /**
    * 
    * @param type $id
    * @param type $isTransaction
    * @return boolean
    * @throws EntityNotFoundException
    * @throws \Exception
    */
    function remove($id, $isTransaction = true) {
        $entidade = $this->loadEntity($id);
        if ($entidade === null) {
            throw new EntityNotFoundException();
        }
        try {
            if ($isTransaction) { $this->orm->getManager()->beginTransaction(); }
            $this->beforeRemove($entidade);
            $entidade->finalize();
            $this->orm->getManager()->flush($entidade);
            $this->afterRemove($entidade);
            if ($isTransaction) { $this->orm->getManager()->commit(); }
            return true;
        } catch (\Exception $ex) {
            if ($isTransaction) { $this->orm->getManager()->rollback(); }
            throw $ex;
        }
    }
    
    /**
    * 
    * @param array $ids
    * @return boolean
    * @throws \Exception
    */
    function removeBatch(array $ids) {
        try {
            $this->orm->getManager()->beginTransaction();
            foreach($ids as $id) {
                $this->remove($id, false);
            }
            $this->orm->getManager()->commit();
            return true;
        } catch(\Exception $ex) {
            $this->orm->getManager()->rollback();
            throw $ex;
        }
    }
    
    /**
    * Define a string padrão a ser usada na cláusula select da query de busca. 
    * Importante ressaltar que os joins necessários para carregar entidades associadas 
    * devem ser realizados na query, tipicamente por meio do método prepareQuery.
    * 
    * @return array mapa de regras de unicidade
    */
    protected function selectMap() {
        return [$this->queryAlias()];
    }
    
    /**
    * 
    * @param type $id
    * @param type $entityClass
    * @return type
    */
    protected function loadEntity($id, $entityClass = null) {
        $class = $entityClass === null ? $this->getEntityClass() : $entityClass;
        $qb = $this->orm->getRepository($class)
            ->createQueryBuilder($this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = true')
            ->andWhere($this->queryAlias() . '.' . self::ATTR_ID . ' = :id')
            ->setParameter('id', $id);
        return $qb->getQuery()->getSingleResult();
    }
    
    /**
    * 
    * @param array $params
    * @return type
    */
    protected function buildQuery(array $params) {
        $qb = $this->orm->getManager()->createQueryBuilder()
            ->select($this->selectMap())
            ->from($this->getEntityClass(), $this->queryAlias())
            ->where($this->queryAlias() . '.' . self::ATTR_ATIVO . ' = true');
        $this->prepareQuery($qb, $params);
        foreach($params as $k => $v) {
            if(!is_null($v) && $v !== '' && key_exists($k, $this->parameterMap())) {
                $f = $this->parameterMap()[$k];
                $f($qb, $v);
            }
        }
        $this->beforeExecuteQuery($qb, $params);
        return $qb;
    }
    
    /**
    * 
    * @param type $entidade
    * @param type $isUpdate
    * @throws UniqueViolationException
    */
    protected function checkUniqueness($entidade, $isUpdate = false) {
        foreach ($this->uniqueMap($entidade) as $constraint) {
            if (count($this->findAll($constraint)) > ($isUpdate ? 1 : 0)) {
                throw new UniqueViolationException();
            }
        }
    }
    
    /**
    * 
    * @param type $entidade
    */
    protected function prepareQuery(QueryBuilder $qb, array $params) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function beforeExecuteQuery(QueryBuilder $qb, array $params) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function beforeCreate($entidade) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function afterCreate($entidade) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function beforeUpdate($entidade) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function afterUpdate($entidade) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function beforeRemove($entidade) {}
    
    /**
    * 
    * @param type $entidade
    */
    protected function afterRemove($entidade) {}
    
}



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

namespace CoreBundle\Event;

use CoreBundle\ORM\AbstractEntity;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
* Evento que representa a ocorrência de uma operação de escrita sobre uma entidade.
* 
* Listeners registrados na aplicação podem capturar estes eventos e realizar outras 
* ações em resposta, tendo como informações a entidade (após a aplicação da operação) 
* e o tipo de ação executado, os quais estão definidos como constantes na classe.
* 
* O padrão de nomenclatura usado para os eventos será sempre o nome do repositório
* da entidade, que segue o padrão NomeBundle:NomeClasse, seguido do caracter : e do 
* nome da ação executada.
*/
class EntityEvent extends Event {
    
    const ACTION_CREATED = 'Created';
    const ACTION_UPDATED = 'Updated';
    const ACTION_REMOVED = 'Removed';
    
    private $entity;
    private $action;
    
    function __construct(AbstractEntity $entity, $action = self::ACTION_CREATED) {
        $this->entity = $entity;
        $this->action = $action;
    }
    
    function getEntity() {
        return $this->entity;
    }
    
    function getAction() {
        return $this->action;
    }
    
    /**
    * Retorna o nome identificador do evento.
    * @return string nome do evento
    */
    function getName() {
        return $this->entity->getRepository() . ':' . $this->action;
    }
    
    /**
     * Cria e envia um evento para o dispatcher passado como argumento.
     * @param AbstractEntity $entity
     * @param type $action
     * @param EventDispatcherInterface $dispatcher
     */
    static function createAndDispatch(AbstractEntity $entity, $action, EventDispatcherInterface $dispatcher) {
        $event = new EntityEvent($entity, $action);
        $dispatcher->dispatch($event->getName(), $event);
    }
    
}

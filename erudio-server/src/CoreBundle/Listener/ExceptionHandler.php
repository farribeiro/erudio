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

namespace CoreBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use CoreBundle\Exception\PublishedException;

/**
* Serviço de tratamento de exceções publicadas, ou seja, exceções que devem ser
* retornadas ao cliente com um feedback do erro cometido. Basicamente, as exceções
* são interceptadas e convertidas em um objeto JSON padronizado.
*/
class ExceptionHandler implements EventSubscriberInterface {
    
    /**
    * Define o evento kernel.exception do Symfony como monitorado por este listener.
    * @return array eventos monitorados
    */
    static function getSubscribedEvents() {
        return ['kernel.exception' => 'onException'];
    }

    /**
    * Realiza o tratamento adequado da exceção de acordo com sua natureza. Atualmente
    * são divididos dois tipos de exceção, de autenticação e de validade da operação.
    * 
    * @param GetResponseForExceptionEvent $event
    */
    function onException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        if ($exception instanceof PublishedException) {
            $event->setResponse(
                $this->createResponse(JsonResponse::HTTP_BAD_REQUEST, $exception->getMessage())
            );
        } else if ($exception instanceof AuthenticationException) {
            $event->setResponse(
                $this->createResponse(JsonResponse::HTTP_UNAUTHORIZED, $exception->getMessage())
            );
        }
    }
    
    /**
     * Cria o objeto de resposta de erro a ser retornado ao cliente.
     * 
     * @param int $httpCode
     * @param string $message
     * @return JsonResponse resposta JSON
     */
    function createResponse($httpCode, $message) {
        return new JsonResponse([
            'error' => [
                'code' => $httpCode,
                'message' => $message
            ]
        ], $httpCode);
    }
    
}

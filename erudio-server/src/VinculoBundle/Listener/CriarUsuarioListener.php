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

namespace VinculoBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use VinculoBundle\Event\VinculoEvent;
use AuthBundle\Service\UsuarioFacade;
use AuthBundle\Entity\Usuario;
use PessoaBundle\Service\PessoaFisicaFacade;

/**
* Gera um usuário para a pessoa vinculada à uma instituição de ensino, caso ela ainda
* não possua. Estes usuários recebem como login padrão o seu CPF para garantia da unicidade.
*
*/
class CriarUsuarioListener implements EventSubscriberInterface {
    
     private $usuarioFacade;
     private $pessoaFacade;
    
    function __construct(UsuarioFacade $usuarioFacade, PessoaFisicaFacade $pessoaFacade) {
        $this->usuarioFacade = $usuarioFacade;
        $this->pessoaFacade = $pessoaFacade;
    }
    
    static function getSubscribedEvents() {
        return [VinculoEvent::VINCULO_CRIADO => 'onVinculoCriado'];
    }
    
    function onVinculoCriado(VinculoEvent $event) {
        $pessoa = $event->getVinculo()->getFuncionario();
        if (!$pessoa->getUsuario()) {
            $usuario = Usuario::criarUsuario($pessoa, $pessoa->getCpfCnpj());
            $this->usuarioFacade->create($usuario);
            $pessoa->setUsuario($usuario);
            $this->pessoaFacade->update($pessoa->getId(), $pessoa);
        }
    }
    
}

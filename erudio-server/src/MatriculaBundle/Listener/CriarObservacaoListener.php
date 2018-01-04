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

namespace MatriculaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use CoreBundle\ORM\Exception\UniqueViolationException;
use MatriculaBundle\Service\ObservacaoHistoricoFacade;
use MatriculaBundle\Entity\EtapaCursada;
use MatriculaBundle\Entity\ObservacaoHistorico;
use CoreBundle\Event\EntityEvent;

/**
 * Cria uma observação do histórico em resposta à criação de uma etapa cursada, caso
 * a etapa tenha uma observação padrão definida e o status da etapa cursada seja aprovado.
 */
class CriarObservacaoListener implements EventSubscriberInterface {
    
    private $observacaoFacade;
    
    function __construct(ObservacaoHistoricoFacade $observacaoFacade) {
        $this->observacaoFacade = $observacaoFacade;
    }
    
    static function getSubscribedEvents() {
        return ['MatriculaBundle:EtapaCursada:Created' => 'onEtapaCursadaCriada'];
    }
    
    function onEtapaCursadaCriada(EntityEvent $event) {
        $etapaCursada = $event->getEntity();
        if ($etapaCursada->isAprovado() && $etapaCursada->getEtapa()->getObservacaoAprovacao()) {
            $this->criarObservacaoAutomatica($etapaCursada);
        }
    }
    
    function criarObservacaoAutomatica(EtapaCursada $etapaCursada) {
        try {
            $observacao = ObservacaoHistorico::criar(
                $etapaCursada->getMatricula(), 
                $etapaCursada->getEtapa()->getObservacaoAprovacao()
            );
            $this->observacaoFacade->create($observacao, false);
        } catch (UniqueViolationException $ex) {
            //ignorar
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

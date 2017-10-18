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
use MatriculaBundle\Service\EtapaCursadaFacade;
use MatriculaBundle\Entity\EtapaCursada;
use CoreBundle\Event\EntityEvent;

class CriarEtapaCursadaListener implements EventSubscriberInterface {
    
    private $etapaCursadaFacade;
    
    function __construct(EtapaCursadaFacade $etapaCursadaFacade) {
        $this->etapaCursadaFacade = $etapaCursadaFacade;
    }
    
    static function getSubscribedEvents() {
        return ['MatriculaBundle:Enturmacao:Updated' => 'onEnturmacaoAtualizada'];
    }
    
    function onEnturmacaoAtualizada(EntityEvent $event) {
        $enturmacao = $event->getEntity();
        if ($enturmacao->getConcluido()) {
            $status = $this->etapaCursadaFacade->isCompleta(
                    $enturmacao->getMatricula(), $enturmacao->getTurma()->getEtapa());
            if ($status) {
                $this->criarEtapaCursada($enturmacao, $status);
            }
        }
    }
    
    private function criarEtapaCursada($enturmacao, $status) {
        $unidadeEnsino = $enturmacao->getTurma()->getUnidadeEnsino();
        try {
            $etapaCursada = new EtapaCursada(
                $enturmacao->getMatricula(), 
                $enturmacao->getTurma()->getEtapa(), 
                $enturmacao->getTurma()->getAno(),
                $unidadeEnsino->getNomeCompleto(), 
                $unidadeEnsino->getEndereco()->getCidade(), 
                $status,
                $enturmacao
            );
            $this->etapaCursadaFacade->create($etapaCursada);
        } catch (UniqueViolationException $ex) {
            //ignorar
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}

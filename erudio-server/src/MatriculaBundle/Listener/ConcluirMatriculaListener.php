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
use MatriculaBundle\Service\MatriculaFacade;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\Enturmacao;
use CoreBundle\Event\EntityEvent;

/**
 * Altera o status da matrícula para APROVADO caso seja registrada a última etapa
 * cursada do curso em seu histórico.
 */
class ConcluirMatriculaListener implements EventSubscriberInterface {
    
   private $matriculaFacade;
    
    function __construct(MatriculaFacade $matriculaFacade) {
        $this->matriculaFacade = $matriculaFacade;
    }
    
    static function getSubscribedEvents() {
        return ['MatriculaBundle:EtapaCursada:Created' => 'onEtapaCursadaCriada'];
    }
    
    function onEtapaCursadaCriada(EntityEvent $event) {
        $etapaCursada = $event->getEntity();
        $quantidadeEtapas = $etapaCursada->getMatricula()->getCurso()->getEtapas()->count();
        if ($etapaCursada->getEtapa()->getOrdem() === $quantidadeEtapas) {
            $this->aprovarMatricula($etapaCursada->getMatricula(), $etapaCursada->getEnturmacao());
        }
    }
    
    function aprovarMatricula(Matricula $matricula, Enturmacao $enturmacaoUltimoAno) {
        $dataConclusao = $enturmacaoUltimoAno->getTurma()->getCalendario()->getDataTermino();
        $matricula->concluir($dataConclusao);
        $this->matriculaFacade->update($matricula);
    }

}

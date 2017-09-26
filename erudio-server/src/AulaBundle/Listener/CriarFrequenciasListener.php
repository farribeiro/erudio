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

namespace AulaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Collections\ArrayCollection;
use CoreBundle\Event\EntityEvent;
use MatriculaBundle\Service\EnturmacaoFacade;
use MatriculaBundle\Entity\Enturmacao;
use AulaBundle\Service\FrequenciaFacade;
use AulaBundle\Entity\Aula;
use AulaBundle\Entity\Frequencia;

class CriarFrequenciasListener implements EventSubscriberInterface {
    
    private $enturmacaoFacade;
    private $frequenciaFacade;
    
    function __construct(EnturmacaoFacade $enturmacaoFacade, FrequenciaFacade $frequenciaFacade) {
        $this->enturmacaoFacade = $enturmacaoFacade;
        $this->frequenciaFacade = $frequenciaFacade;
    }

    static function getSubscribedEvents() {
        return ['AulaBundle:Aula:Created' => 'onAulaCriada'];
    }
    
    function onAulaCriada(EntityEvent $event) {
        $aula = $event->getEntity();
        $frequencias = $this->gerarFrequencias($aula);
        $this->frequenciaFacade->createBatch(new ArrayCollection($frequencias));
    }
    
    function gerarFrequencias(Aula $aula) {
        $enturmacoes = $this->enturmacaoFacade->findAll([
            'dataAula' => $aula->getDia()->getData(),
            'turma' => $aula->getTurma()
        ]);
        return array_map(function($enturmacao) use ($aula) {
           return $this->gerarFrequencia($enturmacao, $aula);
        }, $enturmacoes);
    }
    
    function gerarFrequencia(Enturmacao $enturmacao, Aula $aula) {
        $disciplinasOfertadas = $aula->getDisciplinasOfertadas()->toArray();
        $disciplinasCursadas = $enturmacao->getDisciplinasCursadas()
            ->filter(function($d) use ($disciplinasOfertadas) {
                return $d->possuiEquivalente($disciplinasOfertadas);
            })->toArray();
        return new Frequencia($aula, Frequencia::PRESENCA, $disciplinasCursadas);
    }
    
}

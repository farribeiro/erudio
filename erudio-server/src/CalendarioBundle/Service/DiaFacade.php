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

namespace CalendarioBundle\Service;

use Doctrine\ORM\QueryBuilder;
use \CoreBundle\ORM\AbstractFacade;
use \CalendarioBundle\Entity\Calendario;

class DiaFacade extends AbstractFacade {
    
    public function getEntityClass() {
        return 'CalendarioBundle:Dia';
    }
    
    function queryAlias() {
        return 'd';
    }
    
    function parameterMap() {
        return array (
            'data' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('d.data = :dia')->setParameter('dia', $value);
            }
        );
    }
    
    protected function prepareQuery(QueryBuilder $qb, array $params) {
        $qb->join('d.calendario', 'calendario')
            ->andWhere('calendario.id = :calendario')->setParameter('calendario', $params['calendario']);
    }

    protected function afterUpdate($dia) {
        $calendario = $dia->getCalendario();
        if(!$calendario instanceof Calendario) {
            $dias = $this->orm->getRepository('CalendarioBundle:Dia')->find(array(
                'data' => $dia->getData(), 'calendario' => $calendario->getId()
            ));
            foreach ($dias as $d) {
                if($d->getEfetivo !== $dia->getEfetivo || $d->getLetivo !== $dia->getLetivo) {
                    $d->setEfetivo($dia->getEfetivo());
                    $d->setLetivo($dia->getLetivo());
                    $this->orm->getManager()->merge($d);
                }                    
            }                
        }
    }
}

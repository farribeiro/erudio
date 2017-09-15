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

namespace MatriculaBundle\Service;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\ORM\AbstractFacade;
use CursoBundle\Entity\Etapa;
use MatriculaBundle\Entity\Matricula;
use MatriculaBundle\Entity\DisciplinaCursada;
use MatriculaBundle\Entity\EtapaCursada;

class EtapaCursadaFacade extends AbstractFacade {
   
    private $disciplinaCursadaFacade;
    
    function __construct(RegistryInterface $doctrine, DisciplinaCursadaFacade $disciplinaCursadaFacade) {
        parent::__construct($doctrine);
        $this->disciplinaCursadaFacade = $disciplinaCursadaFacade;
    }
    
    function getEntityClass() {
        return 'MatriculaBundle:EtapaCursada';
    }

     function queryAlias() {
        return 'e';
    }
    
    function parameterMap() {
        return [
            'matricula' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('e.matricula = :matricula')->setParameter('matricula', $value);
            }
        ];
    }
    
    function isCompleta(Matricula $matricula, Etapa $etapa) {
        $disciplinasCursadas = $this->disciplinaCursadaFacade->findByMatriculaAndEtapa($matricula, $etapa, false);
        $quantidadeDisciplinas = count($etapa->getDisciplinas());
        if ($quantidadeDisciplinas > count($disciplinasCursadas)) {
            return false;
        }
        $quantidadeAprovacoes = count(array_unique(
            array_map(
                array_filter($disciplinasCursadas, function($d) {
                    return $d->getStatus() != DisciplinaCursada::STATUS_REPROVADO;
                }),
                function($d) {
                    return $d->getDisciplina();
                }
            )
        ));
        return $quantidadeDisciplinas === $quantidadeAprovacoes 
                ? EtapaCursada::STATUS_APROVADO : EtapaCursada::STATUS_REPROVADO;
    }
    
}

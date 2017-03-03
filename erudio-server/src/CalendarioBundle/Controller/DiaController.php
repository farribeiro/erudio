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

namespace CalendarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use CoreBundle\REST\AbstractEntityResource;

class DiaController extends AbstractEntityResource {
    
    function getEntityClass() {
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
    
    /**
        *   @ApiDoc()
        *  
        *   @FOS\Get("calendarios/{id}/dias")
        * 
        *   @FOS\QueryParam(name = "page", requirements="\d+", default = null) 
        *   @FOS\QueryParam(name = "data", default = null) 
        */
    function cgetAction(Request $request, $id, ParamFetcher $paramFetcher) {
        $params = $paramFetcher->all();
        $params['calendario'] = $id;
        return $this->getList($params);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Get("calendarios/{id}/meses/{mes}")
        */
    function getByMesAction(Request $request, $id, $mes) {
        $calendario = $this->loadEntity($id, 'CalendarioBundle:Calendario');
        if(!$calendario) {
            throw $this->createNotFoundException();
        }
        $mes = strlen($mes) < 2 ? '0' . $mes : $mes;
        $qb = $this->getDoctrine()->getRepository('CalendarioBundle:Dia')->createQueryBuilder('d');
        $dias = $qb->join('d.calendario', 'c')
                   ->where('c.id = :calendario')->andWhere('d.data LIKE :mes')->andWhere('d.ativo = :ativo')
                   ->setParameter('calendario', $id)
                   ->setParameter('ativo',1)
                   ->setParameter('mes', $calendario->getAno() . '-' . $mes . '%')
                   ->getQuery()->getResult();
        $view = View::create($dias, Codes::HTTP_OK);
        $view->getSerializationContext()->setGroups(array(self::SERIALIZER_GROUP_LIST));
        return $this->handleView($view);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Put("calendarios/{id}/dias")
        *  @ParamConverter("dias", converter="fos_rest.request_body")
        */
    function putBatchAction(Request $request, $id, DiaCollection $dias, ConstraintViolationListInterface $errors) {
        if(count($errors) > 0) {
            return $this->handleValidationErrors($errors);
        }
        return $this->updateBatch($dias->dias);
    }
    
    /**
        *  @ApiDoc()
        * 
        *  @FOS\Get("dias/atual")
        */
    function getDataAtualAction() {
        $dateTime = new \DateTime();
        $dataAtual = date_format($dateTime, 'Y-m-d');
        $view = View::create($dataAtual, Codes::HTTP_OK);
        return $this->handleView($view);   
    }
    
}

/**  Wrapper class for batch operations*/
class DiaCollection {
    
    /** @JMS\Type("ArrayCollection<CalendarioBundle\Entity\Dia>") */
    public $dias;
    
}

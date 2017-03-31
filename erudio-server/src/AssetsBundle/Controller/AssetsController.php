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

namespace AssetsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AssetsBundle\Entity\Asset;
use AssetsBundle\Form\AssetType;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated
 * @RouteResource("assets")
 */
class AssetsController {
    
    function getEntityClass() {
        return 'AssetsBundle:Asset';
    }
    
    function queryAlias() {
        return 't';
    }
    
    function parameterMap() {
        return array (
            'file' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.file LIKE :file')->setParameter('file', '%' . $value . '%');
            },
            'label' => function(QueryBuilder $qb, $value) {
                $qb->andWhere('t.label LIKE :label')->setParameter('label', '%' . $value . '%');
            }
        );
    }
    
    /**
        *   @ApiDoc()
        */
    function getAction(Request $request, $id) {
        return $this->getOne($id);
    }
    
    /**
        *   @ApiDoc()
        * 
        *   @QueryParam(name = "page", requirements="\d+", default = null) 
        *   @QueryParam(name = "file", nullable = true)
        *   @QueryParam(name = "label", nullable = true)
        */
    function cgetAction(Request $request, ParamFetcher $paramFetcher) {
        return $this->getList($paramFetcher);
    }
    
    /**
        *  @ApiDoc()
        *  @Post("assets")
        */
    function postAction(Request $request) {
        try {
            $username = $request->request->get('username');
            $usuario = $this->getDoctrine()->getManager()->getRepository('AuthBundle:Usuario')->findBy(array('username'=>$username));
            if (!empty($usuario)) {
                $user = $usuario[0];
                $pessoa = $user->getPessoa();
                $person = $this->getDoctrine()->getManager()->getRepository('PessoaBundle:Pessoa')->find($pessoa->getId());
                $file = $request->files->get('file');
                $asset = new Asset();
                $asset->setLabel('Avatar de Usuário');
                $asset->setSize($file->getSize());
                $asset->setType($file->getMimeType());                
                $addr = $this->get('kernel')->getRootDir() . '/../web/bundles/assets/uploads';
                $filename = md5(date('d/m/y H:i:s')).'.'.$file->guessExtension();
                $file->move($addr,$filename);
                $asset->setFile($filename);
                $this->getDoctrine()->getManager()->persist($asset);
                $this->getDoctrine()->getManager()->flush();
                $person->setAvatar($asset);
                $this->getDoctrine()->getManager()->merge($person);
                $this->getDoctrine()->getManager()->flush();
                return new Response('Upload realizado.');
            } else {
                return new Response('Usuário não encontrado');
            }
        } catch (Exception $e) {
            return new Response($e->getMessage());
        }
    }
    
    /**
        *   @ApiDoc()
        */
    function deleteAction(Request $request, $id) {
        return $this->remove($id);
    }

}

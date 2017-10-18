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

namespace AuthBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as FOS;
use JMS\Serializer\Annotation as JMS;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use AuthBundle\Service\UsuarioFacade;

class AuthenticationController {
    
    private $tokenManager;
    private $userManager;
    
    function __construct(JWTTokenManagerInterface $tokeManager, UsuarioFacade $userManager) {
        $this->tokenManager = $tokeManager;
        $this->userManager = $userManager;
    }
    
    /**
    * @ApiDoc(
    *   resource = true,
    *   section = "Módulo Autenticação",
    *   description = "Cria e retorna um token de autenticação válido por 15 minutos.",
    *   statusCodes = {
    *       200 = "Token de autenticação",
    *       401 = "Usuário ou senha informados são inválidos"
    *   }
    * )
    * 
    * @FOS\Post("tokens")
    * @ParamConverter("credentials", converter="fos_rest.request_body")
    */
    function getTokenAction(Credentials $credentials) {
        $user = $this->userManager->findOne(['username' => $credentials->username]);
        if (!$user) {
            throw new AuthenticationException('Usuário não encontrado');
        }
        if ($user->getPassword() != md5(base64_decode($credentials->password))) {
            throw new AuthenticationException('Senha incorreta');
        }
        return new JsonResponse(['token' => $this->tokenManager->create($user)]);
    }
    
}

class Credentials {  
    /** @JMS\Type("string") */
    public $username;
    
    /** @JMS\Type("string") */
    public $password;
}

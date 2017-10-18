<?php

namespace SME\IntranetBundle\Listener;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Bundle\DoctrineBundle\Registry;

class LogoutListener implements LogoutSuccessHandlerInterface
{
 
    private $router;
    private $kernel;
    private $providerKey;
    private $em;
 
    public function __construct(RouterInterface $router, Registry $doctrine, $kernel)
    {
       $this->router = $router;
       $this->em = $doctrine;
       $this->kernel = $kernel;
    }
 
    /**
        *  This is called when an interactive authentication attempt succeeds. This
        *  is called by authentication listeners inheriting from AbstractAuthenticationListener.
        *  @param Request        $request
        *  @param TokenInterface $token
        *  @return Response The response to return
        */
   function onLogoutSuccess(Request $request) {
	//$url = "http://voldemort/moodle4/intranetLogout.php?env=".$this->kernel->getEnvironment();
        $url = "http://ead.educacao.itajai.sc.gov.br/intranetLogout.php?env=".$this->kernel->getEnvironment();
        //$url = $this->router->generate('intranet_index');
	return new RedirectResponse($url);
   }
   
   public function getProviderKey() {
   	return $this->providerKey;
   }
   
   public function setProviderKey($providerKey) {
   	$this->providerKey = $providerKey;
   }
}
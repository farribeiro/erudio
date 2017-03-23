<?php
namespace AuthBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use AuthBundle\Security\Authentication\Token\WsseUserToken;

class WsseListener implements ListenerInterface {
    
    protected $tokenStorage;
    protected $authenticationManager;

    public function __construct(TokenStorageInterface $tokenStorage, AuthenticationManagerInterface $authenticationManager) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle(GetResponseEvent $event) {
        $request = $event->getRequest();
        $wsseRegex = '/UsernameToken Username="([^"]+)", PasswordDigest="([^"]+)", Nonce="([^"]+)", Created="([^"]+)"/';
        if (!$request->headers->has('x-wsse') || 1 !== preg_match($wsseRegex, $request->headers->get('x-wsse'), $matches)) {
            return;
        }
        $arr = array();
        $token = new WsseUserToken($arr);
        $token->setUser($matches[1]);
        $token->digest   = $matches[2];
        $token->nonce    = $matches[3];
        $token->created  = $matches[4];
        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->tokenStorage->setToken($authToken);
            return;
        } catch (AuthenticationException $failed) {
            // Para negar a autenticação, limpe o token, que ele redireciona novamente para a página de login.
            // Mas cuidado para limpar o token deste usuário e não de todos.
            //$token = $this->tokenStorage->getToken();
            //if ($token instanceof WsseUserToken && $this->providerKey === $token->getProviderKey()) {
            //     $this->tokenStorage->setToken(null);
            //}
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent(null);
            $event->setResponse($response);
            return;
        }
        // O padrão é negar a autenticação.
        $response = new Response();
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        $event->setResponse($response);
    }
}
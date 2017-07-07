<?php

namespace EnhancedProxy_f9daf9f7257328e51dd595c9857ea2af80b1c8c7\__CG__\SME\ProtocoloBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class ServidorController extends \SME\ProtocoloBundle\Controller\ServidorController
{
    private $__CGInterception__loader;

    public function visualizarAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ServidorController', 'visualizarAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function imprimirAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ServidorController', 'imprimirAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}
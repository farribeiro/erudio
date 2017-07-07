<?php

namespace EnhancedProxy_e26cdb8e2e5d45446f7f14c6138056fe96da34b0\__CG__\SME\ProtocoloBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class ProtocoloController extends \SME\ProtocoloBundle\Controller\ProtocoloController
{
    private $__CGInterception__loader;

    public function tomarPosseAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'tomarPosseAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function formEncaminhamentoAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'formEncaminhamentoAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function formAtualizacaoAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'formAtualizacaoAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function encaminharAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'encaminharAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function cancelarEncaminhamentoAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'cancelarEncaminhamentoAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function atualizarAction(\SME\ProtocoloBundle\Entity\Protocolo $protocolo)
    {
        $ref = new \ReflectionMethod('SME\\ProtocoloBundle\\Controller\\ProtocoloController', 'atualizarAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($protocolo));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($protocolo), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}
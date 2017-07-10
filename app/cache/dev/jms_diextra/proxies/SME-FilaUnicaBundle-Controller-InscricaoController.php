<?php

namespace EnhancedProxyb7349af4_a45e546e95050fd717643845b37cab00e4f09a48\__CG__\SME\FilaUnicaBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class InscricaoController extends \SME\FilaUnicaBundle\Controller\InscricaoController
{
    private $__CGInterception__loader;

    public function reordenarGeralAction()
    {
        $ref = new \ReflectionMethod('SME\\FilaUnicaBundle\\Controller\\InscricaoController', 'reordenarGeralAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array());
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array(), $interceptors);

        return $invocation->proceed();
    }

    public function redefinirAnosEscolaresAction()
    {
        $ref = new \ReflectionMethod('SME\\FilaUnicaBundle\\Controller\\InscricaoController', 'redefinirAnosEscolaresAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array());
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array(), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}
<?php

namespace EnhancedProxyb7349af4_6a8aac35268fbf9e4d62561b9713dc0b88710706\__CG__\SME\FilaUnicaBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class UnidadeEscolarController extends \SME\FilaUnicaBundle\Controller\UnidadeEscolarController
{
    private $__CGInterception__loader;

    public function formAlteracaoAction(\SME\FilaUnicaBundle\Entity\UnidadeEscolar $unidade)
    {
        $ref = new \ReflectionMethod('SME\\FilaUnicaBundle\\Controller\\UnidadeEscolarController', 'formAlteracaoAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($unidade));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($unidade), $interceptors);

        return $invocation->proceed();
    }

    public function alterarAction(\SME\FilaUnicaBundle\Entity\UnidadeEscolar $unidade)
    {
        $ref = new \ReflectionMethod('SME\\FilaUnicaBundle\\Controller\\UnidadeEscolarController', 'alterarAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($unidade));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($unidade), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}
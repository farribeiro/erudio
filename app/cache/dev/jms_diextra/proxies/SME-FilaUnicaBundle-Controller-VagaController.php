<?php

namespace EnhancedProxy_e7e707bcb56d811aae0273bc300574aa9925fd56\__CG__\SME\FilaUnicaBundle\Controller;

/**
 * CG library enhanced proxy class.
 *
 * This code was generated automatically by the CG library, manual changes to it
 * will be lost upon next generation.
 */
class VagaController extends \SME\FilaUnicaBundle\Controller\VagaController
{
    private $__CGInterception__loader;

    public function preencherAction(\SME\FilaUnicaBundle\Entity\Vaga $vaga)
    {
        $ref = new \ReflectionMethod('SME\\FilaUnicaBundle\\Controller\\VagaController', 'preencherAction');
        $interceptors = $this->__CGInterception__loader->loadInterceptors($ref, $this, array($vaga));
        $invocation = new \CG\Proxy\MethodInvocation($ref, $this, array($vaga), $interceptors);

        return $invocation->proceed();
    }

    public function __CGInterception__setLoader(\CG\Proxy\InterceptorLoaderInterface $loader)
    {
        $this->__CGInterception__loader = $loader;
    }
}
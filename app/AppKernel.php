<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            
            new SME\IntranetBundle\IntranetBundle(),
            new SME\CommonsBundle\CommonsBundle(),
            new SME\PDFBundle\PDFBundle(),
            new SME\DGPBundle\DGPBundle(),
            new SME\DGPContratacaoBundle\DGPContratacaoBundle(),
            new SME\DGPFormacaoBundle\DGPFormacaoBundle(),
            new SME\DGPProcessoAnualBundle\DGPProcessoAnualBundle(),
            new SME\DGPPromocaoBundle\DGPPromocaoBundle(),
            new SME\DGPPermutaBundle\DGPPermutaBundle(),
            new SME\ProtocoloBundle\ProtocoloBundle(),
            new SME\FilaUnicaBundle\FilaUnicaBundle(),
            new SME\SuporteTecnicoBundle\SuporteTecnicoBundle(),
            new SME\MoodleBundle\MoodleBundle(),
            new SME\PublicBundle\PublicBundle(),
            new SME\PresencaBundle\PresencaBundle(),
            new SME\QuestionarioBundle\QuestionarioBundle(),
            new SME\EstagioBundle\EstagioBundle(),
            new SME\ErudioBundle\ErudioBundle()
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

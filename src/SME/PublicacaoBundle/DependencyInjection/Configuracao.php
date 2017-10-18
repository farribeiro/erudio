<?php
/*
namespace SME\PublicacaoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuracao implements ConfigurationInterface {
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('publicacoes');

        $rootNode->children()
                 ->scalarNode('maxSize')->defaultValue('')->end()
                 ->scalarNode('root')->defaultValue("")->end()
                 ->end();

        return $treeBuilder;
    }
}
*/
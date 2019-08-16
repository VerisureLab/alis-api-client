<?php

namespace VerisureLab\Library\AlisApiClient\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('alis_api_client');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('connections')
                    ->arrayPrototype()
                        ->scalarNode('base_uri')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('service')
                            ->children()
                                ->scalarNode('authentication_service')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('token_storage')->isRequired()->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
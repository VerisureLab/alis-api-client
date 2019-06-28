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
                ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('base_uri')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('service')
                    ->children()
                        ->scalarNode('token_storage')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
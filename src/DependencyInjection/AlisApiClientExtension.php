<?php

namespace VerisureLab\Library\AlisApiClient\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use VerisureLab\Library\AlisApiClient\Service\Transmitter;

class AlisApiClientExtension extends ConfigurableExtension implements CompilerPassInterface
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('verisure_lab.alis_api_client.base_uri', $mergedConfig['base_uri']);
        $container->setParameter('verisure_lab.alis_api_client.connections', $mergedConfig['connections']);
    }

    public function process(ContainerBuilder $container): void
    {
        $baseUri = $container->getParameter('verisure_lab.alis_api_client.base_uri');

        foreach ($container->getParameter('verisure_lab.alis_api_client.connections') as $connectionName => $settings) {
            $transmitterName = 'verisure_lab.alis_api_client.transmitter.'.$connectionName;

            $transmitterDefinition = $container->register($transmitterName, Transmitter::class);
            $transmitterDefinition
                ->addArgument(new Reference($settings['service']['token_storage']))
                ->addArgument(new Reference($settings['service']['authentication_service']))
                ->addArgument($baseUri);
        }
    }
}
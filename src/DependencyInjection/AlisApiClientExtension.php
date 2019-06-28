<?php

namespace VerisureLab\Library\AlisApiClient\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class AlisApiClientExtension extends ConfigurableExtension implements CompilerPassInterface
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->setParameter('verisure_lab.alis_client.client_id', $mergedConfig['client_id']);
        $container->setParameter('verisure_lab.alis_client.client_secret', $mergedConfig['client_secret']);
        $container->setParameter('verisure_lab.alis_client.base_uri', $mergedConfig['base_uri']);
        $container->setParameter('verisure_lab.alis_client.token_storage', $mergedConfig['service']['token_storage']);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');
    }

    public function process(ContainerBuilder $container)
    {
        $tokenStorage = $container->getDefinition($container->getParameter('verisure_lab.alis_client.token_storage'));

        $container->getDefinition('verisure_lab.alis_client.authentication_service')
            ->setArgument(1, $tokenStorage);

        $container->getDefinition('verisure_lab.alis_client.transmitter')
            ->setArgument(0, $tokenStorage);
    }
}
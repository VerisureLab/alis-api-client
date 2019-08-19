<?php

namespace VerisureLab\Library\AlisApiClient\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use VerisureLab\Library\AlisApiClient\Service\ApiClient;
use VerisureLab\Library\AlisApiClient\Service\ClientBlacklist;
use VerisureLab\Library\AlisApiClient\Service\ClientChannel;
use VerisureLab\Library\AlisApiClient\Service\ClientLead;
use VerisureLab\Library\AlisApiClient\Service\ClientSource;
use VerisureLab\Library\AlisApiClient\Service\ClientSupplier;
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
            $clientArguments = [
                new Reference($settings['service']['token_storage']),
                new Reference($settings['service']['authentication_service']),
                $baseUri
            ];

            $clientLeadDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client.lead', ClientLead::class);
            $clientLeadDefinition->setArguments($clientArguments)->addTag('verisure_lab.alis_api_client.client', ['alias' => 'lead']);

            $clientChannelDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client.channel', ClientChannel::class);
            $clientChannelDefinition->setArguments($clientArguments)->addTag('verisure_lab.alis_api_client.client', ['alias' => 'channel']);

            $clientSourceDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client.source', ClientSource::class);
            $clientSourceDefinition->setArguments($clientArguments)->addTag('verisure_lab.alis_api_client.client', ['alias' => 'source']);

            $clientSupplierDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client.supplier', ClientSupplier::class);
            $clientSupplierDefinition->setArguments($clientArguments)->addTag('verisure_lab.alis_api_client.client', ['alias' => 'supplier']);

            $clientBlacklistDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client.blacklist', ClientBlacklist::class);
            $clientBlacklistDefinition->setArguments($clientArguments)->addTag('verisure_lab.alis_api_client.client', ['alias' => 'blacklist']);

            $clientDefinition = $container->register('verisure_lab.alis_api_client.'.$connectionName.'.client', ApiClient::class);

            $taggedServices = $container->findTaggedServiceIds('verisure_lab.alis_api_client.client');

            foreach ($taggedServices as $id => $tags) {
                foreach ($tags as $attributes) {
                    $clientDefinition->addMethodCall('addClient', [$attributes['alias'], new Reference($id)]);
                }
            }
        }
    }
}
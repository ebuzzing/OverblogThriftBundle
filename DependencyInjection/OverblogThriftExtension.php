<?php

namespace Overblog\ThriftBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OverblogThriftExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('thrift.config.compiler.path', $config['compiler']['path']);

        $container->setParameter('thrift.config.services', $config['services']);

        //Servers
        foreach($config['servers'] as $name => $service)
        {
            $config['servers'][$name]['service_config'] = $config['services'][$service['service']];
        }

        $container->setParameter('thrift.config.servers', $config['servers']);

        //Clients
        foreach($config['clients'] as $name => $client)
        {
            $config['clients'][$name]['service_config'] = $config['services'][$client['service']];
        }

        $container->setParameter('thrift.config.clients', $config['clients']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }
}

<?php
namespace Fhm\MediaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FhmMediaExtension extends Extension
{
    const MEDIA_SERVICE_ALIAS = "fhm_media_service";
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->createMediaServiceAlias($container);
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Create an alias dynamically for the right media service. According to indication in parameter file
     * @param $container
     */
    public function createMediaServiceAlias(ContainerBuilder $container)
    {
        $container->setAlias(self::MEDIA_SERVICE_ALIAS, $container->getParameter('fhm_media')['service']);
    }
}

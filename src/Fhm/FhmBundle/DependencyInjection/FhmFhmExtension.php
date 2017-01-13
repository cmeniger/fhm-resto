<?php

namespace Fhm\FhmBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FhmFhmExtension extends Extension
{
    const ORM = "doctrine.orm.entity_manager";
    const ODM = "doctrine.odm.mongodb.document_manager";

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->selectDatabase($container);
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function selectDatabase(ContainerBuilder $container)
    {
        $container->getParameter('fhm_database') == 'orm' ?
            $container->setAlias('fhm_database_manager', self::ORM) :
            null;

        $container->getParameter('fhm_database') == 'odm' ?
            $container->setAlias('fhm_database_manager', self::ODM) :
            null;
    }
}

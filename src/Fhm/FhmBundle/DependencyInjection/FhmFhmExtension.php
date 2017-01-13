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
    const EntityManager = "fhm.entity.manager";
    const ODM = "doctrine.odm.mongodb.document_manager";
    const DocumentManager = "fhm.document.manager";

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
        switch ($container->getParameter('fhm_database'))
        {
            case 'odm':
                $container->setAlias('fhm_database_manager', self::ODM);
                $container->setAlias('fhm_object_manager', self::DocumentManager);
                break;

            case 'orm':
                $container->setAlias('fhm_database_manager', self::ORM);
                $container->setAlias('fhm_object_manager', self::EntityManager);
                break;
        }
    }
}

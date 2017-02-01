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
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->selectDatabase($container, $config);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * @param ContainerBuilder $container
     */
    public function selectDatabase(ContainerBuilder $container, $config)
    {
        $container->setParameter('database_manager', $config['fhm_database_manager']);
        switch ($container->getParameter('database_manager')) {
            case 'odm':
                $container->setAlias('fhm.database.manager', self::ODM);
                break;
            case 'orm':
                $container->setAlias('fhm.database.manager', self::ORM);
                break;
        }
    }
}

<?php
namespace Fhm\FhmBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

/**
 * Generates a bundle.
 */
class BundleGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($namespace, $bundle, $dir)
    {
        $dir .= '/' . strtr($namespace, '\\', '/');
        if(file_exists($dir))
        {
            if(!is_dir($dir))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" exists but is a file.', realpath($dir)));
            }
            $files = scandir($dir);
            if($files != array('.', '..'))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dir)));
            }
            if(!is_writable($dir))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dir)));
            }
        }
        $basename   = substr($bundle, 0, -6);
        $route      = explode('_', Container::underscore($basename));
        $parameters = array(
            'namespace'       => $namespace,
            'bundle'          => $bundle,
            'basename'        => $basename,
            'extension_alias' => implode('_', $route),
            'document'        => ucfirst($route[1]),
            'src'             => ucfirst($route[0]),
            'route'           => $route,
        );
        $this->setSkeletonDirs(array(__DIR__ . '/../Resources', __DIR__ . '/../Resources/skeleton/bundle'));
        $this->renderFile('Bundle.php.twig', $dir . '/' . $bundle . '.php', $parameters);
        $this->renderFile('Controller_AdminController.php.twig', $dir . '/Controller/AdminController.php', $parameters);
        $this->renderFile('Controller_FrontController.php.twig', $dir . '/Controller/FrontController.php', $parameters);
        $this->renderFile('Controller_ApiController.php.twig', $dir . '/Controller/ApiController.php', $parameters);
        $this->renderFile('DependencyInjection_Extension.php.twig', $dir . '/DependencyInjection/' . $basename . 'Extension.php', $parameters);
        $this->renderFile('DependencyInjection_Configuration.php.twig', $dir . '/DependencyInjection/Configuration.php', $parameters);
        $this->renderFile('Document_Document.php.twig', $dir . '/Document/' . $parameters['document'] . '.php', $parameters);
        $this->renderFile('Form_Handler_README.md.twig', $dir . '/Form/Handler/Admin/README.md', $parameters);
        $this->renderFile('Form_Handler_README.md.twig', $dir . '/Form/Handler/Api/README.md', $parameters);
        $this->renderFile('Form_Handler_README.md.twig', $dir . '/Form/Handler/Front/README.md', $parameters);
        $this->renderFile('Form_Type_Admin_CreateType.php.twig', $dir . '/Form/Type/Admin/CreateType.php', $parameters);
        $this->renderFile('Form_Type_Admin_UpdateType.php.twig', $dir . '/Form/Type/Admin/UpdateType.php', $parameters);
        $this->renderFile('Form_Type_Front_CreateType.php.twig', $dir . '/Form/Type/Front/CreateType.php', $parameters);
        $this->renderFile('Form_Type_Front_UpdateType.php.twig', $dir . '/Form/Type/Front/UpdateType.php', $parameters);
        $this->renderFile('Form_Type_README.md.twig', $dir . '/Form/Type/Api/README.md', $parameters);
        $this->renderFile('Repository_DocumentRepository.php.twig', $dir . '/Repository/' . $parameters['document'] . 'Repository.php', $parameters);
        $this->renderFile('Resources_config_services.yml.twig', $dir . '/Resources/config/services.yml', $parameters);
        $this->renderFile('Resources_views_README.md.twig', $dir . '/Resources/views/README.md', $parameters);
        $this->renderFile('Test_Controller_AdminControllerTest.twig', $dir . '/Tests/Controller/AdminControllerTest.php', $parameters);
        $this->renderFile('Test_Controller_ApiControllerTest.twig', $dir . '/Tests/Controller/ApiControllerTest.php', $parameters);
        $this->renderFile('Test_Controller_FrontControllerTest.twig', $dir . '/Tests/Controller/FrontControllerTest.php', $parameters);
        $this->renderFile('Test_Controller_FrontControllerTest.twig', $dir . '/Tests/Controller/FrontControllerTest.php', $parameters);
        $this->renderFile('Services_Document.php.twig', $dir . '/Services/' . $parameters['document'] . '.php', $parameters);
        $this->renderFile('app_Resources_translations_fr_bundle.fr.yml.twig', 'app/Resources/translations/fr/' . $bundle . '.fr.yml', $parameters);
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/create.html.twig', array_merge($parameters, array('parent' => 'Admin/create')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/detail.html.twig', array_merge($parameters, array('parent' => 'Admin/detail')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/export.html.twig', array_merge($parameters, array('parent' => 'Admin/export')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/import.html.twig', array_merge($parameters, array('parent' => 'Admin/import')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/index.html.twig', array_merge($parameters, array('parent' => 'Admin/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/update.html.twig', array_merge($parameters, array('parent' => 'Admin/update')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/index.html.twig', array_merge($parameters, array('parent' => 'Api/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/autocomplete.html.twig', array_merge($parameters, array('parent' => 'Api/autocomplete')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/historic.html.twig', array_merge($parameters, array('parent' => 'Api/historic')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/detail.html.twig', array_merge($parameters, array('parent' => 'Front/detail')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/index.html.twig', array_merge($parameters, array('parent' => 'Front/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/create.html.twig', array_merge($parameters, array('parent' => 'Front/create')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/update.html.twig', array_merge($parameters, array('parent' => 'Front/update')));
    }
}
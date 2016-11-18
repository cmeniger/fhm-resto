<?php
namespace Fhm\FhmBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

/**
 * Generates a directory bundle.
 */
class DirectoryGenerator extends Generator
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function generate($namespace, $bundle, $directory, $dir)
    {
        $dir .= '/' . strtr($namespace, '\\', '/');
        $dirDirectory = $dir . 'Controller/' . ucfirst($directory);
        if(!is_dir($dir))
        {
            throw new \RuntimeException(sprintf('The parent bundle "%s" does not exist.', $bundle));
        }
        if(file_exists($dirDirectory))
        {
            if(!is_dir($dirDirectory))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" exists but is a file.', realpath($dirDirectory)));
            }
            $files = scandir($dirDirectory);
            if($files != array('.', '..'))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not empty.', realpath($dirDirectory)));
            }
            if(!is_writable($dirDirectory))
            {
                throw new \RuntimeException(sprintf('Unable to generate the bundle as the target directory "%s" is not writable.', realpath($dirDirectory)));
            }
        }
        $basename   = substr($bundle, 0, -6);
        $route      = explode('_', Container::underscore($basename));
        $parameters = array(
            'namespace'       => $namespace,
            'bundle'          => $bundle,
            'basename'        => $basename,
            'extension_alias' => implode('_', $route) . '_' . $directory,
            'document'        => ucfirst($route[1]) . ucfirst($directory),
            'src'             => ucfirst($route[0]),
            'route'           => $route,
            'directory'       => $directory
        );
        $this->setSkeletonDirs(array(__DIR__ . '/../Resources', __DIR__ . '/../Resources/skeleton/directory'));
        $this->renderFile('Controller_AdminController.php.twig', $dir . '/Controller/' . ucfirst($directory) . '/AdminController.php', $parameters);
        $this->renderFile('Controller_FrontController.php.twig', $dir . '/Controller/' . ucfirst($directory) . '/FrontController.php', $parameters);
        $this->renderFile('Controller_ApiController.php.twig', $dir . '/Controller/' . ucfirst($directory) . '/ApiController.php', $parameters);
        $this->renderFile('Document_Document.php.twig', $dir . '/Document/' . $parameters['document'] . '.php', $parameters);
        $this->renderFile('Form_Type_Admin_CreateType.php.twig', $dir . '/Form/Type/Admin/' . ucfirst($directory) . '/CreateType.php', $parameters);
        $this->renderFile('Form_Type_Admin_UpdateType.php.twig', $dir . '/Form/Type/Admin/' . ucfirst($directory) . '/UpdateType.php', $parameters);
        $this->renderFile('Form_Type_Front_CreateType.php.twig', $dir . '/Form/Type/Front/' . ucfirst($directory) . '/CreateType.php', $parameters);
        $this->renderFile('Form_Type_Front_UpdateType.php.twig', $dir . '/Form/Type/Front/' . ucfirst($directory) . '/UpdateType.php', $parameters);
        $this->renderFile('Repository_DocumentRepository.php.twig', $dir . '/Repository/' . $parameters['document'] . 'Repository.php', $parameters);
        $this->renderFile('app_Resources_translations_fr_bundle.fr.yml.twig', 'app/Resources/translations/fr/' . $bundle . '.fr.yml', array_merge($parameters, array('source' => file_get_contents('app/Resources/translations/fr/' . $bundle . '.fr.yml'))));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/create.html.twig', array_merge($parameters, array('parent' => 'Admin/create')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/detail.html.twig', array_merge($parameters, array('parent' => 'Admin/detail')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/export.html.twig', array_merge($parameters, array('parent' => 'Admin/export')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/import.html.twig', array_merge($parameters, array('parent' => 'Admin/import')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/index.html.twig', array_merge($parameters, array('parent' => 'Admin/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Admin/' . ucfirst($directory) . '/update.html.twig', array_merge($parameters, array('parent' => 'Admin/update')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/' . ucfirst($directory) . '/index.html.twig', array_merge($parameters, array('parent' => 'Api/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/' . ucfirst($directory) . '/autocomplete.html.twig', array_merge($parameters, array('parent' => 'Api/autocomplete')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Api/' . ucfirst($directory) . '/historic.html.twig', array_merge($parameters, array('parent' => 'Api/historic')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/' . ucfirst($directory) . '/detail.html.twig', array_merge($parameters, array('parent' => 'Front/detail')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/' . ucfirst($directory) . '/index.html.twig', array_merge($parameters, array('parent' => 'Front/index')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/' . ucfirst($directory) . '/create.html.twig', array_merge($parameters, array('parent' => 'Front/create')));
        $this->renderFile('app_Resources_views_xxx.html.twig', 'app/Resources/views/' . $basename . '/Front/' . ucfirst($directory) . '/update.html.twig', array_merge($parameters, array('parent' => 'Front/update')));
    }
}
<?php
namespace Fhm\MediaBundle\Command;

use Fhm\MediaBundle\Document\Media;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyS3Command extends ContainerAwareCommand
{
    protected $container;

    protected function configure()
    {
        $this
            ->setDefinition(array())
            ->setDescription('Copy all media in AWS S3')
            ->setHelp(<<<EOT
The <info>fhm:media:copy:s3</info> command for copy all media in AWS S3.
EOT
            )
            ->setName('fhm:media:copy:s3');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[fhm:media:copy:s3]");
        $urlBase   = $this->getcontainer()->get('kernel')->getRootDir() . '/../';
        $urlOrigin = $urlBase . 'media/';
        $urlWeb    = $urlBase . 'web/datas/media/';
        $medias    = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:Media')->getAll();
        // Clear folder
        $output->writeln('- Clear folder << ' . $urlOrigin . ' >>');
        $this->_clearFolder($urlOrigin);
        foreach($medias as $media)
        {
            // Web
            if(is_dir($urlWeb . $media->getId()))
            {
                $output->writeln($media->getId() . ' | ' . $media->getType() . ' | ' . $media->getExtension());
                $this->getcontainer()->get('fhm_media_s3')->setModel($media)->copy($urlWeb . $media->getId() . '/', '/datas/' . $media->getId() . '/');
            }
            else
            {
                $output->writeln($media->getId() . ' | error | folder << ' . $urlWeb . $media->getId() . ' >> does not exist');
            }
            // Media
            if(file_exists($urlOrigin . $media->getId()))
            {
                $this->getcontainer()->get('fhm_media_s3')->setModel($media)->copy($urlOrigin . $media->getId(), '/media/' . $media->getId(), true);
            }
        }
        $output->writeln("End");

        return 0;
    }

    /**
     * Inject a dependency injection container, this is used when using the
     * command as a service

     */
    function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Since we are using command as a service, getContainer() is not available
     * hence we need to pass the container (via services.yml) and use this function to switch
     * between containers..

     */
    public function getcontainer()
    {
        if(is_object($this->container))
        {
            return $this->container;
        }

        return parent::getcontainer();
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _clearFolder($folder)
    {
        $folder = substr($folder, -1, 1) != '/' ? $folder . '/' : $folder;
        $files  = array_diff(scandir($folder), array('.', '..'));
        foreach($files as $file)
        {
            if(is_dir($folder . $file))
            {
                $this->_deleteFolder($folder . $file);
            }
        }

        return $this;
    }

    /**
     * @param $folder
     *
     * @return $this
     */
    private function _deleteFolder($folder)
    {
        $folder = substr($folder, -1, 1) != '/' ? $folder . '/' : $folder;
        $files  = array_diff(scandir($folder), array('.', '..'));
        foreach($files as $file)
        {
            is_dir($folder . $file) ? $this->_deleteFolder($folder . $file) : unlink($folder . $file);
        }
        rmdir($folder);

        return $this;
    }
}

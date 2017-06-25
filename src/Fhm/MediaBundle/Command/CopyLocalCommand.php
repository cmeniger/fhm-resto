<?php
namespace Fhm\MediaBundle\Command;

use Fhm\MediaBundle\Document\Media;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyLocalCommand extends ContainerAwareCommand
{
    protected $container;

    protected function configure()
    {
        $this
            ->setDefinition(array())
            ->setDescription('Copy all media in local')
            ->setHelp(<<<EOT
The <info>fhm:media:copy:local</info> command for copy all media in local.
EOT
            )
            ->setName('fhm:media:copy:local');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[fhm:media:copy:local]");
        $urlBase   = $this->getcontainer()->get('kernel')->getRootDir() . '/../';
        $urlOrigin = $urlBase . 'media/';
        $urlWeb    = $urlBase . 'web/datas/media/';
        $medias    = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:Media')->getAll();
        // Clear folder
        $output->writeln('- Clear folder << ' . $urlOrigin . ' >>');
        $this->_clearFolder($urlOrigin);
        // Copy
        foreach($medias as $media)
        {
            $this->getcontainer()->get('fhm_media_s3')->setModel($media)->copyLocal();
            if(is_dir($urlWeb . $media->getId()))
            {
                $output->writeln($media->getId() . ' | ' . $media->getType() . ' | ' . $media->getExtension());
            }
            else
            {
                $output->writeln($media->getId() . ' | error | key << ' . $media->getId() . ' >> does not exist');
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

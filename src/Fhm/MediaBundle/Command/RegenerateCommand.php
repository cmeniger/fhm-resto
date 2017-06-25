<?php
namespace Fhm\MediaBundle\Command;

use Fhm\MediaBundle\Document\Media;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RegenerateCommand extends ContainerAwareCommand
{
    protected $container;

    protected function configure()
    {
        $this
            ->setDefinition(array())
            ->setDescription('Regenerate media')
            ->setHelp(<<<EOT
The <info>fhm:media:regenerate</info> command regenerate media with good size.
EOT
            )
            ->setName('fhm:media:regenerate');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[fhm:media:regenerate]");
        $urlBase   = $this->getcontainer()->get('kernel')->getRootDir() . '/../';
        $urlOrigin = $urlBase . 'media/';
        $urlWeb    = $urlBase . 'web/datas/media/';
        $medias    = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:Media')->getAll();
        foreach($medias as $media)
        {
            if($media->getType() == 'image')
            {
                $media->setDateUpdate(new \DateTime());
                $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($media);
                $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
                if(is_dir($urlWeb . $media->getId()))
                {
                    $output->writeln($media->getId() . ' | ' . $media->getType() . ' | ' . $media->getExtension());
                    $this->getcontainer()->get($this->getcontainer()->getParameter('fhm_media')['service'])->setModel($media)->generateImage();
                }
                else
                {
                    $output->writeln($media->getId() . ' | error | folder << ' . $urlWeb . $media->getId() . ' >> does not exist');
                }
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
}

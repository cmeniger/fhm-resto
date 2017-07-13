<?php
namespace Project\DefaultBundle\Command;

use Fhm\MediaBundle\Document\Media;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends ContainerAwareCommand
{
    protected $container;

    protected function configure()
    {
        $this
            ->setDefinition(array())
            ->setDescription('Fix command')
            ->setHelp(<<<EOT
The <info>project:fix</info> command fix.
EOT
            )
            ->setName('project:fix');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[project:fix]");
//        /*
//         * MEDIA
//         */
//        $output->writeln("-----------------------------");
//        $output->writeln("- MEDIA");
//        $output->writeln("-----------------------------");
//        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:Media')->getAll();
//        foreach($datas as $data)
//        {
//            $output->writeln($data->getId() . ' | ' . $data->getName());
//            $data->setPrivate(false);
//            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
//            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
//        }
        // END
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

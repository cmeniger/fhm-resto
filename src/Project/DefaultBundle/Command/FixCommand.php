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
        /*
         * MEDIA
         */
        $output->writeln("-----------------------------");
        $output->writeln("- MEDIA");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:Media')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->setPrivate(false);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * MEDIA TAG
         */
        $output->writeln("-----------------------------");
        $output->writeln("- MEDIA TAG");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmMediaBundle:MediaTag')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->setPrivate(false);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * NEWS
         */
        $output->writeln("-----------------------------");
        $output->writeln("- NEWS");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmNewsBundle:News')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * NEWS GROUP
         */
        $output->writeln("-----------------------------");
        $output->writeln("- NEWS GROUP");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmNewsBundle:NewsGroup')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * CONTACT
         */
        $output->writeln("-----------------------------");
        $output->writeln("- CONTACT");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmContactBundle:Contact')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * CONTACT MESSAGE
         */
        $output->writeln("-----------------------------");
        $output->writeln("- CONTACT MESSAGE");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmContactBundle:Message')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getContact()->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * GALLERY
         */
        $output->writeln("-----------------------------");
        $output->writeln("- GALLERY");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmGalleryBundle:Gallery')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * GALLERY ITEM
         */
        $output->writeln("-----------------------------");
        $output->writeln("- GALLERY ITEM");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmGalleryBundle:GalleryItem')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * SLIDER
         */
        $output->writeln("-----------------------------");
        $output->writeln("- SLIDER");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmSliderBundle:Slider')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * SLIDER ITEM
         */
        $output->writeln("-----------------------------");
        $output->writeln("- SLIDER ITEM");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmSliderBundle:SliderItem')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * NEWSLETTER
         */
        $output->writeln("-----------------------------");
        $output->writeln("- NEWSLETTER");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmNewsletterBundle:Newsletter')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * PARTNER
         */
        $output->writeln("-----------------------------");
        $output->writeln("- PARTNER");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmPartnerBundle:Partner')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
        /*
         * PARTNER
         */
        $output->writeln("-----------------------------");
        $output->writeln("- PARTNER GROUP");
        $output->writeln("-----------------------------");
        $datas = $this->getcontainer()->get('doctrine_mongodb')->getManager()->getRepository('FhmPartnerBundle:PartnerGroup')->getAll();
        foreach($datas as $data)
        {
            $output->writeln($data->getId() . ' | ' . $data->getName());
            $data->sortUpdate();
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->persist($data);
            $this->getcontainer()->get('doctrine_mongodb')->getManager()->flush();
        }
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

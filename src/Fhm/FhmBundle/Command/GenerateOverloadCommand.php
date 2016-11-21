<?php
namespace Fhm\FhmBundle\Command;

use Fhm\FhmBundle\Generator\OverloadGenerator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\HttpKernel\KernelInterface;
use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Sensio\Bundle\GeneratorBundle\Command\Helper\QuestionHelper;

/**
 * Generates fhm overload bundles.
 */
class GenerateOverloadCommand extends GeneratorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('parent', '', InputOption::VALUE_REQUIRED, 'The parent bundle name')
            ))
            ->setDescription('Generates a overload bundle')
            ->setHelp(<<<EOT
The <info>fhm:generate:overload</info> command helps you generates new Fhm overload bundles.

By default, the command interacts with the developer to tweak the generation.
Any passed option will be used as a default value for the interaction
(<comment>--parent</comment> is the only one needed if you follow the
conventions):

<info>php app/console fhm:generate:overload --parent=Fhm/SiteBundle</info>

If you want to disable any user interaction, use <comment>--no-interaction</comment> but don't forget to pass all needed options:

<info>php app/console fhm:generate:overload --parent=Fhm/SiteBundle --no-interaction</info>

Note that the bundle parent must end with "Bundle".
EOT
            )
            ->setName('fhm:generate:overload');
    }

    /**
     * @see Command
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        if($input->isInteractive())
        {
            if(!$questionHelper->ask($input, $output, new ConfirmationQuestion($questionHelper->getQuestion('Do you confirm generation', 'yes', '?'), true)))
            {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }
        foreach(array('parent') as $option)
        {
            if(null === $input->getOption($option))
            {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }
        // validate the namespace, but don't require a vendor namespace
        $parentNamespace = Validators::validateBundleNamespace($input->getOption('parent'), false);
        $parentBundle    = Validators::validateBundleName(strtr($parentNamespace, array('\\' => '')));
        $namespace       = explode('\\', $parentNamespace);
        $namespace[0]    = "Project";
        $namespace       = implode('\\', $namespace);
        $bundle          = strtr($namespace, array('\\' => ''));
        $dir             = "src";
        $questionHelper->writeSection($output, 'Bundle generation');
        if(!$this->getContainer()->get('filesystem')->isAbsolutePath($dir))
        {
            $dir = getcwd() . '/' . $dir;
        }
        $generator = $this->getGenerator();
        $generator->generate($parentNamespace, $parentBundle, $namespace, $bundle, $dir);
        $output->writeln('Generating the bundle code: <info>OK</info>');
        $errors = array();
        $runner = $questionHelper->getRunner($output, $errors);
        // check that the namespace is already autoloaded
        $runner($this->checkAutoloader($output, $namespace, $bundle, $dir));
        // register the bundle in the Kernel class
        $runner($this->updateKernel($questionHelper, $input, $output, $this->getContainer()->get('kernel'), $namespace, $bundle));
        // routing
        $runner($this->updateRouting($questionHelper, $input, $output, $bundle, 'annotation'));
        $questionHelper->writeGeneratorSummary($output, $errors);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the FHM overload bundle generator');
        // namespace
        $parent = null;
        try
        {
            // validate the parent option (if any) but don't require the vendor namespace
            $parent = $input->getOption('parent') ? Validators::validateBundleNamespace($input->getOption('parent'), false) : null;
        }
        catch(\Exception $error)
        {
            $output->writeln($questionHelper->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }
        if(null === $parent)
        {
            $output->writeln(array(
                '',
                'Your application code must be written in <comment>bundles</comment>. This command helps',
                'you generate them easily.',
                '',
                'Each bundle is hosted under a namespace (like <comment>Acme/Bundle/BlogBundle</comment>).',
                'The namespace should begin with a "vendor" name like your company name, your',
                'project name, or your client name, followed by one or more optional category',
                'sub-namespaces, and it should end with the bundle name itself',
                '(which must have <comment>Bundle</comment> as a suffix).',
                '',
                'See http://symfony.com/doc/current/cookbook/bundles/best_practices.html#index-1 for more',
                'details on bundle naming conventions.',
                '',
                'Use <comment>/</comment> instead of <comment>\\ </comment> for the namespace delimiter to avoid any problem.',
                '',
            ));
            $acceptedNamespace = false;
            while(!$acceptedNamespace)
            {
                $question = new Question($questionHelper->getQuestion('Bundle parent namespace', $input->getOption('parent')), $input->getOption('parent'));
                $question->setValidator(function ($answer)
                {
                    return Validators::validateBundleNamespace($answer, false);
                });
                $parent = $questionHelper->ask($input, $output, $question);
                // mark as accepted, unless they want to try again below
                $acceptedNamespace = true;
            }
            $input->setOption('parent', $parent);
        }
        // optional files to generate
        $output->writeln(array(
            '',
            'To help you get started faster, the command can generate some',
            'code snippets for you.',
            '',
        ));
        // summary
        $output->writeln(array(
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a overload bundle of \"<info>%s</info>\" using the \"<info>%s</info>\" format.", $parent, 'annotation'),
            '',
        ));
    }

    protected function checkAutoloader(OutputInterface $output, $parent, $bundle, $dir)
    {
        $output->write('Checking that the bundle is autoloaded: ');
        if(!class_exists($parent . '\\' . $bundle))
        {
            return array(
                '- Edit the <comment>composer.json</comment> file and register the bundle',
                '  namespace in the "autoload" section:',
                '',
            );
        }
    }

    protected function updateKernel(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output, KernelInterface $kernel, $parent, $bundle)
    {
        $auto = true;
        $output->write('Enabling the bundle inside the Kernel: ');
        $manip = new KernelManipulator($kernel);
        try
        {
            $ret = $auto ? $manip->addBundle($parent . '\\' . $bundle) : false;
            if(!$ret)
            {
                $reflected = new \ReflectionObject($kernel);

                return array(
                    sprintf('- Edit <comment>%s</comment>', $reflected->getFilename()),
                    '  and add the following bundle in the <comment>AppKernel::registerBundles()</comment> method:',
                    '',
                    sprintf('    <comment>new %s(),</comment>', $parent . '\\' . $bundle),
                    '',
                );
            }
        }
        catch(\RuntimeException $e)
        {
            return array(
                sprintf('Bundle <comment>%s</comment> is already defined in <comment>AppKernel::registerBundles()</comment>.', $parent . '\\' . $bundle),
                '',
            );
        }
    }

    protected function updateRouting(QuestionHelper $questionHelper, InputInterface $input, OutputInterface $output, $bundle, $format)
    {
        $auto = true;
        $output->write('Importing the bundle routing resource: ');
        $routingFile = substr($bundle, 0, 7) == 'Project' ? 'routing_project' : (substr($bundle, 0, 3) == 'Shop' ? 'routing_shop' : (substr($bundle, 0, 3) == 'Fhm' ? 'routing_fhm' : 'routing_other'));
        $routing     = new RoutingManipulator($this->getContainer()->getParameters('kernel.root_dir') . '/config/' . $routingFile . '.yml');
        try
        {
            $ret = $auto ? $routing->addResource($bundle, $format) : true;
            if(!$ret)
            {
                if('annotation' === $format)
                {
                    $help = sprintf("        <comment>resource: \"@%s/Controller/\"</comment>\n        <comment>type:     annotation</comment>\n", $bundle);
                }
                else
                {
                    $help = sprintf("        <comment>resource: \"@%s/Resources/config/routing.%s\"</comment>\n", $bundle, $format);
                }
                $help .= "        <comment>prefix:   /</comment>\n";

                return array(
                    '- Import the bundle\'s routing resource in the app main routing file:',
                    '',
                    sprintf('    <comment>%s:</comment>', $bundle),
                    $help,
                    '',
                );
            }
        }
        catch(\RuntimeException $e)
        {
            return array(
                sprintf('Bundle <comment>%s</comment> is already imported.', $bundle),
                '',
            );
        }
    }

    protected function createGenerator()
    {
        return new OverloadGenerator($this->getContainer()->get('filesystem'));
    }
}

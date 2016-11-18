<?php
namespace Fhm\FhmBundle\Command;

use Fhm\FhmBundle\Generator\DirectoryGenerator;
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
 * Generates FHM directory bundles.
 */
class GenerateDirectoryCommand extends GeneratorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('parent', '', InputOption::VALUE_REQUIRED, 'The parent bundle name'),
                new InputOption('directory', '', InputOption::VALUE_REQUIRED, 'The directory name')
            ))
            ->setDescription('Generates a directory bundle')
            ->setHelp(<<<EOT
The <info>fhm:generate:directory</info> command helps you generates new Fhm directory bundles.

By default, the command interacts with the developer to tweak the generation.
Any passed option will be used as a default value for the interaction
(<comment>--parent</comment> is the only one needed if you follow the
conventions):

<info>php app/console fhm:generate:directory --parent=Fhm/SiteBundle --directory=Test</info>

If you want to disable any user interaction, use <comment>--no-interaction</comment> but don't forget to pass all needed options:

<info>php app/console fhm:generate:directory --parent=Fhm/SiteBundle --directory=Test --no-interaction</info>

Note that the bundle parent must end with "Bundle".
EOT
            )
            ->setName('fhm:generate:directory');
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
        foreach(array('parent', 'directory') as $option)
        {
            if(null === $input->getOption($option))
            {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }
        // validate the namespace, but don't require a vendor namespace
        $namespace = Validators::validateBundleNamespace($input->getOption('parent'), false);
        $directory = strtolower($input->getOption('directory'));
        $bundle    = Validators::validateBundleName(strtr($namespace, array('\\' => '')));
        $dir       = "src";
        $questionHelper->writeSection($output, 'Bundle generation');
        if(!$this->getContainer()->get('filesystem')->isAbsolutePath($dir))
        {
            $dir = getcwd() . '/' . $dir;
        }
        $generator = $this->getGenerator();
        $generator->generate($namespace, $bundle, $directory, $dir);
        $output->writeln('Generating the bundle code: <info>OK</info>');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the FHM directory bundle generator');
        // namespace
        $parent    = null;
        $directory = null;
        try
        {
            // validate the parent option (if any) but don't require the vendor namespace
            $parent = $input->getOption('parent') ? Validators::validateBundleNamespace($input->getOption('parent'), false) : null;
        }
        catch(\Exception $error)
        {
            $output->writeln($questionHelper->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }
        try
        {
            // validate the parent option (if any) but don't require the vendor namespace
            $directory = $input->getOption('directory');
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
        if(null === $directory)
        {
            $acceptedNamespace = false;
            while(!$acceptedNamespace)
            {
                $question = new Question($questionHelper->getQuestion('Directory name', $input->getOption('directory')), $input->getOption('directory'));
                $question->setValidator(function ($answer)
                {
                    return $answer;
                });
                $directory = $questionHelper->ask($input, $output, $question);
                // mark as accepted, unless they want to try again below
                $acceptedNamespace = true;
            }
            $input->setOption('directory', $directory);
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
            sprintf("You are going to generate the directory \"<info>%s</info>\" to bundle \"<info>%s</info>\" using the \"<info>%s</info>\" format.", $directory, $parent, 'annotation'),
            '',
        ));
    }

    protected function createGenerator()
    {
        return new DirectoryGenerator($this->getContainer()->get('filesystem'));
    }
}

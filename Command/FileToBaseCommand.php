<?php
namespace Nidhognit\PassSecurityBundle\Command;

use Doctrine\ORM\EntityManager;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FileToBaseCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    /** @var  OutputInterface */
    private $output;

    protected function configure()
    {
        $this->setName('PassBundle:base')
            ->setDescription('Migrating passwords from a file into the database')
            ->addOption(
                'table',
                null,
                InputOption::VALUE_REQUIRED,
                'Table name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->output = $output;

        if ($input->getOption('table')) {

        }

        $output->writeln('Finish');
    }
}
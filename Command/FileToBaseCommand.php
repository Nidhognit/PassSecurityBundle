<?php
namespace Nidhognit\PassSecurityBundle\Command;

use Doctrine\ORM\EntityManager;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;
use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileToBaseCommand extends ContainerAwareCommand
{
    /** @var EntityManager */
    private $em;

    /** @var  OutputInterface */
    private $output;

    protected function configure()
    {
        $this->setName('passbundle:base')
            ->setDescription('Migrating passwords from a file into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->output = $output;
        $passBaseService = $this->getContainer()->get('pass_security.base_reader');
        $passFileService = $this->getContainer()->get('pass_security.file_reader');

        $className = $passBaseService->getDefaultClass();
        /** @var InterfacePassSecurityEntity $passBaseClass */
        $passBaseClass = new $className();

        $passFileService->openFile();
        $file = $passFileService->getFile();
        $row = 1;
        while (($password = fgets($file)) !== false) {

            $class = clone $passBaseClass;
            $class->setPassword(trim($password));
            $class->setNumber($row);
            $this->em->persist($class);

            ++$row;
        }

        $this->em->flush();
        $passFileService->closeFile();
        $output->writeln('You write '. $row .'password in you base');
    }
}
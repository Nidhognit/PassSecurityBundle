<?php
namespace Nidhognit\PassSecurityBundle\Command;

use Doctrine\ORM\EntityManager;
use Nidhognit\PassSecurityBundle\DependencyInjection\Services\DataBaseReader;
use Nidhognit\PassSecurityBundle\DependencyInjection\Services\FileReader;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;
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
    /** @var  DataBaseReader */
    protected $passBaseService;
    /** @var  FileReader */
    protected $passFileService;

    protected function configure()
    {
        $this->setName('passbundle:base')
            ->addOption(
                'sql',
                null,
                InputOption::VALUE_NONE,
                'Use "SQL" instead "Entity"'
            )
            ->setDescription('Migrating passwords from a file into the database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->output = $output;
        $this->passBaseService = $this->getContainer()->get('pass_security.base_reader');
        $this->passFileService = $this->getContainer()->get('pass_security.file_reader');

        $this->passFileService->openFile();
        if ($input->getOption('sql')) {
            $row = $this->writeToDatabaseUseSql();
        } else {
            $row = $this->writeToDatabaseUseEntity();
        }
        $this->passFileService->closeFile();

        $output->writeln('You write ' . $row . 'password in you base');
    }

    protected function writeToDatabaseUseEntity()
    {
        $className = $this->passBaseService->getClass();
        /** @var InterfacePassSecurityEntity $passBaseClass */
        $passBaseClass = new $className();

        $file = $this->passFileService->getFile();
        $row = 1;
        while (($password = fgets($file)) !== false) {

            $class = clone $passBaseClass;
            $class->setPassword(trim($password));
            $class->setNumber($row);
            $this->em->persist($class);

            ++$row;
        }
        $this->em->flush();

        return $row;
    }

    protected function writeToDatabaseUseSql()
    {
        $tableName = $this->em->getClassMetadata($this->passBaseService->getRepository())->getTableName();
        $conn = $this->em->getConnection();

        $file = $this->passFileService->getFile();
        $row = 1;
        while (($password = fgets($file)) !== false) {

            $conn->insert($tableName, [
                'password' => trim($password),
                'number' => $row
            ]);

            ++$row;
        }

        return $row;
    }
}
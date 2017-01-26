<?php
namespace Nidhognit\PassSecurityBundle\Command;

use \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckPasswordCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('passbundle:check')
            ->addArgument(
                'password',
                null,
                InputArgument::REQUIRED,
                'Your password'
            )
            ->setHelp("This will help you verify the password via the console");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $password = $input->getArgument('password');
        $manager = $this->getContainer()->get('pass_security.manager');
        $number = $manager->getNumberOrNull($password);

        if ($number) {
            $text = 'Your password (' . $password . ') was found, his number is ' . $number;
        } else {
            $text = 'Your password (' . $password . ') has not been found';
        }

        $output->writeln($text);
    }
}
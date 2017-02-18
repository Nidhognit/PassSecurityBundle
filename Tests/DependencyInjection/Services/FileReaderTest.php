<?php

/**
 * This class was created with the help of the God Emperor
 * Create a service for each case - law of programming, write them all in a row - the law of heresy
 *
 *  'A Heretic may see the truth and seek redemption. He may be forgiven his past and will be absolved in death. A Bad code can never be forgiven. A Bad code will never find peace
 * in this world or the next.'
 *      -Cardinal Khrysdam, Instructum Absolutio
 *
 * Keep us God Emperor
 */

namespace Nidhognit\PassSecurityBundle\Tests\Services\DependencyInjection;

use Nidhognit\PassSecurityBundle\DependencyInjection\Services\FileReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileReaderTest extends KernelTestCase
{
    /** @var  ContainerInterface */
    private $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    public function testFindPassword()
    {
        /** @var FileReader $fileReader */
        $fileReader = $this->container->get('pass_security.file_reader');

        $this->assertEquals($fileReader->findByPassword('123456'), 1);
        $this->assertEquals($fileReader->findByPassword('dragon'), 10);
        $this->assertEquals($fileReader->findByPassword('matrix'), 100);
    }

    public function testNotFindPassword()
    {
        /** @var FileReader $fileReader */
        $fileReader = $this->container->get('pass_security.file_reader');

        $this->assertEquals($fileReader->findByPassword('11111111111111111111111111111111'), null);
    }
}
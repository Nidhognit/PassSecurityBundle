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

    public function testFindPasswordIn100kFile()
    {
        $fileReader = new FileReader($this->getFile100kConfig());

        $this->assertEquals($fileReader->findByPassword('123456'), 1);
        $this->assertEquals($fileReader->findByPassword('dragon'), 10);
        $this->assertEquals($fileReader->findByPassword('matrix'), 100);
    }

    public function testNotFindPasswordIn100kFile()
    {
        $fileReader = new FileReader($this->getFile100kConfig());

        $this->assertNull($fileReader->findByPassword('11111111111111111111111111111111'));
        $this->assertNull($fileReader->findByPassword('911'));
    }

    public function testFindPasswordIn1MFile()
    {
        $fileReader = new FileReader($this->getFile1MConfig());

        $this->assertEquals($fileReader->findByPassword('123456'), 1);
        $this->assertEquals($fileReader->findByPassword('911'), 180099);
        $this->assertEquals($fileReader->findByPassword('x767mv161rus'), 894668);
    }

    public function testNotFindPasswordIn1MFile()
    {
        $fileReader = new FileReader($this->getFile1MConfig());

        $this->assertNull($fileReader->findByPassword('11111111111111111111111111111111'));
    }

    protected function getFile100kConfig()
    {
        $ds = DIRECTORY_SEPARATOR;
        $file = realpath(dirname(__FILE__)) . $ds . '..' . $ds. '..' . $ds. '..' . $ds . 'DataFiles' . $ds . 'Pass100k.txt';

        return [
            'file' => $file,
        ];
    }

    protected function getFile1MConfig()
    {
        $ds = DIRECTORY_SEPARATOR;
        $file = realpath(dirname(__FILE__)) . $ds . '..' . $ds. '..' . $ds. '..' . $ds . 'DataFiles' . $ds . 'Pass1M.txt';

        return [
            'file' => $file,
        ];
    }
}
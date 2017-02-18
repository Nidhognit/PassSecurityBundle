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
use Nidhognit\PassSecurityBundle\DependencyInjection\Services\PassSecurity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PassSecurityrTest extends KernelTestCase
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
        /** @var PassSecurity $passManager */
        $passManager = $this->container->get('pass_security.manager');

        $this->assertEquals($passManager->getNumberOrNull('123456'), 1);
        $this->assertEquals($passManager->getNumberOrNull('dragon'), 10);
        $this->assertEquals($passManager->getNumberOrNull('matrix'), 100);
    }

    public function testNotFindPassword()
    {
        /** @var PassSecurity $passManager */
        $passManager = $this->container->get('pass_security.manager');

        $this->assertEquals($passManager->getNumberOrNull('11111111111111111111111111111111'), null);
    }
}
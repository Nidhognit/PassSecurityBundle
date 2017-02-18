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

namespace Nidhognit\PassSecurityBundle\Tests\DependencyInjection;


use Nidhognit\PassSecurityBundle\DependencyInjection\PassSecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class NidhognitDependencyInjectionTest extends \PHPUnit_Framework_TestCase
{

    public function testDefaultFile100k()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['file'] = 'Pass100k';
        $loader->load([$config], new ContainerBuilder());
    }

    public function testDefaultFile1M()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['file'] = 'Pass1M';
        $loader->load([$config], new ContainerBuilder());
    }

    public function testDefaultBaseConfiguration()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['type'] = 'base';
        $loader->load([$config], new ContainerBuilder());
    }

    public function testDefaultCustomConfiguration()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['type'] = 'custom';
        $loader->load([$config], new ContainerBuilder());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadThrowExceptionSetWrongFile()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['file'] = 'custom.csv';
        $loader->load([$config], new ContainerBuilder());

    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadThrowExceptionSetWrongBaseClass()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['type'] = 'base';
        $config['class'] = 'Nidhognit\PassSecurityBundle\DependencyInjection\Configuration';
        $loader->load([$config], new ContainerBuilder());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLoadThrowExceptionNotSetCustomService()
    {
        $loader = new PassSecurityExtension();
        $config = $this->getFullConfig();
        $config['type'] = 'custom';
        unset($config['custom_service']);
        $loader->load([$config], new ContainerBuilder());
    }

    protected function getFullConfig()
    {
        $yaml = <<<EOF
type: "file"
file: Pass100k
class: Nidhognit\PassSecurityBundle\Entity\PassSecurityBase
repository: PassSecurityBundle:PassSecurityBase
custom_service: "acme_bundle.my_service"
EOF;
        $parser = new Parser();
        return $parser->parse($yaml);
    }
}
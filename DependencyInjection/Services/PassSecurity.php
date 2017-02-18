<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class PassSecurity
{
    const TYPE_FILE = 'file';
    const TYPE_BASE = 'base';
    const TYPE_CUSTOM = 'custom';

    /** @var string */
    protected $defaultType = self::TYPE_FILE;
    /** @var  InterfaceReader */
    protected $reader;
    /** @var  ContainerInterface */
    protected $container;

    /**
     * PassSecurity constructor.
     * @param ContainerInterface $container
     * @param array              $options
     */
    public function __construct(ContainerInterface $container, $options)
    {
        $this->container = $container;
        $this->setReaderByOptions($options);
    }

    /**
     * @param      $password
     * @param null $limit
     * @return int|null
     */
    public function getNumberOrNull($password, $limit = null)
    {
        return $this->reader->findByPassword($password, $limit);
    }

    /**
     * @param array $options
     */
    private function setReaderByOptions(&$options)
    {
        switch ($options['type']) {
            case self::TYPE_FILE:
                $this->reader = $this->container->get('pass_security.file_reader');
                break;
            case self::TYPE_BASE;
                $this->reader = $this->container->get('pass_security.base_reader');
                break;
            case self::TYPE_CUSTOM:
                $this->reader = $this->container->get($options['custom_service']);
                if (!$this->reader instanceof InterfaceReader) {
                    throw new \InvalidArgumentException('Service ' . $options['custom_service'] . ' must be implements InterfaceReader');
                }
                break;
            default:
                throw new \InvalidArgumentException('Value "type" must be ' . self::TYPE_FILE . ', ' . self::TYPE_BASE . ' or ' . self::TYPE_CUSTOM);
        }
    }
}
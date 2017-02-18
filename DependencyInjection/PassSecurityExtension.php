<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection;

use Nidhognit\PassSecurityBundle\DependencyInjection\Services\FileReader;
use Nidhognit\PassSecurityBundle\DependencyInjection\Services\PassSecurity;
use Nidhognit\PassSecurityBundle\Entity\InterfacePassSecurityEntity;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class PassSecurityExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        switch ($config['type']) {
            case PassSecurity::TYPE_FILE:
                $this->prepareFileConfig($config);
                break;
            case PassSecurity::TYPE_BASE:
                $this->prepareBaseConfig($config);
                break;
            case PassSecurity::TYPE_CUSTOM:
                $this->prepareCustomConfig($config);
                break;
            default:
                throw new \InvalidArgumentException('Option "type" must be: ' . PassSecurity::TYPE_FILE . ', ' . PassSecurity::TYPE_BASE . ' or ' . PassSecurity::TYPE_CUSTOM . '. You use ' . $config['type']);
        }

        $container->setParameter('pass_security', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    protected function prepareFileConfig(array &$configs)
    {
        $file = $configs['file'];

        if ($file === FileReader::FILE_100k || $file === FileReader::FILE_1M) {
            $file = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DataFiles' . DIRECTORY_SEPARATOR . $file . '.txt';
            $configs['file'] = $file;
        }

        if (!file_exists($file)) {
            throw new \InvalidArgumentException('File not found');
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== 'txt') {
            throw new \InvalidArgumentException('File in variable "file" must be with "txt" extension');
        }
    }

    protected function prepareBaseConfig(array &$configs)
    {
        if (isset($configs['file'])) {
            $file = $configs['file'];

            if ($file === FileReader::FILE_100k || $file === FileReader::FILE_1M) {
                $file = realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'DataFiles' . DIRECTORY_SEPARATOR . $file . '.txt';
                $configs['file'] = $file;
            }
        }
        $class = $configs['class'];

        $classObj = new $class();
        if(!$classObj instanceof InterfacePassSecurityEntity){
            throw new \InvalidArgumentException('Class '. $class .' must be implement "InterfacePassSecurityEntity"');
        }
    }

    protected function prepareCustomConfig(array $configs)
    {
        if (!isset($configs['custom_service'])) {
            throw new \InvalidArgumentException('You must specify "custom_service" in parameters');
        }
    }
}

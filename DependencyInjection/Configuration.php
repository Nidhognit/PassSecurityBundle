<?php

namespace Nidhognit\PassSecurityBundle\DependencyInjection;

use Nidhognit\PassSecurityBundle\DependencyInjection\Services\PassSecurity;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pass_security');

        $rootNode
            ->children()

            ->scalarNode('type')
            ->defaultValue(PassSecurity::TYPE_FILE)
            ->end()

            ->scalarNode('file')
            ->defaultValue(realpath(dirname(__FILE__)). DIRECTORY_SEPARATOR. '..'  . DIRECTORY_SEPARATOR .'DataFiles' . DIRECTORY_SEPARATOR . 'Pass100k.txt')
            ->end()

            ->scalarNode('custom_service')
            ->end()

            ->end();

        return $treeBuilder;
    }
}

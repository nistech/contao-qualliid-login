<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\DependencyInjection;

use Nistech\ContaoQualliIdLogin\OAuth2\Client\QualliIdBackendClientFactory;
use Nistech\ContaoQualliIdLogin\OAuth2\Client\QualliIdFrontendClientFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'nistech_contao_qualliid_login';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('contao_oauth2_clients')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode(QualliIdBackendClientFactory::NAME)
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable_login')
                                    ->defaultValue(true)
                                ->end()
                                ->scalarNode('client_id')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('client_secret')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode(QualliIdFrontendClientFactory::NAME)
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enable_login')
                                    ->defaultValue(true)
                                ->end()
                                ->scalarNode('client_id')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('client_secret')
                                    ->cannotBeEmpty()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}

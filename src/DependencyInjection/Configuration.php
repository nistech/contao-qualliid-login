<?php

declare(strict_types=1);

/*
 * This file is part of Contao GitHub Login.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/nistech/contao-qualli.id-client
 */

namespace NistechContaoQualliIdLogin\DependencyInjection;

use NistechContaoQualliIdLogin\OAuth2\Client\QualliIdBackendClientFactory;
use NistechContaoQualliIdLogin\OAuth2\Client\QualliIdFrontendClientFactory;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'nistech_contao_qualliid_client';

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

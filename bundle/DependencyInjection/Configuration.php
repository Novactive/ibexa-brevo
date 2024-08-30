<?php

namespace AlmaviaCX\IbexaBrevo\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\Configuration as SiteAccessConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends SiteAccessConfiguration
{
    public const EXTENSION_ALIAS = 'almaviacx_brevo';
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::EXTENSION_ALIAS);
        $rootNode = $treeBuilder->getRootNode();
        $systemNode = $this->generateScopeBaseNode($rootNode);
        $systemNode
            ->booleanNode('is_enabled')->defaultFalse()->end()
            ->variableNode('contact_mapping')->end()
            ->arrayNode('brevo_api')
                ->children()
                    ->scalarNode('api_key')->isRequired()->end()
                    ->scalarNode('username')->defaultValue('')->end()
                    ->scalarNode('password')->defaultValue('')->end()
                    ->scalarNode('host')->defaultValue('https://api.brevo.com/v3')->end()
                    ->scalarNode('userAgent')->defaultValue('Swagger-Codegen/2.0.0/php')->end()
                    ->booleanNode('debug')->defaultFalse()->end()
                    ->scalarNode('debug_file')->defaultValue('php://output')->end()
                    ->scalarNode('temp_folder_path')->defaultNull()->end()
                    //->variableNode('options')->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
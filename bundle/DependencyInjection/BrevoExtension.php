<?php

declare(strict_types=1);

namespace AlmaviaCX\IbexaBrevo\DependencyInjection;

use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use Ibexa\Bundle\Core\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;


final class BrevoExtension extends Extension implements PrependExtensionInterface
{
    public const CONFIG_DIR = __DIR__.'/../Resources/config';
    public function getAlias(): string
    {
        return Configuration::EXTENSION_ALIAS;
    }
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(self::CONFIG_DIR));
        $loader->load('default_settings.yaml');

        $processor = new ConfigurationProcessor($container, Configuration::EXTENSION_ALIAS);
        $processor->mapSetting('is_enabled', $config);
        $processor->mapSetting('contact_mapping', $config);
        $processor->mapConfigArray('brevo_api', $config,ContextualizerInterface::MERGE_FROM_SECOND_LEVEL);
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $configs = [
            'monolog' => 'monolog.yaml',
            //ibexa => 'ibexa.yaml'
        ];
        foreach ($configs as $configKey => $fileName) {
            $configFile = self::CONFIG_DIR.'/'.$fileName;
            $container->prependExtensionConfig($configKey, Yaml::parse(file_get_contents($configFile)));
            $container->addResource(new FileResource($configFile));
        }
    }
}

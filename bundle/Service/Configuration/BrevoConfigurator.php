<?php

namespace AlmaviaCX\IbexaBrevo\Service\Configuration;

use AlmaviaCX\IbexaBrevo\DependencyInjection\Configuration;
use Brevo\Client\Configuration as BrevoClientConfiguration;
use Ibexa\Contracts\Core\SiteAccess\ConfigResolverInterface;

class BrevoConfigurator
{
    public function __construct(protected readonly ConfigResolverInterface $configResolver)
    {
    }
    public function getBrevoApiSettings(): array
    {
        return (array) $this->configResolver->getParameter('brevo_api', Configuration::EXTENSION_ALIAS);
    }


    public function getConfiguration(): BrevoClientConfiguration
    {
        $apiSettings = $this->getBrevoApiSettings();
        $brevoConfiguration = BrevoClientConfiguration::getDefaultConfiguration();
        $brevoConfiguration
            ->setApikey('api-key', $apiSettings['api_key'])
            ->setUsername((string)$apiSettings['username'])
            ->setPassword((string)$apiSettings['password'])
            ->setHost($apiSettings['host'])
            ->setUserAgent((string)$apiSettings['user_agent'])
            ->setDebug((bool)$apiSettings['debug'])
            ->setDebugFile((string)$apiSettings['debug_file']);
        if (!empty($apiSettings['temp_folder_path'])) {
            $brevoConfiguration->setTempFolderPath((string)$apiSettings['temp_folder_path']);
        }
        return $brevoConfiguration;
    }
}
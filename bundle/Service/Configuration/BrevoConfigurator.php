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
        return (array) $this->getParameter('brevo_api');
    }
    public function getParameter(string $parameterName)
    {
        return $this->configResolver->getParameter($parameterName, Configuration::EXTENSION_ALIAS);
    }


    public function getConfiguration(): BrevoClientConfiguration
    {
        $apiSettings = $this->getBrevoApiSettings();
        $debug = (bool)($apiSettings['debug']?? false);
        $brevoConfiguration = BrevoClientConfiguration::getDefaultConfiguration()
            ->setApikey('api-key', $apiSettings['api_key'])
            ->setDebugFile($debug);
        if (!empty($apiSettings['username'])) {
            $brevoConfiguration->setUsername((string)$apiSettings['username']);
        }
        if (!empty($apiSettings['password'])) {
            $brevoConfiguration->setPassword((string)$apiSettings['password']);
        }
        if (!empty($apiSettings['host'])) {
            $brevoConfiguration->setHost((string)$apiSettings['host']);
        }
        if (!empty($apiSettings['userAgent'])) {
            $brevoConfiguration->setUserAgent((string)$apiSettings['userAgent']);
        }
        if (!empty($apiSettings['debug_file'])) {
            $brevoConfiguration->setDebugFile((string)$apiSettings['debug_file']);
        }
        if (!empty($apiSettings['temp_folder_path'])) {
            $brevoConfiguration->setTempFolderPath((string)$apiSettings['temp_folder_path']);
        }
        return $brevoConfiguration;
    }
}
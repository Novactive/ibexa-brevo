<?php

namespace AlmaviaCX\IbexaBrevo\Service\Api;

use AlmaviaCX\IbexaBrevo\Exception\BrevoException;
use AlmaviaCX\IbexaBrevo\Exception\UndefinedRequiredFieldException;
use AlmaviaCX\IbexaBrevo\Service\Configuration\BrevoConfigurator;
use Brevo\Client\Configuration;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class BrevoApi
{
    protected BrevoConfigurator $brevoConfigurator;
    protected LoggerInterface $logger;
    protected array $requiredFields = [];

    #[Required]
    public function setConfigurator(BrevoConfigurator $brevoConfigurator): void
    {
        $this->brevoConfigurator = $brevoConfigurator;
    }
    #[Required]
    public function setLogger(LoggerInterface $almaviacxBrevoLogger): void
    {
        $this->logger = $almaviacxBrevoLogger;
    }

    protected function getConfiguration(): Configuration
    {
        return $this->brevoConfigurator->getConfiguration();
    }

    /**
     * @throws BrevoException
     */
    function validate($data): void
    {
        foreach ($this->requiredFields as $requiredField) {
            if (!isset($data[$requiredField])) {
                throw new UndefinedRequiredFieldException('Required field ' . $requiredField . ' not set');
            }
        }
    }
    public function setRequiredFields(array $requiredFields): void
    {
        $this->requiredFields = $requiredFields;
    }
}
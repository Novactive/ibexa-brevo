<?php

declare(strict_types=1);
namespace AlmaviaCX\IbexaBrevo;

use AlmaviaCX\IbexaBrevo\DependencyInjection\AlmaviaCXBrevoExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AlmaviaCXBrevoBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new AlmaviaCXBrevoExtension();
    }
}

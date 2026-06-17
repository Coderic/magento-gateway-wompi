<?php
declare(strict_types=1);

namespace Wompi\Payment\Model\Checkout;

use Wompi\Payment\Model\Config;
use Wompi\Payment\Model\Payment\Method;
use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function getConfig(): array
    {
        if (!$this->config->isActive()) {
            return [];
        }

        return [
            'payment' => [
                Method::CODE => [
                    'title' => $this->config->getTitle() ?: 'Wompi',
                    'environment' => $this->config->getEnvironment(),
                    'redirectMessage' => (string) __(
                        'You will be redirected to Wompi to complete your payment securely.'
                    ),
                ],
            ],
        ];
    }
}

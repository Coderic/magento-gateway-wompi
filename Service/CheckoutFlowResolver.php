<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Wompi\Payment\Api\CheckoutFlowInterface;
use Wompi\Payment\Model\Agregador\AgregadorCheckoutFlow;
use Wompi\Payment\Model\Config;
use Wompi\Payment\Model\Gateway\GatewayCheckoutFlow;

class CheckoutFlowResolver
{
    public function __construct(
        private readonly Config $config,
        private readonly AgregadorCheckoutFlow $agregadorCheckoutFlow,
        private readonly GatewayCheckoutFlow $gatewayCheckoutFlow,
    ) {
    }

    public function resolve(?int $storeId = null): CheckoutFlowInterface
    {
        return $this->config->getBusinessModel($storeId) === 'gateway'
            ? $this->gatewayCheckoutFlow
            : $this->agregadorCheckoutFlow;
    }
}

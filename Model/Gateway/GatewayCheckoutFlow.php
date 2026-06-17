<?php
declare(strict_types=1);

namespace Wompi\Payment\Model\Gateway;

use Wompi\Payment\Api\CheckoutFlowInterface;
use Wompi\Payment\Model\Agregador\AgregadorCheckoutFlow;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Plan Gateway: mismo Web Checkout que Agregador en v2.0.
 * Punto de extensión para validaciones o campos adquirente futuros.
 */
class GatewayCheckoutFlow implements CheckoutFlowInterface
{
    public function __construct(
        private readonly AgregadorCheckoutFlow $agregadorCheckoutFlow,
    ) {
    }

    public function buildCheckoutPayload(OrderInterface $order): array
    {
        return $this->agregadorCheckoutFlow->buildCheckoutPayload($order);
    }
}

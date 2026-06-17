<?php
declare(strict_types=1);

namespace Wompi\Payment\Model\Agregador;

use Wompi\Payment\Api\CheckoutFlowInterface;
use Wompi\Payment\Service\CheckoutPayloadBuilder;
use Magento\Sales\Api\Data\OrderInterface;

class AgregadorCheckoutFlow implements CheckoutFlowInterface
{
    public function __construct(
        private readonly CheckoutPayloadBuilder $checkoutPayloadBuilder,
    ) {
    }

    public function buildCheckoutPayload(OrderInterface $order): array
    {
        return $this->checkoutPayloadBuilder->buildForOrder($order);
    }
}

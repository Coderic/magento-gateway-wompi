<?php
declare(strict_types=1);

namespace Wompi\Payment\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface CheckoutFlowInterface
{
    /**
     * @return array<string, string>
     */
    public function buildCheckoutPayload(OrderInterface $order): array;
}

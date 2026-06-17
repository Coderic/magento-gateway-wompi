<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Magento\Sales\Model\OrderFactory;

class OrderStoreResolver
{
    public function __construct(
        private readonly OrderFactory $orderFactory,
    ) {
    }

    public function resolveStoreIdByIncrementId(string $incrementId): ?int
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        if (!$order->getId()) {
            return null;
        }

        return (int) $order->getStoreId();
    }
}

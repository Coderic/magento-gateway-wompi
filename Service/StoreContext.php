<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Wompi\Payment\Model\Config;
use Magento\Store\Model\StoreManagerInterface;

class StoreContext
{
    public function __construct(
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    public function resolveStoreId(?int $hintStoreId = null): int
    {
        if ($hintStoreId !== null && $hintStoreId > 0) {
            return $hintStoreId;
        }

        try {
            $code = $this->config->getAllowedStoreViewCode();
            if ($code !== '') {
                return (int) $this->storeManager->getStore($code)->getId();
            }
        } catch (\Exception) {
            // fall through to default store
        }

        return (int) $this->storeManager->getStore()->getId();
    }
}

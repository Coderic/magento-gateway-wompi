<?php
declare(strict_types=1);

namespace Wompi\Payment\Plugin\Payment\Model;

use Wompi\Payment\Model\Config;
use Wompi\Payment\Model\Payment\Method;
use Magento\Payment\Model\MethodInterface;
use Magento\Payment\Model\MethodList;
use Magento\Store\Model\StoreManagerInterface;

class MethodListPlugin
{
    public function __construct(
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    /**
     * @param MethodInterface[] $result
     * @return MethodInterface[]
     */
    public function afterGetAvailableMethods(MethodList $subject, array $result): array
    {
        $store = $this->storeManager->getStore();
        $allowedCode = $this->config->getAllowedStoreViewCode((int) $store->getId());

        if ($allowedCode === '' || $store->getCode() === $allowedCode) {
            return $result;
        }

        return array_values(array_filter(
            $result,
            static fn (MethodInterface $method): bool => $method->getCode() !== Method::CODE
        ));
    }
}

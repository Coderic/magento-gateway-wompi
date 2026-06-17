<?php
declare(strict_types=1);

namespace Wompi\Payment\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    private const XML_PATH = 'payment/wompi_payment/';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager,
    ) {
    }

    public function isActive(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH . 'active', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getTitle(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH . 'title', ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getBusinessModel(?int $storeId = null): string
    {
        $model = (string) $this->scopeConfig->getValue(
            self::XML_PATH . 'business_model',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return in_array($model, ['agregador', 'gateway'], true) ? $model : 'agregador';
    }

    public function isSandbox(?int $storeId = null): bool
    {
        return $this->getEnvironment($storeId) === 'sandbox';
    }

    public function getEnvironment(?int $storeId = null): string
    {
        $mode = (string) $this->scopeConfig->getValue(
            self::XML_PATH . 'test_mode',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return in_array($mode, ['sandbox', 'production'], true) ? $mode : 'sandbox';
    }

    public function getPublicKey(?int $storeId = null): string
    {
        return $this->getScopedKey('public_key', $storeId);
    }

    public function getPrivateKey(?int $storeId = null): string
    {
        return $this->getScopedKey('private_key', $storeId);
    }

    public function getIntegrityKey(?int $storeId = null): string
    {
        return $this->getScopedKey('integrity_key', $storeId);
    }

    public function getEventsKey(?int $storeId = null): string
    {
        return $this->getScopedKey('events_key', $storeId);
    }

    public function getAllowedStoreViewCode(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH . 'store_view_code',
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getCheckoutUrl(): string
    {
        return 'https://checkout.wompi.co/p/';
    }

    public function getCallbackUrl(?int $storeId = null): string
    {
        return $this->storeManager->getStore($storeId)->getUrl(
            'wompi/payment/callback',
            ['_secure' => true, '_nosid' => true]
        );
    }

    public function getApiBaseUrl(?int $storeId = null): string
    {
        return $this->getEnvironment($storeId) === 'production'
            ? 'https://production.wompi.co/v1'
            : 'https://sandbox.wompi.co/v1';
    }

    private function getScopedKey(string $baseKey, ?int $storeId): string
    {
        $suffix = $this->isSandbox($storeId) ? '_test' : '_production';

        return (string) $this->scopeConfig->getValue(
            self::XML_PATH . $baseKey . $suffix,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}

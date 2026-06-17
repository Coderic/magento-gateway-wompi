<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Wompi\Payment\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class TransactionVerifier
{
    public function __construct(
        private readonly Config $config,
        private readonly Curl $curl,
        private readonly Json $json,
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function fetch(string $transactionId, int $storeId): ?array
    {
        $privateKey = $this->config->getPrivateKey($storeId);
        if ($privateKey === '') {
            return null;
        }

        $url = $this->config->getApiBaseUrl($storeId) . '/transactions/' . rawurlencode($transactionId);
        $this->curl->addHeader('Authorization', 'Bearer ' . $privateKey);
        $this->curl->get($url);
        if ($this->curl->getStatus() !== 200) {
            return null;
        }

        try {
            $body = $this->json->unserialize($this->curl->getBody());
        } catch (\InvalidArgumentException) {
            return null;
        }

        return is_array($body) ? $body : null;
    }

    public function isApproved(array $payload): bool
    {
        $status = (string) ($payload['data']['status'] ?? $payload['status'] ?? '');
        return strtoupper($status) === 'APPROVED';
    }
}

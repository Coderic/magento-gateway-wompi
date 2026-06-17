<?php
declare(strict_types=1);

namespace Wompi\Payment\Controller\Payment;

use Wompi\Payment\Model\Config;
use Wompi\Payment\Service\EventSignatureValidator;
use Wompi\Payment\Service\OrderPaymentUpdater;
use Wompi\Payment\Service\OrderStoreResolver;
use Wompi\Payment\Service\StoreContext;
use Wompi\Payment\Service\TransactionVerifier;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

class Webhook implements HttpPostActionInterface, CsrfAwareActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly Json $json,
        private readonly EventSignatureValidator $eventSignatureValidator,
        private readonly TransactionVerifier $transactionVerifier,
        private readonly OrderPaymentUpdater $orderPaymentUpdater,
        private readonly Config $config,
        private readonly StoreContext $storeContext,
        private readonly OrderStoreResolver $orderStoreResolver,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setHttpResponseCode(200);
        $result->setContents('OK');

        $body = (string) $this->request->getContent();
        if ($body === '') {
            return $result;
        }

        try {
            $payload = $this->json->unserialize($body);
        } catch (\InvalidArgumentException) {
            return $result;
        }

        if (!is_array($payload)) {
            return $result;
        }

        $transaction = $payload['data']['transaction'] ?? $payload['data'] ?? null;
        if (!is_array($transaction)) {
            return $result;
        }

        $transactionId = (string) ($transaction['id'] ?? '');
        $reference = (string) ($transaction['reference'] ?? '');
        if ($transactionId === '' || $reference === '') {
            return $result;
        }

        $storeId = $this->orderStoreResolver->resolveStoreIdByIncrementId($reference)
            ?? $this->storeContext->resolveStoreId();

        $eventsKey = $this->config->getEventsKey($storeId);
        $headerChecksum = (string) $this->request->getHeader('X-Event-Checksum');

        if (!$this->eventSignatureValidator->isValid($payload, $eventsKey, $headerChecksum)) {
            $this->logger->warning('Wompi webhook: invalid event signature for reference ' . $reference);
            return $result;
        }

        $verified = $this->transactionVerifier->fetch($transactionId, $storeId);
        if ($verified === null) {
            $this->logger->warning('Wompi webhook: could not verify transaction ' . $transactionId);
            return $result;
        }

        $status = strtoupper((string) ($verified['data']['status'] ?? ''));
        if ($status === 'APPROVED') {
            $this->orderPaymentUpdater->markPaidByIncrementId($reference, $transactionId);
            $this->logger->info(
                'Wompi webhook: payment approved',
                ['reference' => $reference, 'transaction_id' => $transactionId, 'store_id' => $storeId]
            );
        } elseif (in_array($status, ['DECLINED', 'ERROR', 'VOIDED'], true)) {
            $this->orderPaymentUpdater->markCanceledByIncrementId($reference, $transactionId, $status);
            $this->logger->info(
                'Wompi webhook: payment not completed',
                ['reference' => $reference, 'transaction_id' => $transactionId, 'status' => $status]
            );
        }

        return $result;
    }

    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}

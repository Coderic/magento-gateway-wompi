<?php
declare(strict_types=1);

namespace Wompi\Payment\Controller\Payment;

use Wompi\Payment\Service\OrderPaymentUpdater;
use Wompi\Payment\Service\StoreContext;
use Wompi\Payment\Service\TransactionVerifier;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\OrderFactory;

class Callback implements HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly RequestInterface $request,
        private readonly TransactionVerifier $transactionVerifier,
        private readonly OrderPaymentUpdater $orderPaymentUpdater,
        private readonly OrderFactory $orderFactory,
        private readonly CheckoutSession $checkoutSession,
        private readonly StoreContext $storeContext,
        private readonly ManagerInterface $messageManager,
    ) {
    }

    public function execute(): ResultInterface
    {
        $transactionId = (string) $this->request->getParam('id');
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if ($transactionId === '') {
            $this->messageManager->addNoticeMessage(__('Payment status will be confirmed shortly.'));
            return $redirect->setPath('checkout/onepage/success');
        }

        $storeId = $this->resolveStoreId();
        $payload = $this->transactionVerifier->fetch($transactionId, $storeId);

        if ($payload === null) {
            $this->messageManager->addNoticeMessage(__('We are verifying your Wompi payment.'));
            return $redirect->setPath('checkout/onepage/success');
        }

        $status = strtoupper((string) ($payload['data']['status'] ?? ''));
        $reference = (string) ($payload['data']['reference'] ?? '');

        if ($status === 'APPROVED' && $reference !== '') {
            $this->orderPaymentUpdater->markPaidByIncrementId($reference, $transactionId);
            $this->messageManager->addSuccessMessage(__('Your Wompi payment was received.'));
            return $redirect->setPath('checkout/onepage/success');
        }

        if (in_array($status, ['DECLINED', 'ERROR', 'VOIDED'], true) && $reference !== '') {
            $this->orderPaymentUpdater->markCanceledByIncrementId($reference, $transactionId, $status);
            $this->messageManager->addErrorMessage(__('Your Wompi payment could not be completed.'));
            return $redirect->setPath('checkout/onepage/failure');
        }

        $this->messageManager->addNoticeMessage(__('We are verifying your Wompi payment.'));
        return $redirect->setPath('checkout/onepage/success');
    }

    private function resolveStoreId(): int
    {
        $orderId = (int) $this->request->getParam('order_id');
        if ($orderId <= 0) {
            $orderId = (int) $this->checkoutSession->getLastOrderId();
        }

        if ($orderId > 0) {
            $order = $this->orderFactory->create()->load($orderId);
            if ($order->getId()) {
                return (int) $order->getStoreId();
            }
        }

        return $this->storeContext->resolveStoreId();
    }
}

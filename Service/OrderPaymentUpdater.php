<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Wompi\Payment\Model\Payment\Method;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Psr\Log\LoggerInterface;

class OrderPaymentUpdater
{
    public const STATUS_WOMPI_PAID = 'wompi_paid';

    public function __construct(
        private readonly OrderFactory $orderFactory,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function markPaidByIncrementId(string $incrementId, string $transactionId): void
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        if (!$order->getId()) {
            $this->logger->warning('Wompi: order not found for reference ' . $incrementId);
            return;
        }

        if ($order->getPayment()?->getMethod() !== Method::CODE) {
            return;
        }

        if ($order->getState() === Order::STATE_PROCESSING || $order->getState() === Order::STATE_COMPLETE) {
            return;
        }

        try {
            $payment = $order->getPayment();
            $payment->setTransactionId($transactionId);
            $payment->setIsTransactionClosed(true);
            $payment->registerCaptureNotification((float) $order->getGrandTotal(), true);
            $order->setState(Order::STATE_PROCESSING);
            $order->setStatus(self::STATUS_WOMPI_PAID);
            $order->addCommentToStatusHistory(
                __('Wompi payment approved. Transaction ID: %1', $transactionId)
            );
            $this->orderRepository->save($order);
        } catch (LocalizedException $e) {
            $this->logger->error('Wompi capture failed: ' . $e->getMessage());
        }
    }

    public function markCanceledByIncrementId(string $incrementId, string $transactionId, string $status): void
    {
        $order = $this->orderFactory->create()->loadByIncrementId($incrementId);
        if (!$order->getId()) {
            return;
        }

        if ($order->getPayment()?->getMethod() !== Method::CODE) {
            return;
        }

        if (in_array($order->getState(), [Order::STATE_PROCESSING, Order::STATE_COMPLETE, Order::STATE_CANCELED], true)) {
            return;
        }

        try {
            $order->setState(Order::STATE_CANCELED);
            $order->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_CANCELED));
            $order->addCommentToStatusHistory(
                __('Wompi payment not completed (%1). Transaction ID: %2', $status, $transactionId)
            );
            $this->orderRepository->save($order);
        } catch (LocalizedException $e) {
            $this->logger->error('Wompi cancel update failed: ' . $e->getMessage());
        }
    }
}

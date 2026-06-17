<?php
declare(strict_types=1);

namespace Wompi\Payment\Plugin\Checkout\Controller;

use Wompi\Payment\Model\Payment\Method;
use Magento\Checkout\Controller\Onepage\Success;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Sales\Model\Order;

class SuccessRedirectPlugin
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly RedirectFactory $redirectFactory,
        private readonly ModuleManager $moduleManager,
    ) {
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    public function afterExecute(Success $subject, $result)
    {
        if ($this->moduleManager->isEnabled('Hyva_Checkout')) {
            return $result;
        }

        $orderId = (int) $this->checkoutSession->getLastOrderId();
        if ($orderId <= 0) {
            return $result;
        }

        $order = $this->checkoutSession->getLastRealOrder();
        if (!$order || $order->getPayment()?->getMethod() !== Method::CODE) {
            return $result;
        }

        if ($order->getState() !== Order::STATE_PENDING_PAYMENT) {
            return $result;
        }

        return $this->redirectFactory->create()->setPath('wompi/payment/redirect', ['order_id' => $orderId]);
    }
}

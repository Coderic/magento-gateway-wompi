<?php
declare(strict_types=1);

namespace Wompi\Payment\Controller\Payment;

use Wompi\Payment\Model\Config;
use Wompi\Payment\Model\Payment\Method;
use Wompi\Payment\Service\CheckoutFlowResolver;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Escaper;
use Magento\Sales\Model\OrderFactory;

class Redirect implements HttpGetActionInterface
{
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly CheckoutSession $checkoutSession,
        private readonly OrderFactory $orderFactory,
        private readonly CheckoutFlowResolver $checkoutFlowResolver,
        private readonly Config $config,
        private readonly RequestInterface $request,
        private readonly Escaper $escaper,
    ) {
    }

    public function execute(): ResultInterface
    {
        $orderId = (int) $this->request->getParam('order_id');
        if ($orderId <= 0) {
            $orderId = (int) $this->checkoutSession->getLastOrderId();
        }

        $order = $this->orderFactory->create()->load($orderId);
        if (!$order->getId() || $order->getPayment()?->getMethod() !== Method::CODE) {
            return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('checkout/cart');
        }

        $storeId = (int) $order->getStoreId();
        $payload = $this->checkoutFlowResolver->resolve($storeId)->buildCheckoutPayload($order);
        $action = $this->escaper->escapeUrl($this->config->getCheckoutUrl());
        $fields = '';
        foreach ($payload as $name => $value) {
            $fields .= sprintf(
                '<input type="hidden" name="%s" value="%s"/>',
                $this->escaper->escapeHtmlAttr($name),
                $this->escaper->escapeHtmlAttr($value)
            );
        }

        $html = <<<HTML
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Wompi</title></head>
<body><p>Redirigiendo a Wompi...</p>
<form id="wompi-redirect" action="{$action}" method="GET">{$fields}</form>
<script>document.getElementById('wompi-redirect').submit();</script></body></html>
HTML;

        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
        $result->setContents($html);
        return $result;
    }
}

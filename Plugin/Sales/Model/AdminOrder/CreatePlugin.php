<?php
declare(strict_types=1);

namespace Wompi\Payment\Plugin\Sales\Model\AdminOrder;

use Wompi\Payment\Model\Payment\Method;
use Magento\Sales\Model\AdminOrder\Create;
use Magento\Sales\Model\Order;

/**
 * Admin Edit Order no copia el pago offsite al quote; sin metodo Magento muestra "No Payment Methods".
 */
class CreatePlugin
{
    public function afterInitFromOrder(Create $subject, Create $result, Order $order): Create
    {
        $orderPayment = $order->getPayment();
        if ($orderPayment?->getMethod() !== Method::CODE) {
            return $result;
        }

        $quotePayment = $subject->getQuote()->getPayment();
        $quotePayment->setMethod(Method::CODE);
        $quotePayment->setAdditionalInformation($orderPayment->getAdditionalInformation());

        return $result;
    }
}

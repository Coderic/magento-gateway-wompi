<?php
declare(strict_types=1);

namespace Wompi\Payment\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Sales\Model\Order;

/**
 * Wompi Plan Agregador: redireccion a Web Checkout (checkout.wompi.co).
 *
 * No confundir $_isGateway (termino Magento: procesamiento en sitio del comercio)
 * con el Plan Gateway de Wompi (adquirencia propia con Bancolombia).
 * Este metodo es offsite: Wompi facilita medios de pago y consigna al comercio.
 */
class Method extends AbstractMethod
{
    public const CODE = 'wompi_payment';

    protected $_code = self::CODE;
    protected $_isGateway = false;
    protected $_canAuthorize = false;
    protected $_canCapture = true;
    protected $_canRefund = false;
    protected $_canVoid = false;
    /**
     * Permite mostrar Wompi en Admin > Edit Order (Magento exige canUseInternal).
     * El checkout storefront sigue siendo el flujo principal; en Admin solo es_co tiene Wompi activo.
     */
    protected $_canUseInternal = true;

    protected $_canUseCheckout = true;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded = true;
    protected $_canOrder = false;

    public function initialize($paymentAction, $stateObject): self
    {
        $stateObject->setData('state', Order::STATE_PENDING_PAYMENT);
        $stateObject->setData('status', 'pending_payment');
        $stateObject->setData('is_notified', false);
        return $this;
    }
}

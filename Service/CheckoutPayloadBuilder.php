<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

use Wompi\Payment\Model\Config;
use Wompi\Payment\Service\IntegritySignature;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\Order;

class CheckoutPayloadBuilder
{
    private const WOMPI_CURRENCY = 'COP';

    public function __construct(
        private readonly Config $config,
        private readonly IntegritySignature $integritySignature,
        private readonly CurrencyFactory $currencyFactory,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function buildForOrder(OrderInterface $order): array
    {
        $storeId = (int) $order->getStoreId();
        $publicKey = $this->config->getPublicKey($storeId);
        $integrityKey = $this->config->getIntegrityKey($storeId);
        if ($publicKey === '' || $integrityKey === '') {
            throw new LocalizedException(__('Wompi is not configured for this store.'));
        }

        $reference = (string) $order->getIncrementId();
        $amountInCents = $this->resolveAmountInCents($order);
        $signature = $this->integritySignature->build(
            $reference,
            $amountInCents,
            self::WOMPI_CURRENCY,
            $integrityKey
        );

        $payload = [
            'public-key' => $publicKey,
            'currency' => self::WOMPI_CURRENCY,
            'amount-in-cents' => (string) $amountInCents,
            'reference' => $reference,
            'signature:integrity' => $signature,
            'redirect-url' => $this->config->getCallbackUrl($storeId),
        ];

        $taxInCents = $this->resolveTaxInCents($order);
        if ($taxInCents > 0) {
            $payload['tax-in-cents:vat'] = (string) $taxInCents;
        }

        $this->appendCustomerData($payload, $order);
        $this->appendShippingAddress($payload, $order);

        return $payload;
    }

    private function resolveTaxInCents(OrderInterface $order): int
    {
        $tax = (float) $order->getTaxAmount();
        if ($tax <= 0) {
            return 0;
        }

        return (int) round($tax * 100);
    }

    /**
     * @param array<string, string> $payload
     */
    private function appendCustomerData(array &$payload, OrderInterface $order): void
    {
        $address = $this->resolveCustomerAddress($order);
        $email = (string) ($order->getCustomerEmail() ?: $address?->getEmail());
        if ($email !== '') {
            $payload['customer-data:email'] = $email;
        }

        $firstName = (string) ($address?->getFirstname() ?: $order->getCustomerFirstname());
        $lastName = (string) ($address?->getLastname() ?: $order->getCustomerLastname());
        $name = trim($firstName . ' ' . $lastName);
        if ($name !== '') {
            $payload['customer-data:full-name'] = $name;
        }

        $phone = (string) ($address?->getTelephone() ?? '');
        if ($phone !== '') {
            $payload['customer-data:phone-number'] = $phone;
            $payload['customer-data:phone-number-prefix'] = '+57';
        }
    }

    /**
     * @param array<string, string> $payload
     */
    private function appendShippingAddress(array &$payload, OrderInterface $order): void
    {
        $shipping = $order->getShippingAddress();
        if (!$shipping || !$shipping->getId()) {
            return;
        }

        $street = $shipping->getStreet();
        $line1 = is_array($street) ? (string) ($street[0] ?? '') : (string) $street;
        if ($line1 !== '') {
            $payload['shipping-address:address-line-1'] = $line1;
        }

        if (is_array($street) && isset($street[1]) && (string) $street[1] !== '') {
            $payload['shipping-address:address-line-2'] = (string) $street[1];
        }

        $country = (string) $shipping->getCountryId();
        if ($country !== '') {
            $payload['shipping-address:country'] = $country;
        }

        $city = (string) $shipping->getCity();
        if ($city !== '') {
            $payload['shipping-address:city'] = $city;
        }

        $region = (string) $shipping->getRegion();
        if ($region !== '') {
            $payload['shipping-address:region'] = $region;
        }

        $phone = (string) $shipping->getTelephone();
        if ($phone !== '') {
            $payload['shipping-address:phone-number'] = $phone;
        }

        $name = trim((string) $shipping->getFirstname() . ' ' . (string) $shipping->getLastname());
        if ($name !== '') {
            $payload['shipping-address:name'] = $name;
        }
    }

    private function resolveCustomerAddress(OrderInterface $order): ?OrderAddressInterface
    {
        $billing = $order->getBillingAddress();
        if ($billing && $billing->getId()) {
            return $billing;
        }

        $shipping = $order->getShippingAddress();
        if ($shipping && $shipping->getId()) {
            return $shipping;
        }

        return null;
    }

    private function resolveAmountInCents(OrderInterface $order): int
    {
        $orderCurrency = (string) $order->getOrderCurrencyCode();
        $grandTotal = (float) $order->getGrandTotal();

        if ($orderCurrency === self::WOMPI_CURRENCY) {
            return (int) round($grandTotal * 100);
        }

        $baseCurrency = (string) $order->getBaseCurrencyCode();
        if ($baseCurrency === self::WOMPI_CURRENCY) {
            return (int) round((float) $order->getBaseGrandTotal() * 100);
        }

        $rate = $this->currencyFactory->create()->load($orderCurrency)->getAnyRate(self::WOMPI_CURRENCY);
        if ($rate === false || $rate <= 0) {
            throw new LocalizedException(
                __('Cannot convert order total to COP for Wompi. Enable COP for store view es_co.')
            );
        }

        return (int) round($grandTotal * (float) $rate * 100);
    }
}

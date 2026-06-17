# Instalación

## Requisitos

Ver [COMPATIBILITY.md](COMPATIBILITY.md): Magento 2.4.6+, PHP 8.1+, checkout Luma.

## Composer

```bash
composer require wompi/magento-payment
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Verificación

1. `bin/magento module:status Wompi_Payment` ? enabled
2. Admin ? Payment Methods ? **Wompi (Colombia)**
3. Webhook en [comercios.wompi.co](https://comercios.wompi.co): `{base_url}wompi/payment/webhook`

## Siguiente paso

- [configuration.md](configuration.md)
- [deployment.md](deployment.md)

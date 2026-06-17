# wompi/magento-gateway-wompi

Extensión **Wompi_Payment** para Magento 2: integración con [Wompi Colombia](https://wompi.co) mediante **Web Checkout** (planes Agregador y Gateway).

| Campo | Valor |
|-------|-------|
| **Paquete Composer** | `wompi/magento-gateway-wompi` |
| **Módulo Magento** | `Wompi_Payment` |
| **Namespace** | `Wompi\Payment\` |
| **Maintainer** | Coderic (`coderic@coderic.org`) |
| **Vendor Marketplace** | Wompi |
| **Repositorio** | `git@github.com:Coderic/magento-gateway-wompi.git` |

Documentación completa en [`docs/README.md`](docs/README.md).

## Instalación

```bash
composer require wompi/magento-gateway-wompi
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
bin/magento setup:di:compile
```

Desarrollo local (path repo):

```bash
# composer.json repositories + require wompi/magento-gateway-wompi @dev
composer update wompi/magento-gateway-wompi
```

## Configuración

Admin: **Stores → Configuration → Sales → Payment Methods → Wompi (Colombia)**

- **Plan Wompi:** Agregador (recomendado) o Gateway
- **Modo de prueba:** alterna entre llaves sandbox y producción (ambas pueden guardarse a la vez)
- **Webhook:** `{base_url}wompi/payment/webhook`

## Checkout Luma

Tras place order, redirect a `wompi/checkout/start` (Web Checkout Wompi).

## Migración desde `coderic/module-wompi-co`

`setup:upgrade` ejecuta parches de datos que migran `payment/coderic_wompi_co/*` → `payment/wompi_payment/*` y el método de pago en pedidos históricos.

Actualizar URL del webhook en el panel Wompi: `/wompi/payment/webhook` (antes `/wompico/payment/webhook`).

## Licencia

OSL-3.0 / AFL-3.0

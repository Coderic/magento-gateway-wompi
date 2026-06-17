# wompi/magento-payment

Extensión oficial **Wompi_Payment** para Magento 2: integración con [Wompi Colombia](https://wompi.co) mediante **Web Checkout** (planes Agregador y Gateway).

| Campo | Valor |
|-------|-------|
| **Paquete Composer** | `wompi/magento-payment` |
| **Módulo Magento** | `Wompi_Payment` |
| **Namespace** | `Wompi\Payment\` |
| **Vendor** | [Wompi](https://wompi.co) |
| **Soporte** | [ayuda@wompi.co](mailto:ayuda@wompi.co) |

Documentación completa en [`docs/README.md`](docs/README.md).

## ¿Qué hace?

1. Muestra **Wompi** como método de pago en checkout Luma.
2. Tras confirmar el pedido, redirige al cliente a **Web Checkout** (`checkout.wompi.co`).
3. Wompi notifica el resultado vía **webhook** (fuente de verdad) y retorno del navegador (callback).
4. Magento marca el pedido como **Pagado** y registra la captura sin falsos positivos de fraude en pagos offsite.

## Instalación

```bash
composer require wompi/magento-payment
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Configuración

Admin: **Stores → Configuration → Sales → Payment Methods → Wompi (Colombia)**

- **Plan Wompi:** Agregador (recomendado) o Gateway
- **Modo de prueba:** alterna entre llaves sandbox y producción (ambas pueden guardarse a la vez)
- **Webhook:** registrar `{base_url}wompi/payment/webhook` en [comercios.wompi.co](https://comercios.wompi.co)

## Rutas

| Ruta | Uso |
|------|-----|
| `wompi/checkout/start` | Redirect a Web Checkout |
| `wompi/payment/callback` | Retorno del navegador |
| `wompi/payment/webhook` | Eventos Wompi |

## Licencia

OSL-3.0 / AFL-3.0

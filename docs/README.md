# Documentación — Wompi Colombia para Magento 2

<p align="center">
  <img src="assets/wompi_logo.png" alt="Wompi" width="200" />
</p>

Extensión **Wompi_Payment** (`wompi/magento-gateway-wompi`): integración con [Wompi Colombia](https://wompi.co) mediante **Web Checkout**.

| Recurso | Enlace |
|---------|--------|
| Documentación Wompi (Web Checkout) | [Widget & Web Checkout](https://docs.wompi.co/docs/colombia/widget-checkout-web/) |
| Plugin oficial Magento (referencia) | [Magento Plugin](https://docs.wompi.co/docs/colombia/magento-plugin/) |
| Código del módulo | [README raíz](../README.md) |

## Índice

| Documento | Descripción |
|-----------|-------------|
| [installation.md](installation.md) | Requisitos, instalación Composer y activación |
| [configuration.md](configuration.md) | Llaves dual sandbox/producción, plan y panel Wompi |
| [payment-flow.md](payment-flow.md) | Flujo checkout ? Wompi ? callback |
| [webhook.md](webhook.md) | Eventos, firma y URL del webhook |
| [order-states.md](order-states.md) | Estados Magento vs estados Wompi |
| [COMPATIBILITY.md](COMPATIBILITY.md) | Versiones Magento, PHP y temas |
| [plans/gateway.md](plans/gateway.md) | Plan Gateway vs Plan Agregador |
| [upstream/README.md](upstream/README.md) | Trazabilidad con plugin oficial Bancolombia/Wompi |
| [CHANGELOG.md](CHANGELOG.md) | Historial de cambios |

## Resumen rápido

- **Planes:** Agregador (probado) y Gateway (mismo flujo Web Checkout en v2.0)
- **Método de pago:** `wompi_payment`
- **Rutas frontend:** `wompi/checkout/start`, `wompi/payment/callback`, `wompi/payment/webhook`
- **Moneda Wompi:** COP (conversión desde la moneda del pedido si aplica)
- **Fuente de verdad del pago:** webhook (el callback del navegador es respaldo UX)

## Íconos y assets

Los logotipos en `docs/assets/` provienen del plugin oficial **Bancolombia_Wompi** v1.7.0:

| Uso | Archivo | Ruta equivalente en plugin oficial |
|-----|---------|-----------------------------------|
| Admin / documentación | `docs/assets/wompi_logo.png` | `view/adminhtml/web/images/wompi_logo.png` |
| Checkout | `docs/assets/wompi_logo_checkout.png` | `view/frontend/web/js/images/wompi_logo.png` |

## Soporte

- Issues: [Coderic/magento-gateway-wompi](https://github.com/Coderic/magento-gateway-wompi)
- Claves y onboarding: [comercios.wompi.co](https://comercios.wompi.co)

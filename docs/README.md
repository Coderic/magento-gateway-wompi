# Documentación — Wompi Colombia para Magento 2

<p align="center">
  <img src="assets/wompi_logo.png" alt="Wompi" width="200" />
</p>

Extensión **Wompi_Payment** (`wompi/magento-payment`): integración con [Wompi Colombia](https://wompi.co) mediante **Web Checkout**.

| Recurso | Enlace |
|---------|--------|
| Documentación Wompi | [docs.wompi.co](https://docs.wompi.co/docs/colombia/magento-plugin/) |
| Web Checkout | [Widget & Web Checkout](https://docs.wompi.co/docs/colombia/widget-checkout-web/) |
| Plugin oficial (referencia) | [Magento Plugin](https://docs.wompi.co/docs/colombia/magento-plugin/) |
| Código del módulo | [README raíz](../README.md) |

## Índice

| Documento | Descripción |
|-----------|-------------|
| [installation.md](installation.md) | Requisitos, instalación Composer y activación |
| [deployment.md](deployment.md) | Actualización, migración y checklist |
| [configuration.md](configuration.md) | Llaves dual sandbox/producción, plan y panel Wompi |
| [payment-flow.md](payment-flow.md) | Flujo checkout ? Wompi ? callback |
| [webhook.md](webhook.md) | Eventos, firma y URL del webhook |
| [order-states.md](order-states.md) | Estados Magento vs estados Wompi |
| [COMPATIBILITY.md](COMPATIBILITY.md) | Versiones Magento, PHP y temas |
| [plans/gateway.md](plans/gateway.md) | Plan Gateway vs Plan Agregador |
| [upstream/README.md](upstream/README.md) | Trazabilidad con plugin Bancolombia/Wompi |
| [CHANGELOG.md](CHANGELOG.md) | Historial de cambios |

## Resumen rápido

- **Vendor:** [Wompi](https://wompi.co) — soporte: [ayuda@wompi.co](mailto:ayuda@wompi.co)
- **Planes:** Agregador (probado) y Gateway (mismo flujo Web Checkout en v2.0)
- **Método de pago:** `wompi_payment`
- **Rutas:** `wompi/checkout/start`, `wompi/payment/callback`, `wompi/payment/webhook`
- **Moneda:** COP (conversión desde la moneda del pedido si aplica)
- **Fuente de verdad del pago:** webhook

## Íconos

Logotipos en `docs/assets/` alineados con el plugin **Bancolombia_Wompi** v1.7.0 (Adobe Marketplace).

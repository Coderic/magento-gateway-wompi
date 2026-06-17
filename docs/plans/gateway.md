# Plan Gateway

<p align="left">
  <img src="../assets/wompi_logo.png" alt="Wompi" width="160" />
</p>

## Resumen

| Dimensión | Plan Agregador | Plan Gateway |
|-----------|----------------|--------------|
| **Negocio** | Wompi facilita medios de pago | Adquirencia propia con Bancolombia |
| **Comisión Wompi** | Según tarifas Agregador | 0% comisión Wompi |
| **Integración Magento (docs)** | Web Checkout + webhook | **La misma** según documentación Wompi |
| **Estado v2.0** | Probado | Mismo flujo; campos Admin placeholder |

## Comportamiento v2.0

- Selector Admin **Plan Wompi** ? `gateway`
- `GatewayCheckoutFlow` delega a `AgregadorCheckoutFlow` (mismo payload Web Checkout)
- Campo **Gateway merchant ID** visible solo con Plan Gateway (`<depends>`)

## Pendiente de confirmar con Wompi

- Códigos de adquirente y medios de aceptación en payload checkout
- Validaciones adicionales de llaves para cuentas Gateway
- Campos Admin definitivos (`acquirer_merchant_id`, `enabled_payment_methods`, etc.)
- Diferencias de onboarding en panel comercios

## Referencias

- [Plan Agregador](https://wompi.com/es/co/planes-tarifas/plan-avanzado-agregador)
- [Plan Gateway](https://wompi.com/es/co/planes-tarifas/plan-gateway)
- [Plugin Magento](https://docs.wompi.co/docs/colombia/magento-plugin/)

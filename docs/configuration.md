# Configuración

<p align="left">
  <img src="assets/wompi_logo.png" alt="Wompi" width="160" />
</p>

## Panel Magento

**Ruta:** Stores ? Configuration ? Sales ? Payment Methods ? **Wompi (Colombia)**

| Campo | Descripción |
|-------|-------------|
| **Enabled** | Activa el método `wompi_payment` |
| **Plan Wompi** | `agregador` (recomendado) o `gateway` |
| **Title** | Texto visible en checkout (por defecto: `Wompi`) |
| **Modo de prueba** | `sandbox` o `production` — selecciona qué juego de llaves usar |
| **Public key (sandbox)** | `pub_test_*` |
| **Public key (production)** | `pub_prod_*` |
| **Private key (sandbox)** | `prv_test_*` (API) |
| **Private key (production)** | `prv_prod_*` |
| **Integrity secret (sandbox)** | Firma Web Checkout sandbox |
| **Integrity secret (production)** | Firma Web Checkout producción |
| **Events secret (sandbox)** | Validación webhooks sandbox |
| **Events secret (production)** | Validación webhooks producción |
| **Gateway merchant ID** | Solo Plan Gateway (placeholder v2.0) |
| **Restrict to store view code** | Opcional. Vacío = todos los stores. Ejemplo: `es_co` |

### Llaves dual sandbox / producción

Como en el plugin WordPress/WooCommerce y el oficial **Bancolombia_Wompi**, puede **pegar ambos juegos de llaves** y alternar con **Modo de prueba** sin reemplazar valores.

### URL base del store

La `redirect-url` enviada a Wompi se genera desde la URL base del store (Stores ? Configuration ? General ? Web).

Ejemplo en `es_co`:

```
https://tu-dominio.com/es_co/wompi/payment/callback/
```

## Panel Wompi (comercios.wompi.co)

### Webhook de eventos

Registrar la URL en **sandbox** y **producción** por separado:

```
https://{base_url}/wompi/payment/webhook
```

Incluya el prefijo de store view si aplica (`/es_co/wompi/payment/webhook`).

El **Events secret** activo en Magento debe coincidir con el del entorno seleccionado en Developers ? Secrets.

### Claves

Obtenga en Wompi Developers:

- Public key, Private key (API transacciones)
- Integrity secret (Web Checkout)
- Events secret (webhooks)

Documentación: [Ambientes y llaves](https://docs.wompi.co/docs/colombia/ambientes-y-llaves/).

## Moneda

Wompi Colombia opera en **COP**. El módulo convierte el `grand_total` del pedido a centavos COP usando la tasa de cambio de Magento cuando el pedido no está ya en COP.

## Checklist de producción

- [ ] **Modo de prueba** = `production`
- [ ] Llaves `*_production` completas en el store view correcto
- [ ] Llaves `*_test` guardadas para pruebas futuras (opcional)
- [ ] Webhook registrado en panel Wompi producción
- [ ] HTTPS en todo el sitio
- [ ] Pedido de prueba con estado **Pagado** (`wompi_paid`) tras pago aprobado
- [ ] `total_paid` y factura creados (sin comentario `fraud`)

## Siguiente paso

[payment-flow.md](payment-flow.md) — flujo completo del checkout.

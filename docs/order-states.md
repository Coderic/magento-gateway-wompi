# Estados de pedido

## ¿Por qué "Procesando" o "Pagado"?

En Magento **no existe un estado nativo llamado "Pagado"** en el ciclo estándar. Tras confirmar el pago, el pedido pasa a **state** `processing`.

| Estado Magento (`state`) | Status en este módulo | Significado |
|--------------------------|----------------------|-------------|
| `pending_payment` | `pending_payment` | Esperando pago en Wompi |
| `processing` | **`wompi_paid`** (etiqueta: **Pagado**) | Pago confirmado; listo para fulfillment |
| `complete` | (default) | Pedido cumplido / facturado y entregado |
| `canceled` | (default) | Pago rechazado o no completado |

**"Procesando" en Magento estándar** significa *pago recibido, pendiente de preparar/enviar*. Este módulo usa el status personalizado **`wompi_paid`** con etiqueta **Pagado** para mayor claridad en `es_CO`.

## Estados Wompi (API)

| Status Wompi | Acción del módulo |
|--------------|-------------------|
| `APPROVED` | Marca pedido pagado + captura (`registerCaptureNotification` con antifraude desactivado) |
| `DECLINED`, `ERROR`, `VOIDED` | Cancela el pedido |

## Webhook vs callback

| Mecanismo | Rol |
|-----------|-----|
| **Webhook** (`POST /wompi/payment/webhook`) | **Fuente de verdad** según [documentación Wompi](https://docs.wompi.co/docs/colombia/eventos/) |
| **Callback** (`GET /wompi/payment/callback?id=`) | Retorno del navegador; respaldo UX |

En producción el webhook suele procesar el pedido **antes** que el redirect del cliente. Ambos caminos son idempotentes: si el pedido ya está pagado, no se repite la captura.

## Captura y antifraude

Pagos offsite en COP con montos altos pueden disparar el filtro antifraude nativo de Magento (`Suspected Fraud`), dejando `total_paid` en NULL.

**Corrección v2.0:** `registerCaptureNotification($amount, true)` — el segundo parámetro omite la detección de fraude para pagos ya verificados por API Wompi.

## Fulfillment

Tras el pago, el equipo debe provisionar el servicio (p. ej. VPS cloud) y pasar el pedido a `complete` cuando corresponda.

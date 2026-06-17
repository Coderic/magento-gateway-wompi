# Webhook

## URL

Registrar en el panel Wompi (sandbox y producción por separado):

```
https://{base_url}/wompi/payment/webhook
```

Con store view `es_co`:

```
https://tu-dominio.com/es_co/wompi/payment/webhook
```

También funciona sin prefijo de store si el webhook llega a la URL base; el módulo resuelve el **store** desde el `reference` (increment_id del pedido).

## Eventos

Wompi envía `POST` con cuerpo JSON (`transaction.updated`). Debe responder **HTTP 200**; Wompi reintenta hasta 3 veces si falla.

## Validación

1. Firma del evento con **Events secret** del entorno activo (`events_key_test` o `events_key_production`)
2. Re-consulta de la transacción vía API Wompi (`TransactionVerifier`)
3. Actualización del pedido según status `APPROVED` / `DECLINED` / etc.

## Observabilidad

Eventos exitosos se registran en `system.log` con nivel `info`:

```
Wompi webhook: payment approved {"reference":"2000000003","transaction_id":"...","store_id":2}
```

Errores de firma o verificación: nivel `warning`.

## CSRF

El endpoint declara `CsrfAwareActionInterface` y acepta POST externos sin token Magento (requerido para webhooks Wompi).

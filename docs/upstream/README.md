# Upstream: Bancolombia/Wompi (Magento Marketplace)

Referencia arquitectónica del plugin oficial Wompi para Magento 2.

| Recurso | Detalle |
|---------|---------|
| Documentación | [docs.wompi.co — Magento Plugin](https://docs.wompi.co/docs/colombia/magento-plugin/) |
| Versión referencia | `Bancolombia_Wompi` v1.7.0 |
| Evolución | `Wompi_Payment` v2.0 — vendor Wompi, maintainer Coderic |

## Patrones portados

- Redirect `checkout/start` ? Web Checkout GET
- Callback con `?id=` (retorno navegador)
- Webhook con firma de eventos + re-fetch API
- Llaves sandbox/producción simultáneas + selector de entorno
- Íconos `wompi_logo.png` (admin y checkout)

## Diferencias v2.0

| Aspecto | Bancolombia_Wompi | Wompi_Payment |
|---------|-------------------|---------------|
| Vendor Composer | `bancolombia/wompi` | `wompi/magento-gateway-wompi` |
| Plan Agregador/Gateway | Sin selector | Selector Admin `business_model` |
| Status pagado | `processing` default | `wompi_paid` («Pagado») |
| Antifraude captura | (comportamiento stock) | `skipFraudDetection` en offsite |

Este directorio no contiene el código del vendor; solo trazabilidad.

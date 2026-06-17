# Upstream: Bancolombia/Wompi (Adobe Marketplace)

Referencia del plugin histórico Wompi para Magento 2.

| Recurso | Detalle |
|---------|---------|
| Documentación | [docs.wompi.co — Magento Plugin](https://docs.wompi.co/docs/colombia/magento-plugin/) |
| Versión referencia | `Bancolombia_Wompi` v1.7.0 |
| Evolución | `Wompi_Payment` v2.0 — vendor [Wompi](https://wompi.co) |

## Patrones compartidos

- Redirect `checkout/start` ? Web Checkout GET
- Callback con `?id=`
- Webhook con firma + re-fetch API
- Llaves sandbox/producción simultáneas

## Mejoras v2.0

| Aspecto | Bancolombia_Wompi | Wompi_Payment |
|---------|-------------------|---------------|
| Paquete | `bancolombia/wompi` | `wompi/magento-payment` |
| Plan Agregador/Gateway | Sin selector | Selector Admin |
| Status pagado | `processing` default | `wompi_paid` («Pagado») |
| Antifraude captura | Stock | Optimizado offsite |

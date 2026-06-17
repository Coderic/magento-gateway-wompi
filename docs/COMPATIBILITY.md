# Compatibilidad

Probado en **coderic.cloud** (Magento **2.4.9**, PHP **8.3**, tema **Luma** stock).

| Requisito | Versión mínima | Notas |
|-----------|----------------|-------|
| **Magento Open Source / Adobe Commerce** | **2.4.6+** (recomendado **2.4.9+**) | Probado en 2.4.9 |
| **PHP** | **8.1+** (recomendado **8.3+**) | `declare(strict_types=1)` |
| **Checkout** | Luma (Knockout) | Renderer en `checkout_index_index.xml` |
| **Hyvä Checkout** | No incluido | Paquete aparte si aplica licencia |
| **Plan Wompi — Agregador** | v2.0 | Web Checkout probado |
| **Plan Wompi — Gateway** | v2.0 | Mismo Web Checkout; campos extra extensibles |

## Paquete

```json
{
  "name": "wompi/magento-gateway-wompi",
  "version": "2.0.0"
}
```

## Migración desde v1.x (`coderic/module-wompi-co`)

| Antes | Después |
|-------|---------|
| `Coderic_WompiCo` | `Wompi_Payment` |
| `coderic_wompi_co` | `wompi_payment` |
| `wompico/*` | `wompi/*` |
| Una llave + `environment` | Llaves `*_test` / `*_production` + `test_mode` |

Ejecutar `bin/magento setup:upgrade` para aplicar parches de migración de configuración.

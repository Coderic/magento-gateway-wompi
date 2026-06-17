# Despliegue

## Instalación o actualización

```bash
composer require wompi/magento-payment:^2.0
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade --no-interaction
bin/magento setup:di:compile
bin/magento cache:flush
```

## Webhook en panel Wompi

Registrar en [comercios.wompi.co](https://comercios.wompi.co) (sandbox y producción por separado):

```
https://{base_url}/wompi/payment/webhook
```

Si usa store views con prefijo de URL:

```
https://{dominio}/{store_view}/wompi/payment/webhook
```

## Migración desde instalaciones previas

`setup:upgrade` ejecuta parches que detectan configuración legacy en base de datos y la mapean a `payment/wompi_payment/*`, incluyendo llaves dual `_test` / `_production`.

Tras migrar, actualice la URL del webhook en el panel Wompi si antes usaba otro front name (p. ej. `/wompico/`).

## Desarrollo con path repository

```json
{
  "repositories": [
    { "type": "path", "url": "../magento2-module-wompi-co" }
  ],
  "require": { "wompi/magento-payment": "@dev" },
  "extra": {
    "installer-paths": {
      "app/code/Wompi/Payment": ["wompi/magento-payment"]
    }
  }
}
```

Montaje típico en contenedor Docker:

```yaml
- ./vendor/wompi/magento-payment:/var/www/html/app/code/Wompi/Payment
```

## Checklist post-despliegue

- [ ] `bin/magento module:status Wompi_Payment` ? enabled
- [ ] Admin: llaves sandbox y producción; **Modo de prueba** correcto
- [ ] Webhook registrado en ambos entornos Wompi
- [ ] Pago sandbox: status **Pagado** (`wompi_paid`), `total_paid` y factura OK
- [ ] Log: `Wompi webhook: payment approved` en `system.log`

## Siguiente paso

[configuration.md](configuration.md)

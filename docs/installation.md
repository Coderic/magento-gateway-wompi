# Instalación

## Requisitos

Ver [COMPATIBILITY.md](COMPATIBILITY.md): Magento 2.4.6+, PHP 8.1+, checkout Luma.

## Composer (producción)

```bash
composer require wompi/magento-gateway-wompi
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Repositorio path (desarrollo Coderic)

```json
{
  "repositories": [
    {
      "type": "path",
      "url": "../magento2-module-wompi-co"
    }
  ],
  "require": {
    "wompi/magento-gateway-wompi": "@dev"
  },
  "extra": {
    "installer-paths": {
      "app/code/Wompi/Payment": ["wompi/magento-gateway-wompi"]
    }
  }
}
```

```bash
composer update wompi/magento-gateway-wompi
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
```

## Podman (coderic.cloud)

Montaje en `compose.yaml`:

```
../magento2-module-wompi-co:/var/www/html/app/code/Wompi/Payment
```

## Verificación

1. `bin/magento module:status Wompi_Payment` ? enabled
2. Admin ? Payment Methods ? Wompi (Colombia)
3. Registrar webhook en panel Wompi

## Migración desde `coderic/module-wompi-co`

```bash
bin/magento module:disable Coderic_WompiCo
bin/magento module:enable Wompi_Payment
bin/magento setup:upgrade
```

Los parches de datos migran configuración y método en pedidos históricos.

## Siguiente paso

[configuration.md](configuration.md) — llaves API, entorno y webhook.

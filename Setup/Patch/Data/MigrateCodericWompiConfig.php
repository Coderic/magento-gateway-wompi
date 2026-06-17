<?php
declare(strict_types=1);

namespace Wompi\Payment\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateCodericWompiConfig implements DataPatchInterface
{
    private const OLD_PREFIX = 'payment/coderic_wompi_co/';
    private const NEW_PREFIX = 'payment/wompi_payment/';

    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('core_config_data');

        $select = $connection->select()
            ->from($table, ['config_id', 'scope', 'scope_id', 'path', 'value'])
            ->where('path LIKE ?', self::OLD_PREFIX . '%');

        $rows = $connection->fetchAll($select);
        if ($rows === []) {
            return;
        }

        $environmentByScope = [];
        foreach ($rows as $row) {
            $suffix = substr((string) $row['path'], strlen(self::OLD_PREFIX));
            if ($suffix === 'environment') {
                $key = $row['scope'] . ':' . $row['scope_id'];
                $environmentByScope[$key] = (string) $row['value'];
            }
        }

        foreach ($rows as $row) {
            $suffix = substr((string) $row['path'], strlen(self::OLD_PREFIX));
            $scopeKey = $row['scope'] . ':' . $row['scope_id'];
            $env = $environmentByScope[$scopeKey] ?? 'sandbox';
            $keySuffix = $env === 'production' ? '_production' : '_test';

            $newPath = match ($suffix) {
                'environment' => self::NEW_PREFIX . 'test_mode',
                'public_key', 'private_key', 'integrity_key', 'events_key' =>
                    self::NEW_PREFIX . $suffix . $keySuffix,
                default => self::NEW_PREFIX . $suffix,
            };

            $connection->insertOnDuplicate(
                $table,
                [
                    'scope' => $row['scope'],
                    'scope_id' => $row['scope_id'],
                    'path' => $newPath,
                    'value' => $row['value'],
                ],
                ['value']
            );
        }

        $connection->insertOnDuplicate(
            $table,
            [
                'scope' => 'default',
                'scope_id' => 0,
                'path' => self::NEW_PREFIX . 'business_model',
                'value' => 'agregador',
            ],
            ['value']
        );
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}

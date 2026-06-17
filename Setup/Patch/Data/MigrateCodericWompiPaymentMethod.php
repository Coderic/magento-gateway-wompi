<?php
declare(strict_types=1);

namespace Wompi\Payment\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MigrateCodericWompiPaymentMethod implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    public function apply(): void
    {
        $connection = $this->moduleDataSetup->getConnection();
        $paymentTable = $this->moduleDataSetup->getTable('sales_order_payment');

        $connection->update(
            $paymentTable,
            ['method' => 'wompi_payment'],
            ['method = ?' => 'coderic_wompi_co']
        );
    }

    public static function getDependencies(): array
    {
        return [MigrateCodericWompiConfig::class];
    }

    public function getAliases(): array
    {
        return [];
    }
}

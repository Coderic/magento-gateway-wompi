<?php
declare(strict_types=1);

namespace Wompi\Payment\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class BusinessModel implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'agregador', 'label' => __('Plan Agregador (recomendado)')],
            ['value' => 'gateway', 'label' => __('Plan Gateway')],
        ];
    }
}

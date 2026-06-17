<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

class IntegritySignature
{
    public function build(string $reference, int $amountInCents, string $currency, string $integritySecret): string
    {
        $payload = $reference . $amountInCents . strtoupper($currency) . $integritySecret;
        return hash('sha256', $payload);
    }
}

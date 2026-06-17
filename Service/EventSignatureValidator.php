<?php
declare(strict_types=1);

namespace Wompi\Payment\Service;

/**
 * Valida checksum de eventos Wompi (docs Eventos Colombia).
 */
class EventSignatureValidator
{
    public function isValid(array $eventPayload, string $eventsSecret, ?string $headerChecksum = null): bool
    {
        if ($eventsSecret === '') {
            return false;
        }

        $signature = $eventPayload['signature'] ?? null;
        if (!is_array($signature)) {
            return false;
        }

        /** @var list<string> $properties */
        $properties = $signature['properties'] ?? [];
        if ($properties === []) {
            return false;
        }

        $timestamp = (string) ($eventPayload['timestamp'] ?? '');
        $data = $eventPayload['data'] ?? [];
        if (!is_array($data)) {
            return false;
        }

        $concat = '';
        foreach ($properties as $path) {
            $concat .= $this->resolveProperty((string) $path, $data);
        }
        $concat .= $timestamp;
        $concat .= $eventsSecret;

        $calculated = hash('sha256', $concat);
        $expected = strtolower((string) ($signature['checksum'] ?? $headerChecksum ?? ''));

        return $expected !== '' && hash_equals($calculated, $expected);
    }

    private function resolveProperty(string $path, array $data): string
    {
        $current = $data;
        foreach (explode('.', $path) as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return '';
            }
            $current = $current[$segment];
        }

        return is_scalar($current) ? (string) $current : '';
    }
}

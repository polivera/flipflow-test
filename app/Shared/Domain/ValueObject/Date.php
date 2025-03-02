<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Carbon\CarbonImmutable;

final readonly class Date
{
    public const DEFAULT_FORMAT = 'Y-m-d';
    public const DATE_AND_TIME = 'Y-m-d H:i:s';

    private function __construct(public CarbonImmutable $value) {

    }

    public static function now(): Date
    {
        return new self(CarbonImmutable::now());
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }
}

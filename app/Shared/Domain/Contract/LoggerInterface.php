<?php

declare(strict_types=1);

namespace App\Shared\Domain\Contract;

interface LoggerInterface
{
    public function error(string $message, array $context = []): void;
}

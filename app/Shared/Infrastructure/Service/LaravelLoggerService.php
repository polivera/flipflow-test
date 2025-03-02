<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Contract\LoggerInterface;
use Illuminate\Support\Facades\Log;

final readonly class LaravelLoggerService implements LoggerInterface
{
    public function error(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }
}

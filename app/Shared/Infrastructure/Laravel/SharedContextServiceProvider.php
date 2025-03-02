<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Laravel;

use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Infrastructure\Service\LaravelLoggerService;
use Carbon\Laravel\ServiceProvider;

final class SharedContextServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LoggerInterface::class, LaravelLoggerService::class);
    }
}

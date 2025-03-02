<?php

declare(strict_types=1);

use App\Contexts\Scraper\Infrastructure\Factory\ProductScraper\CarrefourProductScraper;

return [
    'product' => [
        'www.carrefour.es' => CarrefourProductScraper::class,
    ]
];

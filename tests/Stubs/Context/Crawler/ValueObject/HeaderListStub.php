<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\HeaderList;

final readonly class HeaderListStub
{
    public static function random(int $amount = 3): HeaderList
    {
        $headers = [];
        for ($i = 0; $i < $amount; $i++) {
            $headers[] = HeaderStub::random();
        }
        return HeaderList::fromArray($headers);
    }
}

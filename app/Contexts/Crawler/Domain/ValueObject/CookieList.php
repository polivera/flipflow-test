<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use ArrayIterator;
use Iterator;

final class CookieList
{
    private function __construct(private array $data)
    {
    }

    public function add(Cookie $cookie): void
    {
        $this->data[$cookie->name] = $cookie;
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->data);
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public static function fromJson(string $json): self
    {
        $cookieArray = json_decode($json, true);
        $resultArray = [];
        foreach ($cookieArray as $name=>$value) {
            $resultArray[$name] = Cookie::create($name, $value);
        }
        return new self($resultArray);
    }
}

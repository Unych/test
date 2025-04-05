<?php

namespace Raketa\BackendTestTask\Repository\Interface;

use Raketa\BackendTestTask\Domain\Cart;

interface CartRepositoryInterface
{
    public function get(string $key): ?Cart;
    public function save(string $key, Cart $cart): void;
    public function has(string $key): bool;
}

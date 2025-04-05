<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Repository\Interface\CartRepositoryInterface;

final class CartManager
{
    public function __construct(
        private CartRepositoryInterface $cartRepository
    )
    {
    }

    public function saveCart(string $key, Cart $cart): void
    {
        $this->cartRepository->save($key, $cart);
    }

    public function getCart(string $key): ?Cart
    {
        return $this->cartRepository->get($key);
    }

    public function hasCart(string $key): bool
    {
        return $this->cartRepository->has($key);
    }
}

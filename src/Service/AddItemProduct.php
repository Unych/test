<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Service;

use Ramsey\Uuid\Uuid;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;

final class AddItemProduct
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartManager       $cartManager
    )
    {
    }

    public function addProductToCart(string $productUuid, int $quantity): \Raketa\BackendTestTask\Domain\Cart
    {
        $product = $this->productRepository->getByUuid($productUuid);
        $cart    = $this->cartManager->getCart();

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity
        ));

        return $this->cartManager->getCart(session_id());
    }
}

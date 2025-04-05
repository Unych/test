<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;

final class CartView
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid'           => $cart->getUuid(),
            'customer'       => [
                'id'    => $cart->getCustomer()->getId(),
                'name'  => $cart->getCustomer()->getFullName(),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
            'items'          => [],
        ];

        foreach ($cart->getItems() as $item) {
            $product = $this->productRepository->getByUuid($item->getProductUuid());

            $data['items'][] = [
                'uuid'     => $item->getUuid(),
                'price'    => $item->getPrice(),
                'total'    => $item->getTotal(),
                'quantity' => $item->getQuantity(),
                'product'  => [
                    'id'        => $product->getId(),
                    'uuid'      => $product->getUuid(),
                    'name'      => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price'     => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $cart->getTotal();

        return $data;
    }
}
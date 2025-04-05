<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

final class ProductsView
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    public function toArray(string $category): array
    {
        $products = $this->productRepository->getByCategory($category);

        return array_map(
            fn(Product $product) => $this->mapProductToArray($product),
            $products
        );
    }

    private function mapProductToArray(Product $product): array
    {
        return [
            'id'          => $product->getId(),
            'uuid'        => $product->getUuid(),
            'category'    => $product->getCategory(),
            'description' => $product->getDescription(),
            'thumbnail'   => $product->getThumbnail(),
            'price'       => $product->getPrice(),
        ];
    }
}

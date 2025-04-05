<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\Exception\DbalException;

final class ProductRepository
{
    public function __construct(
        private Connection $connection
    )
    {
    }

    public function getByUuid(string $uuid): Product
    {
        try {
            $row = $this->connection->fetchAssociative(
                'SELECT * FROM products WHERE uuid = :uuid',
                ['uuid' => $uuid]
            );

            if (!$row) {
                throw new \RuntimeException("Product not found: {$uuid}");
            }

            return $this->make($row);
        } catch (DbalException $e) {
            throw new \RuntimeException('Database error while fetching product', 0, $e);
        }
    }

    public function getByCategory(string $category): array
    {
        try {
            $rows = $this->connection->fetchAllAssociative(
                'SELECT * FROM products WHERE is_active = 1 AND category = :category',
                ['category' => $category]
            );

            return array_map(fn(array $row) => $this->make($row), $rows);
        } catch (DbalException $e) {
            throw new \RuntimeException('Database error while fetching products by category', 0, $e);
        }
    }

    private function make(array $row): Product
    {
        return new Product(
            (int)$row['id'],
            $row['uuid'],
            (bool)$row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            (float)$row['price'],
        );
    }
}
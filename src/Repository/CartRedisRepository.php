<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Connector;
use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Raketa\BackendTestTask\Repository\Interface\CartRepositoryInterface;

final class CartRedisRepository implements CartRepositoryInterface
{
    private const TTL_SECONDS = 86400;

    public function __construct(
        private Connector       $connector,
        private LoggerInterface $logger
    )
    {
    }

    public function get(string $key): ?Cart
    {
        try {
            return $this->connector->get($key);
        } catch (ConnectorException $e) {
            $this->logger->error('Redis: ошибка при получении корзины', [
                'key'       => $key,
                'exception' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function save(string $key, Cart $cart): void
    {
        try {
            $this->connector->set($key, $cart, self::TTL_SECONDS);
        } catch (ConnectorException $e) {
            $this->logger->error('Redis: ошибка при сохранении корзины', [
                'key'       => $key,
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function has(string $key): bool
    {
        return $this->connector->has($key);
    }
}

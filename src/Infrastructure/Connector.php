<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Redis;
use RedisException;

final class Connector
{
    public function __construct(
        private Redis $redis
    )
    {
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key): ?Cart
    {
        try {
            $data = $this->redis->get($key);
            return $data ? unserialize($data) : null;
        } catch (RedisException $e) {
            throw new ConnectorException('Failed to get from Redis', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, Cart $value, int $ttl): void
    {
        try {
            $this->redis->setex($key, $ttl, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Failed to set in Redis', $e->getCode(), $e);
        }
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key) > 0;
    }
}

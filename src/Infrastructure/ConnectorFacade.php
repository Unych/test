<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Redis;
use RedisException;

final class ConnectorFacade
{
    public static function fromConfig(array $config): Connector
    {

        try {
            $config = require __DIR__ . '/../Infrastructure/config.php';

            $redis = new Redis();
            $redis->connect($config['host'], $config['port'], 2.0);

            if (!empty($config['password'])) {
                $redis->auth($config['password']);
            }

            if ($redis->ping() !== '+PONG') {
                throw new RedisException('Redis ping failed');
            }

            return new Connector($redis);

        } catch (RedisException $e) {
            throw new ConnectorException('Redis connection failed', $e->getCode(), $e);
        }
    }
}

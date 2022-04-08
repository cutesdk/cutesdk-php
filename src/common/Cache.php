<?php

// default cache handler 

namespace cutesdk\common;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class Cache
{
    private $cache;

    public function __construct(array $options)
    {
        $driver = $options['driver'] ?? 'file';

        if ($driver == 'redis') {
            $dsn = $options['redis']['dsn'] ?? '';
            if ($dsn) {
                $conn = RedisAdapter::createConnection($dsn);
                $this->cache = new RedisAdapter($conn);
            }
        } else {
            $this->cache = new FilesystemAdapter($options['namespace'] ?? '', $options['expire'] ?? 0, $options['dir'] ?? null);
        }
    }

    public function get(string $key): mixed
    {
        $cacheItem = $this->cache->getItem($key);

        return $cacheItem->get();
    }

    public function set(string $key, mixed $value, int $expire)
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->set($value);
        $cacheItem->expiresAfter($expire);

        $this->cache->save($cacheItem);
    }
}

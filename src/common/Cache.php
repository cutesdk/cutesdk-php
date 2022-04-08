<?php

// default cache handler 

namespace cutesdk\common;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class Cache
{
    private $driver;

    private $cache;

    public function __construct(array $options)
    {
        $driver = $options['driver'] ?? 'file';

        if ($driver == 'redis') {
        } else {
            $this->cache = new FilesystemAdapter($options['namespace'] ?? '', $options['expire'] ?? 0, $options['dir'] ?? null);
        }

        $this->driver = $driver;
    }

    public function get(string $key): mixed
    {
        if ($this->driver == 'file') {
            $cacheItem = $this->cache->getItem($key);

            return $cacheItem->get();
        }

        return null;
    }

    public function set(string $key, mixed $value, int $expire)
    {
        if ($this->driver == 'file') {
            $cacheItem = $this->cache->getItem($key);
            $cacheItem->set($value);
            $cacheItem->expiresAfter($expire);

            $this->cache->save($cacheItem);
        }
    }
}

<?php

namespace cutesdk\ttapp;

class AccessToken
{
    private $client;

    private $cacheKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCacheKey(): string
    {
        if (!$this->cacheKey) {
            $this->cacheKey = sprintf('ttapp.access_token.%s', $this->client->getAppid());
        }

        return $this->cacheKey;
    }

    public function setCacheKey(string $key)
    {
        $this->cacheKey = $key;
    }

    public function getToken(): string
    {
        // get access_token from cache
        $cache = $this->client->getCache();

        $token = $cache->get($this->getCacheKey());

        if ($token && is_string($token)) {
            return $token;
        }

        // get access_token from api
        $res = $this->client->fetchAccessToken();

        if ($token = $res->get('data.access_token')) {
            // set access_token to cache
            $this->setToken($token, $res->get('data.expires_in'));

            return $token;
        }

        return '';
    }

    public function setToken(string $token, int $expire)
    {
        // set access_token to cache 

        $cache = $this->client->getCache();

        $cache->set($this->getCacheKey(), $token, $expire);
    }
}

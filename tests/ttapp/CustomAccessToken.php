<?php

namespace cutesdk\tests\ttapp;

use cutesdk\ttapp\Client;

class CustomAccessToken
{

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getToken()
    {
        $cache = $this->client->getCache();
        $cacheKey = sprintf('ttapp:access_token:%s', $this->client->getAppid());

        $token = $cache->get($cacheKey);
        if ($token) {
            return $token;
        }

        $res = $this->client->fetchAccessToken();
        if ($token = $res->get('data.access_token')) {
            $expire = $res->get('data.expires_in');

            $cache->set($cacheKey, $token, $expire - 120);

            return $token;
        }

        return '';
    }
}

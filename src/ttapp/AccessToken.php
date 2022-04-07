<?php

namespace cutesdk\ttapp;

class AccessToken
{
    private $client;

    private $cache;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->cache = $client->getCache();
    }

    public function getToken()
    {
        // todo: get access_token from cache

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
        // todo set access_token to cache 
        return true;
    }
}

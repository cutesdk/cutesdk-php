<?php

namespace cutesdk\ttapp;

use cutesdk\common\Request;
use cutesdk\common\Cache;

class Client
{
    // client options
    private $options;

    // request handler 
    private $request;

    // cache handler
    private $cache;

    // access_token handler
    private $accessToken;

    public function __construct(array $customOptions)
    {
        $options = array_merge(Option::$defaultOptions, $customOptions);

        $this->options = $options;

        $this->request = new Request($options['request'] ?? []);

        // default cache handler
        $this->cache = new Cache($options['cache'] ?? []);

        // default access_token handler
        $this->accessToken = new AccessToken($this);
    }

    // set custom cache handler
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    // get cache handler
    public function getCache()
    {
        return $this->cache;
    }

    // set custom access_token handler
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    // get access_token handler 
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function pickAccessToken()
    {
        return $this->getAccessToken()->getToken();
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getAppid()
    {
        return $this->options['appid'] ?? '';
    }

    public function getSecret()
    {
        return $this->options['secret'] ?? '';
    }

    public function __call($method, $arguments)
    {
        $api = new Api($this);

        if (method_exists($api, $method)) {
            return call_user_func_array([$api, $method], $arguments);
        }

        if (method_exists($this->request, $method)) {
            return call_user_func_array([$this->request, $method], $arguments);
        }

        throw new \Exception('method not exists: ' . $method);
    }
}

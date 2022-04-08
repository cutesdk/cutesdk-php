<?php

namespace cutesdk\tests\ttapp;

use cutesdk\ttapp\Client;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'appid' => 'ttfb118dac643b1233',
            'secret' => '92811c680709b5bbc442dba42bb2a681cf04acd5',
            'request' => [
                'debug' => false
            ],
        ]);
    }

    public function testApiCode2session()
    {
        $code = 'xxx';
        $res = $this->client->code2session($code);

        $this->assertSame(40018, $res->get('err_no'));
    }

    public function testApiFetchAccessToken()
    {
        $res = $this->client->fetchAccessToken();

        $this->assertSame(7200, $res->get('data.expires_in'));
    }

    public function testPickAccessToken()
    {
        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }

    public function testPostApiWithAccessTokenInHeader()
    {
        $uri = '/api/v2/tags/text/antidirt';
        $data = [
            'tasks' => [
                [
                    'content' => 'hello'
                ]
            ]
        ];

        $res = $this->client->postJson($uri, $data, [
            'headers' => [
                'X-Token' => $this->client->pickAccessToken()
            ]
        ]);


        $this->assertNotEmpty($res->get('log_id'));
    }

    public function testPostApiWithAccessTokenInBody()
    {
        $uri = '/api/apps/censor/image';

        $data = [
            'app_id' => $this->client->getAppid(),
            'access_token' => $this->client->pickAccessToken(),
            'image' => 'xx',
            'image_data' => 'xxx'
        ];
        $res = $this->client->postJson($uri, $data);

        $this->assertNotEmpty($res->get('error'));
    }

    public function testCustomCache()
    {
        $this->client->setCache(new CustomCache());

        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }

    public function testCustomAccessToken()
    {
        $this->client->setAccessToken(new CustomAccessToken($this->client));

        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }

    public function testCustomCacheKey()
    {
        $options = [
            'cache' => [
                'driver' => 'redis',
                'redis' => [
                    'dsn' => 'redis://test123@127.0.0.1:6379/1'
                ]
            ]
        ];

        $this->client->setOptions($options);

        $cacheKey = "easywechat.kernel.access_token." . md5(json_encode([
            'grant_type' => 'client_credential',
            'appid' => $this->client->getAppid(),
            'secret' => $this->client->getSecret(),
        ]));

        $this->client->getAccessToken()->setCacheKey($cacheKey);

        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }

    public function testCacheWithRedis()
    {
        $options = [
            'cache' => [
                'driver' => 'redis',
                'redis' => [
                    'dsn' => 'redis://test123@127.0.0.1:6379/1'
                ]
            ]
        ];

        $this->client->setOptions($options);

        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }
}

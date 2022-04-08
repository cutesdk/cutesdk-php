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
            'debug' => false,
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

        $this->assertSame(3, $res->get('error'));
    }

    public function testCustomCache()
    {
        $this->client->setCache(new CustomCache());

        $res = $this->client->pickAccessToken();

        $this->assertNotEmpty($res);
    }
}

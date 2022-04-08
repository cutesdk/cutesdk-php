<?php

namespace cutesdk\ttapp;

use cutesdk\common\Response;

class Api
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // api: getAccessToken
    public function fetchAccessToken(): Response
    {
        $uri = '/api/apps/v2/token';
        $data = [
            'appid' => $this->client->getAppid(),
            'secret' => $this->client->getSecret(),
            'grant_type' => 'client_credential'
        ];

        return $this->client->postJson($uri, $data);
    }

    // api: code2session
    public function code2session(string $code, string $anonymousCode = ''): Response
    {
        $uri = '/api/apps/v2/jscode2session';
        $data = [
            'appid' => $this->client->getAppid(),
            'secret' => $this->client->getSecret(),
            'code' => $code,
            'anonymous_code' => $anonymousCode
        ];

        return $this->client->postJson($uri, $data);
    }

    // api: createQRCode
    public function createQrcode(array $data = []): Response
    {
        $uri = '/api/apps/qrcode';

        $data['access_token'] = $this->client->pickAccessToken();

        return $this->client->postJson($uri, $data);
    }

    public function checkContent(array $contents)
    {
        $uri = '/api/v2/tags/text/antidirt';

        $data = [
            'tasks' => []
        ];

        foreach ($contents as $content) {
            $data['tasks'][] = [
                'content' => $content
            ];
        }

        return $this->client->postJson($uri, $data, [
            'headers' => [
                'X-Token' => $this->client->pickAccessToken()
            ]
        ]);
    }
}

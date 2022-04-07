<?php

namespace cutesdk\common;

use GuzzleHttp\Client;

class Request
{
    // request options;
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    // make post request with json data
    public function postJson(string $uri, array $data = [], array $options = []): Response
    {
        $options['json'] = $data;

        return $this->post($uri, $options);
    }

    // make post request
    public function post(string $uri, array $options = []): Response
    {
        return $this->request('POST', $uri, $options);
    }

    // make get request
    public function get(string $uri, array $options): Response
    {
        return $this->request('GET', $uri, $options);
    }

    // make request
    public function request(string $method, string $uri, array $options): Response
    {
        $client = new Client([
            'base_uri' => $this->options['base_uri'] ?? '',
            'debug' => $this->options['debug'] ?? false
        ]);

        $response = $client->request($method, $uri, $options);

        return new Response($response);
    }
}

<?php

namespace cutesdk\common;

class Response
{
    private $innerResponse;
    private $bodyContents;

    public function __construct(\Psr\Http\Message\ResponseInterface $response)
    {
        $this->innerResponse = $response;
    }

    public function contents()
    {
        if ($this->bodyContents) {
            return $this->bodyContents;
        }
        try {
            $contents = $this->innerResponse->getBody()->getContents();
            $this->bodyContents = $contents;

            return $contents;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function json(): array
    {
        try {
            $json = json_decode($this->contents(), true);

            return $json ?? [];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function get(string $key)
    {
        try {
            $json = $this->json();

            $value = $json;
            $keyArr = explode('.', $key);
            foreach ($keyArr as $k) {
                $value = $value[$k] ?? '';
            }

            return $value;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this->innerResponse, $method)) {
            return call_user_func_array([$this->innerResponse, $method], $arguments);
        }

        throw new \Exception('method not exists: ' . $method);
    }
}

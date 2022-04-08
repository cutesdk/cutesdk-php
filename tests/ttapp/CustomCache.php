<?php

namespace cutesdk\tests\ttapp;

class CustomCache
{
    public function get(string $key): mixed
    {
        $cacheData = file_get_contents($this->getCacheFile($key));

        if (!$cacheData) {
            return null;
        }

        $cacheData = json_decode($cacheData, true);

        $expireAt = $cacheData['expireAt'] ?? 0;
        if ($expireAt < time()) {
            return null;
        }

        $value = $cacheData['value'] ?? '';

        return $value;
    }

    public function set(string $key, mixed $value, int $expire)
    {
        $expireAt = time() + $expire;
        file_put_contents($this->getCacheFile($key), json_encode(['key' => $key, 'value' => $value, 'expireAt' => $expireAt]));
    }

    private function getCacheFile(string $key): string
    {
        $cacheDir = './tmp';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cacheFile = sprintf('%s/cache_%s', $cacheDir, base64_encode($key));
        if (!is_file($cacheFile)) {
            touch($cacheFile);
        }

        return $cacheFile;
    }
}

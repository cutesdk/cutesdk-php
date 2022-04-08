<?php

namespace cutesdk\ttapp;

class Option
{
    public static $defaultOptions = [
        'appid' => '',
        'secret' => '',
        'request' => [
            'base_uri' => 'https://developer.toutiao.com',
            'debug' => false,
        ],
        'cache' => [
            'driver' => 'file',
        ]
    ];
}

## Quick Start

- install cutesdk via composer 

```shell
composer require cutesdk/cutesdk
```

## Basic Usage

```php
<?php

require './vendor/autoload.php';

use cutesdk\ttapp\Client;

// new cient 
$client = new Client([
    'appid' => 'ttxxx',
    'secret' => 'xxxxxx',
    'request' => [
        'debug' => true
    ],
]);

// use built-in api
$code = 'xxx';
$res = $client->code2session($code);
var_dump($res->get('data.openid'));

// request any apis:
$uri = '/api/apps/share_config';
$data = [
    'appid' => $client->getAppid(),
    'uniq_id' => 'xxx',
    'access_token' => $client->pickAccessToken(),
    'type' => 2
];
$res = $client->postJson($uri, $data);
var_dump($res->get('err_no'));
```
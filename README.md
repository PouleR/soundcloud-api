[![Latest Stable Version](https://poser.pugx.org/pouler/soundcloud-api/v/stable)](https://packagist.org/packages/pouler/soundcloud-api)
[![Latest Unstable Version](https://poser.pugx.org/pouler/soundcloud-api/v/unstable)](https://packagist.org/packages/pouler/soundcloud-api)

# SoundCLoud API PHP

This is a PHP wrapper for the [SoundCloud API](https://developers.soundcloud.com/docs/api/reference).

## Requirements
* PHP >=8.1
* Symfony HTTP Client

## Installation
Install it using [Composer](https://getcomposer.org/):

```sh
composer require pouler/soundcloud-api
```

```php
<?php

require 'vendor/autoload.php';

$curl = new \Symfony\Component\HttpClient\CurlHttpClient();
$client = new PouleR\SoundCloudAPI\SoundCloudClient($curl);

$api = new \PouleR\SoundCloudAPI\SoundCloudAPI($client);
$api->setClientId('client.id');

// Get information about given user
$api->getUser(123456);

// Get information about current usr
$api->setAccessToken('access.token');
$api->getUser();

var_dump($result);

```

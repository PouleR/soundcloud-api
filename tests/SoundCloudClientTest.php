<?php

namespace PouleR\SoundCloudAPI\Tests;

use PHPUnit\Framework\TestCase;
use PouleR\SoundCloudAPI\SoundCloudAPIException;
use PouleR\SoundCloudAPI\SoundCloudClient;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;

/**
 * Class SoundCloudClientTest
  */
class SoundCloudClientTest extends TestCase
{
    /**
     * @throws SoundCloudAPIException
     */
    public function testAPIRequestException(): void
    {
        $callback = function () {
            throw new TransportException('Whoops', 500);
        };

        $httpClient = new MockHttpClient($callback);
        $apiClient = new SoundCloudClient($httpClient);

        $this->expectException(SoundCloudAPIException::class);
        $this->expectExceptionMessage('API Request: test, Whoops');
        $this->expectExceptionCode(500);

        $apiClient->apiRequest('GET', 'test');
    }
}

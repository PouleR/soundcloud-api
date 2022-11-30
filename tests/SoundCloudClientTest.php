<?php

namespace PouleR\SoundCloudAPI\Tests;

use PHPUnit\Framework\TestCase;
use PouleR\SoundCloudAPI\SoundCloudAPIException;
use PouleR\SoundCloudAPI\SoundCloudClient;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class SoundCloudClientTest
  */
class SoundCloudClientTest extends TestCase
{
    /**
     *
     */
    public function testClientId(): void
    {
        $apiClient = new SoundCloudClient($this->createMock(HttpClientInterface::class));

        $apiClient->setClientId('id');
        self::assertEquals('id', $apiClient->getClientId());
    }

    /**
     *
     */
    public function testAccessToken(): void
    {
        $apiClient = new SoundCloudClient($this->createMock(HttpClientInterface::class));

        $apiClient->setAccessToken('token');
        self::assertEquals('token', $apiClient->getAccessToken());
    }

    /**
     *
     */
    public function testResponseType(): void
    {
        $apiClient = new SoundCloudClient($this->createMock(HttpClientInterface::class));
        self::assertEquals(SoundCloudClient::RETURN_AS_OBJECT, $apiClient->getResponseType());

        $apiClient->setResponseType(SoundCloudClient::RETURN_AS_ASSOC);
        self::assertEquals(SoundCloudClient::RETURN_AS_ASSOC, $apiClient->getResponseType());
    }

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

    /**
     * @throws SoundCloudAPIException
     */
    public function testAPIRequestClientId(): void
    {
        $callback = function ($method, $url, $options) {
            self::assertEquals('GET', $method);
            self::assertEquals('https://api.soundcloud.com/tracks?client_id=client.id', $url);
            self::assertContains('accept: application/json; charset=utf-8', $options['headers']);

            return new MockResponse('{}', ['http_code' => 201]);
        };

        $httpClient = new MockHttpClient($callback);
        $apiClient = new SoundCloudClient($httpClient);
        $apiClient->setClientId('client.id');

        self::assertIsObject($apiClient->apiRequest('GET', 'tracks'));
        self::assertEquals(201, $apiClient->getLastHttpStatusCode());
    }


    /**
     * @throws SoundCloudAPIException
     */
    public function testAPIRequestAccessToken(): void
    {
        $callback = function ($method, $url, $options) {
            self::assertEquals('GET', $method);
            self::assertEquals('https://api.soundcloud.com/tracks', $url);
            self::assertContains('Authorization: OAuth access.token', $options['headers']);

            return new MockResponse('{}', ['http_code' => 201]);
        };

        $httpClient = new MockHttpClient($callback);
        $apiClient = new SoundCloudClient($httpClient);
        $apiClient->setAccessToken('access.token');
        $apiClient->setResponseType(SoundCloudClient::RETURN_AS_ASSOC);

        self::assertIsArray($apiClient->apiRequest('GET', 'tracks'));
        self::assertEquals(201, $apiClient->getLastHttpStatusCode());
    }
}

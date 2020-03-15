<?php

namespace PouleR\SoundCloudAPI\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use PouleR\SoundCloudAPI\SoundCloudAPI;
use PouleR\SoundCloudAPI\SoundCloudAPIException;
use PouleR\SoundCloudAPI\SoundCloudClient;

/**
 * Class SoundCloudAPITest
 */
class SoundCloudAPITest extends TestCase
{
    /**
     * @var SoundCloudClient|MockObject
     */
    private $client;

    /**
     * @var SoundCloudAPI
     */
    private $api;

    /**
     *
     */
    public function setUp(): void
    {
        $this->client = $this->createMock(SoundCloudClient::class);
        $this->api = new SoundCloudAPI($this->client);
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testUser(): void
    {
        $this->client->expects(static::exactly(2))
            ->method('apiRequest')
            ->withConsecutive(
                ['GET', 'me'],
                ['GET', 'users/1234']
            )->willReturn('{"OK"}');

        self::assertEquals('{"OK"}', $this->api->getUser());
        self::assertEquals('{"OK"}', $this->api->getUser(1234));
    }
}

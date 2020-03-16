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
     *
     */
    public function testAccessToken(): void
    {
        $this->client->expects(static::once())
            ->method('setAccessToken')
            ->with('token');

        $this->api->setAccessToken('token');
    }

    /**
     *
     */
    public function testClientId(): void
    {
        $this->client->expects(static::once())
            ->method('setClientId')
            ->with('id');

        $this->api->setClientId('id');
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetUser(): void
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

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'tracks/123')
            ->willReturn('{"OK"}');

        self::assertEquals('{"OK"}', $this->api->getTrack(123));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetTracks(): void
    {
        $this->client->expects(static::exactly(2))
            ->method('apiRequest')
            ->withConsecutive(
                ['GET', 'me/tracks'],
                ['GET', 'users/4321/tracks']
            )->willReturn('{"OK"}');

        self::assertEquals('{"OK"}', $this->api->getTracks());
        self::assertEquals('{"OK"}', $this->api->getTracks(4321));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testRepostTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'e1/me/track_reposts/1337')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->repostTrack(1337));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testLikeTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'e1/me/track_likes/555')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->likeTrack(555));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testCommentOnTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with(
                'POST',
                'tracks/123/comments',
                [],
                ['comment[body]' => 'Test', 'comment[timestamp]' => 0]
            )->willReturn('{}');

        self::assertEquals('{}', $this->api->commentOnTrack(123, 'Test'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testFollowUser(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'me/followings/222')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->followUser(222));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testUnFollowUser(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('DELETE', 'me/followings/333')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->unFollowUser(333));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testFollowings(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'me/followings')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->getFollowings());
    }


    /**
     * @throws SoundCloudAPIException
     */
    public function testResolveUrl(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'resolve?url=https://soundcloud.com/test')
            ->willReturn('{}');

        self::assertEquals('{}', $this->api->resolveUrl('https://soundcloud.com/test'));
    }
}

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
        $expected1 = ['GET', 'me'];
        $expected2 = ['GET', 'users/1234'];

        $matcher = static::exactly(2);
        $this->client->expects($matcher)
            ->method('apiRequest')
            ->willReturnCallback(function (string $key, string $value) use ($matcher, $expected1, $expected2) {
                match ($matcher->numberOfInvocations()) {
                    1 =>  $this->assertEquals($expected1, $value),
                    2 =>  $this->assertEquals($expected2, $value),
                };
            })
            ->willReturn(['OK']);

        self::assertSame(['OK'], $this->api->getUser());
        self::assertSame(['OK'], $this->api->getUser(1234));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'tracks/123')
            ->willReturn(['OK']);

        self::assertSame(['OK'], $this->api->getTrack(123));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testDeleteTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('DELETE', 'tracks/789')
            ->willReturn(['OK']);

        self::assertEquals(['OK'], $this->api->deleteTrack(789));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetStreamUrlsForTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'tracks/555/streams')
            ->willReturn(['OK']);

        self::assertEquals(['OK'], $this->api->getStreamUrlsForTrack(555));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testGetTrackSecretToken(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'tracks/456?secret_token=unittest')
            ->willReturn(['OK']);

        self::assertEquals(['OK'], $this->api->getTrack(456, 'unittest'));
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
            )->willReturn(['OK']);

        self::assertEquals(['OK'], $this->api->getTracks());
        self::assertEquals(['OK'], $this->api->getTracks(4321));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testRepostTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'e1/me/track_reposts/1337')
            ->willReturn([]);

        self::assertEquals([], $this->api->repostTrack(1337));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testLikeTrack(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'e1/me/track_likes/555')
            ->willReturn([]);

        self::assertEquals([], $this->api->likeTrack(555));
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
            )->willReturn([]);

        self::assertEquals([], $this->api->commentOnTrack(123, 'Test'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testFollowUser(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('PUT', 'me/followings/222')
            ->willReturn([]);

        self::assertEquals([], $this->api->followUser(222));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testUnFollowUser(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('DELETE', 'me/followings/333')
            ->willReturn([]);

        self::assertEquals([], $this->api->unFollowUser(333));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testFollowings(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'me/followings')
            ->willReturn([]);

        self::assertEquals([], $this->api->getFollowings());
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testResolveUrl(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'resolve?url=https://soundcloud.com/test')
            ->willReturn([]);

        self::assertEquals([], $this->api->resolveUrl('https://soundcloud.com/test'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testSearchTracks(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'tracks?q=searchquery')
            ->willReturn([]);

        self::assertIsArray($this->api->searchTracks('searchquery'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testSearchPlaylists(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'playlists?q=searchquery')
            ->willReturn([]);

        self::assertIsArray($this->api->searchPlaylists('searchquery'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testSearchUsers(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with('GET', 'users?q=searchquery')
            ->willReturn([]);

        self::assertIsArray($this->api->searchUsers('searchquery'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testAuthenticate(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with(
                'POST',
                'oauth2/token',
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                'client_id=&client_secret=secret&grant_type=client_credentials'
            )->willReturn([]);

        self::assertEquals([], $this->api->authenticate('secret'));
    }

    /**
     * @throws SoundCloudAPIException
     */
    public function testRefreshToken(): void
    {
        $this->client->expects(static::once())
            ->method('apiRequest')
            ->with(
                'POST',
                'oauth2/token',
                ['Content-Type' => 'application/x-www-form-urlencoded'],
                'client_id=&client_secret=secret&grant_type=refresh_token&refresh_token=refresh'
            )->willReturn([]);

        self::assertEquals([], $this->api->refreshToken('secret', 'refresh'));
    }
}

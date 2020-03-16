<?php

namespace PouleR\SoundCloudAPI;

/**
 * Class SoundCloudAPI
 */
class SoundCloudAPI
{
    /**
     * @var SoundCloudClient
     */
    private $client;

    /**
     * SoundCloudAPI constructor.
     *
     * @param SoundCloudClient $client
     */
    public function __construct(SoundCloudClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->client->setAccessToken($accessToken);
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->client->setClientId($clientId);
    }

    /**
     * Get a user
     * https://developers.soundcloud.com/docs/api/reference#users
     *
     * @param int|null $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getUser(?int $userId = null)
    {
        $url = 'me';

        if (null !== $userId) {
            $url = sprintf('users/%d', $userId);
        }

        return $this->client->apiRequest('GET', $url);
    }

    /**
     * Get a track
     * https://developers.soundcloud.com/docs/api/reference#tracks
     *
     * @param int $trackId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getTrack(int $trackId)
    {
        $url = sprintf('tracks/%d', $trackId);

        return $this->client->apiRequest('GET', $url);
    }

    /**
     * List of tracks of the user
     * https://developers.soundcloud.com/docs/api/reference#users
     *
     * @param int|null $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getTracks(?int $userId = null)
    {
        $url = sprintf('me/tracks');

        if (null !== $userId) {
            $url = sprintf('users/%d/tracks', $userId);
        }

        return $this->client->apiRequest('GET', $url);
    }

    /**
     * @param int $trackId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function repostTrack(int $trackId)
    {
        $url = sprintf('e1/me/track_reposts/%d', $trackId);

        return $this->client->apiRequest('PUT', $url);
    }

    /**
     * @param int $trackId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function likeTrack(int $trackId)
    {
        $url = sprintf('e1/me/track_likes/%d', $trackId);

        return $this->client->apiRequest('PUT', $url);
    }

    /**
     * @param int    $trackId
     * @param string $comment
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function commentOnTrack(int $trackId, string $comment)
    {
        $url = sprintf('tracks/%d/comments', $trackId);
        $data = [
            'comment[body]' => $comment,
            'comment[timestamp]' => 0
        ];

        return $this->client->apiRequest('POST', $url, [], $data);
    }

    /**
     * Follow a user
     * https://developers.soundcloud.com/docs/api/reference#me
     *
     * @param int $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function followUser(int $userId)
    {
        $url = sprintf('me/followings/%d', $userId);

        return $this->client->apiRequest('PUT', $url);
    }

    /**
     * Unfollow a user
     * https://developers.soundcloud.com/docs/api/reference#me
     *
     * @param int $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function unFollowUser(int $userId)
    {
        $url = sprintf('me/followings/%d', $userId);

        return $this->client->apiRequest('DELETE', $url);
    }

    /**
     * List of users who are followed by the user
     * https://developers.soundcloud.com/docs/api/reference#me
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getFollowings()
    {
        return $this->client->apiRequest('GET', 'me/followings');
    }

    /**
     * The resolve resource allows you to lookup and access API resources when you only know the SoundCloud.com URL.
     * https://developers.soundcloud.com/docs/api/reference#resolve
     *
     * @param string $url
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function resolveUrl(string $url)
    {
        $url = sprintf('resolve?url=%s', $url);

        return $this->client->apiRequest('GET', $url);
    }
}

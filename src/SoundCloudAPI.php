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
}

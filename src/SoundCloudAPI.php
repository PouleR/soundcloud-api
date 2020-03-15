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
     * @param SoundCloudClient $client
     */
    public function __construct(SoundCloudClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getUser(int $userId)
    {
        $url = sprintf('users/%d', $userId);

        return $this->client->apiRequest('GET', $url);
    }

    /**
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getCurrentUser()
    {
        return $this->client->apiRequest('GET', 'me');
    }

    /**
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
     * @param int $userId
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getTracksForUser(int $userId)
    {
        $url = sprintf('users/%d/tracks', $userId);

        return $this->client->apiRequest('GET', $url);
    }

    /**
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function getTracksForCurrentUser()
    {
        return $this->client->apiRequest('GET', 'me/tracks');
    }

    /**
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
}

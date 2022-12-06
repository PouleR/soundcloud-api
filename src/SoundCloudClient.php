<?php

namespace PouleR\SoundCloudAPI;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class SoundCloudClient
 */
class SoundCloudClient
{
    /**
     * SoundCloud URL
     */
    public const API_URL = 'https://api.soundcloud.com';

    /**
     * Return types for json_decode
     */
    public const RETURN_AS_OBJECT = 0;
    public const RETURN_AS_ASSOC = 1;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    /**
     * @var string
     */
    private string $accessToken = '';

    /**
     * @var string
     */
    private string $clientId = '';

    /**
     * @var int
     */
    protected int $lastHttpStatusCode = 0;

    /**
     * @var int
     */
    protected int $responseType = self::RETURN_AS_OBJECT;

    /**
     * SoundCloudClient constructor.
     *
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return SoundCloudClient
     */
    public function setAccessToken(string $accessToken): SoundCloudClient
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return SoundCloudClient
     */
    public function setClientId(string $clientId): SoundCloudClient
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastHttpStatusCode(): int
    {
        return $this->lastHttpStatusCode;
    }

    /**
     * @return int
     */
    public function getResponseType(): int
    {
        return $this->responseType;
    }

    /**
     * @param int $responseType
     *
     * @return SoundCloudClient
     */
    public function setResponseType(int $responseType): SoundCloudClient
    {
        $this->responseType = $responseType;

        return $this;
    }

    /**
     * @param string $method
     * @param string $service
     * @param array  $headers
     * @param mixed  $body
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function apiRequest(string $method, string $service, array $headers = [], mixed $body = null): object|array
    {
        $url = sprintf(
            '%s/%s',
            self::API_URL,
            $service
        );

        if (empty($this->accessToken) && !empty($this->clientId)) {
            $url = sprintf(
                '%s%sclient_id=%s',
                $url,
                parse_url($url, PHP_URL_QUERY) ? '&' : '?',
                $this->clientId
            );
        }

        $defaultHeaders = $this->getDefaultHeaders();
        $headers = array_merge($headers, $defaultHeaders);

        try {
            $response = $this->httpClient->request($method, $url, ['headers' => $headers, 'body' => $body, 'timeout' => 10]);
            $this->lastHttpStatusCode = $response->getStatusCode();

            return json_decode($response->getContent(), $this->responseType === self::RETURN_AS_ASSOC);
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | TransportExceptionInterface $exception) {
            throw new SoundCloudAPIException(
                sprintf(
                    'API Request: %s, %s (%s)',
                    $service,
                    $exception->getMessage(),
                    $exception->getCode()
                ),
                $exception->getCode()
            );
        }
    }

    /**
     * @param string $method
     * @param string $service
     * @param array  $headers
     * @param mixed  $body
     *
     * @return array|object
     *
     * @throws SoundCloudAPIException
     */
    public function urlRequest(string $method, string $service, array $headers = [], mixed $body = null): ?string
    {
        $url = sprintf(
            '%s/%s',
            self::API_URL,
            $service
        );

        if (empty($this->accessToken)) {
            throw new SoundCloudAPIException(
                sprintf(
                    'URL Request: %s, %s',
                    $service,
                    'No access token was set'
                ),
                500
            );
        }

        $defaultHeaders = $this->getDefaultHeaders();
        $headers = array_merge($headers, $defaultHeaders);

        try {
            $response = $this->httpClient->request($method, $url, ['headers' => $headers, 'body' => $body]);
            $this->lastHttpStatusCode = $response->getStatusCode();

            return $response->getInfo('url');
        } catch (ServerExceptionInterface | ClientExceptionInterface | RedirectionExceptionInterface | TransportExceptionInterface $exception) {
            throw new SoundCloudAPIException(
                sprintf(
                    'URL Request: %s, %s (%s)',
                    $service,
                    $exception->getMessage(),
                    $exception->getCode()
                ),
                $exception->getCode()
            );
        }
    }

    /**
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        $headers = ['accept' => 'application/json; charset=utf-8'];

        if (!empty($this->accessToken)) {
            $headers['Authorization'] = sprintf('OAuth %s', $this->accessToken);
        }

        return $headers;
    }
}

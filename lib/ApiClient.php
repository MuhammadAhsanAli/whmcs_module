<?php

class ApiClient
{
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * Send a GET request.
     *
     * @param string $endpoint
     * @return array
     */
    public function get(string $endpoint): array
    {
        return $this->request('GET', $endpoint);
    }

    /**
     * Send a POST request.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data): array
    {
        return $this->request('POST', $endpoint, ['form_params' => ['data' => $data]]);
    }

    /**
     * Send a PUT request.
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function put(string $endpoint, array $data): array
    {
        return $this->request('PUT', $endpoint, ['json' => $data]);
    }

    /**
     * Send a DELETE request.
     *
     * @param string $endpoint
     * @return array
     */
    public function delete(string $endpoint): array
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Make an HTTP request.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array|string
     */
    protected function request(string $method, string $endpoint, array $options = []): array|string
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request($method,  $this->buildUrl($endpoint), $options);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build the full URL for the request.
     *
     * @param string $endpoint
     * @return string
     */
    protected function buildUrl(string $endpoint): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }
}

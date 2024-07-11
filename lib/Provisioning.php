<?php

class Provisioning
{
    /**
     * ApiClient instance for making API requests.
     *
     * @var ApiClient
     *
     */
    private ApiClient $apiClient;

    /**
     * Provisioning constructor.
     */
    public function __construct()
    {
        $this->apiClient = new ApiClient(API_BASE_URL);
    }

    /**
     * Create a new service.
     *
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
       return $this->apiClient->post('create', $params);
    }

    /**
     * Suspend a service.
     *
     * @param array $params
     * @return array
     */
    public function suspend(array $params): array
    {
       return $this->apiClient->put('suspend/'.$params['serviceid'], ['status' => 'suspended']);
    }

    /**
     * Terminate a service.
     *
     * @param array $params
     * @return array
     */
    public function terminate(array $params): array
    {
       return $this->apiClient->delete('terminate/'.$params['serviceid']);
    }

    /**
     * Return the dummy list of services.
     *
     * @return array
     */
    public function listServices(): array
    {
        //Dummy data to show the raws in table
        return [
            [
                'id' => 1,
                'storage' => 100,
                'bandwidth' => 500,
            ],
            [
                'id' => 2,
                'storage' => 250,
                'bandwidth' => 1000,

            ],
            [
                'id' => 3,
                'storage' => 500,
                'bandwidth' => 2000,

            ],
            [
                'id' => 4,
                'storage' => 50,
                'bandwidth' => 500,

            ],
            [
                'id' => 5,
                'storage' => 200,
                'bandwidth' => 1000,
            ],
        ];
    }

    /**
     * Update client information.
     *
     * @param array $data
     * @return array
     */
    public function updateClient(array $data): array
    {
        return $this->apiClient->post('/clients/update', $data);
    }
}

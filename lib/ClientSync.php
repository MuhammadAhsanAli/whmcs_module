<?php

class ClientSync
{
    /**
     * Logger instance for logging message.
     *
     * @var Logger
     *
     */
    private Logger $logger;

    /**
     * Provisioning instance for accessing crm method.
     *
     * @var Provisioning
     *
     */
    private Provisioning $provisioning;

    /**
     * ClientSync constructor.
     * Initializes the Logger and Provisioning instances.
     */
    public function __construct()
    {
        $this->logger = new Logger();
        $this->provisioning = new Provisioning();
    }

    /**
     * Synchronizes clients from WHMCS to the external system (CRM).
     *
     * @note This method fetches client data from WHMCS and pushes it to CRM.
     *
     * @return string Message indicating the result of the synchronization.
     */
    public function syncClients(): string
    {
        try {
            $clients = $this->getClients();
            foreach ($clients as $client) {
                $this->syncClient($client);
            }
            return "Client synchronization completed successfully.";
        } catch (\Exception $e) {
            $this->logger->logActivity("Client data synchronization failed: {$e->getMessage()}");
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Retrieves clients from WHMCS.
     *
     * @return array Array of clients.
     * @throws \Exception If retrieval of clients fails.
     */
    private function getClients(): array
    {
        $result = localAPI('GetClients', []);
        if ($result['result'] !== 'success') {
            throw new \Exception('Failed to retrieve clients from WHMCS: ' . $result['message']);
        }
        return $result['clients']['client'];
    }

    /**
     * Synchronizes a single client's data with the external system.
     *
     * @param array $client Client data to synchronize.
     * @return void
     */
    private function syncClient(array $client): void
    {
        $clientId = $client['id'];
        $data = $this->transformClientData($client);
        $response = $this->provisioning->updateClient($data);
        if ($response['success']) {
            $this->logger->logActivity("Client data synced successfully for client ID {$clientId}");
        } else {
            $this->logger->logActivity("Failed to sync client data for client ID {$clientId}: {$response['error']}");
        }
    }

    /**
     * Transforms client data to the required format for the external system.
     *
     * @param array $client Client data from WHMCS.
     * @return array Transformed client data.
     */
    private function transformClientData(array $client): array
    {
        return [
            'client_id' => $client['id'],
            'client_name' => $client['firstname'] . ' ' . $client['lastname'],
            'client_company_name' => $client['companyname'],
            'client_email' => $client['email'],
            'client_created_date' => $client['datecreated'],
            'client_group_id' => $client['groupid'],
            'client_status' => $client['status'],
        ];
    }
}

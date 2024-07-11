<?php

/**
 * Security check to prevent direct access to the file if WHMCS constant is not defined.
 *
 * @return void
 */
if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

/**
 * Define the base URL for the API endpoint used by the module.
 *
 * @var string API_BASE_URL
 */
define('API_BASE_URL', 'http://localhost/test_api/test.php');

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/api.php';
require_once __DIR__ . '/lib/Logger.php';
require_once __DIR__ . '/lib/ApiClient.php';
require_once __DIR__ . '/lib/Provisioning.php';
require_once __DIR__ . '/lib/Validator.php';

/**
 * Provides metadata about the custom module.
 *
 * @return array Metadata information including DisplayName and APIVersion.
 */
function customModule_MetaData(): array
{
    return [
        'DisplayName' => 'Custom Module',
        'APIVersion' => '1.1',
    ];
}

/**
 * Defines configuration options for the custom module.
 *
 * @return array Array of configuration options including 'Storage Space' and 'Bandwidth'.
 */
function customModule_ConfigOptions(): array
{
    return [
        'Storage Space' => [
            'Type' => 'text',
            'Size' => '25',
            'Default' => '10GB',
            'Description' => 'Enter storage space in GB|TB',
        ],
        'Bandwidth' => [
            'Type' => 'text',
            'Size' => '25',
            'Default' => '100GB',
            'Description' => 'Enter bandwidth in GB|TB',
        ],
    ];
}

/**
 * Creates a new service account based on provided parameters.
 *
 * @param array $params Array of parameters including 'configoption1' (Storage Space) and 'configoption2' (Bandwidth).
 * @return string Success message or error message if validation fails.
 */
function customModule_CreateAccount(array $params): string
{
    return handleServiceAction($params, 'create');
}

/**
 * Suspends a service account based on the given parameters.
 *
 * @param array $params Array of parameters including 'serviceid' identifying the service to suspend.
 * @return string Success message or error message if suspension fails.
 */
function customModule_SuspendAccount(array $params): string
{
    return handleServiceAction($params, 'suspend');
}

/**
 * Terminates a service account based on the given parameters.
 *
 * @param array $params Array of parameters including 'serviceid' identifying the service to terminate.
 * @return string Success message or error message if termination fails.
 */
function customModule_TerminateAccount(array $params): string
{
    return handleServiceAction($params, 'terminate');
}

/**
 * Handles service actions such as create, suspend, or terminate based on the provided parameters.
 *
 * @param array $params Array of parameters including 'serviceid', 'configoption1' (Storage Space), and 'configoption2' (Bandwidth).
 * @param string $action Action to perform ('create', 'suspend', or 'terminate').
 * @return string Success message or error message if an exception occurs or validation fails.
 */
function handleServiceAction(array $params, $action): string
{
    $provisioning = new Provisioning();
    $response = [];
    try {
        $service_id = (int) $params['serviceid'] ?? 0;
        switch ($action) {
            case 'create':
                $storage = $params['configoption1'] ?? null;
                $bandwidth = $params['configoption2'] ?? null;

                if (!Validator::validateStorage($storage)) {
                    return "Invalid storage format.";
                }

                if (!Validator::validateBandwidth($bandwidth)) {
                    return "Invalid bandwidth format.";
                }

                $response = $provisioning->create([
                    'storage' => $storage,
                    'bandwidth' => $bandwidth,
                ]);
                break;

            case 'suspend':
                $response = $provisioning->suspend(['serviceid' => $service_id]);
                break;

            case 'terminate':
                $response = $provisioning->terminate(['serviceid' => $service_id]);
                break;

            default:
                return "Unsupported action requested.";
        }
    } catch (Exception $e) {
        Logger::logActivity($params, $e->getMessage(), $e->getTraceAsString());
        return $e->getMessage();
    }

    if ($response['status'] == 'error') {
        return $response['message'];
    }

    return 'success';
}

/**
 * Handles client area requests and displays relevant information or error messages.
 *
 * @param array $params Array of parameters including 'serviceid' and client-related data.
 * @return array Array with client area template and variables including 'services' if listing is required.
 */
function customModule_ClientArea($params): array
{
    $action = isset($_REQUEST['custommodule_action']) ? $_REQUEST['custommodule_action'] : '';

    if ($action == 'create' || $action == 'suspend' || $action == 'terminate') {
        $result = handleServiceAction($params, $action);

        if ($result !== 'success') {
            return array(
                'templatefile' => 'error.tpl',
                'vars' => array(
                    'usefulErrorHelper' => $result,
                ),
            );
        }
    }

    $provisioning = new Provisioning();
    $services = $provisioning->listServices();

    return [
        'templatefile' => 'listRecords',
        'vars' => [
            'services' => $services,
        ],
    ];
}
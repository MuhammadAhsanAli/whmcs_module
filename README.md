# WHMCS Custom Provisioning and CRM Integration

This project focuses on developing a robust solution leveraging WHMCS for custom provisioning and seamless CRM integration. The custom provisioning module enables the creation, suspension, and termination of services through a configurable interface, ensuring flexibility in managing storage space and bandwidth service options. Additionally, the integration script synchronizes client data between WHMCS and an external CRM system, facilitating data retrieval, transformation, and synchronization via mock API endpoints. This project exemplifies system integration and service management within the WHMCS ecosystem.

## Getting Started

This guide offers essential insights for effectively using this WHMCS module, emphasizing configuration of module settings and client data synchronization with CRM systems

Usage
-----

### Manage Provisiosning Service:

1. **Viewing Provisioning List:**
   - Display details such as ID, Storage (GB), Bandwidth (GB), with actions available to Suspend or Terminate the service.

2. **Create Provisioning Record:**
   - Fill in details for Storage Space (GB/TB) and Bandwidth (GB/TB).

3. **Suspend Service:**
   - Click the "Suspend" button in the list view to mark the account as suspended.

4. **Terminate Service:**
   - Click the "Terminate" button in the list view to terminate the account.

### WHMCS Client Data Synchronization:

1. **Fetch Clients:**
   - Call the `syncClients` method of the `ClientSync` class to send a GET request to WHMCS using the GetClients action.

2. **Transform API Data:**
   - Transform the retrieved data and map it to align with the required CRM format.

3. **Update Clients in CRM:**
   - Push data via a POST request to CRM to update client data.


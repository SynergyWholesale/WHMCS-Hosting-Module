<?php

/**
 * Synergy Wholesale Hosting Module
 *
 * @copyright Copyright (c) Synergy Wholesale Pty Ltd 2020
 * @license https://github.com/synergywholesale/whmcs-hosting-module/LICENSE
 */

use WHMCS\Database\Capsule as DB;

define('API_ENDPOINT', 'https://{{API}}');
define('MODULE_VERSION', '{{VERSION}}');

function synergywholesale_hosting_renameModule($names_map)
{
    foreach ($names_map as $old => $new) {
        foreach (DB::table('tblproducts')->where('servertype', $old)->select('id')->get() as $pr) {
            DB::table('tblproducts')
                ->where('id', $pr->id)
                ->update(['servertype' => $new]);
        }
    }
}

function synergywholesale_hosting_MetaData()
{
    return [
        'DisplayName' => 'Synergy Wholesale Hosting'
    ];
}

function synergywholesale_hosting_ConfigOptions()
{
    $relid = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];

    synergywholesale_hosting_renameModule(
        ['synergyWholesaleHosting' => 'synergywholesale_hosting']
    );
    
    $check = DB::table('tblservers')
        ->where('name', 'Synergy Wholesale')
        ->count();

    if (!$check) {
        $server_id = DB::table('tblservers')->insertGetId([
            'name' => 'Synergy Wholesale',
            'ipaddress' => '',
            'assignedips' => '',
            'hostname' => '',
            'monthlycost' => '0.00',
            'noc' => '',
            'statusaddress' => '',
            'nameserver1' => '',
            'nameserver1ip' => '',
            'nameserver2' => '',
            'nameserver2ip' => '',
            'nameserver3' => '',
            'nameserver3ip' => '',
            'nameserver4' => '',
            'nameserver4ip' => '',
            'nameserver5' => '',
            'nameserver5ip' => '',
            'maxaccounts' => '0',
            'type' => 'synergywholesale_hosting',
            'username' => '',
            'password' => '',
            'accesshash' => '',
            'secure' => 'on',
            'port' => null,
            'active' => 1,
            'disabled' => 0
        ]);

        $accounts = DB::table('tblhosting')
            ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
            ->where('tblproducts.servertype', 'synergywholesale_hosting')
            ->select('tblhosting.id')
            ->get();

        foreach ($accounts as &$account) {
            $account = $account->id;
        }

        DB::table('tblhosting')
            ->whereIn('id', $accounts)
            ->update(['server' => $server_id]);
    }

    # Should return an array of the module options for each product - maximum of 24
    DB::table('tblproducts')
        ->where('id', $relid)
        ->update(["configoption5" => MODULE_VERSION]);

    $fields = [
        [
            'name' => 'Product',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Hosting Id',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Server Hostname',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Server IP Address',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Nameserver 1',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Nameserver 2',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Nameserver 3',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'Email',
            'type' => 'text',
            'admin' => '',
            'required' => 'on',
            'showorder' => 'on'
        ],
        [
            'name' => 'DKIM Public Key',
            'type' => 'text',
            'admin' => 'on',
            'required' => 'off',
            'showorder' => 'off'
        ],
        [
            'name' => 'First Name',
            'type' => 'text',
            'admin' => '',
            'required' => 'off',
            'showorder' => 'on'
        ],
        [
            'name' => 'Last Name',
            'type' => 'text',
            'admin' => '',
            'required' => 'off',
            'showorder' => 'on'
        ],
    ];

    foreach ($fields as $field) {
        DB::table('tblcustomfields')
            ->updateOrInsert(
                [
                    'relid' => $relid,
                    'fieldname' => $field['name']
                ],
                [
                    'type' => 'product',
                    'fieldtype' => $field['type'],
                    'fieldoptions' => '',
                    'adminonly' => $field['admin'],
                    'required' => $field['required'],
                    'showorder' => $field['showorder'],
                    'sortorder' => '0'
                ]
            );
    }

    return [
        'API Key' => [
            'Type' => 'text',
            'Size' => '60'
        ],
        'Reseller ID' => [
            'Type' => 'text',
            'Size' => '60'
        ],
        'Hosting Plan' => [
            'FriendlyName' => 'Hosting Plan',
            'Type' => 'text',
            'Size' => '60'
        ],
        'Hosting Location' => [
            'FriendlyName' => 'Hosting Location',
            'Type' => 'dropdown',
            'Options' => 'NextDC S1 - Sydney Australia',
            'Size' => '60'
        ],
        'Module Version' => [
            'FriendlyName' => 'Module Version',
            'Description' => MODULE_VERSION
        ],
        'IP Address' => [
            'FriendlyName' => 'IP Address',
            'Description' => $_SERVER['SERVER_ADDR'] . ' - You\'ll need to provide IP address to access our API'
        ]
    ];
}

function synergywholesale_hosting_AdminCustomButtonArray()
{
    return [
        'Recreate Service' => 'recreate',
        'Synchronize Data' => 'synchronize'
    ];
}

function customValues($params)
{
    $result = DB::table('tblcustomfields')
        ->select('id', 'fieldname')
        ->where('relid', $params['pid'])
        ->get();
    $ids = [];
    foreach ($result as $data) {
        $ids[$data->fieldname] = $data->id;
    }
    unset($result);
    $result = DB::table('tblcustomfieldsvalues')
        ->where('relid', $params['serviceid'])
        ->select('fieldid', 'relid', 'value')
        ->get();
    $values = [];
    foreach ($result as $data) {
        $values[$data->fieldid] = $data->value;
    }
    return [
        'hoid' => $values[$ids['Hosting Id']],
        'server_ip' => $values[$ids['Server IP Address']],
        'server_hostname' => $values[$ids['Server Hostname']],
        'email' => $values[$ids['Email']],
        'product' => $values[$ids['Product']],
        'dkim' => $values[$ids['DKIM Public Key']],
        'firstName' => $values[$ids['First Name']],
        'lastName' => $values[$ids['Last Name']],
        'ids' => $ids,
        'nameservers' => [
            $values[$ids['Nameserver 1']],
            $values[$ids['Nameserver 2']],
            $values[$ids['Nameserver 3']]
        ]
    ];
}

function synergywholesale_hosting_synchronize($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];

    $updateCustomField = function ($service_id, $field_id, $value) {

        $serviceCustomField = DB::table('tblcustomfieldsvalues')
            ->where('fieldid', $field_id)
            ->where('relid', $service_id)
            ->first();
        if ($serviceCustomField) {
            return DB::table('tblcustomfieldsvalues')
                ->where('fieldid', $field_id)
                ->where('relid', $service_id)
                ->update(['value' => $value]);
        } else {
            return DB::table('tblcustomfieldsvalues')->insert([
                'fieldid' => $field_id,
                'relid' => $service_id,
                'value' => $value
            ]);
        }
    };

    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];

    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingGetService', $data);
    if ($apiResult->errorMessage == 'Hosting Get Service Completed Successfully') {
        $updateCustomField($params['serviceid'], $customValues['ids']['Server Hostname'], $apiResult->server);
        $updateCustomField($params['serviceid'], $customValues['ids']['Server IP Address'], $apiResult->serverIPAddress);
        $updateCustomField($params['serviceid'], $customValues['ids']['Product'], $apiResult->product);
        $updateCustomField($params['serviceid'], $customValues['ids']['DKIM Public Key'], (isset($apiResult->dkim) ? $apiResult->dkim : ''));
        $updateCustomField($params['serviceid'], $customValues['ids']['First Name'], (isset($apiResult->firstName) ? $apiResult->firstName : ''));
        $updateCustomField($params['serviceid'], $customValues['ids']['Last Name'], (isset($apiResult->lastName) ? $apiResult->lastName : ''));

        $updateData = [
            'username' => $apiResult->username,
            'domain' => $apiResult->domain,
            'dedicatedip' => $apiResult->dedicatedIPv4,
            'password' => encrypt($apiResult->password),
            'diskusage'   => $apiResult->disk_usage,
            'disklimit'  => $apiResult->disk_limit,
            'bwusage'    => $apiResult->bw_usage,
            'bwlimit'   => $apiResult->bw_limit,
            'lastupdate' => DB::raw('now()')
        ];

        foreach ($apiResult->nameServers as $index => $nameserver) {
            if ($index >= 3) {
                break;
            }
            
            $field_id = null;
            $fieldName = sprintf('Nameserver %d', $index + 1);
            if (array_key_exists($fieldName, $customValues['ids'])) {
                $field_id = $customValues['ids'][$fieldName];
            }

            if (is_null($field_id)) {
                synergywholesale_hosting_ConfigOptions($params['pid']);
                $customValues = customValues($service_id);
                if (!array_key_exists($fieldName, $customValues['ids'])) {
                    continue;
                }
                $field_id = $customValues['ids'][$fieldName];
            }

            $updateCustomField($params['serviceid'], $field_id, $nameserver);
        }

        DB::table('tblhosting')
                ->where('id', $params['serviceid'])
                ->update($updateData);
        return 'success';
    } else {
        return $apiResult->errorMessage . '. Error code: ' . $apiResult->status;
    }
}

function synergywholesale_hosting_CreateAccount($params)
{
    $domain = $params['domain'];
    $username = $params['username'];
    $password = $params['password'];
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $plan = $params['configoption3'];
    $location = $params['configoption4'];
    $customValues = customValues($params);
    $updateCustomField = function ($service_id, $field_id, $value) {
        $serviceCustomField = DB::table('tblcustomfieldsvalues')
            ->where('fieldid', $field_id)
            ->where('relid', $service_id)
            ->first();
        if ($serviceCustomField) {
            return DB::table('tblcustomfieldsvalues')
                ->where('fieldid', $field_id)
                ->where('relid', $service_id)
                ->update(['value' => $value]);
        } else {
            return DB::table('tblcustomfieldsvalues')->insert([
                'fieldid' => $field_id,
                'relid' => $service_id,
                'value' => $value
            ]);
        }
    };
    if (strpos($location, 'Melbourne') !== false) {
        $customValues['locationName'] = 'MELBOURNE';
    } else {
        $customValues['locationName'] = 'SYDNEY';
    }
    $data = [
        'planName' => $plan,
        'locationName' => $customValues['locationName'],
        'domain' => $domain,
        'email' => $customValues['email'],
        'username' => $username,
        'password' => $password,
        'firstName' => $customValues['firstName'],
        'lastName' => $customValues['lastName'],
        'api_method' => 'WHMCS',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingPurchaseService', $data);
    if (in_array($apiResult->status, ['OK', 'OK_PENDING'], true)) {
        $apiSyncResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingGetService', [
            'hoid' => $apiResult->hoid,
            'reason' => 'WHMCS',
            'api_method' => '1',
            'whmcs_ver' => $params['whmcsVersion']
        ]);
        $fieldsToUpdate = [['field_id' => $customValues['ids']['Hosting Id'], 'value' => $apiResult->hoid]];
        if (isset($apiSyncResult->serverIPAddress)) {
            $fieldsToUpdate[] = [
                'field_id' => $customValues['ids']['Server IP Address'],
                'value' => $apiSyncResult->serverIPAddress
            ];
        }

        if (isset($apiSyncResult->server)) {
            $fieldsToUpdate[] = [
                'field_id' => $customValues['ids']['Server Hostname'],
                'value' => $apiSyncResult->server
            ];
        }

        foreach ($fieldsToUpdate as $customFields) {
            $updateCustomField($params['serviceid'], $customFields['field_id'], $customFields['value']);
        }
        if (isset($apiResult->username) && $apiResult->username != '') {
            DB::table('tblhosting')
                    ->where('id', $params['serviceid'])
                    ->update(['username' => $apiResult->username]);
        }

        return synergywholesale_hosting_synchronize($params);
    } else {
        return $apiResult->errorMessage . '. Error code: ' . $apiResult->status;
    }
}

function synergywholesale_hosting_recreate($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $data = [
        'newPassword' => 'AUTO',
        'hoid' => $hoid,
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingRecreateService', $data);
    if ($apiResult->status == 'OK') {
        DB::table('tblhosting')
                ->where('id', $params['serviceid'])
                ->update(['password' => encrypt($apiResult->password)]);
    }
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_TerminateAccount($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingTerminateService', $data);
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_SuspendAccount($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingSuspendService', $data);
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_UnsuspendAccount($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingUnsuspendService', $data);
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_ChangePassword($params)
{
    $password = $params['password'];
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'newPassword' => $password,
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingChangePassword', $data);
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_api($reseller_id, $apiKey, $action, array $data = [])
{
    try {
        $data = array_merge([
            'resellerID' => $reseller_id,
            'apiKey' => $apiKey
        ], $data);
        $client = new \SoapClient(
            null,
            [
                'location' => API_ENDPOINT,
                'uri' => '',
                'trace' => true,
                'exceptions' => true
            ]
        );
        $result = $client->$action($data);
        logModuleCall('Synergy Hosting', $action, $data, (array)$result);
        return $result;
    } catch (\Exception $e) {
        logModuleCall('Synergy Hosting', $action, $data, [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }

    return false;
}

function synergywholesale_hosting_ChangePackage($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $plan = $params['configoption3'];
    $location = $params['configoption4'];
    $customValues = customValues($params);
    if (strpos($location, 'Melbourne') !== false) {
        $customValues['locationName'] = 'MELBOURNE';
    } else {
        $customValues['locationName'] = 'SYDNEY';
    }
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'newPlanName' => $plan,
        'newLocationName' => $customValues['locationName'],
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingChangePackage', $data);
    return synergywholesale_hosting_status($apiResult);
}

function synergywholesale_hosting_status($apiResult)
{
    if (is_null($apiResult)) {
        return 'Fatal error';
    }
    if ($apiResult->status == 'OK') {
        return 'success';
    } else {
        return $apiResult->errorMessage . '. Error code: ' . $apiResult->status;
    }
}

function synergywholesale_hosting_ClientArea($params)
{
    $resellerId = $params['templatevars']['moduleParams']['configoption2'];
    $apiKey = $params['templatevars']['moduleParams']['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['templatevars']['moduleParams']['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingGetService', $data);
    if ($apiResult->dedicatedIPv4 == null) {
        $apiResult->dedicatedIPv4 = 'Dedicated IP has not been configured';
    }
    if (!isset($apiResult->plan)) {
        return sprintf('<div class="alert alert-danger">%s</div>', htmlentities($apiResult->errorMessage));
    }
    return [
        'templatefile' => 'clientarea',
        'vars' => [
            'product' => $apiResult->product,
            'domain' => $apiResult->domain,
            'plan' => $apiResult->plan,
            'status' => $apiResult->status,
            'server' => $apiResult->server,
            'dedicatedIP' => $apiResult->dedicatedIPv4,
            'serverIP' => $apiResult->serverIPAddress,
            'serverHostname' => $apiResult->server,
            'dkim' => (isset($apiResult->dkim) ? $apiResult->dkim : ''),
            'mxrecords' => (isset($apiResult->mxrecords) ? json_decode($apiResult->mxrecords) : '')
        ],
    ];
}

function synergywholesale_hosting_ServiceSingleSignOn(array $params)
{
    $url = synergywholesale_hosting_get_login($params);

    if (! $url) {
        return [
            'success' => false,
            'errorMsg' => 'Failed to login to service.',
        ];
    }

    return [
        'success' => true,
        'redirectTo' => $url,
    ];
}

function synergywholesale_hosting_client_login($params)
{
    $url = synergywholesale_hosting_get_login($params);
    if ($url) {
        header(sprintf('Location: %s', $url));
        exit;
    } else {
        return 'Please contact support.';
    }
}

function synergywholesale_hosting_get_login($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $hoid = $customValues['hoid'];
    $email = $customValues['email'];
    $data = [
        'identifier' => (!empty($hoid) ? $hoid : $email),
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['configoption5']
    ];
    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingGetService', $data);
    try {
        return synergywholesale_hosting_getLoginUrl($apiResult->username, $apiResult->password, $apiResult->server, $apiResult->product);
    } catch (\Exception $e) {
        logModuleCall('Synergy Hosting', 'login', $data, ['exception' => get_class($e), 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    }
    
    return false;
}

function synergywholesale_hosting_getLoginUrl($user, $pass, $hostname, $product = 'Custom Hosting', $service = 'cpanel', $goto = '/') 
{
    switch ($product) {
        case 'Custom Hosting':
            $servicePorts = [
                'cpanel' => 2083,
                'whm' => 2087,
                'webmail' => 2096
            ];
            $port = isset($servicePorts[$service]) ? $servicePorts[$service] : $servicePorts['cpanel'];
            $postFields = [
                'user' => $user,
                'pass' => $pass,
                'goto_uri' => $goto
            ];
            $url = 'https://' . $hostname . ':' . $port;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url . '/login');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection' => 'close']);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            $page = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }

            if (isset($error_msg)) {
                return $error_msg;
            }

            curl_close($ch);
            $session = $token = [];
            if (!preg_match('/session=([^\;]+)/', $page, $session)) {
                return false;
            }
            if (!preg_match('|<META HTTP-EQUIV="refresh"[^>]+URL=/(cpsess\d+)/|i', $page, $token)) {
                return false;
            }

            return sprintf(
                '%s/%s/login/?session=%s',
                $url,
                $token[1],
                $session[1],
                $goto == '/' ? '' : '&goto_uri=' . urlencode($goto)
            );
        case 'Email Hosting':
            $baseUrl = 'https://' . $hostname;
            $defaultQuery = http_build_query([
                'action' => 'login',
                'username' => $user,
                'password' => $pass,
                'custom' => 'ajaxdirect'
            ]);

            return "{$baseUrl}/?{$defaultQuery}";
    }
}

function synergywholesale_hosting_ClientAreaCustomButtonArray($params)
{
    $customValues = customValues($params);
    $product = $customValues['product'];

    switch ($product) {
        case 'Custom Hosting':
            return [
                'Login' => 'client_login',
                'Manage Temporary URL' => 'tempurl',
                'Check Firewall' => 'firewall',
            ];

        case 'Email Hosting':
            return [
                'Login' => 'client_login',
            ];
    }
}

function synergywholesale_hosting_tempurl($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $data = [
        'hoid' => $customValues['hoid'],
        'reason' => 'WHMCS',
        'api_method' => '1',
        'whmcs_ver' => $params['whmcsVersion'],
        'whmcs_mod_ver' => $params['templatevars']['moduleParams']['configoption5']
    ];
    $hostingDetails = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingGetService', $data);

    $tempUrl = $hostingDetails->tempUrl;
    $tempUrlAddress = 'http://' . $hostingDetails->server . '/~' . $hostingDetails->username;

    $now = new DateTime("now");
    $tempUrlDate = $hostingDetails->tempUrlDate;
    
    $tempUrlDate = date('Y-m-d', strtotime($tempUrlDate . ' + 28 days'));
    $tempUrlDate = new DateTime($tempUrlDate);
    $dayDiff = date_diff($now, $tempUrlDate);
    $daysRemain = $dayDiff->format('%a');

    if (isset($_POST['module_function']) && $_POST['module_function'] == 'toggle') {
        if ($tempUrl) {
            $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingDisableTempUrl', $data);
        } else {
            $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingEnableTempUrl', $data);
        }
        if ($apiResult->status == 'OK') {
            $tempUrl = !$tempUrl;
        }
    } elseif (isset($_POST['module_function']) && $_POST['module_function'] == 'reset') {
        $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingDisableTempUrl', $data);
        $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingEnableTempUrl', $data);

        $message = 'Your temporary URL time limit has been reset to 28 days.';
    }
   
    return [
        'templatefile' => 'tempurl',
        'vars' => [
            'server' => $hostingDetails->server,
            'tempUrl' => $tempUrl,
            'domainName' => $hostingDetails->domain,
            'tempUrlAddress' => $tempUrlAddress,
            'daysRemain' => $daysRemain,
            'message'   =>  $message
        ],
    ];
}

function synergywholesale_hosting_firewall($params)
{
    $resellerId = $params['configoption2'];
    $apiKey = $params['configoption1'];
    $customValues = customValues($params);
    $message = '';
    if (isset($_POST['module_function']) && in_array($_POST['module_function'], ['check', 'unblock_ip'])) {
        if ($_POST['ipopt'] == 'custom' && !empty($_POST['customip'])) {
            $ipAddress = $_POST['customip'];
        } elseif ($_POST['ipopt'] == 'myIp') {
            $ipAddress = $_POST['myIpAddress'];
        }
        $data = [
            'hoid' => $customValues['hoid'],
            'ipAddress' => $ipAddress,
            'reason' => 'WHMCS',
            'api_method' => '1',
            'whmcs_ver' => $params['whmcsVersion'],
            'whmcs_mod_ver' => $params['templatevars']['moduleParams']['configoption5']
        ];
        if (!empty($ipAddress)) {
            if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                $message = 'IP Address invalid! Please enter a valid IP Address.';
            } else {
                $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingCheckFirewall', $data);
                if (!$apiResult->blocked) {
                    $message = 'The IP Address ' . $ipAddress . ' is not blocked!';
                }
            }
        } else {
            $message = 'Please enter an IP Address!';
        }
        $blocked = $apiResult->blocked;
    }
    if (isset($_POST['module_function']) && $_POST['module_function'] == 'unblock_ip') {
        if ($apiResult->status == 'OK') {
            if ($blocked) {
                $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'hostingUnblockFirewall', $data);
                $message = 'The IP Address ' . $ipAddress . ' has been unblocked!';
                if ($apiResult->status == 'OK') {
                    $blocked = false;
                }
            }
        }
    }

    return [
        'templatefile' => 'firewall',
        'vars' => [
            'ipAddress' => $ipAddress,
            'blocked'   => $blocked,
            'message'   => $message
        ],
    ];
}

function synergywholesale_hosting_UsageUpdate($params, $page = 1)
{
    $limit = 500;
    $serverid = $params['serverid'];

    $config = DB::table('tblhosting')
        ->join('tblproducts', 'tblhosting.packageid', '=', 'tblproducts.id')
        ->where('tblproducts.servertype', '=', 'synergywholesale_hosting')
        ->where('tblproducts.configoption1', '!=', '')
        ->select([
            'tblproducts.configoption1',
            'tblproducts.configoption2'
        ])
        ->first();

    if (!$config) {
        return false;
    }

    $apiKey = $config->configoption1;
    $resellerId = $config->configoption2;

    $apiResult = synergywholesale_hosting_api($resellerId, $apiKey, 'listHosting', [
        'page' => $page,
        'limit' => $limit
    ]);

    foreach ($apiResult->hoidList as $i => $service) {
        $hosting = DB::table('tblhosting')
            ->join('tblcustomfieldsvalues', 'tblhosting.id', '=', 'tblcustomfieldsvalues.relid')
            ->join('tblcustomfields', 'tblcustomfields.id', '=', 'tblcustomfieldsvalues.fieldid')
            ->where('tblcustomfields.fieldname', '=', 'Hosting Id')
            ->where('tblcustomfieldsvalues.value', '=', $service->hoid)
            ->select('tblhosting.id')
            ->first();

        if (!$hosting) {
            continue;
        }

        DB::table('tblhosting')
            ->where([
                'id' => $hosting->id
            ])
            ->update([
                'diskusage'   => $service->diskUsage,
            'disklimit'  => $service->diskLimit,
            'bwusage'    => $service->bandwidth,
            'bwlimit'   => 0,
            'lastupdate' => DB::raw('now()')
        ]);

        if ($i == $limit - 1) {
            return synergywholesale_hosting_UsageUpdate($params, $page + 1);
        }
    }

    return true;
}

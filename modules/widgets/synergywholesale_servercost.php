<?php

use WHMCS\Database\Capsule as DB;
use WHMCS\Module\AbstractWidget;

class CostWidgetAPI
{
    protected $resellerID;
    protected $apiKey;
    protected $soap;

    public function __construct($resellerID, $apiKey)
    {
        $this->soap = new SoapClient(null, array("location" => "https://api.synergywholesale.com", "uri" => "", 'trace' => true));
        $this->apiKey = $apiKey;
        $this->resellerID = $resellerID;
    }

    public function request($command, $params = [])
    {
        $params['resellerID'] = $this->resellerID;
        $params['apiKey'] = $this->apiKey;

        try {
            $response = $this->soap->$command($params);
        } catch (Exception $e) {
            // Do stuff?
        }

        return $response;
    }

    public function balanceQuery()
    {
        return $this->request('balanceQuery');
    }

    public function listHosting()
    {
        return $this->request('listHosting');
    }

    public function hostingListPackages()
    {
        return $this->request('hostingListPackages');
    }
}

class CostWidget extends AbstractWidget
{
    protected $title = 'Synergy Wholesale Server Cost';
    protected $description = '';
    protected $weight = 150;
    protected $columns = 1;
    protected $cache = false;
    protected $cacheExpiry = 120;
    protected $requiredPermission = '';

    public function getData()
    {
        $query = DB::table('tblproducts')
            ->where('servertype', 'synergywholesale_hosting')
            ->first();

        $result['api_key'] = $query->configoption1;
        $result['reseller_id'] = $query->configoption2;

        return $result;
    }

    public function generateOutput($result)
    {
        // Get if button is clicked
        $syncCost = App::getFromRequest('sync_cost');
        $content = "";

        // Find current SWS Server
        $query = DB::table('tblservers')
            ->where('name', 'Synergy Wholesale')
            ->first();

        // Check if SWS server is found
        if (!empty($query)) {
            // Check if button is clicked
            if ($syncCost) {
                // Check if details are in the module.
                if (!empty($result['api_key']) && !empty($result['reseller_id'])) {
                    $api = new CostWidgetAPI($result['reseller_id'], $result['api_key']);

                    $hostingServices = $api->listHosting();

                    // Check if it was able to get hosting list
                    if ($hostingServices->status == "OK") {
                        // Collect and get 'active' services
                        $services = collect($hostingServices->hoidList);
                        $activeServicesPlans = $services->whereIn('serviceStatus', ['Active', 'Suspended', 'Suspended By Staff', 'Pending Completion', 'Pending Upgrade'])->countBy('plan');

                        $hostingPlans = $api->hostingListPackages();

                        // Check if it was abel to get plan list
                        if ($hostingPlans->status == "OK") {
                            $plans = collect($hostingPlans->packages);
                            $cost = '0.00';

                            // loop collected services plans and get plan cost and count it up
                            foreach ($activeServicesPlans as $key => $value) {
                                $planData = $plans->where('name', $key)->first();

                                $cost = bcadd($cost, bcmul($planData->price, $value, 2), 2);
                            }

                            // Add new cost into the database
                            DB::table('tblservers')
                                ->where('name', 'Synergy Wholesale')
                                ->update(['monthlycost' => $cost]);

                            // Refetch it in case we synced
                            $query = DB::table('tblservers')
                                ->where('name', 'Synergy Wholesale')
                                ->first();

                            $content .= "<div style='text-align: center; color: green; font-weight: bold; font-size: 12px; padding: 16px;'> Successfully synced server cost.</div>";
                        } else {
                            $content .= "<div style='text-align: center; color: red; font-weight: bold; font-size: 12px; padding: 16px;'> Failed to get plan information, please try again.</div>";
                        }
                    } else {
                        $content .= "<div style='text-align: center; color: red; font-weight: bold; font-size: 12px; padding: 16px;'> Unable to log into Synergy Wholesale system, check product module settings.</div>";
                    }
                } else {
                    $content .= "<div style='text-align: center; color: red; font-weight: bold; font-size: 12px; padding: 16px;'> Unable to find Synergy Wholesale details, check product module settings.</div>";
                }
            }

            $content .= "<div style='text-align: center; color: green; font-weight: bold; font-size: 18px; padding: 8px;'>Monthly Cost: $" . number_format($query->monthlycost, 2) . "</div>";
            $content .= "<div style='text-align: center; padding: 8px;'> <button type='button' id='sync_cost' class='btn btn-sm btn-info'>Sync Cost</button></div>";
            $content .= "
                <script>
                $(document).ready(function() {
                    $('#sync_cost').click(function() {
                        refreshWidget('CostWidget', 'sync_cost=1');
                    });
                });
                </script>
                ";
        } else {
            $content .= "<div style='text-align: center; color: red; font-weight: bold; font-size: 18px; padding: 16px;'> Failed to get Synergy Server Monthly Cost</div>";
        }

        return $content;
    }
}

add_hook("AdminHomeWidgets", 1, function () {
    return new CostWidget();
});

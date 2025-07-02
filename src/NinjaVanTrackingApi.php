<?php 

namespace Afzafri;

class NinjaVanTrackingApi
{
    public static function crawl($trackingNo, $include_info = false)
    {
        $domain = "walrus.ninjavan.co";
        $url = "https://$domain/my/dash/1.2/public/orders?tracking_id=" . urlencode(trim($trackingNo));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout in seconds
        
        $result = curl_exec($ch);
        $httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errormsg = (curl_error($ch)) ? curl_error($ch) : "No error";
        curl_close($ch);

        // Initialize response array
        $trackres = array();
        $trackres['http_code'] = $httpstatus;
        $trackres['error_msg'] = $errormsg;

        // Parse JSON response
        $jsonData = json_decode($result, true);
        
        if ($httpstatus === 200 && isset($jsonData['events']) && is_array($jsonData['events']) && !empty($jsonData['events'])) {
            $trackres['status']  = 1;
            $trackres['message'] = "Record Found";

            $trackres['data'] = [];

            // Order created info
            $trackres['data'][0] = [
                'date_time' => $jsonData['created_at'] ?? '',
                'process'   => 'Order created',
                'location'  => '',
            ];

            foreach ($jsonData['events'] as $i => $event) {
                // Build readable summary from tags (if any) or fallback to type
                $location = '';
                if (isset($event['data']) && is_array($event['data']) && !empty($event['data'])) {
                    $location = implode(', ', array_values($event['data']));
                } else {
                    $location = "In Transit";
                }

                $process = self::parseEvent($event['type'] ?? '');

                $trackres['data'][$i+1] = [
                    'date_time' => $event['time']  ?? '',
                    'process'   => $process,
                    'location'     => $location,
                ];
            }
        } else {
            $trackres['status'] = 0;
            $trackres['message'] = "No Record Found or API Error";
        }

        if ($include_info) {
            // Project info
            $trackres['info']['creator'] = "Afif Zafri (afzafri)";
            $trackres['info']['project_page'] = "https://github.com/afzafri/NinjaVan-Tracking-API";
            $trackres['info']['date_updated'] = "02/07/2025"; // Updated to current date in DD/MM/YYYY format
        }

        return $trackres;
    }

    private static function parseEvent($event)
    {
        $events = [
            'added_to_shipment' => 'Departed Ninja Van warehouse',
            'arrived_at_destination_hub' => 'Parcel is being processed at Ninja Van warehouse',
            'arrived_at_origin_hub' => 'Parcel is being processed at Ninja Van warehouse',
            'arrived_at_transit_hub' => 'Parcel is being processed at Ninja Van warehouse',
            'cancel' => 'Parcel delivery has been cancelled',
            'create_order' => 'Order created',
            'delivery_failure' => 'Delivery is unsuccessful',
            'delivery_success' => 'Successfully delivered',
            'driver_inbound_scan' => 'Parcel is being delivered',
            'driver_pickup_scan' => 'Successfully picked up from sender',
            'first_hub_inbound_scan' => 'Parcel is being processed at Ninja Van origin warehouse',
            'forced_success' => 'Successfully delivered',
            'from_dp_to_customer' => 'Parcel successfully collected',
            'from_dp_to_driver' => 'Departed Parcel Dropoff Counter / Box',
            'from_driver_to_dp' => 'Parcel delivered to Parcel Collection Counter / Box',
            'from_shipper_to_dp' => 'Parcel dropped off at Parcel Dropoff Counter / Box',
            'hub_inbound_scan' => 'Parcel is being processed at Ninja Van warehouse',
            'parcel_routing_scan' => 'Parcel is being processed at Ninja Van warehouse',
            'reschedule' => 'Parcel delivery has been rescheduled',
            'resume' => 'Parcel delivery resumed',
            'route_inbound_scan' => 'Parcel is being processed at Ninja Van warehouse',
            'rts' => 'Parcel is being returned to sender'
        ];

        return $events[strtolower($event)] ?? $event;
    }
}

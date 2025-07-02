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

                $process = $event['type'] ?? '';

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
}

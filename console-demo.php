<?php

require 'vendor/autoload.php';

use Afzafri\NinjaVanTrackingApi;

if (isset($argv[1])) {
	print_r(NinjaVanTrackingApi::crawl($argv[1]));
} else {
	echo "Usage: " . $argv[0] . " <Tracking code>\n";
}
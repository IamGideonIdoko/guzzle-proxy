<?php
# composer's autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

# where to make request to
$targetUrl = 'https://httpbin.org/ip';

# update <YOUR_ZENROWS_API_KEY> with a valid API key
$proxy = 'http://<YOUR_ZENROWS_API_KEY>:premium_proxy=true@proxy.zenrows.com:8001';

# proxies
$proxies = [
    'http'  => $proxy,
    'https' => $proxy,
];

$client = new Client([
    RequestOptions::PROXY => $proxies,
    RequestOptions::VERIFY => false, # disable SSL certificate validation
    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
]);

try {
    $body = $client->get($targetUrl)->getBody();
    echo $body->getContents();
} catch (\Exception $e) {
    echo $e->getMessage();
}

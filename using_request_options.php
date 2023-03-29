<?php
# composer's autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

# where to make request to
$targetUrl = 'https://httpbin.org/ip';

# proxies
$proxies = [
  'http'  => 'http://190.43.92.130:999',
  'https' => 'http://5.78.76.237:8080',
];

$client = new Client([
  RequestOptions::PROXY => $proxies,
  RequestOptions::VERIFY => false, # disable SSL certificate validation
  RequestOptions::TIMEOUT => 5, # timeout of 5 seconds
]);

try {
  $body = $client->get($targetUrl)->getBody();
  echo $body->getContents();
} catch (\Exception $e) {
  echo $e->getMessage();
}

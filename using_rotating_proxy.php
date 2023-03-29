<?php
# composer's autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

function get_random_proxies(): array {
    # (Get free proxies here: https://geonode.com/free-proxy-list)
    $http_proxies = array(
        'http://190.43.92.130:999',
        'http://201.182.251.142:999',
        # ...
        'http://200.123.15.250:999'
    );
    
    $https_proxies = array(
        'http://5.78.76.237:8080',
        'http://8.218.239.205:8888',
        # ...
        'http://169.55.89.6:80'
    );
    $http_proxy = $http_proxies[array_rand($http_proxies)];
    $https_proxy = $https_proxies[array_rand($https_proxies)];
    # proxies
    $proxies = [
        'http'  => $http_proxy,
        'https' => $https_proxy,
    ];
    return $proxies;
}

function rotating_proxy_request(string $http_method, string $targetUrl, int $max_attempts = 3): string
{
    $response = null;
    $attempts = 1;

    while ($attempts <= $max_attempts) {
        $proxies = get_random_proxies();
        echo "Using proxy: ".json_encode($proxies).PHP_EOL;
        $client = new Client([
            RequestOptions::PROXY => $proxies,
            RequestOptions::VERIFY => false, # disable SSL certificate validation
            RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
        ]);
        try {
            $body = $client->request(strtoupper($http_method), $targetUrl)->getBody();
            $response = $body->getContents();
            break;
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
            echo "Attempt ".$attempts." failed!".PHP_EOL;
            if ($attempts < $max_attempts) {
                echo "Retrying with a new proxy".PHP_EOL;
            }
            $attempts += 1;
        }
    }
    return $response;
}

$response = rotating_proxy_request('get', 'https://httpbin.org/ip');
// $response = rotating_proxy_request('get', 'https://www.g2.com/products/zenrows/reviews'); # 403

echo $response;
<?php
# composer's autoloader
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;

# where to make request to
$targetUrl = 'https://httpbin.org/ip';

# proxies
$proxies = [
  'http'  => 'http://username:password@190.43.92.130:999',
  'https' => 'http://username:password@5.78.76.237:8080',
];

function proxy_middleware(array $proxies) 
{
  return function (callable $handler) use ($proxies) {
    return function (RequestInterface $request, array $options) use ($handler, $proxies) {
      # add proxy to request option
      $options[RequestOptions::PROXY] = $proxies; 
      return $handler($request, $options);
      };
  };
}

$stack = HandlerStack::create();
$stack->push(proxy_middleware($proxies));

$client = new Client([
  'handler' => $stack,
  RequestOptions::VERIFY => false, # disable SSL certificate validation
  RequestOptions::TIMEOUT => 5, # timeout of 5 seconds
]);

try {
  $body = $client->get($targetUrl)->getBody();
  echo $body->getContents();
} catch (\Exception $e) {
  echo $e->getMessage();
}

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;

// Load in API access token
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$apiAccessToken = $_ENV['API_ACCESS_TOKEN'];

// HTTP headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Test school ID
$schoolId = "A1930499544";

$client = new Client([
    'base_uri' => "https://api.wonde.com/v1.0/schools/{$schoolId}/",
    'headers' => [
        'Authorization' => 'Bearer ' . $apiAccessToken,
        'Accept' => 'application/json'
    ]
]);

try {
    $response = $client->get('');
    $data = json_decode($response->getBody(), true);
} catch (\Exception $e) {
    http_response_code(500);
    $data = [
        'error' => 'Error fetching data from Wonde API',
        'details' => $e->getMessage()
    ];
}

echo json_encode($data);
exit();

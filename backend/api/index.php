<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;

// Load the env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Access the API_ACCESS_TOKEN
$apiAccessToken = $_ENV['API_ACCESS_TOKEN'];

// Set the school ID
$schoolId = "A1930499544";

$client = new Client([
    'base_uri' => "https://api.wonde.com/v1.0/schools/{$schoolId}/",
    'headers' => [
        'Authorization' => 'Bearer ' . $apiAccessToken,
        'Accept' => 'application/json'
    ]
]);

try {
    // Fetch school data
    $schoolResponse = $client->get("");
    $schoolData = json_decode($schoolResponse->getBody(), true);

    // Fetch employees data
    $teachersResponse = $client->get("employees", [
        'query' => [
            'has_role' => 'false',
            'has_class' => 'true'
        ]
    ]);
    $teachersData = json_decode($teachersResponse->getBody(), true);


    // Combine the school and teachers data
    $data = [
        'school' => $schoolData['data'],
        'teachers' => $teachersData['data']
    ];

} catch (\Exception $e) {
    http_response_code(500);
    $data = [
        'error' => 'Error fetching data from Wonde API',
        'details' => $e->getMessage()
    ];
}

echo json_encode($data);
exit();

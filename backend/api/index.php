<?php

// Load in API access token
require_once __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
$apiAccessToken = $_ENV['API_ACCESS_TOKEN'];

var_dump($apiAccessToken);

// $client = new \Wonde\Client($apiAccessToken);


// header('Content-Type: application/json');
// header('Access-Control-Allow-Origin: *');

// $data = [
//     'message' => 'Hello from the PHP backend!'
// ];

// echo json_encode($data);
// exit();
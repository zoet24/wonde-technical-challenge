<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = [
    'message' => 'Hello from the PHP backend!'
];

echo json_encode($data);
exit();

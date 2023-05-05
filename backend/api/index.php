<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // This allows cross-origin requests; for better security, replace '*' with your frontend domain.

// Access the API_ACCESS_TOKEN from the .env file
$apiAccessToken = $_ENV['API_ACCESS_TOKEN'];

// Set the school ID
$schoolId = "A1930499544";

$client = new Client([
    'base_uri' => "https://api.wonde.com/v1.0/",
    'headers' => [
        'Authorization' => 'Bearer ' . $apiAccessToken,
        'Accept' => 'application/json'
    ]
]);

try {
    // SCHOOL
    // Fetch school data
    $schoolResponse = $client->get("schools/{$schoolId}");
    $schoolData = json_decode($schoolResponse->getBody(), true)['data'];
    $school = [
        'name' => $schoolData['name']
    ];

    // TEACHERS
    // Fetch all staff employed at test school
    $allEmployeesResponse = $client->get("schools/{$schoolId}/employees", [
        'query' => [
            'include' => 'employment_details'
        ]
    ]);
    $allEmployeesData = json_decode($allEmployeesResponse->getBody(), true)['data'];

    // Get all teaching staff from all employees
    $teachersData = array_filter($allEmployeesData, function($employee) {
        return $employee['employment_details']['data']['current'] === true &&
               $employee['employment_details']['data']['teaching_staff'] === true;
    });

    $teachers = array_map(function($teacher) {
        return [
            'id' => $teacher['id'],
            'title' => $teacher['title'],
            'forename' => $teacher['forename'],
            'surname' => $teacher['surname']
        ];
    }, $teachersData);


    // CLASSES
    // Fetch all classes at test school
    $classesResponse = $client->get("schools/{$schoolId}/classes", [
        'query' => [
            'include' => 'employees',
            // 'include' => 'students',
        ]
    ]);
    $classesData = json_decode($classesResponse->getBody(), true)['data'];


    // Combine the school and teachers data
    $data = [
        // 'school' => $school,
        'teachers' => $teachers,
        // 'classes' => $classesData
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

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


    // CLASSES
    // Fetch all classes at test school
    $classesResponse = $client->get("schools/{$schoolId}/classes", [
        'query' => [
            'include' => 'employees',
        ]
    ]);
    $classesData = json_decode($classesResponse->getBody(), true)['data'];

    $classes = array_map(function($class) {
        // Find the main teacher's id
        $mainTeacherId = null;
        foreach ($class['employees']['data'] as $employee) {
            if (isset($employee['meta']['is_main_teacher']) && $employee['meta']['is_main_teacher'] === true) {
                $mainTeacherId = $employee['id'];
                break;
            }
        }

        return [
            'id' => $class['id'],
            'name' => $class['name'],
            'main_teacher_id' => $mainTeacherId
        ];
    }, $classesData);


    // TEACHERS
    // Fetch all staff employed at test school
    $allEmployeesResponse = $client->get("schools/{$schoolId}/employees", [
        'query' => [
            'include' => 'employment_details'
        ]
    ]);
    $allEmployeesData = json_decode($allEmployeesResponse->getBody(), true)['data'];

    // Get all teaching staff from all employees
    $allTeachersData = array_filter($allEmployeesData, function($employee) {
        return $employee['employment_details']['data']['current'] === true &&
               $employee['employment_details']['data']['teaching_staff'] === true;
    });

    $teachersData = array_map(function($teacher) {
        return [
            'id' => $teacher['id'],
            'title' => $teacher['title'],
            'forename' => $teacher['forename'],
            'surname' => $teacher['surname']
        ];
    }, $allTeachersData);


    $teachersById = [];
    foreach ($teachersData as $teacher) {
        $teacher['classes'] = []; // Initialize an empty 'classes' array for each teacher
        $teachersById[$teacher['id']] = $teacher;
    }

    // Iterate through the classes array and add them to the corresponding teacher
    foreach ($classes as $class) {
        $mainTeacherId = $class['main_teacher_id'];
        if (isset($teachersById[$mainTeacherId])) {
            $teachersById[$mainTeacherId]['classes'][] = $class;
        }
    }

    // Convert the associative array back to an indexed array, only include teachers who have classes
    $teachersWithClasses = [];
    foreach ($teachersById as $teacher) {
        if (!empty($teacher['classes'])) {
            $teachersWithClasses[] = $teacher;
        }
    }
    


    // Combine the school and teachers data
    $data = [
        // 'school' => $school,
        'teachers' => $teachersWithClasses,
        // 'classes' => $classes
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

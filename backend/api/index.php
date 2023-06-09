<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;

// Load the .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Access the API_ACCESS_TOKEN from the .env file
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
    // SCHOOL
    // Fetch school data
    $schoolResponse = $client->get("");
    $schoolData = json_decode($schoolResponse->getBody(), true)['data'];
    $school = [
        'name' => $schoolData['name'],
    ];


    // CLASSES
    // Fetch all classes at test school
    $classesResponse = $client->get("classes", [
        'query' => [
            'include' => 'employees,students',
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

        // Extract the students' data and sort alphabetically
        $students = array_map(function($student) {
            return [
                'id' => $student['id'],
                'forename' => $student['forename'],
                'surname' => $student['surname']
            ];
        }, $class['students']['data']);

        usort($students, function($a, $b) {
            if ($a['surname'] === $b['surname']) {
                return strcmp($a['forename'], $b['forename']);
            }
            return strcmp($a['surname'], $b['surname']);
        });

        return [
            'id' => $class['id'],
            'name' => $class['name'],
            'main_teacher_id' => $mainTeacherId,
            'students' => $students
        ];
    }, $classesData);


    // TEACHERS
    // Fetch all staff employed at test school
    $allEmployeesResponse = $client->get("employees", [
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
        $teacher['classes'] = [];
        $teachersById[$teacher['id']] = $teacher;
    }

    // Iterate through the classes array and add them to the corresponding teacher
    foreach ($classes as $class) {
        $mainTeacherId = $class['main_teacher_id'];
        if (isset($teachersById[$mainTeacherId])) {
            $teachersById[$mainTeacherId]['classes'][] = $class;
        }
    }

    // Only include teachers who have classes
    $teachersWithClasses = [];
    foreach ($teachersById as $teacher) {
        if (!empty($teacher['classes'])) {
            $teachersWithClasses[] = $teacher;
        }
    }
    
    // Combine the school and teachers data
    $data = [
        'school' => $school,
        'teachers' => $teachersWithClasses,
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

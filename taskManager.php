<?php

$file = 'tasks.json'; // Path to your JSON file

// Make sure the JSON file exists and is writable. If it doesn't exist, create it.
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

header('Content-Type: application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Read and return the tasks
        echo file_get_contents($file);
        break;
    case 'POST':
        // Add a new task
        $tasks = json_decode(file_get_contents($file), true);
        $input = json_decode(file_get_contents('php://input'), true);
        $tasks[] = $input; // Add new task
        $tasks[$input['index']]['completed'] = 0;
        file_put_contents($file, json_encode($tasks));
        echo json_encode($input); // Return the added task
        break;
    case 'PUT':
        // Update an existing task (completed status)
        $tasks = json_decode(file_get_contents($file), true);
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['index']) && isset($input['completed'])) {
            $tasks[$input['index']]['completed'] = $input['completed'];
            file_put_contents($file, json_encode($tasks));
            echo json_encode($tasks[$input['index']]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
        }
        break;
    case 'DELETE':
        // Delete a task
        $tasks = json_decode(file_get_contents($file), true);
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['index'])) {
            array_splice($tasks, $input['index'], 1);
            file_put_contents($file, json_encode($tasks));
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
        }
        break;
    default:
        http_response_code(405); // Method Not Allowed
        break;
}

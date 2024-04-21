<?php

include 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$text = isset($data['text']) ? $data['text'] : ''; // Get task text from JSON data
$priority = isset($data['priority']) ? (int) $data['priority'] : 1; // Get Priority from JSON data, deafault to 1 if not provided
$dueDate = isset($data['dueDate']) ? $data['dueDate'] : NULL; // Get due date from JSON data 

$response = ['success' => false]; //Initialize array with success value as false

if ($text) { //if user inputs text in task
    $stmt = $pdo->prepare("INSERT INTO tasks (taskDescription, priority, dueDate, completed) VALUES (?, ?, ?, 0)");// Prepare SQL statement to insert task into the database
    $success = $stmt->execute([$text, $priority, $dueDate]); //Execute the prepared statement with the provided tasks
    if ($success) { //If insertion is successful
        $response['success'] = true; // Set success flag in response to true
        $response['id'] = $pdo->lastInsertId(); // GET ID of last inserted row and assign it to the response array
    }
}

echo json_encode($response);
?>
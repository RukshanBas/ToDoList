<?php
include 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true); // Get JSON input from request body

$id = isset($data['id']) ? (int) $data['id'] : 0; // Extract task ID from input, deafault is 0 if no input given
$completed = isset($data['completed']) ? (int) $data['completed'] : 0; // Extract completion status from JSON data, deafault is 0 if not provided

$response = ['success' => false]; // Initialize response array with success value as false

if ($id) { // Check if task ID is provided and not zero
    $stmt = $pdo->prepare("UPDATE tasks SET completed = ? WHERE id = ?"); //Prepare SQL statement to update task completion status
    $success = $stmt->execute([$completed, $id]); //Execute the statement with the given task ID and completion status
    $response['success'] = $success; // Set success flag in response to the result of the update operation
}



echo json_encode($response);
?>
<?php
include 'connect.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true); // Get JSON input from the request body

$id = isset($data['id']) ? (int) $data['id'] : 0; // Extract task ID from JSON data, default to 0 if not provided

$response = ['success' => false]; // Initialize response array with default success value as false

if ($id) { // Check if task ID is given and not zero
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?"); // Prepare SQL statement to delete task from the database
    $success = $stmt->execute([$id]); // Execute the prepared statement with provided task ID
    $response['success'] = $success; // Set success flag in repsonse to the tresult of the delete operation
}

echo json_encode($response);
?>
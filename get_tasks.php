<?php
include 'connect.php';
header('Content-Type: application/json');

$stmt = $pdo->prepare("SELECT * FROM tasks ORDER BY priority DESC, dueDate ASC"); //Prepare SQL statement to get tasks based on priority descending and and due date ascending
$stmt->execute(); //Execute the prepared statement

$results = $stmt->fetchAll(PDO::FETCH_ASSOC); //Fetch all rows from the result set as an associative array

echo json_encode($results);
?>
<?php
$servername = "localhost";
$username = "root"; // default WAMP username
$password = ""; // default WAMP password is empty
$dbname = "todolist2"; // your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
if (!isset($_POST['commandId'])) {
    echo json_encode(array('error' => 'No commandId provided'));
    exit;
}
$commandId = intval($_POST['commandId']);
require_once 'db_connection.php';
if ($mysqli->connect_error) {
    echo json_encode(array('error' => 'Database connection failed: ' . $mysqli->connect_error));
    exit;
}
$sql = "SELECT done FROM command WHERE id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $commandId);
    $stmt->execute();
    $stmt->bind_result($done);
    if ($stmt->fetch()) {
        $response = array('done' => $done);
    } else {
        $response = array('error' => 'Command not found');
    }
    $stmt->close();
} else {
    $response = array('error' => 'Failed to prepare the SQL statement');
}
$mysqli->close();
echo json_encode($response);
?>

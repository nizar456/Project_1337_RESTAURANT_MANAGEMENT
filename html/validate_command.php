<?php
require_once 'db_connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $commandId = $_POST['command_id'];
    $query = "UPDATE command SET done = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $commandId);
    if ($stmt->execute()) {
        echo "Command validated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    header("Location: commands.php");
    exit();
}
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$mysqli = new mysqli("localhost", "username", "password", "database");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get selected products and quantities from POST request
$selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : '';

if (!empty($selectedProducts)) {
    $selectedProductData = json_decode($selectedProducts, true);
    $date = date('Y-m-d'); // Assuming you want to insert today's date
    $tableNum = isset($_POST['tableNum']) ? $_POST['tableNum'] : 1; // Example table number
    $etudiantId = isset($_POST['etudiantId']) ? $_POST['etudiantId'] : null; // Get user ID

    // Prepare and execute the insert query for the command
    $stmt = $mysqli->prepare("INSERT INTO command (date, table_num, etudiant_id) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "Prepare failed: " . $mysqli->error;
        exit();
    }

    $stmt->bind_param("sii", $date, $tableNum, $etudiantId);

    if ($stmt->execute()) {
        $commandId = $stmt->insert_id;

        $stmt->close();

        foreach ($selectedProductData as $product) {
            $productId = $product['productId'];
            $quantity = $product['quantity'];

            $stmt = $mysqli->prepare("INSERT INTO contains (command_id, product_id, quantity) VALUES (?, ?, ?)");
            if (!$stmt) {
                echo "Prepare failed: " . $mysqli->error;
                exit();
            }

            $stmt->bind_param("iii", $commandId, $productId, $quantity);

            if (!$stmt->execute()) {
                echo "Error inserting product: " . $stmt->error;
                exit();
            }

            $stmt->close();
        }
        echo "Command saved successfully!";
    } else {
        echo "Error saving command: " . $stmt->error;
    }
} else {
    echo "No products selected.";
}

$mysqli->close();
?>

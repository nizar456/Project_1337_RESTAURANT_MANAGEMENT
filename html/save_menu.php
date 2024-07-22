<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
require_once 'db_connection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['selectedProducts'])) {
        $selectedProducts = explode(',', $_POST['selectedProducts']);
        $menuDate = date('Y-m-d'); // Current date

        // Start a transaction to ensure atomicity
        $conn->begin_transaction();

        try {
            // Insert into today_menu
            if ($stmt = $conn->prepare("INSERT INTO today_menu (menu_date) VALUES (?)")) {
                $stmt->bind_param("s", $menuDate);
                if (!$stmt->execute()) {
                    throw new Exception("Error executing statement for today_menu: " . $stmt->error);
                }
                $menuId = $stmt->insert_id;
                $stmt->close();
            } else {
                throw new Exception("Error preparing statement for today_menu: " . $conn->error);
            }

            // Insert into menu_contains
            if ($stmt = $conn->prepare("INSERT INTO menu_contains (menu_id, product_id) VALUES (?, ?)")) {
                foreach ($selectedProducts as $productId) {
                    $productId = (int)$productId; // Ensure product ID is an integer
                    // Check if product_id exists in the products table
                    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE id = ?");
                    $checkStmt->bind_param("i", $productId);
                    $checkStmt->execute();
                    $checkStmt->bind_result($count);
                    $checkStmt->fetch();
                    $checkStmt->close();

                    if ($count == 0) {
                        throw new Exception("Product ID $productId does not exist in the products table.");
                    }

                    $stmt->bind_param("ii", $menuId, $productId);
                    if (!$stmt->execute()) {
                        throw new Exception("Error inserting product $productId: " . $stmt->error);
                    }
                }
                $stmt->close();
            } else {
                throw new Exception("Error preparing statement for menu_contains: " . $conn->error);
            }

            // Commit transaction
            $conn->commit();

            // Redirect to success page
            header("Location: success.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaction if an error occurs
            $conn->rollback();

            // Log the error
            error_log($e->getMessage());

            // Redirect to error page
            header("Location: error.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    } else {
        error_log("No products selected in POST data: " . print_r($_POST, true));
        header("Location: error.php?error=no_products");
        exit();
    }
} else {
    error_log("Invalid request method.");
    header("Location: error.php?error=invalid_request");
    exit();
}
?>

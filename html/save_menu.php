<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
require_once 'db_connection.php'; // Make sure this file includes the $conn variable

// Retrieve selected product IDs from POST request
$selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : '';

// Ensure that the selectedProducts is not empty
if (!empty($selectedProducts)) {
    // Split the product IDs into an array
    $productIds = explode(',', $selectedProducts);
    
    // Clean up the array
    $productIds = array_map('intval', array_filter($productIds));

    if (!empty($productIds)) {
        // Prepare SQL statements
        $todayMenuDate = date('Y-m-d');

        // Delete existing menu for today if it exists
        $deleteExistingMenuStmt = $conn->prepare("DELETE FROM today_menu WHERE menu_date = ?");
        $deleteExistingMenuStmt->bind_param('s', $todayMenuDate);
        $deleteExistingMenuStmt->execute();
        $deleteExistingMenuStmt->close();

        // Insert new menu record for today
        $insertMenuStmt = $conn->prepare("INSERT INTO today_menu (menu_date) VALUES (?)");
        $insertMenuStmt->bind_param('s', $todayMenuDate);
        $insertMenuStmt->execute();
        $menuId = $conn->insert_id; // Get the last inserted menu ID

        // Insert selected products into menu_contains table
        $insertMenuContainsStmt = $conn->prepare("INSERT INTO menu_contains (menu_id, product_id) VALUES (?, ?)");
        foreach ($productIds as $productId) {
            $insertMenuContainsStmt->bind_param('ii', $menuId, $productId);
            $insertMenuContainsStmt->execute();
        }

        // Close statements
        $insertMenuStmt->close();
        $insertMenuContainsStmt->close();
        
        // Redirect to success page with a query parameter
        header("Location: success.php?menu_id=$menuId");
    } else {
        // If no products are selected, redirect with an error message
        header("Location: todaymenu.php?error=No products selected");
    }
} else {
    // If selectedProducts is empty, redirect with an error message
    header("Location: todaymenu.php?error=No products selected");
}

// Close connection
$conn->close();
?>

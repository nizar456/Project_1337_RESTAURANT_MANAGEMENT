<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
require_once 'db_connection.php';

header('Content-Type: application/json');

// Check if 'selectedProducts' is set
if (!isset($_POST['selectedProducts']) || empty($_POST['selectedProducts'])) {
    echo json_encode([]);
    exit();
}

// Get the selected products
$selectedProducts = explode(',', $_POST['selectedProducts']);
$selectedProducts = array_filter($selectedProducts); // Remove empty values

// Fetch product details and categories
$productDetails = [];
if (!empty($selectedProducts)) {
    $placeholders = implode(',', array_fill(0, count($selectedProducts), '?'));
    $stmt = $conn->prepare("SELECT p.id AS product_id, p.nom AS product_name, c.nom AS category_name
                            FROM products p
                            LEFT JOIN categories c ON p.category_id = c.id
                            WHERE p.id IN ($placeholders)");

    // Generate the types string for binding parameters
    $types = str_repeat('i', count($selectedProducts));
    $stmt->bind_param($types, ...$selectedProducts);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $productDetails[] = $row;
    }
    $stmt->close();
}

echo json_encode($productDetails);
?>

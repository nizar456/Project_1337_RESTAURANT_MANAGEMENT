<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProducts = explode(',', $_POST['selectedProducts']);
    if (!empty($selectedProducts)) {
        $products = [];
        foreach ($selectedProducts as $productId) {
            $sql = "SELECT p.nom AS product_name, c.nom AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $productId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $products[] = $result->fetch_assoc();
            }
        }
        echo json_encode($products);
    } else {
        echo json_encode([]);
    }
}
?>

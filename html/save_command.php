<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connection.php';
    $tableNum = isset($_POST['tableNum']) ? intval($_POST['tableNum']) : null;
    $etudiantId = isset($_POST['etudiantId']) ? intval($_POST['etudiantId']) : null;
    $selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : '';
    if ($tableNum !== null && $etudiantId !== null && !empty($selectedProducts)) {
        $stmt = $conn->prepare("INSERT INTO command (date, table_num, etudiant_id) VALUES (CURDATE(), ?, ?)");
        $stmt->bind_param("ii", $tableNum, $etudiantId);
        $stmt->execute();
        $commandId = $stmt->insert_id;
        $selectedProductsArray = json_decode($selectedProducts, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($selectedProductsArray as $product) {
                $productId = intval($product['productId']);
                $quantity = intval($product['quantity']);
                $stmt = $conn->prepare("INSERT INTO contains (command_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $commandId, $productId, $quantity);
                $stmt->execute();
            }
            echo "Command saved successfully!";
        } else {
            echo "Error decoding JSON!";
        }
    } else {
        echo "Invalid data!";
    }
}
?>

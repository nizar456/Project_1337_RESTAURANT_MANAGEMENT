<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
require_once 'db_connection.php';
$selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : '';
if (!empty($selectedProducts)) {
    $productIds = explode(',', $selectedProducts);
    $productIds = array_map('intval', array_filter($productIds));
    if (!empty($productIds)) {
        $todayMenuDate = date('Y-m-d');
        $deleteExistingMenuStmt = $conn->prepare("DELETE FROM today_menu WHERE menu_date = ?");
        $deleteExistingMenuStmt->bind_param('s', $todayMenuDate);
        $deleteExistingMenuStmt->execute();
        $deleteExistingMenuStmt->close();
        $insertMenuStmt = $conn->prepare("INSERT INTO today_menu (menu_date) VALUES (?)");
        $insertMenuStmt->bind_param('s', $todayMenuDate);
        $insertMenuStmt->execute();
        $menuId = $conn->insert_id;
        $insertMenuContainsStmt = $conn->prepare("INSERT INTO menu_contains (menu_id, product_id) VALUES (?, ?)");
        foreach ($productIds as $productId) {
            $insertMenuContainsStmt->bind_param('ii', $menuId, $productId);
            $insertMenuContainsStmt->execute();
        }
        $insertMenuStmt->close();
        $insertMenuContainsStmt->close();
        header("Location: success.php?menu_id=$menuId");
    } else {
        header("Location: todaymenu.php?error=No products selected");
    }
} else {
    header("Location: todaymenu.php?error=No products selected");
}
$conn->close();
?>

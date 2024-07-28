<?php
include 'db_connection.php';
if (isset($_POST['selectedProducts'])) {
    $selectedProducts = $_POST['selectedProducts'];
    $productIds = explode(',', $selectedProducts);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $sql = "SELECT p.nom AS product_name, c.nom AS category_name 
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <style>
        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        #popup h2 {
            margin-top: 0;
        }
        #popup ul {
            list-style-type: none;
            padding: 0;
        }
        #popup ul li {
            margin: 5px 0;
        }
        #popup .close {
            cursor: pointer;
            color: #f00;
            float: right;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <div id="overlay"></div>
    <div id="popup">
        <span class="close" onclick="closePopup()">×</span>
        <h2>Selected Products</h2>
        <ul>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <li><?php echo htmlspecialchars($product['product_name']) . ' (' . htmlspecialchars($product['category_name']) . ')'; ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No products selected.</li>
            <?php endif; ?>
        </ul>
    </div>
    <script>
        function showPopup() {
            document.getElementById('overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        }
        function closePopup() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($products)): ?>
                showPopup();
            <?php endif; ?>
        });
    </script>
</body>
</html>
/*
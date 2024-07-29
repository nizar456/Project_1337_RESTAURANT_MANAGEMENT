<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection.php';
function isAuthenticated() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && $_SESSION['role'] === 'student';
}
if (!isAuthenticated()) {
    header("Location: login.php");
    exit();
}
function getUserCommandStatus($conn, $etudiantId) {
    $sql_done = "SELECT `done`, `date` FROM `command` WHERE `etudiant_id` = ? ORDER BY `date` DESC LIMIT 1";
    $stmt = $conn->prepare($sql_done);
    $stmt->bind_param("s", $etudiantId);
    $stmt->execute();
    $result = $stmt->get_result();

    $doneStatus = null;
    $commandDate = null;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $doneStatus = $row['done'];
        $commandDate = $row['date'];
    }
    $stmt->close();

    return [$doneStatus, $commandDate];
}
function resetDoneStatus($doneStatus, $commandDate, $currentDate, $currentHour) {
    if ($commandDate !== $currentDate) {
        $doneStatus = null;
    }
    if ($currentHour >= 15) {
        $doneStatus = null;
    }
    return $doneStatus;
}
$etudiantId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$currentDate = date('Y-m-d');
$currentHour = (new DateTime())->format('H');
list($doneStatus, $commandDate) = getUserCommandStatus($conn, $etudiantId);
$doneStatus = resetDoneStatus($doneStatus, $commandDate, $currentDate, $currentHour);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
function fetchTodayMenu($conn) {
    $sql = "
        SELECT 
            mc.id AS menu_contains_id,
            p.id AS product_id,
            p.nom AS product_name,
            p.url AS product_url,
            c.id AS category_id,
            c.nom AS category_name
        FROM today_menu tm
        JOIN menu_contains mc ON mc.menu_id = tm.id
        JOIN products p ON mc.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE tm.menu_date = CURDATE() -- Ensure we only get today's menu
        ORDER BY c.id, p.nom
    ";
    $result = $conn->query($sql);
    $menuByCategory = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categoryId = $row['category_id'];
            $categoryName = $row['category_name'];
            if (!isset($menuByCategory[$categoryId])) {
                $menuByCategory[$categoryId] = [
                    'name' => $categoryName,
                    'products' => []
                ];
            }
            $menuByCategory[$categoryId]['products'][] = $row;
        }
    }
    return $menuByCategory;
}
$menuByCategory = fetchTodayMenu($conn);

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template" />
        <meta name="description" content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework" />
        <meta name="robots" content="noindex,nofollow" />
        <title>1337 Restaurant</title>
        <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
        <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png" />
        <link href="css/style.min.css" rel="stylesheet" />
        <style>
            @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
                font-family: "Poppins", sans-serif;
                font-size: 16px;
                font-weight: normal;
            }
            .command-card {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
                background-color: #f9f9f9;
            }

            .command-card h5 {
                margin-top: 0;
            }

            .container-fluid {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                flex-direction: column;
                text-align: center;  
            }
            
            .countdown {
                font-size: 3rem;
                margin: 20px 0;
            }

            .title1 {
                font-size: 1.5rem;
                margin-bottom: 20px;
            }
            .product-name{
                margin-top: 15px;
                margin-bottom: 0;
                padding-bottom: 0;
                font-size: 19px;
            }
            .snippet-body {
                background-color: #f4f4f4;
            }
            .container {
                margin: 40px auto;
            }
            #header {
                width: 100%;
                height: 60px;
                box-shadow: 5px 5px 15px #e8e8e8;
            }
            .col-lg-4,
            .col-md-6 {
                padding-right: 0;
            }
            .card {
                padding: 10px;
                cursor: pointer;
                transition: 0.3s all ease-in-out;
                height: 350px;
                margin-left: 30px
            }
            .card.selected {
                box-shadow: 2px 2px 15px greenyellow;
                transform: scale(1.02);
            }
            .card:hover {
                box-shadow: 2px 2px 15px #fd9a6ce5;
                transform: scale(1.02);
            }
            .card .product-name {
                font-weight: 600;
            }
            .card-img img {
                padding: auto;
                margin-top: 40px;
                width: inherit;
                height: 200px;
                object-fit: contain;
                display: block;
        
            }
            .category-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin-top: 30px;
                margin-bottom: 15px;
                margin-left: 15px;
            }
            .navbar-collapse .collapse{
                font-family: "Nunito", sans-serif;
                font-size: 14px;
            }
            #menu{
                margin-right: 20px;
            }
            .button {
                background-color: #2f323e; 
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                font-weight: bold;
                display: flex;
                font-size: 16px;
                margin: 0; 
                float: right; 
                border-radius: 0.5rem;
            }
            .container-btn {
                padding: 0 10px 70px 0;
                margin:0 ;
            }
            #popup {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
                padding: 20px;
                background: #fff;
                border: 1px solid #ccc;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            #popup h2 {
                margin-top: 5px;
            }
            #popup ul {
                list-style-type: none;
                padding: 0;
                margin-top: 5px;
            }
            #popup ul li {
                margin: 5px 0;
                font-size: 17px;
            }
            #popup .close {
                cursor: pointer;
                color: #2f323e;
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
            .quantity-input {
                position: relative;
                display: inline-block;
                border: black;
                border-radius: 0.5rem;
                background-color: #2f323e;
                color: white;
                padding: 5px;
            }
            .quantity-input-content {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
            }
            .quantity-input-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }
            .quantity-input-content a:hover {
                background-color: white;
            }
            .quantity-input:hover .quantity-input-content {
                display: block;
            }
            .quantity-input:hover .dropbtn {
                background-color: #3e8e41;
            }
            .table {
                margin-top: 25px;
            }
            input[type=number] {
                width: 250px;
                padding: 10px 20px;
                margin: 8px 0;
                box-sizing: border-box;
                border-radius: 0.5rem;
                border: none;
                margin-left: 40px;
                }
            .nomenu{
                margin-left: 25px;
                margin-top: 30px;
                font-weight: 500;
            }
        </style>
    </head>
    <body>
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
            data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
            <header class="topbar" data-navbarbg="skin5">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-header" data-logobg="skin6">
                        <a class="navbar-brand" href="s_TodayMenu.php">
                            <b class="logo-icon">
                                <img src="plugins/images/logo-icon.png" alt="homepage" />
                            </b>
                            <span class="logo-text">
                                <img src="plugins/images/logo-text.png" alt="homepage" />
                            </span>
                        </a>
                        <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                            href="javascript:void(0)"><i class="ti-menu ti-close"></i>
                        </a>
                    </div>
                    <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                        <ul class="navbar-nav ms-auto d-flex align-items-center">
                            <li>
                                <span class="text-white font-medium">Bonjour <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="left-sidebar" data-sidebarbg="skin6">
                <div class="scroll-sidebar">
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="s_TodayMenu.php"
                                    aria-expanded="false">
                                    <i class="fa fa-columns" aria-hidden="true"></i>
                                    <span class="hide-menu">Today Menu</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="s_Commands.php"
                                    aria-expanded="false">
                                    <i class="fa fa-table" aria-hidden="true"></i>
                                    <span class="hide-menu">Command your food</span>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Logoutss.php"
                                    aria-expanded="false">
                                    <i class="fas fa-arrow-alt-circle-right" aria-hidden="true"></i>
                                    <span class="hide-menu">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <div class="page-wrapper">
                <div class="page-breadcrumb bg-white">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                            <h4 class="page-title">Command your food</h4>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="container-fluid" id="cont">
                        <?php if ($doneStatus === null): ?>
                            <div class="title1" id="title1">Time Remaining to order your Lunch</div>
                            <div class="countdown" id="countdown"></div>
                        <?php else: ?>
                            <div class="title1" id="confirmationMessage">
                                <?php echo $doneStatus == 1 ? "Your command is completed!" : "Your command is not yet completed."; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($doneStatus === null ): ?>
                        <form id="menuForm" method="POST" action="save_command.php">
                            <input type="hidden" id="selectedProducts" name="selectedProducts" value="">
                            <input type="hidden" id="etudiantId" name="etudiantId" value="<?php echo htmlspecialchars((string)$etudiantId); ?>">
                            <div id="menu">
                                <div id="products">
                                    <?php if (!empty($menuByCategory)): ?>
                                        <div class="table" id="table">
                                            <h2 class="category-title">Choose your table number</h2>
                                            <label for="tableNum">
                                                <input type="number" id="tableNum" class="number-input" min="1" max="50" placeholder="Table Number" />
                                            </label>
                                        </div>
                                        <?php foreach ($menuByCategory as $categoryId => $categoryData): ?>
                                            <h2 class="category-title" data-category-id="<?php echo htmlspecialchars($categoryId); ?>">
                                                <?php echo htmlspecialchars($categoryData['name']); ?>
                                            </h2>
                                            <div class="row mx-0">
                                                <?php foreach ($categoryData['products'] as $product): ?>
                                                    <div class="col-lg-4 col-md-6 pt-md-4 pt-3">
                                                        <div class="card d-flex flex-column align-items-center" 
                                                            data-product-id="<?php echo htmlspecialchars($product['product_id']); ?>"
                                                            data-category="<?php echo htmlspecialchars($categoryId); ?>">
                                                            <div class="product-name text-center"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                                            <div class="card-img">
                                                                <img src="<?php echo htmlspecialchars($product['product_url']); ?>" alt=""/>
                                                            </div>
                                                            <?php if ($categoryId == 3 || $categoryId == 4): ?>
                                                                <select class="quantity-input mt-2" data-product-id="<?php echo htmlspecialchars($product['product_id']); ?>">
                                                                    <option value="">Select Quantity</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                </select>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="container-btn">
                                            <button type="submit" class="button">Confirm</button>
                                        </div>
                                    <?php else: ?>
                                        <h2 class="nomenu">No menu items found for today.</h2>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
                <footer class="footer text-center">
                    2024 © 1337 Restaurant
                </footer>
            </div>
        </div>
        <div id="overlay"></div>
        <div id="popup">
            <span class="close">×</span>
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
        <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="js/app-style-switcher.js"></script>
        <script src="js/waves.js"></script>
        <script src="js/sidebarmenu.js"></script>
        <script src="js/custom.js"></script>
        <script> 
            function updateCountdown() {
                function resetDoneStatus() {
                    var currentDate = new Date();
                    var currentHour = currentDate.getHours();
                    var commandDate = <?php echo json_encode($commandDate); ?>;
                    var currentDateString = currentDate.toISOString().split('T')[0];
                    if (commandDate !== currentDateString) {
                        doneStatus = null;
                        return true;
                    }                
                    if (currentHour >= 15) {
                        doneStatus = null;
                        return true;
                    }
                        return false;
                    }
                const now = new Date();
                const hours = now.getHours();
                const minutes = now.getMinutes();
                const seconds = now.getSeconds();
                var doneStatus = <?php echo json_encode($doneStatus); ?>;
                let targetHour, targetMinute, targetSecond, titleText;
                if (hours < 12) {
                    targetHour = 12;
                    targetMinute = 0;
                    targetSecond = 0;
                    titleText = "Time Remaining to order your Lunch";
                    if (doneStatus == null || resetDoneStatus()){
                    document.getElementById('menuForm').style.display = "none";
                    }
                    else{
                    document.getElementById('confirmationMessage').style.display = "none";
                    }
                } else if (hours >= 12 && hours < 15) {
                    if(doneStatus == null || resetDoneStatus()){        
                    document.getElementById('title1').style.display = "none";
                    document.getElementById('countdown').style.display = "none";
                    document.getElementById('cont').style.display = "none";
                    document.getElementById('menuForm').style.display = "block";
                    }
                    else{
                    document.getElementById('confirmationMessage').style.display = "block"; 
                    }
                    return;
                } else if (hours >= 15) {
                    targetHour = 15 + 24;
                    targetMinute = 0;
                    targetSecond = 0;
                    titleText = "Time Remaining until next order period";
                    if (doneStatus == null || resetDoneStatus()){
                    document.getElementById('title1').style.display = "block";
                    document.getElementById('countdown').style.display = "block";
                    document.getElementById('menuForm').style.display = "none";
                    }
                    else{
                        document.getElementById('confirmationMessage').style.display = "none";
                    }
                }
                const targetTime = new Date();
                targetTime.setHours(targetHour, targetMinute, targetSecond, 0);
                const remainingTime = targetTime - now;
                if (remainingTime <= 0) {
                    if (doneStatus==null || resetDoneStatus()){
                    document.getElementById('title1').style.display = "none";
                    document.getElementById('countdown').style.display = "none";
                    document.getElementById('menuForm').style.display = "none";
                    }
                    return;
                }
                const remainingHours = Math.floor(remainingTime / (1000 * 60 * 60));
                const remainingMinutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
                const remainingSeconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                if(doneStatus == null || resetDoneStatus()){
                document.getElementById('title1').textContent = titleText;
                document.getElementById('countdown').textContent = `${remainingHours.toString().padStart(2, '0')}:${remainingMinutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                }
            }
            setInterval(updateCountdown, 1000);
        </script>
        <script>
            $(document).ready(function() {
                    let selectedProductsByCategory = {};
                    $(".card").on("click", function() {
                        const productId = $(this).data("product-id");
                        const categoryId = $(this).closest('.row').prev('.category-title').data("category-id");
                        const categoryName = $(this).closest('.row').prev('.category-title').text().trim();
                        const productName = $(this).find('.product-name').text().trim();
                        const quantity = $(this).find('.quantity-input').val() ? $(this).find('.quantity-input').val() : 1;
                        console.log(`Product: ${productName}, Quantity: ${quantity}`);
                        if (categoryId === 1 || categoryId === 2) {
                        if (selectedProductsByCategory[categoryId] && selectedProductsByCategory[categoryId].productId !== productId) {
                            $(".card[data-product-id='" + selectedProductsByCategory[categoryId].productId + "']").removeClass("selected");
                        }
                        }
                        $(this).toggleClass("selected");
                        if ($(this).hasClass("selected")) {
                            selectedProductsByCategory[categoryId] = {
                                name: categoryName,
                                productId: productId,
                                products: [{
                                    name: productName,
                                    quantity: quantity
                                }]
                            };
                        } else {
                            delete selectedProductsByCategory[categoryId];
                        }
                        $("#selectedProducts").val(JSON.stringify(selectedProductsByCategory));
                    });
                    $("#menuForm").on("submit", function(event) {
                        event.preventDefault();
                        let selectedProducts = [];
                        for (let categoryId in selectedProductsByCategory) {
                            const category = selectedProductsByCategory[categoryId];
                            category.products.forEach(function(product) {
                                selectedProducts.push({
                                    productId: category.productId,
                                    productName: product.name,
                                    quantity: product.quantity
                                });
                            });
                        }
                        let tableNum = $("#tableNum").val();
                        let etudiantId = $("#etudiantId").val();
                        const principalSelected = selectedProductsByCategory[1]; 
                        const secondarySelected = selectedProductsByCategory[2];
                        if (!principalSelected || !secondarySelected) {
                            alert("Please select one product from each category.");
                            return;
                        }
                        console.log("Selected Products:", selectedProducts);
                        $.ajax({
                            type: "POST",
                            url: "save_command.php",
                            data: {
                                selectedProducts: JSON.stringify(selectedProducts),
                                tableNum: tableNum,
                                etudiantId: etudiantId
                            },
                            success: function(response) {
                                let popupContent = $("#popup ul");
                                popupContent.empty();
                                if (Object.keys(selectedProductsByCategory).length > 0) {
                                    for (let categoryId in selectedProductsByCategory) {
                                        const category = selectedProductsByCategory[categoryId];
                                        popupContent.append(`<li><strong>${category.name}:</strong></li>`);
                                        category.products.forEach(function(product) {
                                            popupContent.append(`<li> - ${product.name} (Qty: ${product.quantity})</li>`);
                                        });
                                    }
                                } else {
                                    popupContent.append(`<li>No products selected.</li>`);
                                }
                                $("#popup").show();
                                $("#overlay").show();
                            },
                            error: function() {
                                alert('An error occurred.');
                            }
                        });
                    });
                    $(".close").on("click", function() {
                        closePopup();
                    });
                    function closePopup() {
                        $("#popup").hide();
                        $("#overlay").hide();
                        location.reload();
                    }
                });
        </script>
    </body>
</html>

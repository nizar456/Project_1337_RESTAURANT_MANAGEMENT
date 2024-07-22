<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
require_once 'db_connection.php';

// Prevent caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Query to get products with their category names, ordered by category ID
$sql = "
    SELECT 
        p.id AS product_id, 
        p.nom AS product_name, 
        p.url AS product_url, 
        c.id AS category_id,
        c.nom AS category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY c.id, p.nom
";
$result = $conn->query($sql);
$productsByCategory = [];

// Organize products by category
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categoryId = $row['category_id'];
        $categoryName = $row['category_name'];
        if (!isset($productsByCategory[$categoryId])) {
            $productsByCategory[$categoryId] = [
                'name' => $categoryName,
                'products' => []
            ];
        }
        $productsByCategory[$categoryId]['products'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template" />
    <meta name="description" content="Ample Admin Lite is powerful and clean admin dashboard template, inspired from Bootstrap Framework" />
    <meta name="robots" content="noindex,nofollow" />
    <title>1337 Restaurant</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
    <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png" />
    <link href="css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins&display=swap");
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
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
            margin-top: 60px;
            width: inherit;
            height: 180px;
            object-fit: contain;
            display: block;
        }
        .category-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .button {
            background-color: #2f323e; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            font-size: 16px;
            margin: 0; /* Add margin to space it from other elements */
            float: right; /* Align the button to the right */
            border-radius: 0.5rem;
        }
        .container-btn {
            padding: 0 10px 70px 0;
            margin:0 ;
        }
        .container-fluid{
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
</head>
<body>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5">
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <a class="navbar-brand" href="index.html">
                        <b class="logo-icon">
                            <img src="plugins/images/logo-icon.png" alt="homepage" />
                        </b>
                        <span class="logo-text">
                            <img src="plugins/images/logo-text.png" alt="homepage" />
                        </span>
                    </a>
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
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
                        <li class="sidebar-item pt-2">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="index.php" aria-expanded="false">
                                <i class="far fa-clock" aria-hidden="true"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="TodayMenu.php" aria-expanded="false">
                                <i class="fa fa-columns" aria-hidden="true"></i>
                                <span class="hide-menu">Today Menu</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Commands.php" aria-expanded="false">
                                <i class="fa fa-table" aria-hidden="true"></i>
                                <span class="hide-menu">Commands</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Add_product_page.php" aria-expanded="false">
                                <i class="fa fa-columns" aria-hidden="true"></i>
                                <span class="hide-menu">Add Product</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Logoutss.php" aria-expanded="false">
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
                        <h4 class="page-title">Today Menu</h4>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <form id="menuForm" method="POST">
                    <input type="hidden" id="selectedProducts" name="selectedProducts" value="">
                    <div id="products">
                        <?php if (!empty($productsByCategory)): ?>
                            <?php foreach ($productsByCategory as $categoryId => $categoryData): ?>
                                <h2 class="category-title"><?php echo htmlspecialchars($categoryData['name']); ?></h2>
                                <div class="row mx-0">
                                    <?php foreach ($categoryData['products'] as $product): ?>
                                        <div class="col-lg-4 col-md-6 pt-md-4 pt-3">
                                            <div class="card d-flex flex-column align-items-center" data-product-id="<?php echo htmlspecialchars($product['product_id']); ?>">
                                                <div class="product-name text-center"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                                <div class="card-img">
                                                    <img src="<?php echo htmlspecialchars($product['product_url']); ?>" alt=""/>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No products found.</p>
                        <?php endif; ?>
                    </div>
                    <div class="container-btn">
                        <button type="submit" class="button">Validate</button>
                    </div>
                </form>
            </div>
            <footer class="footer text-center">
                2024 © 1337 Restaurant
            </footer>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let selectedProducts = [];

            $(".card").on("click", function() {
                const productId = $(this).data("product-id");
                $(this).toggleClass("selected");
                if ($(this).hasClass("selected")) {
                    selectedProducts.push(productId);
                } else {
                    selectedProducts = selectedProducts.filter(id => id !== productId);
                }
                $("#selectedProducts").val(selectedProducts.join(","));
            });

            $("#menuForm").on("submit", function(e) {
                e.preventDefault(); // Prevent default form submission

                const selectedProductsList = $("#selectedProducts").val();
                if (selectedProductsList === "") {
                    alert("No products were selected.");
                    return;
                }

                $.ajax({
                    url: "save_menu.php",
                    type: "POST",
                    data: { selectedProducts: selectedProductsList },
                    success: function(data) {
                        let selectedProducts = JSON.parse(data);
                        if (selectedProducts.length > 0) {
                            let html = "<h3>Selected Products</h3><ul>";
                            $.each(selectedProducts, function(index, item) {
                                html += "<li>Product: " + item.product_name + " (Category: " + item.category_name + ")</li>";
                            });
                            html += "</ul>";

                            $("<div>").html(html).dialog({
                                title: "Confirm Selection",
                                modal: true,
                                buttons: {
                                    "Confirm": function() {
                                        $(this).dialog("close");
                                        $("#menuForm")[0].submit();
                                    },
                                    "Cancel": function() {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                        } else {
                            alert("No products were selected or found.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while processing your request.");
                    }
                });
            });
        });
    </script>
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <script src="js/waves.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>

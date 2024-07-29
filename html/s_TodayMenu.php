<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
require_once 'db_connection.php';
$today = date('Y-m-d');
$menuSql = "SELECT id FROM today_menu WHERE menu_date = '$today'";
$menuResult = $conn->query($menuSql);
$menuId = null;
if ($menuResult->num_rows > 0) {
    $menuRow = $menuResult->fetch_assoc();
    $menuId = $menuRow['id'];
}
$productsByCategory = [];
if ($menuId !== null) {
    $sql = "
        SELECT 
            p.id AS product_id, 
            p.nom AS product_name, 
            p.url AS product_url, 
            c.id AS category_id,
            c.nom AS category_name
        FROM menu_contains mc
        LEFT JOIN products p ON mc.product_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE mc.menu_id = $menuId
        ORDER BY c.id, p.nom
    ";
    $result = $conn->query($sql);
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
}
?>

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
        .category-title {
            margin-top: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .product-card {
            flex: 0 1 calc(33.333% - 20px);
            max-width: calc(33.333% - 20px);
            margin: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background-color: #fff;
            transition: transform 0.2s ease-in-out;
        }
        .product-card img {
            width: 100%;
            height: auto;
        }
        .product-card .product-name {
            font-size: 1.2em;
            padding: 10px;
            background-color: #f8f8f8;
        }
        .container-fluid {
        display: flex;
        height: 100vh;
        flex-direction: column;  
      }
    </style>
</head>
<body>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <a class="navbar-brand" href="s_TodayMenu.php">
                        <b class="logo-icon"><img src="plugins/images/logo-icon.png" alt="homepage" /></b>
                        <span class="logo-text"><img src="plugins/images/logo-text.png" alt="homepage" /></span>
                    </a>
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
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
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="s_TodayMenu.php" aria-expanded="false">
                                <i class="fa fa-columns" aria-hidden="true"></i>
                                <span class="hide-menu">Today Menu</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="s_Commands.php" aria-expanded="false">
                                <i class="fa fa-table" aria-hidden="true"></i>
                                <span class="hide-menu">Command your food</span>
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
                <div id="products">
                    <?php if (!empty($productsByCategory)): ?>
                        <?php foreach ($productsByCategory as $categoryId => $categoryData): ?>
                            <h2 class="category-title"><?php echo htmlspecialchars($categoryData['name']); ?></h2>
                            <div class="product-container">
                                <?php foreach ($categoryData['products'] as $product): ?>
                                    <div class="product-card">
                                        <div class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></div>
                                        <img src="<?php echo htmlspecialchars($product['product_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>"/>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h2>No products found for today's menu.</h2>
                    <?php endif; ?>
                </div>
            </div>
            <footer class="footer text-center">
                2024 © 1337 Restaurant
            </footer>
        </div>
    </div>
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <script src="js/waves.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>

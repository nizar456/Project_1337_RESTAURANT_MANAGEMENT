<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}

// Prevent caching
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords"
    content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template" />
  <meta name="description"
    content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework" />
  <meta name="robots" content="noindex,nofollow" />
  <title>1337 Restaurant</title>
  <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png" />
  <!-- Custom CSS -->
  <link href="css/style.min.css" rel="stylesheet" />
  <style>
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
          <a class="navbar-brand" href="index.php">
            <b class="logo-icon">
              <img src="plugins/images/logo-icon.png" alt="homepage" />
            </b>
            <span class="logo-text">
              <img src="plugins/images/logo-text.png" alt="homepage" />
            </span>
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
            <h4 class="page-title">Commands</h4>
          </div>
        </div>
      </div>
      <div class="container-fluid">
        <?php
        require_once 'db_connection.php';
        
        // Get current date
        $currentDate = date('Y-m-d');
        
        // Query to get commands for the current day that are not done
        $query = "SELECT c.id, c.date, c.table_num, c.etudiant_id, c.done, e.nom AS etudiant_name
                  FROM command c
                  JOIN etudiant e ON c.etudiant_id = e.id
                  WHERE c.date = ? AND c.done = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $currentDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="command-card">';
                echo '<h5>Command ID: ' . $row['id'] . '</h5>';
                echo '<p>Date: ' . $row['date'] . '</p>';
                echo '<p>Table Number: ' . $row['table_num'] . '</p>';
                echo '<p>Student Name: ' . $row['etudiant_name'] . '</p>';
                
                // Query to get products for the current command
                $commandId = $row['id'];
                $productQuery = "SELECT p.nom AS product_name, c.quantity
                                 FROM contains c
                                 JOIN products p ON c.product_id = p.id
                                 WHERE c.command_id = ?";
                $productStmt = $conn->prepare($productQuery);
                $productStmt->bind_param('i', $commandId);
                $productStmt->execute();
                $productResult = $productStmt->get_result();
                
                echo '<ul>';
                while ($productRow = $productResult->fetch_assoc()) {
                    echo '<li>' . $productRow['product_name'] . ' - Quantity: ' . $productRow['quantity'] . '</li>';
                }
                echo '</ul>';
                
                echo '<form method="POST" action="validate_command.php">';
                echo '<input type="hidden" name="command_id" value="' . $row['id'] . '">';
                echo '<button type="submit" class="btn btn-success">Validate Command</button>';
                echo '</form>';
                
                echo '</div>';
            }
        } else {
            echo '<p>No commands for today.</p>';
        }
        
        $stmt->close();
        $conn->close();
        ?>
      </div>
      <footer class="footer text-center">
        2024 © 1337
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

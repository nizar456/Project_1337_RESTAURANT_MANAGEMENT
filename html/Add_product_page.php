<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: logout.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
        #message {
          display: none;
          position: fixed;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          padding: 10px;
          background-color: #f0f0f0;
          border: 1px solid #ccc;
          border-radius: 5px;
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
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
      <header class="topbar" data-navbarbg="skin5">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
          <div class="navbar-header" data-logobg="skin6">
            <a class="navbar-brand" href="dashboard.php">
              <b class="logo-icon">
                <img src="plugins/images/logo-icon.png" alt="homepage" />
              </b>
              <span class="logo-text">
                <img src="plugins/images/logo-text.png" alt="homepage" />
              </span>
            </a>
            <a class=" nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i>
            </a>
          </div>
          <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
              <li>
                <span class="text-white font-medium">Bonjour <?php echo htmlspecialchars($_SESSION['user_name']);?></span>
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
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="dashboard.php" aria-expanded="false">
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
                <a class="sidebar-link waves-effect waves-dark sidebar-link" href="Commands.php"
                  aria-expanded="false">
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
      <div class="page-wrapper" style="min-height: 250px">
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <h4 class="page-title">Add products</h4>
            </div>
          </div>
        </div>
        <div class="container-fluid">
          <h2>Add a New Product</h2>
          <div id="message"></div>
          <form id="productForm" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="productName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="productName" name="productName" required>
            </div>
            <div class="mb-3">
              <label for="category" class="form-label">Category</label>
              <select class="form-control" id="category" name="category" required>
                <?php
                  require_once 'db_connection.php';
                  $query = "SELECT id, nom FROM categories";
                  $result = $conn->query($query);
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value=\"" . $row['id'] . "\">" . $row['nom'] . "</option>";
                      }
                    }
                  ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="productImage" class="form-label">Product Image</label>
              <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
          </form>
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
    <script>
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        fetch('add_product.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById('message');
            messageDiv.innerText = data.message;
            messageDiv.style.display = 'block';
            if (data.success) {
                messageDiv.style.backgroundColor = 'green';
                messageDiv.style.color = 'white';
                document.getElementById('productForm').reset();
            } else {
                messageDiv.style.backgroundColor = 'red';
                messageDiv.style.color = 'white';
            }
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        })
        .catch(error => console.error('Error:', error));
    });
    </script>
  </body>
</html>

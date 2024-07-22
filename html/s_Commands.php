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

        .container-fluid {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            background-color: #f0f0f0;
            text-align: center;
        }

        .countdown {
            font-size: 3rem;
            margin: 20px 0;
        }

        .title {
            font-size: 1.5rem;
            margin-bottom: 20px;
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
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none"
                        href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
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
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <!-- User Profile-->
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
                <!-- End Sidebar navigation -->
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
            <div class="container-fluid">
                <div class="title" id="title">Time Remaining to order your Lunch</div>
                <div class="countdown" id="countdown"></div>
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
    <script>
        function updateCountdown() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const seconds = now.getSeconds();

            let targetHour, targetMinute, targetSecond, titleText;

            if (hours < 12) {
                // Countdown to 12:00
                targetHour = 12;
                targetMinute = 0;
                targetSecond = 0;
                titleText = "Time Remaining to order your Lunch";
            } else if (hours >= 12 && hours < 15) {
                // Between 12:00 and 15:00
                document.getElementById('title').textContent = "Hello World";
                document.getElementById('countdown').textContent = "";
                return;
            } else if (hours >= 15) {
                // Countdown to 12:00 the next day
                targetHour = 12 + 24; // 12:00 the next day
                targetMinute = 0;
                targetSecond = 0;
                titleText = "Time Remaining to order your Lunch";
            }

            const targetTime = new Date();
            targetTime.setHours(targetHour, targetMinute, targetSecond, 0);

            const remainingTime = targetTime - now;

            if (remainingTime <= 0) {
                document.getElementById('title').textContent = "Hello World";
                document.getElementById('countdown').textContent = "";
                return;
            }

            const remainingHours = Math.floor(remainingTime / (1000 * 60 * 60));
            const remainingMinutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            const remainingSeconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            document.getElementById('title').textContent = titleText;
            document.getElementById('countdown').textContent = `${remainingHours.toString().padStart(2, '0')}:${remainingMinutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        setInterval(updateCountdown, 1000);
    </script>
</body>

</html>

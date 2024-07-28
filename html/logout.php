<!DOCTYPE html>
<html dir="ltr">
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
    </style>
  </head>
  <body>
    <div class="main-wrapper" class="authentication-page">
      <div
        class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
          <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
              <div class="card mb-0">
                <div class="card-body">
                  <a href="./dashboard.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                    <b class="logo-icon">
                      <img src="plugins/images/logo-icon.png" alt="homepage" />
                    </b>
                    <span class="logo-text">
                      <img src="plugins/images/logo-text.png" alt="homepage" />
                    </span>
                  </a>
                  <p class="text-center text-muted font-14">Your Fancy Restaurant</p>
                  <form method="POST" action="login.php">
                    <div class="mb-3">
                      <label for="exampleInputEmail1" class="form-label">Username</label>
                      <input type="text" class="form-control" id="exampleInputEmail1" name="name" aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-4">
                      <label for="exampleInputPassword1" class="form-label">Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                      <div class="form-check mb-0">
                        <input class="form-check-input danger" type="checkbox" value="" id="flexCheckChecked">
                        <label class="form-check-label text-dark mb-0" for="flexCheckChecked">
                          Remember this Device
                        </label>
                      </div>
                      <a class="text-danger fw-bolder font-14" href="./dashboard.php">Forgot Password ?</a>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 py-8 mb-4 rounded-2 font-14">Sign In</button>
                  </form>
                  <?php
                  session_start();
                  if (isset($_SESSION['error'])) {
                      echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
                      unset($_SESSION['error']);
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      $(".preloader").fadeOut();
    </script>
  </body>
</html>

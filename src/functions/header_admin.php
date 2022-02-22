<?php

session_start();

$master_api_key = "accessapi1234";
$api_weblink = "http://localhost";

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>moviedroid Betamax</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
  <link rel="stylesheet" href="../css/ui.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=RocknRoll+One&display=swap" rel="stylesheet">
  <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"></script>


  <script>

    $(document).ready(function () {

      // Check for click events on the navbar burger icon
      $(".navbar-burger").click(function () {

        // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");

      });
    });
  </script>
</head>
<?php
if(isset($_SESSION['userId'])) {
    if($_SESSION['accountType'] == 'Admin'){
        //echo '<p>Admin Stuff</p>';
    }
    if($_SESSION['accountType'] == 'User'){
        //echo '<p>User Stuff</p>';
    }
}
?>
<nav class="navbar is-transparent navbar-add">
  <div class="container is-max-desktop">
    <div class="navbar-brand">
      <div class="navbar-item">
        <span class="iconify logo l-icon" data-icon="mdi:robot-excited-outline" data-inline="false"></span>
        <h1 class="logo">moviedroid</h1>
      </div>
      <span class="navbar-burger has-text-light" data-target="navbarMenuHeroA">
        <span></span>
        <span></span>
        <span></span>
      </span>
    </div>
    <div id="navbarMenuHeroA" class="navbar-menu navbar-add">
      <div class="navbar-end">
        <a class="navbar-item my-navbar" href="../index.php">
          Home
        </a>
        <a class="navbar-item my-navbar" href="../movies.php">
          Movies
        </a>
        <div class="navbar-item has-dropdown is-hoverable my-navbar">
          <a class="navbar-link is-arrowless my-navbar" id="opener">
            About
          </a>
          <div class="navbar-dropdown">
            <a class="navbar-item">
              Credits
            </a>
            <a class="navbar-item">
              TMDB
            </a>
            <a class="navbar-item">
              Contact
            </a>
            <hr class="navbar-divider">
            <div class="navbar-item">
              Version 1.0.2
            </div>
          </div>
        </div>
        <span class="navbar-item">
          <a class="my-nav-icon" href="#">
            <span class="icon">
              <i class="iconify" data-icon="fa-solid:search" aria-hidden="true"></i>
            </span>
          </a>
        </span>

        <?php
          if(isset($_SESSION['userId'])) {
            echo '<span class="navbar-item">
                  <form action="../functions/user_setup.php" method="post">
                    <button type="submit" name="logout-submit" class="button is-success">Logout</button>
                  </form>
                  </span>';

            if($_SESSION['accountType'] == 'Admin'){
              echo '<span class="navbar-item">
                    <a class="button is-danger" href="../admin/index.php" title="Admin">Admin</a>
                    </span>';
            }
            if($_SESSION['accountType'] == 'User'){
              echo '<span class="navbar-item">
                    <a class="button is-danger" href="../dashboard/myprofile.php" title="Dashboard">Dashboard</a>
                    </span>';
            }

            //print_r($_SESSION['userName']);
            //print_r($_SESSION['accountType']);
            /**
            echo '<span class="navbar-item">
                    <a class="button is-success is-outlined is-rounded" href="../functions/user_setup.php">
                      <span class="icon">
                        <i class="iconify" data-icon="fa-solid:user-circle" aria-hidden="true"></i>
                      </span>
                      <span>Logout</span>
                    </a>
                  </span>';
            */
          } else {
            echo '<span class="navbar-item">
                    <a class="button is-success is-outlined" href="./login.php">
                      <span class="icon">
                        <i class="iconify" data-icon="fa-solid:user-circle" aria-hidden="true"></i>
                      </span>
                      <span>Login</span>
                    </a>
                  </span>
                  <span class="navbar-item">
                    <a class="button is-success" href="./signup.php">
                      <span class="icon has-text-white">
                        <i class="iconify" data-icon="fa-solid:pen" aria-hidden="true"></i>
                      </span>
                      <span>Sign Up</span>
                    </a>
                  </span>';
          }
        ?>

      </div>
    </div>
  </div>
</nav>

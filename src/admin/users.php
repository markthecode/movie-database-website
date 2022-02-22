<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'Admin'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}
  
  $username = $_SESSION['userName'];  

  $filename="{$api_weblink}/REST/users_api.php?api_key=accessapi1234&all";
   
  $filestring = file_get_contents($filename);
  //print_r($filestring); 
  $arrayjson = json_decode($filestring, true);


?>
<section>
    <div class="container is-max-desktop">
        <div class="columns my-subtitle">
            <div class="column">
                <h1 class="title-font">Welcome, <?php echo $username; ?></h1>
                <hr class="line2">
            </div>
        </div>
    </div>
</section>

<!-- USER SAVED MOVIE LIST WITH FILTERING-->
<section>
    <div class="container is-max-desktop">
        <div class="columns">
            <div class="column is-3">
                <!-- SIDE MENU -->
                <aside class="menu">
                    <p class="menu-label has-text-white">
                        General
                    </p>
                    <ul class="menu-list">
                        <li><a class="my-user-btn" href="index.php">Home</a></li>
                        <li><a class="my-user-btn active" href="users.php">Update Users</a></li>
                        <li><a class="my-user-btn" href="editmovies.php">View Movies</a></li>
                    </ul>
                </aside>
            </div>

            <div class="column">

                <div class="columns my-subtitle">
                    <div class="column">
                        <h3 class="title-font">User Accounts</h3>
                        <hr class="line1">
                    </div>
                </div>
                <div class="columns is-desktop is-multiline">
                   
                        <?php 
                               foreach($arrayjson as $key => $val){
                                  $userId = $val['userId'];
                                  $firstName = $val["firstName"];
                                  $lastName = $val["lastName"];
                                  $userName = $val["userName"];
                                  $userEmail = $val["userEmail"];
                              
                                  echo '<div class="card block is-one-fifth m-5">
                                          <header class="card-header">
                                            <p class="card-header-title">
                                             '. $firstName . ' ' . $lastName . '
                                            </p>  
                                          </header>
                                          <div class="card-content">
                                            <div class="content">
                                              
                                              <p>'.$userName.'</p>
                                              <p>'.$userEmail.'</p>
                                              
                                              <br>
                                              
                                            </div>
                                          </div>
                                          <footer class="card-footer">
                                            <a href="user_details.php?keyid='.$userId.'" class="card-footer-item">View</a>
                                            <a href="edituser.php?keyid='.$userId.'" class="card-footer-item">Edit</a>
                                            <a href="process.php?keyid='.$userId.'" class="card-footer-item">Delete</a>
                                          </footer>
                                        </div>';

                               }                              
                            ?>
                  
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="columns">
            <div class="column">
                <br><br><br>
            </div>
        </div>
    </div>
</section>
<?php

require "../functions/footer.php";

?>
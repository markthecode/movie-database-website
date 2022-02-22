<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'Admin'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}
  
  $username = $_SESSION['userName'];  

  $userId = $_GET['keyid'];
    
  $filename="{$api_weblink}/REST/users_api.php?api_key=accessapi1234&userId={$userId}";
   
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
                <div class="columns">
                    <div class="column">
                   
                        <?php 
                               foreach($arrayjson as $key => $val){
                                  
                                  $firstName = $val["firstName"];
                                  $lastName = $val["lastName"];
                                  $userName = $val["userName"];
                                  $userEmail = $val["userEmail"];
                                  $memberType = $val["memberType"];
                                  $accountEmail = $val["accountEmail"];
                                  $accountLevel = $val["accountLevel"];
                                  $accountActive = $val["accountActive"];
                              
                                   echo '<article class="panel is-info box">
                                                <p class="panel-heading">' .$firstName . ' ' . $lastName . '</p>
                                            <div class="panel-block box">
                                            <p class="control has-icons-left">
                                            <strong>Username : </strong> '. $userName .'
                                            </p>
                                            <p class="control has-icons-left">
                                            <strong>Email : </strong> '. $userEmail .'
                                            </p>
                                            </div>
                                            <a class="panel-block is-active">
                                            <strong>MemberType : </strong> ' . $memberType . '
                                            </a>
                                            <a class="panel-block">
                                            <strong>Account Email : </strong> ' . $accountEmail . '
                                            </a>
                                            <a class="panel-block">
                                            <strong>Account Level : </strong> ' . $accountLevel . '
                                            </a>
                                            <a class="panel-block">
                                            <strong>Account Active : </strong> '; 

                                            if($accountActive != 1) {
                                                echo "<button class='button is-danger m-2'>Not Active</button>";
                                            } else {
                                                
                                                echo "<button class='button is-success m-2'>Active</button>";
                                            }
                                    echo   '
                                            </a>
                                            <footer class="card-footer">
                                            <a href="edituser.php?keyid='.$userId.'" class="card-footer-item">Edit</a>
                                            <a href="process.php?keyid='.$userId.'" class="card-footer-item">Delete</a>
                                            </footer>
                                            </article>';


                                   

                               }                              
                            ?>
                    </div>
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
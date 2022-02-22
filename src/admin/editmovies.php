<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'Admin'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}
  
  $username = $_SESSION['userName']; 
  
  $userId = $_SESSION['userId'];
  
  $apidata="{$api_weblink}/REST/users_api.php?api_key={$master_api_key}&userId={$userId}";
     
  $filestring = file_get_contents($apidata);
  //print_r($filestring); 
  $data_array = json_decode($filestring, true);
  
  //var_dump($data_array = json_decode($filestring, true));

  foreach($data_array as $key => $val){
                                  
    $firstName = $val["firstName"];
    $lastName = $val["lastName"];
    $userName = $val["userName"];
    $userEmail = $val["userEmail"];
  }

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
                        <li><a class="my-user-btn" href="users.php">Update Users</a></li>
                        <li><a class="my-user-btn active" href="editmovies.php">View Movies</a></li>
                    </ul>
                    
                </aside>
            </div>

            <div class="column">

                <div class="columns my-subtitle">
                    <div class="column">
                        <h3 class="title-font">Admin Area</h3>
                        <hr class="line1">
                    </div>
                </div>
                <div class="columns">
                    <div class="column">

                        
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
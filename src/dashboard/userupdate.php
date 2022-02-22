<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}
  
  $username = $_SESSION['userName']; 
  $userId = $_SESSION['userId']; 

  $apidata="{$api_weblink}/REST/users_api.php?api_key=accessapi1234&userId={$userId}";
     
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
/// Genre for Search
$genreapi = "{$api_weblink}/REST/genres_api.php?api_key={$master_api_key}&all";

$genrestring = file_get_contents($genreapi);
//print_r($filestring); 
$genre_array = json_decode($genrestring, true);

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
                        <li><a class="my-user-btn" href="myprofile.php">My Profile</a></li>
                        <li><a class="my-user-btn active" href="userupdate.php">Update Account</a></li>
                        <li><a class="my-user-btn" href="mycollection.php">My Collection</a></li>
                        <li><a class="my-user-btn" href="mustseemovies.php">Must See Movies <span class="iconify"
                                    data-icon="bx:bxs-camera-movie" data-inline="false"></span></a></li>
                        <li><a class="my-user-btn" href="myfavourites.php">My Favourites <span class="iconify"
                                    data-icon="ant-design:heart-filled" data-inline="false"></span></a></li>
                    </ul>
                    <p class="menu-label has-text-white">
                        Sort
                    </p>
                    <div class="control is-expanded">
                        <div class="select is-fullwidth is-success is-small">
                            <select>
                                <option>Popularity Descending</option>
                                <option>Popularity Ascending</option>
                                <option>Rating Descending</option>
                                <option>Rating Ascending</option>
                                <option>Title (A-Z)</option>
                            </select>
                        </div>
                    </div>

                    <p class="menu-label has-text-white">
                        Filter
                    </p>
                    <div class="field">
                        <div class="control has-icons-right">
                            <input class="input is-success is-small" type="text" placeholder="Text input"
                                value="Filter by keywords ...">
                            <span class="icon is-small is-right">
                                <i class="iconify" data-icon="fa-solid:search"></i>
                            </span>
                        </div>
                    </div>
                    <p class="menu-label has-text-white">
                        Genres
                    </p>
                    <div class="control has-text-white">
                    <?php 
 
                        foreach($genre_array as $key => $val){
                            $genre = $val["name"];
                        echo "<label class='checkbox my-checkbox m-1'><input type='checkbox'> {$genre} </label>";

                        }
       
                    ?>
                    </div>
                    <p class="menu-label has-text-white">
                        Age Rating
                    </p>
                    <div class="control has-text-white">
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> U </label>
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> PG </label>
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> 12A </label>
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> 12 </label>
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> 15 </label>
                        <label class="checkbox my-checkbox m-1"><input type="checkbox"> 18 </label>
                    </div>
                    <p class="menu-label has-text-white">

                    </p>
                    <div class="control">
                        <a class="button is-danger is-small" href="searchresult.html">
                            <span class="icon has-text-white">
                                <i class="iconify" data-icon="mdi:robot-excited-outline" aria-hidden="true"></i>
                            </span>
                            <span>SEARCH</span>
                        </a>
                        <a class="button is-success is-small" href="searchresult.html"
                            title="Find me something to watch Harry">I'M FEELING LUCKY</a>

                    </div>
                </aside>
            </div>

            <div class="column">
                <div class="columns my-subtitle">
                    <div class="column">
                        <h3 class="title-font">Update Your Account</h3>
                        <hr class="line1">
                    </div>
                </div>
                <div class="columns is-centered">
                    <div class="column is-8">
                        <form class="box" action="../functions/user_setup.php" method="post">
                            <?php
                                if(isset($_GET['error'])) {
                                    if($_GET['error'] == "emptyfields") {
                                        echo '<p><strong>Fill in all fields</strong><p>';
                                    $emptyfields = "is-danger";
                                    }    
                                } else if (isset($_GET['update'])) {
                                
                                    if ($_GET['update'] == "success") {
                                    echo '<p><strong>Success Account Updated!</strong></p>';
                                    }
                                }
                            ?>
                            <p>Update your account</p>
                            <!--
                            <div class="field">
                                <label class="label">Username</label>
                                <div class="control">
                                    <input class="input <?php //echo $emptyfields; ?>" type="text" name="username"
                                        placeholder="Username">
                                </div>
                            </div>
                            -->
                            <div class="field">
                                <label class="label">Name</label>
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="text" name="firstname"
                                        placeholder="First Name" <?php echo "value = ' ".$firstName."'" ?>>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="text" name="lastname"
                                        placeholder="Last Name" <?php echo "value = ' ".$lastName."'" ?>>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="email" name="email"
                                        placeholder="e.g. alex@example.com" <?php echo "value = ' ".$userEmail."'" ?>>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Password</label>
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="password" name="pwd"
                                        placeholder="********">
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="password" name="pwd-repeat"
                                        placeholder="Confirm Password">
                                </div>
                            </div>

                            <button class="button is-success" type="submit" name="update-submit">Update Account</button>
                        </form>
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
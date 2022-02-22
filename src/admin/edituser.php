<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'Admin'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}
  
  $username = $_SESSION['userName'];  

  $userId = $_GET['keyid'];
  $movieId = $GET['movieid'];
    
  $filename="{$api_weblink}/REST/users_api.php?api_key=accessapi1234&userId={$userId}";
   
  $filestring = file_get_contents($filename);
  //print_r($filestring); 
  $arrayjson = json_decode($filestring, true);

  foreach($arrayjson as $key => $val){
                                  
    $firstName = $val["firstName"];
    $lastName = $val["lastName"];
    $userName = $val["userName"];
    $userEmail = $val["userEmail"];
    $memberType = $val["memberType"];
    $accountEmail = $val["accountEmail"];
    $accountLevel = $val["accountLevel"];
    $accountActive = $val["accountActive"];

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
                    echo '<article class="panel is-info box">
                            <p class="panel-heading">Update Acount for ' .$firstName . ' ' . $lastName . '</p>
                                <form class="box" action="#" method="post">';
                            
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
                            
                            <div class='field'>
                            <label class="label">Membership Type</label>
                                <div class='control'>
                                <div class="select">
                                     <select id='membershipType' name='membershipType'>
                                        <option value='1'>Adult</option>
                                        <option value='2'>Teen</option>
                                        <option value='3'>Child</option>
                                     </select> 
                                   </div>
                                 </div>
                            </div>
                            <div class="field">
                                <label class="label">Account Email</label>
                                <div class="control">
                                    <input class="input <?php echo $emptyfields; ?>" type="email" name="email"
                                        placeholder="e.g. alex@example.com" <?php echo "value = ' ".$userEmail."'" ?>>
                                </div>
                            </div>
                            <div class='field'>
                            <label class="label">Account Level</label>
                                <div class='control'>
                                <div class="select">
                                     <select id='membershipType' name='membershipType'>
                                        <option value='1'>Admin</option>
                                        <option value='2'>Moderator</option>
                                        <option value='3'>User</option>
                                        <option value='3'>Developer</option>
                                     </select> 
                                   </div>
                                 </div>
                            </div>
                            <div class="field">
                                <label class="label">Create API Key</label>
                                <div class="control">
                                    <input class="input" type="text" name="api_key"
                                        placeholder="Create API Key" >
                                </div>
                            </div>
                            <div class='field'>
                            <label class="label">Account Level</label>
                            <div class="control">
                                <label class="radio">
                                    <input type="radio" name="active">
                                        Active
                                    </label>
                                <label class="radio">
                                    <input type="radio" name="notactive">
                                        Not Active
                                    </label>
                                </div>
                            </div>

                            <button class="button is-success" type="submit" name="update-submit">Update User</button>
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
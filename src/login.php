<?php

require "./functions/header.php";

?>
<section>
    <div class="container">
        <div class="columns">
            <div class="column"><br></div>
        </div>
    </div>
</section>
<section>
    <div class="container is-max-desktop">
        <div class="columns is-centered">
            <div class="column is-4">
                <div class="card dirty-harry-search">
                    <div class="card-content">
                      <p class="title">
                        “Do you feel lucky punk!”
                      </p>
                      <p class="subtitle">
                        Dirty Harry
                      </p>
                      <!-- <a class="button is-danger" href="searchresult.html" title="Find me something to watch Harry">I'M FEELING LUCKY</a> -->
                    </div>
                  </div>
            </div>
            <div class="column is-6">
            <?php
                $printerror = "";
                $emptyfields = "";

                if(isset($_GET['error'])) {
                    if($_GET['error'] == "emptyfields") {
                        $printerror = '<p>Fill in all fields</p>';
                        $emptyfields = "is-danger";

                        } else if ($_GET['error'] == "nouser") {
                            $printerror = '<p>No Account Found, please Sign Up</p>
                                   <div class="field">
                                        <p class="control p-2 m-2">
                                            <a class="button is-success" href="signup.php">
                                                <span class="icon has-text-white">
                                                    <i class="iconify" data-icon="fa-solid:pen" aria-hidden="true"></i>
                                                </span>
                                                <span>Sign Up</span>
                                            </a>
                                     </p>
                                    </div>';
                        } else if($_GET['error'] == "loginerror") {
                            $printerror = '<p>Your Username or Password is wrong.</p>';
                            $emptyfields = "is-danger";

                    }
                } else if(!empty(($_GET['login']))) {
                    if (!empty($_GET['login'] == "success")) {
                    $printsuccess = '<p>Success! Your Logged into your account.</p>';
                    }
                } 
                if(isset($_SESSION['userId'])) {
                    echo '<form action="./functions/user_setup.php" method="post" class="box">'.$printsuccess.'
                            <button type="submit" name="logout-submit" class="button is-success p-2 m-2">Logout</button>';
                          

                    //if(isset($_SESSION['userId'])) {
                        if($_SESSION['accountType'] == 'Admin'){
                            //echo '<p>Admin Stuff</p>';
                            echo '<a class="button is-danger p-2 m-2" href="./admin/index.php" title="Admin">Admin</a>';

                        } 
                        if($_SESSION['accountType'] == 'User'){
                            //echo '<p>User Stuff</p>';
                            echo '<a class="button is-danger p-2 m-2" href="./dashboard/myprofile.php" title="Dashboard">Dashboard</a>';
                        } 
                    //}

                    echo '</form>';
                    
                } else {
                    echo '<form action="./functions/user_setup.php" method="post" class="box">'.$printerror.'
                            <div class="field">
                                <p class="control">
                                    <input type="text" class="input '.$emptyfields.'" name="username" placeholder="Username">
                                </p>
                            </div>
                            <div class="field">
                                <p class="control">
                                    <input type="password" class="input '.$emptyfields.'" name="pwd" placeholder="Password">
                                </p>
                            </div>
                            <div class="field">
                                <p class="control">
                                    <button type="submit" name="login-submit" class="button is-success">Login</button>
                                </p>
                            </div>
                          </form>';     
                }
            ?>   
            </div>
        </div>
    </div>
</section>

<?php

require "./functions/footer.php";

?>
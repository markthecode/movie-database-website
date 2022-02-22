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
                <form class="box" action="./functions/user_setup.php" method="post">
                    <?php
                    if(isset($_GET['error'])) {
                        if($_GET['error'] == "emptyfields") {
                            echo '<p><strong>Fill in all fields</strong></p>';
                            $emptyfields = "is-danger";
                        } else if($_GET['error'] == "usertaken") {
                          echo '<p><strong>Sorry! Username is taken</strong></p>';
                          $usertaken = "is-danger";
                        } else if($_GET['error'] == "invalidusername") {
                          echo '<p><strong>Sorry! Invalid Username</strong></p>';
                          $usertaken = "is-danger";
                        } else if($_GET['error'] == "passwordcheck") {
                          echo '<p><strong>Please check you passwords match</strong></p>';
                          $passwordcheck = "is-danger";
                        }   
                    } else if (isset($_GET['signup'])){ 
                        if ($_GET['signup'] == "success") {
                        echo '<p><strong>Success Sign up!</strong></p>';
                        }
                    }
                    
                    ?>
                    <p>Create an account and save your movies you own into a Collection and when you can't decide what to watch why not let Harry find you a movie!</p>
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control">
                          <input class="input <?php echo $usertaken; ?>" type="text" name="username" <?php if(isset($_GET['username'])) { echo "value = '{$_GET['username']}'";}?>placeholder="Create a Username">
                        </div>
                    </div>
                    <div class="field">
                      <label class="label">Name</label>
                      <div class="control">
                        <input class="input <?php echo $emptyfields; ?>" type="text" name="firstname" <?php if(isset($_GET['firstname'])) { echo "value = '{$_GET['firstname']}'";}?>placeholder="First Name">
                      </div>
                    </div>
                    <div class="field">
                        <div class="control">
                        <input class="input <?php echo $emptyfields; ?>" type="text" name="lastname" <?php if(isset($_GET['lastname'])) { echo "value = '{$_GET['lastname']}'";}?>placeholder="Last Name">
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Email</label>
                      <div class="control">
                        <input class="input <?php echo $emptyfields; ?>" type="email" name="email" <?php if(isset($_GET['email'])) { echo "value = '{$_GET['email']}'";}?>placeholder="e.g. alex@example.com">
                      </div>
                    </div>
                    <div class="field">
                      <label class="label">Password</label>
                      <div class="control">
                        <input class="input <?php echo $emptyfields; echo $passwordcheck;?>" type="password" name="pwd" placeholder="********">
                      </div>
                    </div>
                    <div class="field">
                        <div class="control">
                          <input class="input <?php echo $emptyfields; echo $passwordcheck;?>" type="password" name="pwd-repeat" placeholder="Confirm Password">
                        </div>
                      </div>
                  
                    <button class="button is-success" type="submit" name="signup-submit">Sign Up</button> 
                </form>
            </div>
        </div>
    </div>
</section>
<?php

require "./functions/footer.php";

?>
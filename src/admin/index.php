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
                        <li><a class="my-user-btn active" href="index.php">Home</a></li>
                        <li><a class="my-user-btn" href="users.php">Update Users</a></li>
                        <li><a class="my-user-btn" href="editmovies.php">View Movies</a></li>
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

                        <article class="panel is-success box">
                            <p class="panel-heading">
                                <?php echo $firstName . " " . $lastName; ?>
                            </p>
                            <div class="panel-block box">
                                <p class="control has-icons-left">
                                    <?php echo "<strong>Username:</strong> ". $userName; ?>
                                </p>
                                <p class="control has-icons-left">
                                    <?php echo "<strong>Email:</strong> ". $userEmail; ?>
                                </p>
                            </div>
                            <a class="panel-block is-active">
                                <h2><strong>Total watched movies :</strong> 10,3847</h2>
                            </a>
                            <a class="panel-block">
                                <h2><strong>Most liked Movie : </strong> Star Wars</h2>
                            </a>
                            <a class="panel-block">
                                <h2><strong>Most disliked movies : </strong> Dancer in the Dark</h2>
                            </a>
                            <a class="panel-block">
                                <h2><strong>Total movies : </strong>27,647</h2>
                            </a>
                            <a class="panel-block">
                                <h1><strong>Your Stats</strong></h1>
                            </a>
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </article>
                    </div> 
                </div>
            </div>

            <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Watched', 'Likes', 'Dislikes', 'Favourites', 'Total Movies'],
                    datasets: [{
                        label: 'My Movie Stats',
                        data: [103847, 250, 775, 2340, 27647
                        ],
                        backgroundColor: [
                            'rgb(62, 196, 109)',
                            'rgb(240, 58, 95)',
                            'rgb(234, 147, 32)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(255, 99, 132)',
                        ],
                        borderWidth: 0
                    }]
                },
            }, );
            </script>
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
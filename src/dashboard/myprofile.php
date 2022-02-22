<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User'){
        
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

  $userstats="{$api_weblink}/REST/users_movies_api.php?api_key={$master_api_key}&userid={$userId}&stats";
  $statstring = file_get_contents($userstats);
  $stats_array = json_decode($statstring, true);
  //var_dump($stats_array);

  $totalWatched =  $stats_array['stats'][0]['totalWatched'];
  $totalLikes =  $stats_array['stats'][1]['totalLikes'];
  $totalDislikes = $stats_array['stats'][2]['totalDislikes'];
  $totalFavourites = $stats_array['stats'][3]['totalFavourites'];
  $totalMovies = $stats_array['stats'][4]['totalMovies'];
  
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
                        <li><a class="my-user-btn active" href="myprofile.php">My Profile</a></li>
                        <li><a class="my-user-btn" href="userupdate.php">Update Account</a></li>
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
                        <a class="button is-danger is-small" href="#">
                            <span class="icon has-text-white">
                                <i class="iconify" data-icon="mdi:robot-excited-outline" aria-hidden="true"></i>
                            </span>
                            <span>SEARCH</span>
                        </a>
                        <a class="button is-success is-small" href="#" title="Find me something to watch Harry">I'M
                            FEELING LUCKY</a>

                    </div>
                </aside>
            </div>

            <div class="column">
                <div class="columns my-subtitle">
                    <div class="column">
                        <h3 class="title-font">My Profile</h3>
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
                                <h2><?php echo "<strong>Total watched movies :</strong> ".$totalWatched; ?></h2>
                            </a>
                            <a class="panel-block">
                                <h2><?php echo "<strong>Total liked movies : </strong>".$totalLikes; ?></h2>
                            </a>
                            <a class="panel-block">
                                <h2><?php echo "<strong>Total disliked movies : </strong>".$totalDislikes; ?></h2>
                            </a>
                            <a class="panel-block">
                                <h2><?php echo "<strong>Total favourite movies : </strong>".$totalFavourites; ?></h2>
                            </a>
                            <a class="panel-block">
                                <h2><?php echo "<strong>Total movies : </strong>".$totalMovies; ?></h2>
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
                        data: [<?php echo $totalWatched; ?>, <?php echo $totalLikes; ?>,
                            <?php echo $totalDislikes; ?>, <?php echo $totalFavourites; ?>,
                            <?php echo $totalMovies; ?>
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
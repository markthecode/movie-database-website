<?php

require "../functions/header_admin.php";

if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User'){
        
} else {
    header("Location: ../login.php?error=loginerror");
    exit();
}

$username = $_SESSION['userName'];  
$id = $_SESSION['userId'];

$apidata="{$api_weblink}/REST/users_movies_api.php?api_key=accessapi1234&all&userid={$id}";
   
$filestring = file_get_contents($apidata);
//print_r($filestring); 
$data_array = json_decode($filestring, true);

//var_dump($data_array = json_decode($filestring, true));

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
                        <li><a class="my-user-btn" href="userupdate.php">Update Account</a></li>
                        <li><a class="my-user-btn" href="mycollection.php">My Collection</a></li>
                        <li><a class="my-user-btn" href="mustseemovies.php">Must See Movies <span class="iconify"
                                    data-icon="bx:bxs-camera-movie" data-inline="false"></span></a></li>
                        <li><a class="my-user-btn active" href="myfavourites.php">My Favourites <span class="iconify"
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
                        <h3 class="title-font">My Favourite Movies</h3>
                        <hr class="line1">
                    </div>
                </div>
                <div class="columns is-desktop is-multiline is-centered">
                <?php
                
                  if(!empty($data_array['message'])){
  
                    echo "<article class='message is-danger'>
                    <div class='message-body'>
                    <p><strong>{$data_array['message']}</strong></p>
                    </div>
                    </article>";
      
                    //echo $data_array['message'];                

                  } else {

                    foreach($data_array as $key => $val){
        
                    $movieId = $val["movieId"];
                    $title = $val["title"];
                    $tagline = $val["tagline"];
                    $overview = $val["overview"];
                    $poster_path = $val["poster_path"];
                    $thumbnail = $val["thumbnail"];
                    $release_date = $val["release_date"];
                    $revenue = $val["revenue"];
                    $runtime = $val["runtime"];
                    $rating = $val["rating"];
                    $userRating = $val["userRating"];
                    $watchedMovie = $val["watchedMovie"];
                    $likeMovie = $val["likeMovie"];
                    $dislikeMovie = $val["dislikeMovie"];
                    $favouriteMovie = $val["favouriteMovie"];
                    
                    //echo "<p>{$favouriteMovie}</p>";
                    if($favouriteMovie == 1) {


                    echo "
                        <div class='column is-one-third'>
                          <div class='card'>
                            <div class='card-image'>
                              <figure class='image is-2by3'>
                                <a href='../movie_details.php?id={$movieId}'>
                                  <img src='{$thumbnail}'
                                        title='{$title}'>
                                </a>
                              </figure>
                            </div>
                            <div class='card-content'>
                            <p class='title is-6'>
                            {$title}
                            </p>
                            <footer>";

                            if($rating < 1){
                              echo "<span class='iconify' data-icon='fa-solid:star-half' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating  == 1){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating > 1 &&  $rating < 2){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star-half' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating  == 2){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating > 2 &&  $rating < 3){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star-half' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating  == 3){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating > 3 &&  $rating < 4){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star-half' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating  == 4){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating > 4 &&  $rating < 5){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star-half' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else if($rating  == 5){
                              echo "<span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span class='iconify' data-icon='fa-solid:star' data-inline='false'></span>
                                    <span>{$rating}</span>";
                            } else {
                              
                            }

                            
                    echo "
                            </footer>
                            </div>
                            </div>
                            </div>
                            ";
                          
                            
                          }
                        }
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
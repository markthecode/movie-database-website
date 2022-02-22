<?php

require "./functions/header.php";

$movieapi = "{$api_weblink}/REST/movies_api_v2.php?api_key=accessapi1234&limit=5";
$moviestring = file_get_contents($movieapi);
$data_array = json_decode($moviestring, true);

$filename="{$api_weblink}/REST/genres_api.php?api_key=accessapi1234&all";
   
$filestring = file_get_contents($filename);
$arrayjson = json_decode($filestring, true);

?>
<section class="hero is-success is-medium hero-img">
    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container has-text-centered is-max-desktop">
            <form action="movies_results.php?search" method="post">

                <div class="columns is-mobile is-centered">
                    <div class="column is-8">
                        <div class="field">
                            <div class="control has-icons-right">
                                <input class="input is-success is-medium" name="search" type="input"
                                    placeholder="Search moviedriod">
                                <span class="icon is-small is-right">
                                    <i class="iconify" data-icon="fa-solid:search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="columns is-centered">
                    <div class="column is-4">
                        <div class="control is-expanded">
                            <div class="select is-fullwidth is-success is-medium">
                                <select id="genre" name="genre">
                                    <option value="0">Genre</option>
                                    <?php 
 
                  foreach($arrayjson as $key => $val){
                          $genre = $val["name"];
                          $genreId = $val["id"];
                          echo "<option value='{$genreId}'>{$genre}</option>";
                  }
                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="column is-2">
                        <div class="control is-expanded">
                            <div class="select is-fullwidth is-success is-medium">
                                <select id="rating" name="rating">
                                    <option value="0">Rating</option>
                                    <option value="1">1 STAR</option>
                                    <option value="2">2 STAR</option>
                                    <option value="3">3 STAR</option>
                                    <option value="4">4 STAR</option>
                                    <option value="5">5 STAR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <dic class="column is-2">
                        <div class="control">
                            <button class="button is-danger is-medium" type="submit"
                                name="search-submit">SEARCH</button>
                            <!-- <a class="button is-danger is-medium" href="searchresult.html">
              <span class="icon has-text-white">
                <i class="iconify" data-icon="mdi:robot-excited-outline" aria-hidden="true"></i>
              </span>
              <span>SEARCH</span>
            </a> -->
                        </div>
                    </dic>
                </div>
            </form>

            <?php $randomNumber = mt_rand(5, 2400); ?>
            <div class="columns is-mobile is-centered">
                <div class="column is-8">
                    <?php 
                    if(isset($_GET['error'])) {
                      if($_GET['error'] === "nomoviefound") {

                        echo "<article class='message is-danger'>
                                <div class='message-body'>
                                  <p><strong>Sorry, couldn't seem to find a movie! Please try again!</strong></p>
                                </div>
                              </article>";

                      }
                      if($_GET['error'] === "emptyfields") {

                        echo "<article class='message is-info'>
                                <div class='message-body'>
                                  <p><strong>Please enter a search term and try again!</strong></p>
                                </div>
                              </article>";
                      }
                    }
                    ?>
                    <form action="movie_details.php?id=<?php echo $randomNumber ?>" method="post">
                        <div class="control">
                            <button class="button is-success is-medium" type="submit" name="lucky-submit">MOVIE
                                RECOMMENDER</button>
                            <!-- <a class="button is-success is-medium" href="searchresult.html" title="I'm Feeling Lucky! Find me something to watch Harry">
              <span class="icon has-text-white">
                <i class="iconify" data-icon="bx:bxs-camera-movie" aria-hidden="true"></i>
              </span>
              <span>MOVIE RECOMMENDER</span>
            </a> -->
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>
<section>
    <div class="container is-max-desktop">
        <div class="columns my-subtitle">
            <div class="column">
                <h3 class="title-font">Popular Movies</h3>
                <hr class="line1">
            </div>
        </div>
        <div class="columns">
            <div class="columns is-desktop is-multiline is-centered">
                <?php

    
    /** 
    if($data_array['message']){

        echo "<article class='message is-danger'>
                <div class='message-body'>
                  <p><strong>{$data_array['message']}</strong></p>
                </div>
              </article>";
      */ 
    //} else {

        foreach($data_array['movies'] as $val){
                $id = $val["id"];
                $title = $val["title"];
                $poster_path = $val["poster_path"];
                $thumbnail = $val["thumbnail"];
                $rating = $val["rating"];

        echo "
        <div class='column is-one-quarter'>
          <div class='card'>
            <div class='card-image'>
              <figure class='image is-2by3'>
                <a href='movie_details.php?id={$id}'>
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
        </div>";
      }
    //}
    ?>

            </div>


        </div>
    </div>
    <div class="container">
        <div class="columns">
            <div class="column">

            </div>
        </div>
    </div>
</section>


<section>
    <main>
        <div class="container is-max-desktop">
            <?php
                if(isset($_SESSION['userId'])) {
                    echo '<p>You are Logged in!</p>';
                } else {
                    echo '<p>You are Logged out!</p>';
                }
            ?>


        </div>
    </main>
</section>
<?php

require "./functions/footer.php";

?>
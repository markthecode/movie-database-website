<?php

include "./functions/header.php";

$movieId = $_GET['id'];
if(isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}

$apidata="{$api_weblink}/REST/movies_api_v2.php?api_key=accessapi1234&id={$movieId}";
   
$filestring = file_get_contents($apidata);
//print_r($filestring); 
$data_array = json_decode($filestring, true);

if(!empty($data_array['message'])){

    header("Location: ./index.php?error=nomoviefound");
        exit();

} else {

    foreach($data_array as $key => $val){
        $id = $val["id"];
        $title = $val["title"];
        $tagline = $val["tagline"];
        $overview = $val["overview"];
        $poster_path = $val["poster_path"];
        $thumbnail = $val["thumbnail"];
        $genres = $val["genres"];
        $budget = $val["budget"];
        $popularity = $val["popularity"];
        $release_date = $val["release_date"];
        $revenue = $val["revenue"];
        $runtime = $val["runtime"];
        $rating = $val["rating"];
    }
}


$date = date_create_from_format('Y-n-j', $release_date);



if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User') {



    //when submit button is pressed by web user
    //perform the following PHP
    if(isset($_POST['usersubmit'])) {

        $movie = $_POST['movieId'];
        $user = $_POST['userId'];

        $userapidata="{$api_weblink}/REST/users_movies_api.php?api_key=accessapi1234&userid={$user}&movieid={$movie}";

        //$posteddata;

    if(!empty($_POST['likeMovie'])){
        $pvalue = $_POST['likeMovie'];

        $posteddata = http_build_query(array('likeMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }
    if(!empty($_POST['dislikeMovie'])){
        $pvalue = $_POST['dislikeMovie'];
     
        $posteddata = http_build_query(array('dislikeMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }
    if(!empty($_POST['favouriteMovie'])){
        $pvalue = $_POST['favouriteMovie'];
    
        $posteddata = http_build_query(array('favouriteMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }
    if(!empty($_POST['removeMovie'])){
        $pvalue = $_POST['removeMovie'];
    
        $posteddata = http_build_query(array('removeMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }
    if(!empty($_POST['userRating'])){
        $pvalue = $_POST['userRating'];
    
        $posteddata = http_build_query(array('userRating'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }
    if(!empty($_POST['watchedMovie'])){
        $pvalue = $_POST['watchedMovie'];
    
        $posteddata = http_build_query(array('watchedMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    } 
    if(!empty($_POST['addMovie'])){
        $pvalue = $_POST['addMovie'];
    
        $posteddata = http_build_query(array('addMovie'=>$pvalue));
        $opts = array(
            'http' => array(
                'method'=>'POST',
                'header'=>'Content-Type: application/x-www-form-urlencoded',
                'content'=>$posteddata
            )
        );
      
        $context = stream_context_create($opts);
    
        $result = file_get_contents($userapidata, false, $context);
    }

    $message_array = json_decode($result, true);

    $messagealert = $message_array['message'];
    
    }
}


?>

<section class="hero is-success is-medium search-hero-img">
    <!-- Hero content: will be in the middle -->
    <div class="hero-body">
        <div class="container is-max-desktop">
            <div class="columns">
                <div class='column is-5'>
                
                    <div class='card'>
                        <div class="card-image">
                            <figure class='image is-2by3'>
                                <img src='<?php echo $poster_path; ?>'
                                    title='<?php echo $title; ?>'>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="column">
                <?php
                if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User') {
                        if(!empty($messagealert)){

                            echo "<article class='message is-primary'>
                                    <div class='message-body'>
                                    <p><strong>{$messagealert}</strong></p>
                                    </div>
                                    </article>";

                        }
                }                   
                    ?>
                    <p class="title"><?php echo $title; ?></p>
                    <div class="box">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <p>
                                        <strong>Storyline</strong>
                                        <br>
                                        <?php echo $overview; ?>
                                    </p>
                                    
                                    

                                    <p><small><?php echo $runtime; ?> mins | <?php foreach($genres as $key => $val){echo $val['name'].', ';}?> | <?php echo date_format($date, 'jS F Y');?></small></p>
                                    <!-- <p><small><strong>Directed by</strong> Cathy Yan</small></p> -->
                                    <div>
                                            <?php 

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
                                            ?>

                            
                                </div>
                                <nav class="level">
                                    <div class="level-left">
                                    <?php 
                                    if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User') {

                                        
                                        /** 
                                        if(!empty($_GET['message'])) {

                                            echo "<article class='message is-danger'>
                                                  <div class='message-body'>
                                                    <p><strong>Sorry, couldn't seem to find a movie! Please try again!</strong></p>
                                                  </div>
                                                </article>";
                                  
                                        }
                                        */
                                        echo "<div class='buttons'>";
                                      
                                        //// ADD Movie to User Collection
                                        //<p><input type='submit' name='usersubmit' value='ADD'></p>
                                         echo "<form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                                <input type='hidden' id='addMove' name='addMovie' value='1'> 
                                                <button class='button is-primary p-2 m-2' type='submit' name='usersubmit' value='ADD'>ADD</button>                                          
                                                </form>";
                                        //// REMOVE Movie from User Collection
                                        // <p><input type='submit' name='usersubmit' value='REMOVE'></p>
                                        echo "<form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                              <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                              <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                              <input type='hidden' id='removeMove' name='removeMovie' value='1'>                                           
                                              <button class='button is-primary p-2 m-2'type='submit' name='usersubmit' value='REMOVE'>REMOVE</button>
                                              </form>";

                                        //// SEEN IT Movie
                                        echo "<form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                                <input type='hidden' id='watchedMovie' name='watchedMovie' value='1'> 
                                                <button class='button is-primary p-2 m-2'type='submit' name='usersubmit' value='SEEN IT'>SEEN IT</button>                                           
                                              </form>";

                                        //// RATING Movie
                                        echo "<form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                        <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                        <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                              <div class='field has-addons p-2 m-2 mt-4'>
                                                <div class='control is-expanded'>
                                                    <div class='select is-fullwidth'>
                                                    <select id='userRating' name='userRating'>
                                                        <option value='0'>Rating</option>
                                                        <option value='1'>1</option>
                                                        <option value='1.5'>1.5</option>
                                                        <option value='2'>2</option>
                                                        <option value='2.5'>2.5</option>
                                                        <option value='3'>3</option>
                                                        <option value='3.5'>3.5</option>
                                                        <option value='4'>4</option>
                                                        <option value='4.5'>4.5</option>
                                                        <option value='5'>5</option>
                                                    </select> 
                                                    </div>
                                                </div>
                                                <div class='control'>
                                                <button type='submit' class='button is-primary'name='usersubmit' value='USER RATING'>Choose</button>
                                                </div>
                                              </div>
                                              </form>";                                 
                                                
                                        

                                        echo "</div>";
                                    /**    
                                    echo " 
                                        <div class='level-item'>
                                            <a href='#' class='my-user-btn'
                                                title='ADD to My Collection'>
                                                <span class='icon'><i class='iconify' data-icon='akar-icons:circle-plus'
                                                        aria-hidden='true'></i></span>
                                                <span>ADD</span>
                                            </a>
                                        </div>
                                        <div class='level-item'>
                                            <a href='#' class='my-user-btn'
                                                title='I have seen it already'>
                                                <span class='icon'><i class='iconify'
                                                        data-icon='akar-icons:circle-check'
                                                        aria-hidden='true'></i></span>
                                                <span>SEEN IT</span>
                                            </a>
                                        </div>";
                                    */ 
                                    }
                                    ?>
                                    </div>
                                </nav>
                            </div>
                        </article>
                    </div>
                    <?php 

                        if(isset($_SESSION['userId']) && $_SESSION['accountType'] == 'User') {

                            //echo $_SESSION['userId'];

                            

                        echo "                    
                            <nav class='level'>
                                <div class='level-left'>
                                    ";
                        

                        //// LIKE Movie
                        echo "<div class='level-item'>
                                <form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                <input type='hidden' id='likeMovie' name='likeMovie' value='1'>
                                <div class='level-item'>
                                    <button class='button is-success my-rating-btn' title='Like It' type='submit' name='usersubmit'>
                                    <i class='iconify' data-icon='akar-icons:thumbs-up' aria-hidden='true'></i> Like</button>
                                </div>                                            
                                </form>
                              </div>";
                        // <p><input type='submit' name='usersubmit' value='Like'></p>
                        //// DISLIKE Movie
                        echo "<div class='level-item'>
                                <form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                <input type='hidden' id='dislikeMovie' name='dislikeMovie' value='1'>
                                <div class='level-item'>
                                    <button class='button is-danger my-rating-btn' title='Unlike It' type='submit' name='usersubmit'>
                                    <i class='iconify' data-icon='akar-icons:thumbs-down' aria-hidden='true'></i> Dislike</button>
                                </div>                                             
                                </form>
                              </div>";
                        //<p><input type='submit' name='usersubmit' value='Dislike'></p>

                        //// ADD Favourite Movie
                        echo "<div class='level-item'>
                                <form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                <input type='hidden' id='favouriteMovie' name='favouriteMovie' value='1'>                                             
                                
                                <div class='level-item'>
                                    <button class='button is-warning my-rating-btn' type='submit' name='usersubmit'
                                        title='ADD to My Favourites'><i class='iconify'
                                            data-icon='akar-icons:heart' aria-hidden='true'></i> Add Favourite</button>
                                </div>
                                </form>
                              </div>";
                        //<p><input type='submit' name='usersubmit' value='addfavourite'></p>

                        //// REMOVE Favourite Movie
                        echo "<div class='level-item'>
                                <form action='./movie_details.php?id={$movieId}' method='POST' name='form'>
                                <input type='hidden' id='userId' name='userId' value='{$userId}'>
                                <input type='hidden' id='movieId' name='movieId' value='{$movieId}'>
                                <input type='hidden' id='favouriteMovie' name='favouriteMovie' value='-1'>                                           
                                
                                <div class='level-item'>
                                    <button class='button is-warning my-rating-btn' type='submit' name='usersubmit'
                                        title='ADD to My Favourites'><i class='iconify'
                                            data-icon='akar-icons:heart' aria-hidden='true'></i> Remove Favourite</button>
                                </div>
                                </form>
                              </div>";

                        echo "          
                                    </div>
                                </nav>";
                       
                        }

                    ?>

                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="columns">
            <div class="column">
                <br><br><br>
            </div>
        </div>
    </div>
</section>

<?php

include "./functions/footer.php";

?>
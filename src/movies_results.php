<?php

include "./functions/header.php";

if(empty($_POST['search'])){

    header("Location: ./index.php?error=emptyfields");
    exit();

}

$search = $_POST['search'];
$genre = $_POST['genre'];
$rating = $_POST['rating'];
$resultLimit = 20;

//echo $search.' - '.$genre.' - '.$rating;

$apidata="{$api_weblink}/REST/movies_api_v2.php?api_key=accessapi1234&search={$search}&genre={$genre}&rating={$rating}&limit={$resultLimit}";
     

 
$filestring = file_get_contents($apidata);
 
$data_array = json_decode($filestring, true);

?>

<section>
  <div class="container is-max-desktop">
    <div class="columns my-subtitle">
      <div class="column">
        <h3 class="title-font">Movies</h3>
        <hr class="line1">
      </div>
    </div>

 

    <div class="columns is-desktop is-multiline is-centered">
    <?php

    
    /** 
    if($data_array['message']){

        echo "<article class='message is-danger'>
                <div class='message-body'>
                  <p><strong>{$data_array['message']}</strong></p>
                </div>
              </article>";

    } else {
    */  

        foreach($data_array['movies'] as $val){
                $id = $val["id"];
                $title = $val["title"];
                $poster_path = $val["poster_path"];
                $thumbnail = $val["thumbnail"];
                $rating = $val["rating"];

        echo "
        <div class='column is-3'>
          <div class='card'>
            <div class='card-image'>
              <figure class='image is-2by3'>
                <a href='./movie_details.php?id={$id}'>
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
   // }
    ?>

    </div>
  </div>
  <div class="container">
    <div class="columns">
      <div class="column">

      </div>
    </div>
  </div>
</section>


<?php

include "./functions/footer.php";

?>
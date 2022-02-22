<?php

include "./functions/header.php";

if (isset($_GET['page'])){
    $page = $_GET['page'];
    $apidata="{$api_weblink}/REST/movies_api_v2.php?api_key=accessapi1234&all&page={$page}";
} else {
    $apidata="{$api_weblink}/REST/movies_api_v2.php?api_key=accessapi1234&all";
}

 
$filestring = file_get_contents($apidata);
//print_r($filestring); 
$data_array = json_decode($filestring, true);
//print_r($data_array['pagination']);

//print_r($data_array['movies']);

$first = 1;
$prev =  $data_array['pagination'][0]['Prev'];
$next =  $data_array['pagination'][1]['Next'];
$last =  $data_array['pagination'][2]['Last'];
$middle = $next;
$middleminus = $middle-1;
$middleplus = $middle+1;

//var_dump(json_decode($filestring, true));
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
      <div class="column">
         <!-- PAGINATION -->
        <?php
            echo "
            <nav class='pagination is-right' role='navigation' aria-label='pagination'>";

            if($prev == "disabled") {
              echo "<a class='pagination-previous my-pag-btn' disabled>Previous</a>";
            } else {
              echo "<a class='pagination-previous my-pag-btn' href='./movies.php?page={$prev}'>Previous</a>";
            }
            if($next == "disabled") {
              echo "<a class='pagination-next my-pag-btn' disabled>Next page</a>";
            } else {
              echo "<a class='pagination-next my-pag-btn' href='./movies.php?page={$next}'>Next page</a>";
            }
              
            // aria-current='page'

            echo "
              <ul class='pagination-list'>";

              if($first != $middleminus){
                echo "
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$first}' href='./movies.php?page={$first}'>{$first}</a></li>
                <li><span class='pagination-ellipsis'>&hellip;</span></li>";
              }
              if($middle == "disabled" || $middle == $last){
                $middleminus = $last-3;
                $middle = $last-2;
                $middleplus = $last-1;

                echo "
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$middleminus}' href='./movies.php?page={$middleminus}'>{$middleminus}</a></li>
                <li><a class='pagination-link my-pag-link' aria-label='Page {$middle}' href='./movies.php?page={$middle}'>{$middle}</a></li>
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$middleplus}' href='./movies.php?page={$middleplus}'>{$middleplus}</a></li>";

              }  else {
                
                echo "
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$middleminus}' href='./movies.php?page={$middleminus}'>{$middleminus}</a></li>
                <li><a class='pagination-link my-pag-link' aria-label='Page {$middle}' href='./movies.php?page={$middle}'>{$middle}</a></li>
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$middleplus}' href='./movies.php?page={$middleplus}'>{$middleplus}</a></li>";
              }

            echo "
                <li><span class='pagination-ellipsis'>&hellip;</span></li>
                <li><a class='pagination-link my-pag-link' aria-label='Goto page {$last}' disabled>{$last}</a></li>
              </ul>
            </nav>";

        ?>
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

    } else {
    
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
    }
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
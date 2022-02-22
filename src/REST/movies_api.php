<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

$validrequest = array('Response'=>'valid request');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // echo "GET";

    /// Show All Movies
    if (isset($_GET['all']) && isset($_GET['api_key'])) {

        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            
            $sql = 'SELECT * FROM movies';
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $data_array = array();
            //$genre = getGenresData($conn, $movieid);
            $genre = array();
            
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'title' => $title,
                        'tagline' => $tagline,
                        'overview' => html_entity_decode($overview),
                        'poster_path' => $poster_path,
                        'backdrop_path' => $backdrop_path,
                        'thumbnail' => $thumbnail,
                        'genres' => $genre,
                        'budget' => $budget,
                        'popularity' => $popularity,
                        'release_date' => $release_date,
                        'revenue' => $revenue,
                        'runtime' => $runtime,
                        'rating' => $rating
                            );
          
                    // Push to "data"
                    array_push($data_array, $data_item);

                    //echo json_encode($row);
                }
                /** 
                foreach($data_array as $key => $val)
                {
                    $id = $val['id'];
                    $data_array[$key]['genres'] = $val[getGenresData($conn, $id)];
                }
                
                foreach($data_array AS $key => $val){
                    $id = $val['id'];
                    if( $key == "genres") {
                        $val = getGenresData($conn, $id);
                    }
                    //echo json_encode(getGenresData($conn, $id));
                }
                */
                http_response_code(200);
                echo json_encode($data_array);

            } else {
                // No Result
                echo json_encode(array('message' => 'No Movies Found'));
            }
        }  
      /// Show Movies by ID    
    } else if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $movieid = $_GET['id'];
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
     

        if ($numofrows > 0) {
            /// Get Movie Genres
            $sql = "SELECT * FROM `movie_genres` WHERE movieId = '$movieid';";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;
            $movie_genre_array = array();

            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                extract($row);

                $genreId_item = array('genreId' => $genreId);

                array_push($movie_genre_array, $genreId_item);

            }
        }

        //echo json_encode($movie_genre_array);
        $genre_array = array();

        $count = count($movie_genre_array);
        $loop = 0;

        foreach($movie_genre_array AS $key => $val){

            $genreId = $val['genreId'];
        
            //echo $genreId;

            $sql = "SELECT * FROM `genres` WHERE id = '$genreId'";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;
                   
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){
        
                    extract($row);
        
                    $genre_item = array('name' => $name);
          
                    // Push to "data"
                    array_push($genre_array, $genre_item);

                }
            }
        }
        //echo json_encode($genre_array);
            // GET Movie By Id   
            $sql = "SELECT * FROM movies WHERE movies.id = '$movieid';";
            /** 
            $sql = "SELECT movies.id, movies.title, movies.tagline, movies.overview, movies.poster_path, 
                            movies.backdrop_path, movies.thumbnail, movies.budget, movies.popularity, 
                            movies.release_date, movies.revenue, movies.runtime, keywords.name AS keyword, 
                            keywords.id AS keywordId, genres.name AS genre, genres.id AS genreId
                    FROM movies
                    JOIN movie_keywords 
                    ON movie_keywords.movieId = movies.id
                    JOIN keywords
                    ON keywords.id=movie_keywords.keywordId
                    JOIN movie_genres
                    ON movie_genres.movieId = movies.id
                    JOIN genres
                    ON genres.id = movie_genres.genreId        
                    WHERE movies.id = '$movieid';";
            */
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $data_array = array();
            $genre = $genre_array;

            if($numofrows > 0) {



                while($row = $result->fetch_assoc()){

                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'title' => $title,
                        'tagline' => $tagline,
                        'overview' => html_entity_decode($overview),
                        'poster_path' => $poster_path,
                        'backdrop_path' => $backdrop_path,
                        'thumbnail' => $thumbnail,
                        'genres' => $genre,
                        'budget' => $budget,
                        'popularity' => $popularity,
                        'release_date' => $release_date,
                        'revenue' => $revenue,
                        'runtime' => $runtime,
                        'rating' => $rating
                            );
          
                    // Push to "data"
                    array_push($data_array, $data_item);

                    //echo json_encode($row);
                }
        
                http_response_code(200);
                echo json_encode($data_array);

            } else {
                // No Result
                echo json_encode(array('message' => 'No Movies Found'));
            }
        }        
    } else if (isset($_GET['search_word']) && isset($_GET['api_key'])) {

        $search = $_GET['search_word'];
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
        
        
        
        if ($numofrows > 0) {
                       
            $sql = "SELECT * FROM `movies` WHERE title LIKE '%{$search}%' OR tagline LIKE '%{$search}%' OR overview LIKE '%{$search}%'";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $data_array = array();
            $genre = array();
            
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                    extract($row);

                    $data_item = array(
                        'id' => $id,
                        'title' => $title,
                        'tagline' => $tagline,
                        'overview' => html_entity_decode($overview),
                        'poster_path' => $poster_path,
                        'backdrop_path' => $backdrop_path,
                        'thumbnail' => $thumbnail,
                        'genres' => $genre,
                        'budget' => $budget,
                        'popularity' => $popularity,
                        'release_date' => $release_date,
                        'revenue' => $revenue,
                        'runtime' => $runtime,
                        'rating' => $rating
                            );
          
                    // Push to "data"
                    array_push($data_array, $data_item);

                    //echo json_encode($row);
                }
        
                http_response_code(200);
                echo json_encode($data_array);
                
            } else {
                // No Result
                echo json_encode(array('message' => 'No Movies Found'));
            }
        }    
    } else if (isset($_GET['api_key'])) {
    
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            echo json_encode($validrequest);
    
        } else {
            echo '{"error": "query parameter has key but not valid key"}';
        }
        
    } else {
        echo '{"error": "invalid request, query parameter needed"}';
    }
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_GET['api_key'])) {
 
        $data = json_decode(file_get_contents("php://input"));
 
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            
            $addGenre = htmlspecialchars(strip_tags($data->genre));

            $sql = "INSERT INTO `genres` (`id`, `name`) VALUES (NULL, '{$addGenre}');";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            } else {
                echo '{"added": '.$addGenre.'}';

                http_response_code(200);
            }
        }    
    }
} else {
    http_response_code(405);
}


function getGenresData($conn, $movieid){

    $sql = "SELECT * FROM `movie_genres` WHERE movieId = '$movieid';";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    }
    $numofrows = $result->num_rows;
    $movie_genre_array = array();

    if($numofrows > 0) {
       while($row = $result->fetch_assoc()){

       extract($row);

       $genreId_item = array('genreId' => $genreId);

        array_push($movie_genre_array, $genreId_item);

       }
    }

    //echo json_encode($movie_genre_array);
    $genre_array = array();

    foreach($movie_genre_array AS $key => $val){
    
        $genreId = $val['genreId'];
            
        //echo $genreId;
    
        $sql = "SELECT * FROM `genres` WHERE id = '$genreId'";
        $result = $conn->query($sql);
        if (!$result) {
           echo $conn->error;
           die();
        }
        $numofrows = $result->num_rows;
                       
        if($numofrows > 0) {
            while($row = $result->fetch_assoc()){
           
            extract($row);
            
            $genre_item = array('name' => $name);
              
            // Push to "data"
            array_push($genre_array, $genre_item);
            }
        }
    }
    return $genre_array;
}


/**
 * View Movies
 * 
 * 
 */
function selectRow($conn){

    echo 'word';

}
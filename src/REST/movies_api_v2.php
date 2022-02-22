<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == "GET"){

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
            pagination($conn);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        } 
          
    }
    else if (isset($_GET['api_key']) && isset($_GET['limit'])) {

        $apikey = $_GET['api_key'];
        $resultLimit = $_GET['limit'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            selectRow($conn, $resultLimit);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        } 
          
    }
    else if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $movieId = $_GET['id'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            selectSingleRow($conn, $movieId);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['search']) && isset($_GET['api_key']) && isset($_GET['genre']) && isset($_GET['rating']) && isset($_GET['limit'])) {

        //$search = $_GET['search'];
        $apikey = $_GET['api_key'];

        $search = $_GET['search'];
        $genreId = $_GET['genre'];
        $movierating = $_GET['rating'];
        $resultLimit = $_GET['limit'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            searchMovies($conn, $search, $genreId, $movierating, $resultLimit);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['api_key'])) {
    
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            echo json_encode(array('Response'=>'valid request'));
    
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
        
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
        http_response_code(401);
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
 //echo "POST";
    if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $apikey = $_GET['api_key'];
        $movieId = $_GET['id'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";

        $result = $conn->query($checkapi);

        if (!$result) {
            echo $conn->error;
            die();
        }

        $numofrows = $result->num_rows;

        if ($numofrows > 0) {
            echo json_encode(array('Response'=>'update record'));
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['api_key'])) {
    
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";

        $result = $conn->query($checkapi);

        if (!$result) {
            echo $conn->error;
            die();
        }

        $numofrows = $result->num_rows;

        if ($numofrows > 0) {
            echo json_encode(array('Response'=>'add record'));

        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
        http_response_code(401);
    }
}


/**
 * function add Pagination to Json file when
 * view all movies
 * 
 */
function pagination($conn){

    if (isset($_GET['page'])){
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $records_per_page = 12;
    $offset = ($page-1) * $records_per_page;

    $total_pages_sql = "SELECT COUNT(*) FROM movies";
    $result = mysqli_query($conn, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $records_per_page);
    
    
    $sql = "SELECT * FROM movies LIMIT $offset, $records_per_page";

    $page_array = array();

    if ($page <= 1){
        //echo json_encode(array('Prev' => 'disabled'));
        array_push($page_array, array('Prev' => 'disabled'));
    } else {
        //echo json_encode(array('Prev' => ($page-1)));
        array_push($page_array, array('Prev' => ($page-1)));
    }
    if ($page >= $total_pages){
        //echo json_encode(array('Next' => 'disabled'));
        array_push($page_array, array('Next' => 'disabled'));
    } else {
        //echo json_encode(array('Next' => ($page+1)));
        array_push($page_array, array('Next' => ($page+1)));
    }
    //echo json_encode(array('Last' => $total_pages));
    array_push($page_array, array('Last' => $total_pages));
    
    //echo json_encode(array('pagination' => $page_array));

    $data_array = array();
    $genre = array();

    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

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

                array_push($data_array, $data_item);
            }

            //echo json_encode(array('movies' => $data_array)); 
            //echo json_encode($data_array);   
            echo json_encode(array('pagination' => $page_array, 'movies' => $data_array));

        } else {
            // No Result
            echo json_encode(array('message' => 'No Movies Found'));
        }
    }
}

/**
 * Function displays all movies
 * 
 * TODO - low priority as I don't display all details on view all movies
 * TODO - Add Genre, cast_members, production_companies & keywords
 *   
 */
function selectRow($conn, $resultLimit){

    $data_array = array();
    $genre = array();

    $sql = "SELECT * FROM movies ORDER BY RAND() LIMIT {$resultLimit}";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

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

                array_push($data_array, $data_item);
            }

            echo json_encode(array('movies' => $data_array)); 

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
     
}


/**
 * Display single movie by id
 * 
 * 
 */
function selectSingleRow($conn, $movieId){

    $data_array = array();
    $movie_genre_array = array();
    $genre_array = array();
    $genre = array();

    /// Get Movie Genres
    $sql = "SELECT * FROM `movie_genres` WHERE movieId = '{$movieId}';";
    $result = $conn->query($sql);
    if (!$result) {
         echo $conn->error;
         die();
    }
    $numofrows = $result->num_rows;
    
        
    if($numofrows > 0) {
       while($row = $result->fetch_assoc()){
        
       extract($row);
       $genreId_item = array('genreId' => $genreId);
    
       array_push($movie_genre_array, $genreId_item);
       }
    }

    foreach($movie_genre_array AS $val){

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

    $genre = $genre_array;

    $sql = "SELECT * FROM movies WHERE movies.id = '$movieId';";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            $row = $result->fetch_assoc();
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

                array_push($data_array, $data_item);
            

            echo json_encode($data_array); 

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
     
}

/**
 * function search all movies
 * 
 */
function searchMovies($conn, $search, $genreId, $movierating, $resultLimit){

    if($movierating <= 1){
        $numlower = 0;
        $numupper = 1;
    } else if ($movierating > 1 || $movierating <= 2){
        $numlower = 1;
        $numupper = 2;
    } else if ($movierating > 2 || $movierating <= 3){
        $numlower = 2;
        $numupper = 3;
    } else if ($movierating > 3 || $movierating <= 4){
        $numlower = 3;
        $numupper = 4;
    } else {
        $numlower = 4;
        $numupper = 5;
    }

    
    //$sql = "SELECT * FROM movies LIMIT $offset, $records_per_page";
    $sql = "SELECT * FROM `movies` 
            JOIN movie_genres
            ON movies.id = movie_genres.movieId
            WHERE movies.title LIKE '%{$search}%' OR movies.tagline LIKE '%{$search}%' 
            OR movies.overview LIKE '%{$search}%' OR movie_genres.genreId = {$genreId} 
            AND (rating BETWEEN {$numlower} AND {$numupper}) ORDER BY RAND() LIMIT {$resultLimit}";

    $data_array = array();
    $genre = array();

    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

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

                array_push($data_array, $data_item);
            }

            //echo json_encode(array('movies' => $data_array)); 
            //echo json_encode($data_array);   
            echo json_encode(array('movies' => $data_array));

        } else {
            // No Result
            echo json_encode(array('message' => 'No Movies Found'));
        }
    }
}

/**
 * function add Pagination to Json file when
 * searching movies
 * 
 */
function searchMoviesPages($conn, $search, $genreId, $movierating){

    if (isset($_GET['page'])){
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    if($movierating <= 1){
        $numlower = 0;
        $numupper = 1;
    } else if ($movierating > 1 || $movierating <= 2){
        $numlower = 1;
        $numupper = 2;
    } else if ($movierating > 2 || $movierating <= 3){
        $numlower = 2;
        $numupper = 3;
    } else if ($movierating > 3 || $movierating <= 4){
        $numlower = 3;
        $numupper = 4;
    } else {
        $numlower = 4;
        $numupper = 5;
    }

    $records_per_page = 12;
    $offset = ($page-1) * $records_per_page;

    
    $total_pages_sql = "SELECT COUNT(*) FROM `movies` 
                        JOIN movie_genres
                        ON movies.id = movie_genres.movieId
                        WHERE movies.title LIKE '%{$search}%' OR movies.tagline LIKE '%{$search}%' 
                        OR movies.overview LIKE '%{$search}%' OR movie_genres.genreId = {$genreId} 
                        AND (rating BETWEEN {$numlower} AND {$numupper});";
    $result = mysqli_query($conn, $total_pages_sql);
    $total_rows = mysqli_fetch_array($result)[0];
    $total_pages = ceil($total_rows / $records_per_page);
    
    
    //$sql = "SELECT * FROM movies LIMIT $offset, $records_per_page";
    $sql = "SELECT * FROM `movies` 
            JOIN movie_genres
            ON movies.id = movie_genres.movieId
            WHERE movies.title LIKE '%{$search}%' OR movies.tagline LIKE '%{$search}%' 
            OR movies.overview LIKE '%{$search}%' OR movie_genres.genreId = {$genreId} 
            AND (rating BETWEEN {$numlower} AND {$numupper})
            LIMIT $offset, $records_per_page;";

    $page_array = array();

    if ($page <= 1){
        //echo json_encode(array('Prev' => 'disabled'));
        array_push($page_array, array('Prev' => 'disabled'));
    } else {
        //echo json_encode(array('Prev' => ($page-1)));
        array_push($page_array, array('Prev' => ($page-1)));
    }
    if ($page >= $total_pages){
        //echo json_encode(array('Next' => 'disabled'));
        array_push($page_array, array('Next' => 'disabled'));
    } else {
        //echo json_encode(array('Next' => ($page+1)));
        array_push($page_array, array('Next' => ($page+1)));
    }
    //echo json_encode(array('Last' => $total_pages));
    array_push($page_array, array('Last' => $total_pages));
    
    //echo json_encode(array('pagination' => $page_array));

    $data_array = array();
    $genre = array();

    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

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

                array_push($data_array, $data_item);
            }

            //echo json_encode(array('movies' => $data_array)); 
            //echo json_encode($data_array);   
            echo json_encode(array('pagination' => $page_array, 'movies' => $data_array));

        } else {
            // No Result
            echo json_encode(array('message' => 'No Movies Found'));
        }
    }
}

?>
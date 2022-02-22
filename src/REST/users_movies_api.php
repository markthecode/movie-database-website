<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "GET";
    if (isset($_GET['all']) && isset($_GET['userid']) && isset($_GET['api_key'])) {

        $userId = $_GET['userid'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            getAllUsersMovies($conn, $userId);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['stats']) && isset($_GET['userid']) && isset($_GET['api_key'])) {

        $userId = $_GET['userid'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            usersStats($conn, $userId);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['userid']) && isset($_GET['movieid']) && isset($_GET['api_key'])) {

        $userId = $_GET['userid'];
        $movieId = $_GET['movieid'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            getUsersMovie($conn, $userId, $movieId);
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
            echo json_encode(array('response'=>'valid request'));
            echo json_encode(array('message'=>'need additional values to continue'));
    
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
        
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
        http_response_code(401);
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    //echo "POST";
    if (isset($_GET['userid']) && isset($_GET['movieid']) && isset($_GET['api_key'])) {

        $userId = $_GET['userid'];
        $movieId = $_GET['movieid'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {

            //echo "ADD OR UPDATE";
            usersMovies($conn, $userId, $movieId);
            
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
            echo json_encode(array('response'=>'valid request'));
            echo json_encode(array('message'=>'need additional values to continue'));
    
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
        
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
        http_response_code(401);
    }
}

function usersMovies($conn, $userId, $movieId){

    //TEST if user has added movie

    $sql = "SELECT * FROM `user_movies` WHERE userId = '{$userId}' AND movieId = '{$movieId}'";

    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
        if(isset($_POST['userRating'])){
            $userRating = $_POST['userRating'];
        }
        if(isset($_POST['watchedMovie'])){
            $watchedMovie = $_POST['watchedMovie'];
        }
        if(isset($_POST['likeMovie'])){
            $likeMovie = $_POST['likeMovie'];
        }
        if(isset($_POST['dislikeMovie'])){
            $dislikeMovie = $_POST['dislikeMovie'];
        }
        if(isset($_POST['favouriteMovie'])){
            $favouriteMovie = $_POST['favouriteMovie'];
        }
        if(isset($_POST['removeMovie'])){
            $removeMovie = $_POST['removeMovie'];
        }
        if(isset($_POST['addMovie'])){
            $addMovie = $_POST['addMovie'];
        }
        if($result->num_rows > 0) {
            //echo json_encode(array('message'=>'UPDATE to User'));
            if(!empty($userRating)){

                $sql = "UPDATE user_movies SET userRating = ? WHERE movieId = {$movieId} AND userId = {$userId}";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "d", $userRating);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'User Rating Updated'));
                    http_response_code(200);
                }
            } else if (!empty($watchedMovie)){

                $sql = "UPDATE user_movies SET watchedMovie = ? WHERE movieId = {$movieId} AND userId = {$userId}";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "i", $watchedMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Watched Movie Updated'));
                    http_response_code(200);
                }           
            } else if (!empty($likeMovie)){
                if($likeMovie == 1){
                    $dislikeMovie = -1;
                } else {
                    $dislikeMovie = 1;
                }
                $sql = "UPDATE user_movies SET likeMovie = ?, dislikeMovie = ? WHERE movieId = {$movieId} AND userId = {$userId}";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "ii", $likeMovie, $dislikeMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Liked Movie Updated'));
                    http_response_code(200);
                } 
            } else if (!empty($dislikeMovie)){
                if($dislikeMovie == 1){
                    $likeMovie = -1;
                } else {
                    $likeMovie = 1;
                }
                $sql = "UPDATE user_movies SET likeMovie = ?, dislikeMovie = ? WHERE movieId = {$movieId} AND userId = {$userId}";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "ii", $likeMovie, $dislikeMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'DisLiked Movie Updated'));
                    http_response_code(200);
                }
            } else if (!empty($favouriteMovie)){
                $sql = "UPDATE user_movies SET favouriteMovie = ? WHERE movieId = {$movieId} AND userId = {$userId}";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "i", $favouriteMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Favourite Movie Updated'));
                    http_response_code(200);
                }  
            } else if(!empty($removeMovie)) {
                
                $sql = "DELETE FROM `user_movies` WHERE movieId = {$movieId} AND userId = {$userId};";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Movie Removed From User List'));
                    http_response_code(200);
                }
            } //else if (empty($userRating) || empty($watchedMovie) || empty($likeMovie) || empty($dislikeMovie) || empty($favouriteMovie)){

              //  echo json_encode(array('error' => 'Empty Update Fields'));

            //} 
        } else {
            /// need to Add userId & movie ID when adding any of these

            if(!empty($userRating)){
                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`, userRating) VALUES (?,?,?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {        
                    mysqli_stmt_bind_param($stmt, "iid", $movieId, $userId, $userRating);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'User Rating Added'));
                    http_response_code(200);
                }
            } else if (!empty($watchedMovie)){
                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`, watchedMovie) VALUES (?,?,?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {
                    
                    mysqli_stmt_bind_param($stmt, "iii", $movieId, $userId, $watchedMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Watched Movie Added'));
                    http_response_code(200);
                }
            } else if (!empty($likeMovie)){
                if($likeMovie == 1){
                    $dislikeMovie = -1;
                } else {
                    $dislikeMovie = 1;
                }
                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`, likeMovie, dislikeMovie) VALUES (?,?,?,?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {

                    mysqli_stmt_bind_param($stmt, "iiii", $movieId, $userId, $likeMovie, $dislikeMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Liked Movie Added'));
                    http_response_code(200);
                }
            } else if (!empty($dislikeMovie)){
                if($dislikeMovie == 1){
                    $likeMovie = -1;
                } else {
                    $likeMovie = 1;
                }
                
                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`, likeMovie, dislikeMovie) VALUES (?,?,?,?);";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {

                    mysqli_stmt_bind_param($stmt, "iiii", $movieId, $userId, $likeMovie, $dislikeMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'DisLiked Movie Added'));
                    http_response_code(200);
                }
            } else if (!empty($favouriteMovie)){

                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`, favouriteMovie) VALUES (?,?,?);";

                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {
                    
                    mysqli_stmt_bind_param($stmt, "iii", $movieId, $userId, $favouriteMovie);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Favourite Movie Added'));
                    http_response_code(200);
                }
            } else if (!empty($addMovie)){

                $sql = "INSERT INTO `user_movies`(`movieId`, `userId`) VALUES (?,?);";

                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo json_encode(array('error' => $stmt->error));
                    http_response_code(401);
                } else {

                    mysqli_stmt_bind_param($stmt, "ii", $movieId, $userId);
                    mysqli_stmt_execute($stmt);

                    echo json_encode(array('message' => 'Added Movie to Collection'));
                    http_response_code(200);
                }        
            } 
        }
    }
}

/**
 * 
 * View Idividual movie by USER ID & MOVIE ID
 * 
 */

function getUsersMovie($conn, $userId, $movieId) {

    $data_array = array();

    //$sql = "SELECT * FROM user_movies WHERE userId = '$userId'";
    $sql = "SELECT user_movies.userId, user_movies.movieId, movies.title, movies.tagline, movies.overview, movies.poster_path, 
                    movies.thumbnail, movies.release_date, movies.revenue, movies.runtime, movies.rating,  
                    user_movies.userRating, user_movies.watchedMovie, user_movies.likeMovie, 
                    user_movies.dislikeMovie, user_movies.favouriteMovie, user_movies.created_at 
            FROM `movies` 
            JOIN user_movies
            ON user_movies.movieId = movies.id
            WHERE user_movies.userId = '{$userId}' AND user_movies.movieId = '{$movieId}' ;";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

        $row = $result->fetch_assoc();

            extract($row);

            $data_item = array(
                'userId' => $userId,
                'movieId' => $movieId,
                'title' => $title,
                'tagline' => $tagline, 
                'overview' => $overview,
                'poster_path' => $poster_path,
                'thumbnail' => $thumbnail,
                'release_date' => $release_date,
                'revenue' => $revenue,
                'runtime' => $runtime,
                'rating' => $rating,
                'userRating' => $userRating,
                'watchedMovie' => $watchedMovie,
                'likeMovie' => $likeMovie,
                'dislikeMovie' => $dislikeMovie,
                'favouriteMovie' => $favouriteMovie,
                'created_at' => $created_at
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
 * 
 * Get All Users Records by ID
 * 
 */

function getAllUsersMovies($conn, $userId){

    $data_array = array();

    //$sql = "SELECT * FROM user_movies WHERE userId = '$userId'";
    $sql = "SELECT user_movies.userId, user_movies.movieId, movies.title, movies.tagline, movies.overview, movies.poster_path, 
                    movies.thumbnail, movies.release_date, movies.revenue, movies.runtime, movies.rating,  
                    user_movies.userRating, user_movies.watchedMovie, user_movies.likeMovie, 
                    user_movies.dislikeMovie, user_movies.favouriteMovie, user_movies.created_at 
            FROM `movies` 
            JOIN user_movies
            ON user_movies.movieId = movies.id
            WHERE user_movies.userId = '$userId';";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

        while($row = $result->fetch_assoc()){

            extract($row);

            $data_item = array(
                'userId' => $userId,
                'movieId' => $movieId,
                'title' => $title,
                'tagline' => $tagline, 
                'overview' => $overview,
                'poster_path' => $poster_path,
                'thumbnail' => $thumbnail,
                'release_date' => $release_date,
                'revenue' => $revenue,
                'runtime' => $runtime,
                'rating' => $rating,
                'userRating' => $userRating,
                'watchedMovie' => $watchedMovie,
                'likeMovie' => $likeMovie,
                'dislikeMovie' => $dislikeMovie,
                'favouriteMovie' => $favouriteMovie,
                'created_at' => $created_at
            );
    
            array_push($data_array, $data_item);

        };
       
        echo json_encode($data_array); 

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    } 
}

function usersStats($conn, $userId){

    $data_array = array();

    $sql = "SELECT COUNT(watchedMovie) AS watchedMovie from  user_movies WHERE watchedMovie = 1 AND userId = {$userId};";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                extract($row);

                //print_r($row);
                $data_item = array(
                    'totalWatched' => $watchedMovie
                );

                array_push($data_array, $data_item);
      
        
            }
        }
    }
    $sql = "SELECT COUNT(likeMovie) AS likeMovie from  user_movies WHERE likeMovie = 1 AND userId = {$userId};";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                extract($row);

                //print_r($row);
                $data_item = array(
                    'totalLikes' => $likeMovie
                );

                array_push($data_array, $data_item);
      
        
            }
        } 
    }
    $sql = "SELECT COUNT(dislikeMovie) AS dislikeMovie from  user_movies WHERE dislikeMovie = 1 AND userId = {$userId};";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                extract($row);

                //print_r($row);
                $data_item = array(
                    'totalDislikes' => $dislikeMovie
                );

                array_push($data_array, $data_item);
      
        
            }
        }
    }
    $sql = "SELECT COUNT(favouriteMovie) AS favouriteMovie from  user_movies WHERE favouriteMovie = 1 AND userId = {$userId};";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                extract($row);

                //print_r($row);
                $data_item = array(
                    'totalFavourites' => $favouriteMovie
                );

                array_push($data_array, $data_item);
      
        
            }
        }; 
    }
    $sql = "SELECT COUNT(id) AS totalMovies from  user_movies WHERE userId = {$userId};";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){

                extract($row);

                //print_r($row);
                $data_item = array(
                    'totalMovies' => $totalMovies
                );

                array_push($data_array, $data_item);
      
        
            }
        }
    }
    echo json_encode(array('stats' => $data_array)); 

}
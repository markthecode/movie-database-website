<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "GET";
    if (isset($_GET['stats']) && isset($_GET['userid']) && isset($_GET['api_key'])) {

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
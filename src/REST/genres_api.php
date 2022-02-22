<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

$validrequest = array('Response'=>'valid request');

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // echo "GET";
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
            
            $sql = 'SELECT * FROM `genres`';
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $genre_array = array();
            
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                    extract($row);

                    $genre_item = array(
                                    'id' => $id,
                                    'name' => $name
                                        );
          
                    // Push to "data"
                    array_push($genre_array, $genre_item);

                    //echo json_encode($row);
                }
        
                http_response_code(200);
                echo json_encode($genre_array);

            } else {
                // No Result
                echo json_encode(array('message' => 'No Genres Found'));
            }
        }  
          
    } else if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $genreid = $_GET['id'];
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
     

        if ($numofrows > 0) {
                       
            $sql = "SELECT * FROM `genres` WHERE id = '$genreid'";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $genre_array = array();
            
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                    extract($row);

                    $genre_item = array(
                                    'id' => $id,
                                    'name' => $name
                                        );
          
                    // Push to "data"
                    array_push($genre_array, $genre_item);

                    //echo json_encode($row);
                }
        
                http_response_code(200);
                echo json_encode($genre_array);

            } else {
                // No Result
                echo json_encode(array('message' => 'No Genres Found'));
            }
        }        
    } else if (isset($_GET['search_name']) && isset($_GET['api_key'])) {

        $search = $_GET['search_name'];
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
                       
            $sql = "SELECT * FROM `genres` WHERE name LIKE '%{$search}%'";
            $result = $conn->query($sql);
            if (!$result) {
                echo $conn->error;
                die();
            }
            $numofrows = $result->num_rows;

            $genre_array = array();
            
            if($numofrows > 0) {
                while($row = $result->fetch_assoc()){

                    extract($row);

                    $genre_item = array(
                                    'id' => $id,
                                    'name' => $name
                                        );
          
                    // Push to "data"
                    array_push($genre_array, $genre_item);

                    //echo json_encode($row);
                }
        
                http_response_code(200);
                echo json_encode($genre_array);

            } else {
                // No Result
                echo json_encode(array('message' => 'No Genres Found'));
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
            
            $addGenre = $data->genre;
            
            if(empty($addGenre)){

                http_response_code(401);

            } else {
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
        }else {
            echo '{"error": "query parameter has key but not valid key"}';
        }
        
    } else {
        echo '{"error": "invalid request, query parameter needed"}';    
    }
} else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $data = json_decode(file_get_contents("php://input"));

        $genreid = $_GET['id'];
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
     

        if ($numofrows > 0) {
            //echo '{"request": "PUT" }';

            $updateGenre = $data->genre;

            if(empty($updateGenre)){

                http_response_code(401);

            } else {
            
                $sql = "UPDATE `genres` SET name = ? WHERE id = ?);";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "si", $updateGenre, $genreid);
                mysqli_stmt_execute($stmt);
            
                if (!$stmt) {
                    echo $conn->error;
                    die();
                } else {
                    echo '{"id": '.$genreid.', "updated": '.$updateGenre.'}';

                    http_response_code(200);
                }
            }
        }else {
            echo '{"error": "query parameter has key but not valid key"}';
        }
        
    } else {
        echo '{"error": "invalid request, query parameter needed"}';
    }    
} else {
    http_response_code(405);
}
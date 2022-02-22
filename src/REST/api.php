<?php

include('../functions/dbconn.php');

header('Content-Type: application/json');

/**
 * View JSON Entries
 * 
 * param = All
 * param = id
 * param = search - first_name, last_name
 * 
 * Only require API_KEY to view
 * 
 */

if($_SERVER['REQUEST_METHOD'] == "GET"){
    //echo "GET";
    
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
            selectRow($conn);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        } 
          
    }
    else if (isset($_GET['id']) && isset($_GET['api_key'])) {

        $id = $_GET['id'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            selectSingleRow($conn, $id);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        }
    }
    else if (isset($_GET['search']) && isset($_GET['api_key'])) {

        $search = $_GET['search'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            selectRowSearch($conn, $search);
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
/**
 * 
 *  ADD an entry
 * 
 *  Only require API_KEY to view
 * 
 *  TODO - Add additional security - login 
 * 
 */
if($_SERVER['REQUEST_METHOD'] == "POST"){   
    //echo "POST";
    if (isset($_GET['api_key']) && isset($_GET['ADD'])) {
    
        $apikey = $_GET['api_key'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            insertRow($conn);
    
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
        }
        
    } else if (isset($_GET['id']) && isset($_GET['api_key'])) {
    
        $apikey = $_GET['api_key'];
        $id = $_GET['id'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            updateRow($conn, $id);
    
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
        }
        
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
    }
}

/**
 * 
 *  delete an entry
 * 
 *  Only require API_KEY to view
 * 
 *  TODO - Add additional security - login 
 * 
 *
if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['accountType'] == 'Admin'){
    echo "DELETE";
}
*/
function selectRow($conn){

    $data_array = array();

    $sql = "SELECT * FROM zz_api_dev";
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
                    'number' => $number,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'decimal_number' => $decimal_number,
                    'created_id' => $created_id
                );

                array_push($data_array, $data_item);
            }

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
    echo json_encode($data_array);  
}

function selectSingleRow($conn, $genreid){

    $data_array = array();

    $sql = "SELECT * FROM zz_api_dev WHERE id = '$genreid'";
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
                    'number' => $number,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'decimal_number' => $decimal_number,
                    'created_id' => $created_id
        );

        array_push($data_array, $data_item);
        

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
    echo json_encode($data_array);   

}

function selectRowSearch($conn, $search){

    $data_array = array();

    $sql = "SELECT * FROM zz_api_dev WHERE first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%'";
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
                    'number' => $number,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'decimal_number' => $decimal_number,
                    'created_id' => $created_id
                );

                array_push($data_array, $data_item);
            }

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
    echo json_encode($data_array);      
}

function insertRow($conn){

    $number = $_POST['number'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $decimal_number = $_POST['decimal_number'];
            
    if(empty($number) || empty($firstname) || empty($lastname) || empty($decimal_number)){

        echo json_encode(array('error' => 'Empty Fields'));
        http_response_code(401);

    } else {
        $sql = "INSERT INTO `zz_api_dev` (`number`, `first_name`, `last_name`, `decimal_number`) 
                VALUES (?,?,?,?);";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo json_encode(array('error' => $stmt->error));
            http_response_code(401);
        } else {

            mysqli_stmt_bind_param($stmt, "issd", $number, $firstname, $lastname, $decimal_number);
            mysqli_stmt_execute($stmt);

            echo json_encode(array('added' => 'New Record'));
            http_response_code(200);
        }
    }
}

function updateRow($conn, $id){

    $number = $_POST['number'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $decimal_number = $_POST['decimal_number'];
            
    if(empty($number) || empty($firstname) || empty($lastname) || empty($decimal_number)){

        echo json_encode(array('error' => 'Empty Fields'));
        http_response_code(401);

    } else {
        $sql = "UPDATE `zz_api_dev` 
                SET `number` = ?, `first_name` = ?, `last_name` = ?, `decimal_number` = ?  
                WHERE id = '$id';";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo json_encode(array('error' => $stmt->error));
            http_response_code(401);
        } else {

            mysqli_stmt_bind_param($stmt, "issd", $number, $firstname, $lastname, $decimal_number);
            mysqli_stmt_execute($stmt);

            echo json_encode(array('updated' => 'Record'));
            http_response_code(200);
        }
    }
}

function deleteRow(){

}
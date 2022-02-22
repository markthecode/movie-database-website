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
            echo selectRow($conn);
        } else {
            echo json_encode(array('error' => 'query parameter has key but not valid key'));
            http_response_code(401);
        } 
          
    }
    else if (isset($_GET['userId']) && isset($_GET['api_key'])) {

        $userId = $_GET['userId'];
        $apikey = $_GET['api_key'];

        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            echo selectSingleRow($conn, $userId);
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
            echo selectRowSearch($conn, $search);
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
    if (isset($_GET['api_key'])) {
    
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
        
    } else {
        echo json_encode(array('error' => 'invalid request, query parameter needed'));
    }
}
/**
 * 
 *  Update an entry
 * 
 *  Only require API_KEY to view
 * 
 *  TODO - Add additional security - login 
 * 
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['accountType'] == 'Admin'){
    echo "PUT - UPDATE";
    if (isset($_GET['id']) && isset($_GET['api_key'])) {
    
        $apikey = $_GET['api_key'];
        $userId = $_GET['userId'];
    
        $checkapi = "SELECT * FROM accounts WHERE api = '$apikey'";
    
        $result = $conn->query($checkapi);
    
        if (!$result) {
            echo $conn->error;
            die();
        }
    
        $numofrows = $result->num_rows;
    
        if ($numofrows > 0) {
            updateRow($conn, $userId);
    
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
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $_SESSION['accountType'] == 'Admin'){
    echo "DELETE";
}

function selectRow($conn){

    $data_array = array();

    //$sql = "SELECT * FROM user";
    $sql = "SELECT user.id, user.firstName, user.lastName, user.userName, user.userEmail, membership.accountEmail, 
                    member_type.memberType, account_level.accountType, accounts.accountActive
            FROM user
            RIGHT JOIN membership
            ON membership.userId = user.id
            RIGHT JOIN member_type
            ON member_type.id = membership.memberType
            RIGHT JOIN accounts
            ON membership.accountId=accounts.id
            RIGHT JOIN account_level
            ON accounts.accountLevel=account_level.id";
            
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){
                extract($row);

                $data_item = array(
                    'userId' => $id,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'userName' => $userName,
                    'userEmail' => $userEmail,
                    'memberType' => $memberType,
                    'accountEmail' => $accountEmail,
                    'accountLevel' => $accountType,
                    'accountActive' => $accountActive
                );
                if($id != null){
                    array_push($data_array, $data_item);
                }
                
            }

        } else {
            // No Result
            echo json_encode(array('message' => 'No Result Found'));
        }
    }
    echo json_encode($data_array);  
}

function selectSingleRow($conn, $userId){

    $data_array = array();

    //$sql = "SELECT * FROM user WHERE id = '$userId'";
    $sql = "SELECT user.id, user.firstName, user.lastName, user.userName, user.userEmail, membership.accountEmail, 
                    member_type.memberType, account_level.accountType, accounts.accountActive
            FROM user
            RIGHT JOIN membership
            ON membership.userId = user.id
            RIGHT JOIN member_type
            ON member_type.id = membership.memberType
            RIGHT JOIN accounts
            ON membership.accountId=accounts.id
            RIGHT JOIN account_level
            ON accounts.accountLevel=account_level.id
            WHERE user.id = '$userId';";

    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        extract($row);

        $data_item = array(
            'userId' => $id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'userName' => $userName,
            'userEmail' => $userEmail,
            'memberType' => $memberType,
            'accountEmail' => $accountEmail,
            'accountLevel' => $accountType,
            'accountActive' => $accountActive
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

    $sql = "SELECT user.id, user.firstName, user.lastName, user.userName, user.userEmail, membership.accountEmail, 
                    member_type.memberType, account_level.accountType, accounts.accountActive
            FROM user
            RIGHT JOIN membership
            ON membership.userId = user.id
            RIGHT JOIN member_type
            ON member_type.id = membership.memberType
            RIGHT JOIN accounts
            ON membership.accountId=accounts.id
            RIGHT JOIN account_level
            ON accounts.accountLevel=account_level.id
            WHERE firstName LIKE '%{$search}%' OR lastName LIKE '%{$search}%';";

    //$sql = "SELECT * FROM user WHERE firstName LIKE '%{$search}%' OR lastName LIKE '%{$search}%'";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
        die();
    } else {
    
        if($result->num_rows > 0){

            while($row = $result->fetch_assoc()){
                extract($row);

                $data_item = array(
                    'userId' => $id,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'userName' => $userName,
                    'userEmail' => $userEmail,
                    'memberType' => $memberType,
                    'accountEmail' => $accountEmail,
                    'accountLevel' => $accountType,
                    'accountActive' => $accountActive
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

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $userName= $_POST['userName'];
    $userEmail = $_POST['userEmail'];
            
    if(empty($firstName) || empty($lastName) || empty($userName) || empty($userEmail)){

        echo json_encode(array('error' => 'Empty Fields'));
        http_response_code(401);

    } else {
        $sql = "INSERT INTO `user` (`firstName`, `lastName`, `userName`, `userEmail`) 
                VALUES (?,?,?,?);";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo json_encode(array('error' => $stmt->error));
            http_response_code(401);
        } else {

            mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $userName, $userEmail);
            mysqli_stmt_execute($stmt);

            echo json_encode(array('added' => 'New Record'));
            http_response_code(200);
        }
    }
}

function updateRow($conn, $id){

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $userName= $_POST['userName'];
    $userEmail = $_POST['userEmail'];
            
    if(empty($firstName) || empty($lastName) || empty($userName) || empty($userEmail)){

        echo json_encode(array('error' => 'Empty Fields'));
        http_response_code(401);

    } else {
        $sql = "UPDATE `user` 
                SET `firstName` = ?, `lastName` = ?, `userName` = ?, `userEmail` = ?  
                WHERE id = '$id';";

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            echo json_encode(array('error' => $stmt->error));
            http_response_code(401);
        } else {

            mysqli_stmt_bind_param($stmt, "ssss", $firstName, $lastName, $userName, $userEmail);
            mysqli_stmt_execute($stmt);

            echo json_encode(array('updated' => 'Record'));
            http_response_code(200);
        }
    }
}

function deleteRow(){

}
<?php

include "dbconn.php";

//header('Content-Type: application/json');

session_start();

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup-submit'])){
    signup($conn);
} 

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login-submit'])){
    login($conn);
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['logout-submit'])){
    logout($conn);
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update-submit'])){
    update($conn);
}




function signup($conn){
    //echo "Sign Up!";

    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwd-repeat'];

    //Default Setup Values
    $def_accountLevel = "3"; //Admin = 1, Moderator = 2, User = 3, Developer = 4
    $def_accountActive = "1"; // True = 1, False = 0
    $def_api_key = ""; //Empty API Key as Default
    $def_memeberType = "1";  //Adult = 1, Teen = 2, Child = 3

    if (empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($passwordRepeat)) {
        // return error if all fields are empty
        header("Location: ../signup.php?error=emptyfields&username=".$username."&firstname=".$firstname."&lastname=".$lastname."&email=".$email);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        // return error invalid user or email
        header("Location: ../signup.php?error=invalidusernameemail&firstname=".$firstname."&lastname=".$lastname);
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // return error invalid email
        header("Location: ../signup.php?error=invalidemail&username=".$username."&firstname=".$firstname."&lastname=".$lastname);
        exit();
    } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        // return error invalid username
        header("Location: ../signup.php?error=invalidusername&firstname=".$firstname."&lastname=".$lastname."&email=".$email);
        exit();
    } else if ($password !== $passwordRepeat) {
        // return error if password don't match
        header("Location: ../signup.php?error=passwordcheck&username=".$username."&firstname=".$firstname."&lastname=".$lastname."&email=".$email);
        exit();
    } else {
        $sql = "SELECT userName FROM user WHERE userName=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../signup.php?error=sqlerror1");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $resultCheck = mysqli_stmt_num_rows($stmt);
            if ($resultCheck > 0){
                // returns if username already taken
                header("Location: ../signup.php?error=usertaken&email=".$email."&firstname=".$firstname."&lastname=".$lastname);
                exit();
            } else {
                $sql = "INSERT INTO user (firstName, lastName, userName, userEmail) 
                        VALUES (?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlerror2");
                    exit();
                } else {

                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "ssss", $firstname, $lastname, $username, $email);
                    mysqli_stmt_execute($stmt);
                    $last_id = mysqli_insert_id($conn);

                    $sql = "INSERT INTO logins (userName, password, userId) 
                            VALUE (?,?,?)";
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "ssi", $username, $hashedPwd, $last_id);
                    mysqli_stmt_execute($stmt);

                    $sql = "INSERT INTO accounts (userId, name, accountLevel, accountActive, api) 
                            VALUES (?,?,?,?,?);";
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "isiis", $last_id, $firstname, $def_accountLevel, $def_accountActive, $def_api_key);
                    mysqli_stmt_execute($stmt);
                    $account_id = mysqli_insert_id($conn);

                    $sql = "INSERT INTO membership (userId, accountId, accountEmail, memberType) 
                            VALUE (?,?,?,?)";
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "iisi", $last_id, $account_id, $email, $def_memeberType);
                    mysqli_stmt_execute($stmt);

                    header("Location: ../signup.php?signup=success");
                    exit();
                }        
            }
        }
    }
    mysqli_close($conn);
}


function login($conn){

    $userName = $_POST['username'];
    $password = $_POST['pwd'];

    if(empty($userName) || empty($password)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM logins WHERE userName=?;";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../login.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $userName);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['password']);
                if($pwdCheck == false) {
                    header("Location: ../login.php?error=loginerror");
                    exit();
                } else if ($pwdCheck == true) {

                    
                    $_SESSION['userId'] = $row['userId'];
                    $_SESSION['userName'] = $row['userName'];

                    $sql = "SELECT user.id, user.userName, account_level.accountType
                            FROM user
                            RIGHT JOIN membership
                            ON membership.userId = user.id
                            RIGHT JOIN member_type
                            ON member_type.id = membership.memberType
                            RIGHT JOIN accounts
                            ON membership.accountId=accounts.id
                            RIGHT JOIN account_level
                            ON accounts.accountLevel=account_level.id
                            WHERE user.userName = ?
                            LIMIT 0,1;";

                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt, $sql);
                    mysqli_stmt_bind_param($stmt, "s", $userName);
                    mysqli_stmt_execute($stmt);
                    $sqlresult = mysqli_stmt_get_result($stmt);
                    $sqlrow = mysqli_fetch_assoc($sqlresult);

                    
                    $_SESSION['accountType'] = $sqlrow['accountType'];
                    

                    header("Location: ../login.php?login=success");
                    exit();


                } else {
                    header("Location: ../login.php?error=loginerror");
                    exit();
                }
            } else {
                header("Location: ../login.php?error=nouser");
                exit();
            }
        }
    }

}

function logout($conn){

    session_start();
    session_unset();
    session_destroy();

    header("Location: ../index.php");

}

function update($conn){

    if(!isset($_SESSION['userId'])){
        header("Location: ../login.php?error=notloggedin");
        exit();
    } else {
        
        $id = $_SESSION['userId'];
        $username = $_SESSION['userName'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['pwd'];
        $passwordRepeat = $_POST['pwd-repeat'];
    
        //Default Setup Values
        $def_accountLevel = "3"; //Admin = 1, Moderator = 2, User = 3, Developer = 4
        $def_accountActive = "1"; // True = 1, False = 0
        $def_api_key = ""; //Empty API Key as Default
        $def_memeberType = "1";  //Adult = 1, Teen = 2, Child = 3
    
        if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($passwordRepeat)) {
            // return error if all fields are empty
            header("Location: ../dashboard/userupdate.php?error=emptyfields&firstname=".$firstname."&lastname=".$lastname."&email=".$email);
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // return error invalid email
            header("Location: ../dashboard/userupdate.php?error=invalidemail&firstname=".$firstname."&lastname=".$lastname);
            exit();
        } else if ($password !== $passwordRepeat) {
            // return error if password don't match
            header("Location: ../dashboard/userupdate.php?error=passwordcheck&firstname=".$firstname."&lastname=".$lastname."&email=".$email);
            exit();
        } else {

            $sql = "UPDATE user SET firstName = ?, lastName = ?, userName = ?, userEmail = ? WHERE id = '$id'";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dashboard/userupdate.php?error=sqlerror2");
                exit();
            } else {

                $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                mysqli_stmt_bind_param($stmt, "ssss", $firstname, $lastname, $username, $email);
                mysqli_stmt_execute($stmt);

                $sql = "UPDATE logins SET userName = ?, password = ? WHERE userId = '$id';";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPwd);
                mysqli_stmt_execute($stmt);

                $sql = "UPDATE accounts SET name = ?, accountLevel = ?, accountActive = ? WHERE userId = '$id';";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "sii", $firstname, $def_accountLevel, $def_accountActive);
                mysqli_stmt_execute($stmt);

                $sql = "UPDATE membership SET accountEmail = ?, memberType = ? WHERE userId = '$id';";
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "si", $email, $def_memeberType);
                mysqli_stmt_execute($stmt);

                header("Location: ../dashboard/userupdate.php?update=success");
                exit();
            }
        } 
        
        /** 
         * to check if user already exists - don't allow username udate
         * 
        else {
            $sql = "SELECT userName FROM user WHERE userName=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../dashboard/userupdate.php?error=sqlerror1");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $username);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt);
                if ($resultCheck > 0){
                    // returns if username already taken
                    header("Location: ../dashboard/userupdate.php?error=usertaken");
                    exit();
                } 
            }
        }
        */
         
        mysqli_close($conn);
    }

}


?>
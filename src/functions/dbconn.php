<?php

// declare the password
$pass = "top-secret";

// declare MySQL username
$user = "database-user";

// declare webserver
$webserver = "movie-mysql-domain";

// declare Database
$db = "moviedb";

// mysqli api library in PHP to connect to DB
$conn = new mysqli($webserver, $user, $pass, $db);
if ($conn -> connect_error) {
    echo "there has been an error ".$conn->connect_error;
} else {
   // echo "connected to db";
}

?>

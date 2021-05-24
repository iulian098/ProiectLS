<?php
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'user');
    define('DB_PASSWORD', '123456789');
    define('DB_NAME', 'userlist');

    //Connect to database
    $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
    if($mysqli === false){
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }
?>
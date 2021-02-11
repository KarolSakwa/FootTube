
<?php

ob_start(); // starts the output buffering

session_start();

date_default_timezone_set('Europe/Warsaw');

try{

    $con = new PDO("mysql:dbname=heroku_e1c8fb3fe28884e; host=us-cdbr-east-03.cleardb.com", "bee5577d57b0b2", "3271b596");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

}

catch(PDOException $e){

    echo "Connection Failed:" . $e->getMessage();

}
/*
//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["host"];
$cleardb_username = $cleardb_url["user"];
$cleardb_password = $cleardb_url["pass"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;
// Connect to DB
$con = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
*/
?>
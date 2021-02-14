<?php

ob_start(); // starts the output buffering

session_start();

date_default_timezone_set('Europe/Warsaw');


try{

    $con = new PDO("mysql:dbname=sql11392555; host=sql11.freemysqlhosting.net", "sql11392555", "FUvtR2GXmT");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

}

catch(PDOException $e){

    echo "Connection Failed:" . $e->getMessage();

}


?>
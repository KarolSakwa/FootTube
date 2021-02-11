<?php 
require_once("../config.php");
require_once("../classes/Video.php");
require_once("../classes/User.php");

$videoId = $_POST["videoId"];
$username = $_SESSION["userLoggedIn"];
$userLoggedInObj = new User($con, $username);
$video = new Video($con, $videoId, $userLoggedInObj);

echo $video->like();

?>
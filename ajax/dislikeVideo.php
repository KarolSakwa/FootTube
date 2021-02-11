<?php 
require_once("../config.php");
require_once("../classes/Video.php");
require_once("../classes/User.php");

$videoId = $_POST["videoId"];

//which user has liked the video

$username = $_SESSION["userLoggedIn"];
$userLoggedInObj = new User($con, $username);
$video = new Video($con, $videoId, $userLoggedInObj);

echo $video->dislike();

?>
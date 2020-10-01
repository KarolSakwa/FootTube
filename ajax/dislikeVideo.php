<?php 
require_once("../includes/config.php");
require_once("../includes/classes/Video.php");
require_once("../includes/classes/User.php");

$videoId = $_POST["videoId"];

//which user has liked the video

$username = $_SESSION["userLoggedIn"];
$userLoggedInObj = new User($con, $username);
$video = new Video($con, $videoId, $userLoggedInObj);

echo $video->dislike();

?>